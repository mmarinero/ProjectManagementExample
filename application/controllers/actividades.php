<?php

/**
 * Panel de control principal
 *
 * @author Mario Marinero <mario.marinero@alumnos.uva.es>
 */
class actividades extends CI_Controller{
    
    private $trabajador;
    
    private $jefeId;

    function __construct() {
        parent::__construct();
        $this->trabajador = Trabajador::loggedTrabajador();
        if (!is_null($this->trabajador)) $this->smarty->assign('trabajador',  $this->trabajador);
        $this->smarty->assign('idProyecto', $this->uri->segment(3));
        if ($this->trabajador->get('rol')->val() == 'admin') {
            $proyectos = Proyecto::loadArray();
        } else {
            $proyectos = $this->trabajador->getJoinedArray('Proyecto', 'TrabajadoresProyecto');
        }
        $buscadorJefe = new TrabajadoresProyecto();
        $jefeOrNull = $buscadorJefe->loadWhere(array(
            'Proyecto'=>$this->uri->segment(3),
            'Trabajador'=>$this->trabajador->getId(),
            'jefe'=>1));
        $this->jefeId= is_object($jefeOrNull) ? $jefeOrNull->get('Trabajador')->val() : null;
        $this->smarty->assign('proyectos',$proyectos);
        $this->smarty->assign('this', $this);
    }
    
     /**
     *
     * @param type $segment
     * @param type $model
     * @param type $redirect
     * @return EX_Model 
     */
    private function requireSegment($segment, $model = null, $redirect = ''){
        $id = $this->uri->segment($segment, null);
        if (!is_null($this->uri->segment($segment, null))){
            if (!is_null($model)){
                $newModel = new $model((int)$id);
                if (!is_null($newModel)) return $newModel;
            } else {
                return $id;
            }
        }
        redirect($redirect);
    }

    public function planIteracion(){
        $idProyecto = $this->requireSegment(3);
        $idPlanFases = $this->requireSegment(4);
        $idIteracion = $this->requireSegment(5);
        if ($this->trabajador->getId() != $this->jefeId) {
            redirect('dashboard');
        }
        $proyecto = new Proyecto($idProyecto);
        $actividadesLoader = new Actividad();
        $actividades = $actividadesLoader->loadArray(array('PlanIteracion'=>$idIteracion));
        $crearActividad= $actividadesLoader->getForm(site_url("actividades/nuevaActividadPost/$idProyecto/$idPlanFases/$idIteracion"),
                array('id'=>'crearActividad', 'class'=>'estandarForm'));
        $roles = Trabajador::$roles;
        array_shift($roles);
        $trabajadores = $proyecto->getJoinedArray('Trabajador', 'TrabajadoresProyecto');
        $this->smarty->assign('crearActividad',$crearActividad);
        $this->smarty->assign('actividades',$actividades);
        $this->smarty->assign('trabajadoresProyecto',$trabajadores);
        $this->smarty->assign('roles', $roles );
        $this->smarty->assign('idIteracion',$idIteracion);
        $this->smarty->view('planIteracion');
    }
    
    public function nuevaActividadPost(){
        $idProyecto = $this->requireSegment(3);
        $idPlanFases = $this->requireSegment(4);
        $idIteracion = $this->requireSegment(5);
        if ($this->trabajador->getId() != $this->jefeId) {
            redirect('dashboard');
        }
        $actividad = new Actividad();
        $input = assocRequest(array_keys($actividad->getFields()));
        $input['PlanIteracion'] = $idIteracion;
        $actividad->DBInsert($input);
        foreach($_POST as $name=>$value){
            if (strpos($name, 'actividad') !== false){
                $predecesora = new ActividadActividad();
                $predecesora->DBInsert(array('Precedente'=>$value, 'Posterior'=>$actividad->getId()));
            }
        }
        redirect("actividades/planIteracion/$idProyecto/$idPlanFases/$idIteracion");
    }

}

