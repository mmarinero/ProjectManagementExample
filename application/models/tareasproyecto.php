<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class TareasProyecto extends EX_Model {

    protected $tableName = __CLASS__;
    
    protected $fields = array();
    
    public function __construct() {
        parent::__construct();
        $proyectos = new Reference($this, new Proyecto(), 'proyectos');
        $trabajadores = new Reference($this, new Trabajador(), 'trabajadores');
        $this->fields = array($proyectos, $trabajadores);
    }
}
