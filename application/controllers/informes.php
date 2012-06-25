<?php

/**
 * Panel de control principal
 *
 * @author Mario Marinero <mario.marinero@alumnos.uva.es>
 */
class Informes extends CI_Controller{
    
    
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

    function lista(){
        $this->smarty->view('informes');
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
   
}

