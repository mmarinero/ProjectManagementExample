<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class TrabajadoresActividad extends EX_Model {
    
    protected $fields = array();
    
    protected function initModel() { 
        $this->references['Actividad'] = new Reference(__CLASS__, 'Actividad', 'cascade');
        $this->references['Trabajador'] = new Reference(__CLASS__, 'Trabajador', 'cascade');
    }
}
