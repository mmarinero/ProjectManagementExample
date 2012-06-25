<?php

/**
 * Panel de control principal
 *
 * @author Mario Marinero <mario.marinero@alumnos.uva.es>
 */
class dashboard extends CI_Controller{
    
    
    /**
     * @var Trabajador 
     */
    private $trabajador;
    
    private $jefeId;
    
    function __construct() {
        parent::__construct();
        $this->trabajador = Trabajador::loggedTrabajador();
        $jefeOrNull = new TrabajadoresProyecto(array(
            'Proyecto'=>$this->uri->segment(3),
            'Trabajador'=>  $this->trabajador->getId(),
            'jefe'=>1));
        $this->jefeId= is_object($jefeOrNull) ? $jefeOrNull->get('Trabajador')->val() : null;
        if ($this->trabajador->get('rol')->val() == 'admin') {
            $proyectos = Proyecto::loadArray();
        } else {
            $proyectos = $this->trabajador->getJoinedArray('Proyecto', 'TrabajadoresProyecto');
        }
        $this->smarty->assign('idProyecto', $this->uri->segment(3, null));
        $this->smarty->assign('idPlan', $this->uri->segment(4, null));
        $this->smarty->assign('jefe', $jefeOrNull);
        $this->smarty->assign('trabajador',$this->trabajador);
        $this->smarty->assign('proyectos',$proyectos);
        $this->smarty->assign('this', $this);
    }

    function index(){
        $this->smarty->view('dashboard');
    }
    
