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

    function asignarActividades(){
        $proyecto = $this->requireSegment(3, 'Proyecto');
        $idPlanFases = $this->requireSegment(4);
        $idIteracion = $this->requireSegment(5);
        $actividad = $this->requireSegment(6, 'Actividad');
        if ($this->trabajador->getId() != $this->jefeId) {
            redirect('dashboard');
	}	
	$trabajadores = $proyecto->getJoinedArray('Trabajador', 'TrabajadoresProyecto') 
	$level = Trabajador::$roles[$actividad->get('rol')->val()];
	for($i = 1;$i<=$level; $i++) {
	    $levels[] = $i;
	}
	$trabajadores = Trabajador::filterLevel($trabajadores,$levels);
	$this->smarty->assign('crearTarea', i(new TareaPersonal())->getForm();
	$this->smarty->assign('trabajadores', $trabajadores);
	$this->smarty->view('asignarActividades');
    }

    function AsignarActividadesPost(){
    }

    public function proyecto(){
	$proyecto = requireSegment(3, 'Proyecto');
	$planFases = new PlanFases(array('Proyecto'=>$proyecto->getId()));
	$iteraciones = $planFases->getReferredArray('PlanIteraciones')
	$actividades array();
	foreach($iterraciones as $iteracion){
	   $actividades = array_merge($iteracion->getReferredArray('Actividad',array('iniciada'=>1), $actividades); 
	}
	$tareas = array();
	foreach($actividades as $actividad){
	   $tareas = array_merge($actividad->getReferredArray('TareaPersonal',array('Trabajador'=>$this->trabajador->getId()), $actividades); 
	}
	$this->smarty->assign('tareas', $tareas);
	foreach($tareas as $tarea){
	    arreglar atributos$formsTareas[] = $tarea->getInputForm(array('appendName'=>$tarea->getId()));
	}
	$this->smarty->assign('formsTareas', $formsTareas);
    }

    public function TareaPost(){
	$idProyecto = requireSegment(3);
	$tarea = new TareaPersonal($_POST['id']));
        $input = assocRequest(array_keys($tarea->getFields()));
	$tarea->DBUpdate($input)
	redirect('actividades/proyecto/'.$idProyecto);
    }
}

