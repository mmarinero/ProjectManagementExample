<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class PlanIteracion extends EX_Model {
    
    protected $fields = array();
    
    protected function initModel() {
        $this->fields['nombre'] = new TypeString(array('name'=>'nombre', "outputName"=>'Nombre'));
        $this->fields['descripcion'] = new TypeText(array('name'=>'descripcion', "outputName"=>'Descripción'));
        $this->fields['inicio'] = new TypeDate(array('name'=>'inicio','outputName'=>'Estimación de inicio'));
        $this->fields['fin'] = new TypeDate(array('name'=>'fin','outputName'=>'Estimación de fin'));
        $this->fields['iniciada'] = new TypeBoolean(array('name'=>'iniciada','outputName'=>'Iteración iniciada'));
        $this->fields['cerrada'] = new TypeBoolean(array('name'=>'cerrada','outputName'=>'Iteración cerrada'));
        $this->references['PlanFases'] = new Reference(__CLASS__, 'PlanFases', 'cascade');
    }
}
