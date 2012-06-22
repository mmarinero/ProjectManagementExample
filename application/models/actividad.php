<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Actividad extends EX_Model {

    protected $tableName = __CLASS__;
    
    protected $fields = array();
    
    protected function initModel() {
        $this->fields['nombre'] = new TypeString(array('name'=>'nombre','outputName'=>'Nombre'));
        $this->fields['descripcion'] = new TypeText(array('name'=>'descripcion','outputName'=>'Descripción'));
        $this->fields['horas'] = new TypeInt(array('name'=>"horas",'outputName'=>"Horas/persona"));
        $this->fields['rol'] = new TypeString('rol');
        $this->references['PlanIteracion'] = new Reference($this, 'PlanIteracion', 'PlanIteracion',array('delete'=>'cascade','update'=>'cascade'));
    }
}
