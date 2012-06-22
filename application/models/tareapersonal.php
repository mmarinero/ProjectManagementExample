<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class TareaPersonal extends EX_Model {

    protected $tableName = __CLASS__;
    
    protected $fields = array();
    
    protected function initModel() {
        $this->fields['nombre'] = new TypeString('nombre');
        $this->fields['descripcion'] = new TypeText('descripcion');
        $this->references['Actividad'] = new Reference($this, 'Actividad', 'Actividad',array('delete'=>'cascade','update'=>'cascade'));
        $this->references['Trabajador'] = new Reference($this, 'Trabajador', 'Trabajador',array('delete'=>'cascade','update'=>'cascade'));
    }
}
