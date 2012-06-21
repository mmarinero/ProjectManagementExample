<?php

/**
 * Panel de control principal
 *
 * @author Mario Marinero <mario.marinero@alumnos.uva.es>
 */
class dashboard extends CI_Controller{
    
    private $trabajador;
    
    private $jefeId;
    
    function __construct() {
        parent::__construct();
        $this->load->library('tank_auth');
        $this->lang->load('tank_auth');
        if (!$this->tank_auth->is_logged_in()) {									// logged in
            redirect('/auth/login');
	}
        $trabajador = Trabajador::fromTankAuth($this->tank_auth->get_user_id());
        $buscadorJefe = new TrabajadoresProyecto();
        $jefeOrNull = $buscadorJefe->loadWhere(array(
            'Proyecto'=>$this->uri->segment(3),
            'Trabajador'=>$trabajador->getId(),
            'jefe'=>1));
        $this->smarty->assign('jefe', $jefeOrNull);
        $this->jefeId= is_object($jefeOrNull) ? $jefeOrNull->get('Trabajador')->getDBValue() : null;
        if (!is_null($trabajador)) $this->smarty->assign('trabajador',$trabajador);
        $this->trabajador = $trabajador;
        $proyectoLoader = new Proyecto();
        $this->smarty->assign('idProyecto', $this->uri->segment(3));
        if ($trabajador->get('rol')->getDBValue() == 'admin') {
            $proyectos = $proyectoLoader->loadArray();
        } else {
            $proyectos = $proyectoLoader->filterTrabajador($trabajador);
        }
        $this->smarty->assign('proyectos',$proyectos);
        $this->smarty->assign('this', $this);
    }

    function index(){
        $this->smarty->view('dashboard');
    }
    
    function proyecto($id=null){
        if ($id===null) {
            redirect('dashboard');
        }
        $proyecto = new Proyecto();
        $proyecto->loadId($id);
        $trabajadoresLoader = new Trabajador();
        $trabajadores = $trabajadoresLoader->filterProyecto($proyecto);
        $this->smarty->assign('trabajadores', $trabajadores);
        $this->smarty->assign('proyecto', $proyecto);
        $this->smarty->view('proyecto');
    }
    
    function crearProyecto($id=null){
        if ($this->trabajador->get('rol') != 'admin') {
            redirect('dashboard');
        }
        $proyecto = new Proyecto();
        $trabajadoresLoader = new Trabajador();
        $trabajadores = $trabajadoresLoader->loadArray();
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
        if ($this->trabajador->get('rol') != 'admin') {
            redirect('dashboard');
        }
        $proyecto = new Proyecto();
        

        $proyecto->DBInsert(assocRequest(array_keys($proyecto->getFields())));
        $trabajadorProyecto = new TrabajadoresProyecto();
              $trabajadorProyecto->DBInsert(array(
                'Proyecto'=> $proyecto->getId(),
                'Trabajador' => $this->input->post('jefeProyecto'),
                'porcentaje' => $this->input->post('dedicacion'),
                'jefe'=> 1));
//        $trabajadoresLoader = new Trabajador();
//        $trabajadores = $trabajadoresLoader->loadArray();
//        foreach ($trabajadores as $trabajador) {
//            $input[] = $trabajador->getId();
//            $input[] = 'dedicacion'.$trabajador->getId();
//        }
//        $input = assocRequest($input);
//        foreach ($trabajadores as $trabajador) {
//            if (!is_null($input[$trabajador->getId()])){
//                $trabajadorProyecto = new TrabajadoresProyecto();
//                $jefe = 0;
//                if ($trabajador->getId() == $this->input->post('jefeProyecto')){
//                    $jefe = 1;
//                }
//                $trabajadorProyecto->DBInsert(array(
//                    'Proyecto'=> $proyecto->getId(),
//                    'Trabajador' => $trabajador->getId(),
//                    'porcentaje' => $input['dedicacion'.$trabajador->getId()],
//                    'jefe'=> $jefe));
//            }
//        }
        redirect('dashboard/proyecto/'.$proyecto->getId());
    }
    
    function editarProyecto($id=null){
        $trabajadoresLoader = new Trabajador();
        $trabajadores = $trabajadoresLoader->loadArray();
        $this->smarty->assign('fixedTrabajadores', $trabajadores);
        if ($this->trabajador->get('rol') != 'admin' || $id == null) {
            redirect('dashboard');
        }
        $proyecto = new Proyecto();
        $crearProyecto = $proyecto->loadId($id)->getForm(site_url('dashboard/editarProyectoPost/'.$id), array('id'=>'crearProyecto', 'class'=>'estandarForm'));
        $this->smarty->assign('crearProyecto', $crearProyecto);
        $this->smarty->assign('buttonText', 'Editar proyecto');
        $this->smarty->view('crearProyecto');
    }
    
    function editarProyectoPost($id){
        if ($this->trabajador->get('rol') != 'admin' || $id == null) {
            redirect('dashboard');
        }
        $proyecto = new Proyecto();
        $proyecto->loadId($id);
        $proyecto->DBUpdate(assocRequest(array_keys($proyecto->getFields())));
        redirect('dashboard/proyecto/'.$proyecto->getId());
    }
    
    function eliminarProyecto($id=null){
        if ($this->trabajador->get('rol') != 'admin' || $id == null) {
            redirect('dashboard');
        }
        $proyecto = new Proyecto();
        $proyecto->DBdelete($id);
        redirect('dashboard');
    }
    
    function planes($id=null){
        if ($this->trabajador->get('rol') != 'Jefe de proyecto' ||
                $id == null ||
                $this->trabajador->getId() != $this->jefeId) {
            redirect('dashboard');
        }
        $planFases = new PlanFases();
        $noEncontrado = $planFases->loadWhere(array('Proyecto'=>$id));
        if (is_null($noEncontrado)){
            $crearFases= $planFases->getForm(site_url('dashboard/crearFasesPost/'.$id),
                    array('id'=>'crearPlanFases', 'class'=>'estandarForm'));
            $this->smarty->assign('crearFases', $crearFases);
            $trabajadoresLoader = new Trabajador();
            $trabajadores = $trabajadoresLoader->loadArray();
            $trabajadores = Trabajador::filterLevel($trabajadores, array(1,2,3,4));
            $this->smarty->assign('trabajadores', $trabajadores);
            $this->smarty->view('planes');
        } else {
            $idPlan = array_shift($this->db->query("select PlanFases.id from PlanFases ".
                    "join Proyecto on Proyecto.id = Proyecto where Proyecto = '$id'")->result_array());
            $iteracionesLoader = new PlanIteracion();
            $iteraciones = $iteracionesLoader->loadArray(array('PlanFases'=> $idPlan['id']));
            foreach ($iteraciones as $iteracion) {
                $iteracion->get('inicio')->setAttr(array('nameAppend'=>$iteracion->getId()));
                $iteracion->get('fin')->setAttr(array('nameAppend'=>$iteracion->getId()));
            }
            $this->smarty->assign('iteraciones',$iteraciones);
            $this->smarty->assign('idPlan',$idPlan);
            $this->smarty->view('planes');
        }
    }
    
    function crearFasesPost($idProyecto) {
        if ($this->trabajador->get('rol') != 'Jefe de proyecto' ||
                $idProyecto == null ||
                $this->trabajador->getId() != $this->jefeId) {
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
        redirect('dashboard/planes/'.$idProyecto);
    }
    function modificarEstimacionIteracionPost($idPlan = null){
        if ($this->trabajador->get('rol') != 'Jefe de proyecto' ||
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

