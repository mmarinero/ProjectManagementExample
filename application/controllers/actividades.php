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
        $iteracion = new PlanIteracion($idIteracion);
        $proyecto = new Proyecto($idProyecto);
        $actividadesLoader = new Actividad();
        $actividades = $actividadesLoader->loadArray(array('PlanIteracion'=>$idIteracion));
        $crearActividad= $actividadesLoader->getForm(site_url("actividades/nuevaActividadPost/$idProyecto/$idPlanFases/$idIteracion"),
                array('id'=>'crearActividad', 'class'=>'estandarForm'));
        $roles = Trabajador::$roles;
        array_shift($roles);
        $predecesoras = array();
        foreach ($actividades as $actividad) {
            $predecesoras[$actividad->getId()] = $actividad->
                    getJoinedArray('Actividad', array(
                        'thisColumnName'=>'Posterior',
                        'referredColumnName'=>'Precedente',
                        "throughModel"=>'ActividadActividad'));
        }
        $trabajadores = $proyecto->getJoinedArray('Trabajador', 'TrabajadoresProyecto');
        $this->smarty->assign('crearActividad',$crearActividad);
        $this->smarty->assign('actividades',$actividades);
        $this->smarty->assign('predecesoras',$predecesoras);
        $this->smarty->assign('trabajadoresProyecto',$trabajadores);
        $this->smarty->assign('roles', $roles );
        $this->smarty->assign('iniciarURL', site_url("actividades/iniciarIteracion/$idProyecto/$idPlanFases/$idIteracion"));
        $this->smarty->assign('asignarURL', site_url("actividades/asignarTareas/$idProyecto/$idPlanFases/$idIteracion"));
        $this->smarty->assign('cerrarURL', site_url("actividades/cerrarActividad/$idProyecto/$idPlanFases/$idIteracion"));
        $this->smarty->assign('idIteracion',$idIteracion);
        $this->smarty->assign('iteracion',$iteracion);
        $this->smarty->view('planIteracion');
    }
    
    public function iniciarIteracion(){
        $idProyecto = $this->requireSegment(3);
        $idPlanFases = $this->requireSegment(4);
        $idIteracion = $this->requireSegment(5);
        $iteracion = $this->requireSegment(5, 'PlanIteracion');
        $iteracion->DBUpdate(array('iniciada'=>1));
        redirect("actividades/planIteracion/$idProyecto/$idPlanFases/$idIteracion");
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

    function asignarTareas(){
        $proyecto = $this->requireSegment(3, 'Proyecto');
        $idPlanFases = $this->requireSegment(4);
        $idIteracion = $this->requireSegment(5);
        $actividad = $this->requireSegment(6, 'Actividad');
        if ($this->trabajador->getId() != $this->jefeId) {
            redirect('dashboard');
	}	
	$trabajadores = $proyecto->getJoinedArray('Trabajador', 'TrabajadoresProyecto');
	$level = Trabajador::$roles[$actividad->get('rol')->val()];
	for($i = 1;$i<=$level; $i++) {
	    $levels[] = $i;
	}
        $tareas = TareaPersonal::loadArray(array('Actividad'=>$actividad->getId()));
        foreach ($tareas as $tarea){
            $tarea->setValues(array('Trab'=> new Trabajador($tarea->get('Trabajador')->val())));
        }
        $this->smarty->assign('tareas', $tareas);
	$trabajadores = Trabajador::filterLevel($trabajadores,$levels);
	$this->smarty->assign('crearTarea', i(new TareaPersonal())->getForm(
                site_url("actividades/AsignarTareasPost/{$proyecto->getId()}/$idPlanFases/$idIteracion/{$actividad->getId()}"),
                array('id'=>'asignaActividades', 'class'=>'estandarForm')));
        $this->smarty->assign('url', "{$proyecto->getId()}/$idPlanFases/$idIteracion/{$actividad->getId()}/");
	$this->smarty->assign('trabajadores', $trabajadores);
        $this->smarty->assign('actividad', $actividad);
	$this->smarty->view('asignarTareas');
    }

    function AsignarTareasPost($idP,$idPl,$idI,$idA){
        $actividad = $this->requireSegment(6, 'Actividad');
        $actividad->DBUpdate(array('iniciada'=>1));
        $tarea = new TareaPersonal();
        $input = assocRequest(array_keys($tarea->getFields()));
        $input['Actividad']= $actividad->getId();
        $input['estado']= 'Pendiente';
        $input['Trabajador']= $_POST['trabajador'];
        $tarea->DBInsert($input);
        redirect("actividades/AsignarTareas/$idP/$idPl/$idI/$idA");
    }

    public function proyecto(){
	$proyecto = $this->requireSegment(3, 'Proyecto');
	$planFases = new PlanFases(array('Proyecto'=>$proyecto->getId()));
	$iteraciones = $planFases->getReferredArray('PlanIteracion');
	$actividades = array();
	foreach($iteraciones as $iteracion){
	   $actividades = array_merge($iteracion->getReferredArray('Actividad',array('iniciada'=>1)), $actividades); 
	}
	$tareas = array();
	foreach($actividades as $actividad){
	   $tareas = array_merge($actividad->getReferredArray('TareaPersonal',array(
               'Trabajador'=>$this->trabajador->getId(),
               'estado'=>'Pendiente')), $tareas); 
	}
	$this->smarty->assign('tareas', $tareas);
        $formsTareas = array();
	foreach($tareas as $tarea){
	    $formsTareas[] = $tarea->getFieldsForm(
                site_url("actividades/tareaPost/{$proyecto->getId()}/{$tarea->getId()}"),
                array('id'=>'crearTarea', 'class'=>'estandarForm'));
	}
	$this->smarty->assign('formsTareas', $formsTareas);
        $this->smarty->view('actividades');
    }

    public function TareaPost(){
	$tarea = $this->requireSegment(4, 'TareaPersonal');
        $input = assocRequest(array('seguimiento', 'estado'));
	$tarea->DBUpdate($input);
	redirect('actividades/proyecto/'.$this->requireSegment(3));
    }
    
    public function ModificarEstado($idp,$idpl,$idi,$ida,$idt){
        $tarea = $this->requireSegment(7, 'TareaPersonal');
        $input = assocRequest(array('estado'));
        $tarea->DBUpdate($input);
        redirect("actividades/asignarTareas/$idp/$idpl/$idi/$ida/$idt");
    }
    
    public function cerrarActividad($idp,$idpl,$idi,$ida){
        $actividad = new Actividad($ida);
        $actividad->DBUpdate(array('cerrada'=>1));
        redirect("actividades/planIteracion/$idp/$idpl/$idi");
    }
}

