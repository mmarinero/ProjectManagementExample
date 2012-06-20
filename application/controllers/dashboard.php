<?php

/**
 * Panel de control principal
 *
 * @author Mario Marinero <mario.marinero@alumnos.uva.es>
 */
class dashboard extends CI_Controller{
    
    private $trabajador;
    

    function __construct() {
        parent::__construct();
        $this->load->library('tank_auth');
        $this->lang->load('tank_auth');
        if (!$this->tank_auth->is_logged_in()) {									// logged in
            redirect('/auth/login');
	}
        $trabajador = Trabajador::fromTankAuth($this->tank_auth->get_user_id());
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
        $proyecto->DBInsert(assocRequest(array_keys($proyecto->getFields())),true);
        
        $trabajadoresLoader = new Trabajador();
        $trabajadores = $trabajadoresLoader->loadArray();
        foreach ($trabajadores as $trabajador) {
            $input[] = $trabajador->getId();
            $input[] = 'dedicacion'.$trabajador->getId();
        }
        $input = assocRequest($input);
        log_message('debug', 'trabajadores '.print_r($input, true));
        foreach ($trabajadores as $trabajador) {
            if (!is_null($input[$trabajador->getId()])){
                $trabajadorProyecto = new TrabajadoresProyecto();
                $trabajadorProyecto->DBInsert(array(
                    'Proyecto'=> $proyecto->getId(),
                    'Trabajador' => $trabajador->getId(),
                    'porcentaje' => $input['dedicacion'.$trabajador->getId()]));
            }
        }
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
}

