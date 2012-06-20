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
        $this->smarty->assign('proyectos',$proyectoLoader->loadArray());
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
        $this->smarty->assign('proyecto', $proyecto->loadId($id));
        $this->smarty->view('proyecto');
    }
    
    function crearProyecto($id=null){
        if ($this->trabajador->get('rol') != 'admin') {
            redirect('dashboard');
        }
        $proyecto = new Proyecto();
        $crearProyecto = $proyecto->getForm(site_url('dashboard/crearProyectoPost'), array('id'=>'crearProyecto', 'class'=>'estandarForm'));
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
        redirect('dashboard/proyecto/'.$proyecto->getId());
    }
    
    function editarProyecto($id=null){
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

