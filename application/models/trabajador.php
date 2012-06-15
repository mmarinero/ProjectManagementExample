<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trabajador extends EX_Model {
    protected $tableName = 'trabajador';
    
    protected $fields = array();
    
    public function __construct() {
        parent::__construct();
        $nombre = new TypeString(array('name'=>'nombre'));
        $descripcion = new TypeString(array('name'=>'descripcion'));
	$proyectos = new Reference($this,new TrabajadoresProyecto(), 'proyectos');
	$tareas = new Reference($this,new Tareas(), 'tareas');
        $this->fields = array($nombre, $descripcion, $proyectos, $tareas);
    }
        
}
