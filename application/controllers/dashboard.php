<?php

/**
 * Panel de control principal
 *
 * @author Mario Marinero <mario.marinero@alumnos.uva.es>
 */
class dashboard extends CI_Controller{
    
    function __construct() {
        parent::__construct();
        $this->load->library('tank_auth');
        $this->lang->load('tank_auth');
        if (!$this->tank_auth->is_logged_in()) {									// logged in
            redirect('/auth/login');
	}
        $trabajador = Trabajador::fromTankAuth($this->tank_auth->get_user_id());
        if (!is_null($trabajador)) $this->smarty->assign('trabajador',$trabajador);
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
        $proyecto = new Proyecto($id);
        $crearProyecto = $proyecto->getForm(site_url('dashboard/crearProyecto'), array('id'=>'crearProyecto', 'class'=>'estandarForm'));
        $this->smarty->assign('crearProyecto', $crearProyecto);
        $this->smarty->view('proyecto');
    }
    
    function crearProyecto(){
        $proyecto = new Proyecto();
        $proyecto->DBInsert(assocRequest(array_keys($proyecto->getFields())),true);
        redirect('dashboard/proyecto/'.$proyecto->getId());
    }
}

