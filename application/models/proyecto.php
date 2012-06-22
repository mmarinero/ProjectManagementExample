<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Proyecto extends EX_Model {
    
    protected $fields = array();

    
    protected function initModel() {
        $this->fields['nombre'] = new TypeString(array('name'=>'nombre','outputName'=>'Nombre'));
        $this->fields['descripcion'] = new TypeText(array('name'=>'descripcion','outputName'=>'Descripción'));
        $this->fields['inicio'] = new TypeDate(array('name'=>'inicio','outputName'=>'Fecha de inicio'));
        $this->fields['fin'] = new TypeDate(array('name'=>'fin','outputName'=>'Fecha de fin'));
        $this->fields['comenzado'] = new TypeBoolean(array('name'=>'comenzado','outputName'=>'Comenzado'));
        $this->fields['cerrado'] = new TypeBoolean(array('name'=>'cerrado','outputName'=>'Cerrado'));
    }
    
    public function DBInsert($values, $crearePlanFases = false){
	parent::DBInsert($values);
        if ($crearePlanFases) {
            $planFases = new PlanFases();	
            $planFases->DBInsert(array('Proyecto'=>$this->getId()));
            return $this->db->insert_id();
        }
    }
    
    public function filterTrabajador($trabajador){
        return $this->loadSimpleJoin($trabajador, 'TrabajadoresProyecto');
    }
}