    function proyecto(){
        $proyecto = $this->requireSegment(3, "Proyecto");
        $trabajadores = $proyecto->getJoinedArray('Trabajador', 'TrabajadoresProyecto');
        $this->smarty->assign('trabajadores', $trabajadores);
        $this->smarty->assign('proyecto', $proyecto);
        $this->smarty->view('proyecto');
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
    
    function crearProyecto(){
        $this->trabajador->auth('admin');
        $proyecto = new Proyecto();
        $trabajadores = Trabajador::loadArray();
        $trabajadores = Trabajador::filterLevel($trabajadores, array(1,2,3,4));
        $this->smarty->assign('trabajadores', $trabajadores);
        $this->smarty->assign('jefes', Trabajador::filterLevel($trabajadores, array(1)));
        $crearProyecto = $proyecto->getForm(site_url('dashboard/crearProyectoPost'), 
                array('id'=>'crearProyecto', 'class'=>'estandarForm'));
        $this->smarty->assign('crearProyecto', $crearProyecto);
        $this->smarty->assign('buttonText', 'Crear proyecto');
        $this->smarty->view('crearProyecto');
    }
    
    function crearProyectoPost(){
        $this->trabajador->auth('admin');
        $proyecto = new Proyecto();
        $proyecto->DBInsert(assocRequest(array_keys($proyecto->getFields())));
        $trabajadorProyecto = new TrabajadoresProyecto();
              $trabajadorProyecto->DBInsert(array(
                'Proyecto'=> $proyecto->getId(),
                'Trabajador' => $this->input->post('jefeProyecto'),
                'porcentaje' => $this->input->post('dedicacion'),
                'jefe'=> 1));
        redirect('dashboard/proyecto/'.$proyecto->getId());
    }
    
    function editarProyecto($id){
        $this->trabajador->auth('admin');
        $trabajadoresLoader = new Trabajador();
        $trabajadores = $trabajadoresLoader->loadArray();
        $this->smarty->assign('fixedTrabajadores', $trabajadores);
        $crearProyecto = $this->requireSegment(3, "Proyecto")->loadId($id)->getForm(site_url('dashboard/editarProyectoPost/'.$id), array('id'=>'crearProyecto', 'class'=>'estandarForm'));
        $this->smarty->assign('crearProyecto', $crearProyecto);
        $this->smarty->assign('buttonText', 'Editar proyecto');
        $this->smarty->view('crearProyecto');
    }
    
    function editarProyectoPost(){
        $this->trabajador->auth('admin');
        $proyecto = $this->requireSegment(3, "Proyecto");
        $proyecto->DBUpdate(assocRequest(array_keys($proyecto->getFields())));
        redirect('dashboard/proyecto/'.$proyecto->getId());
    }
    
    function eliminarProyecto(){
        $this->trabajador->auth('admin');
        i(new Proyecto())->DBdelete($this->requireSegment(3));
        redirect('dashboard');
    }
    
    function planes(){
        $id = $this->requireSegment(3);
        if ($this->trabajador->getId() != $this->jefeId) {
            redirect('dashboard');
        }
        $planFases =new PlanFases(array('Proyecto'=>$id));
        if (is_null($planFases->getId())){
            $crearFases= $planFases->getForm(site_url("dashboard/crearFasesPost/$id"),
                    array('id'=>'crearPlanFases', 'class'=>'estandarForm'));
            $this->smarty->assign('crearFases', $crearFases);
            $trabajadores = Trabajador::loadArray();
            $trabajadores = Trabajador::filterLevel($trabajadores, array(1,2,3,4));
            unset($trabajadores[$this->jefeId]);
            $this->smarty->assign('trabajadores', $trabajadores);
            $this->smarty->view('planes');
        } else {
            $iteraciones= $planFases->getReferredArray('PlanIteracion');
            foreach ($iteraciones as $iteracion) {
                $iteracion->get('inicio')->setAttributes(array('nameAppend'=>$iteracion->getId()));
                $iteracion->get('fin')->setAttributes(array('nameAppend'=>$iteracion->getId()));
            }
            $this->smarty->assign('iteraciones',$iteraciones);
            $this->smarty->assign('idPlan',$planFases->getId());
            $this->smarty->view('planes');
        }
    }
    
    function crearFasesPost() {
        $idProyecto = $this->requireSegment(3);
        if ($this->trabajador->getId() != $this->jefeId) {
            redirect('dashboard');
        }
        $planFases = new PlanFases();
        $input = assocRequest(array_keys($planFases->getFields()));
        $input['Proyecto'] = $idProyecto;
        $planFases->DBInsert($input, true);
                $trabajadoresLoader = new Trabajador();
        $trabajadores = $trabajadoresLoader->loadArray();
        foreach ($trabajadores as $trabajador) {
            $input[] = $trabajador->getId();
            $input[] = 'dedicacion'.$trabajador->getId();
        }
        $input = assocRequest($input);
        foreach ($trabajadores as $trabajador) {
            if (!is_null($input[$trabajador->getId()])){
                $trabajadorProyecto = new TrabajadoresProyecto();
                $jefe = 0;
                if ($trabajador->getId() == $this->input->post('jefeProyecto')){
                    $jefe = 1;
                }
                $trabajadorProyecto->DBInsert(array(
                    'Proyecto'=> $idProyecto,
                    'Trabajador' => $trabajador->getId(),
                    'porcentaje' => $input['dedicacion'.$trabajador->getId()],
                    'jefe'=> $jefe));
            }
        }
        redirect("dashboard/planes/$idProyecto/{$planFases->getId()}");
    }
    function modificarEstimacionIteracionPost($idPlan = null){
        if ($this->trabajador->get('rol')->val() != 'Jefe de proyecto' ||
                $idPlan == null ||
                $this->trabajador->getId() != $this->jefeId) {
            redirect('dashboard');
        }
        $iteracionesLoader = new PlanIteracion();
        $iteraciones = $iteracionesLoader->loadArray(array('PlanFases'=> $idPlan));
        foreach($iteraciones as $iteracion){
            $iteracion->DBUpdate(array(
                'inicio'=>$this->input->post('inicio'.$iteracion->getId()),
                'fin'=>$this->input->post('fin'.$iteracion->getId())));
        }
    }
}

