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
        $buscadorJefe = new TrabajadoresProyecto();
        $jefeOrNull = $buscadorJefe->loadWhere(array(
            'Proyecto'=>$this->uri->segment(3),
            'Trabajador'=>$trabajador->getId(),
            'jefe'=>1));
        $this->jefeId= is_object($jefeOrNull) ? $jefeOrNull->get('Trabajador')->getDBValue() : null;
        $this->smarty->assign('proyectos',$proyectos);
        $this->smarty->assign('this', $this);
    }

    public function planIteracion($idIteracion){
        
        if ($this->trabajador->get('rol') != 'Jefe de proyecto' ||
                $idIteracion == null) {
            redirect('dashboard');
        }
        $idProyecto = array_shift($this->db->query(
                "select Proyecto from PlanFases join PlanIteracion on PlanFases = PlanFases.id where PlanIteracion.id = '".
                $idIteracion."'")->result_array());
        $proyecto = new Proyecto();
        $proyecto->loadId($idProyecto['Proyecto']);
        $actividadesLoader = new Actividad();
        $actividades = $actividadesLoader->loadArray(array('PlanIteracion'=>$idIteracion));
        $crearActividad= $actividadesLoader->getForm(site_url('actividades/nuevaActividadPost/'.$idIteracion),
                array('id'=>'crearActividad', 'class'=>'estandarForm'));
        $trabajadoresLoader = new Trabajador();
        $roles = Trabajador::getRoles();
        array_shift($roles);
        $trabajadores = $trabajadoresLoader->filterProyecto($proyecto);
        $this->smarty->assign('crearActividad',$crearActividad);
        $this->smarty->assign('actividades',$actividades);
        $this->smarty->assign('trabajadoresProyecto',$trabajadores);
        $this->smarty->assign('roles', $roles );
        $this->smarty->assign('idIteracion',$idIteracion);
        $this->smarty->view('planIteracion');
    }
    
    public function nuevaActividadPost($idIteracion){
        if ($this->trabajador->get('rol') != 'Jefe de proyecto' || $idIteracion == null) {
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
        redirect('actividades/planIteracion/'.$idIteracion);
    }

}

