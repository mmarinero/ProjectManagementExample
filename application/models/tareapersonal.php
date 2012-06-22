<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class TareaPersonal extends EX_Model {
    
    protected $fields = array();
    
    protected function initModel() {
        $this->fields['nombre'] = new TypeString('nombre');
        $this->fields['descripcion'] = new TypeText('descripcion');
        $this->references['Actividad'] = new Reference(__CLASS__, 'Actividad', 'cascade');
        $this->references['Trabajador'] = new Reference(__CLASS__, 'Trabajador', 'cascade');
    }
}
