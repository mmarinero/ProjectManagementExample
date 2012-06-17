<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Proyecto extends EX_Model {

    protected $tableName = __CLASS__;
    
    protected $fields = array();
    
    public function __construct() {
        parent::__construct();
        $nombre = new TypeString(array('name'=>'nombre'));
        $descripcion = new TypeText(array('name'=>'descripcion'));
        $this->fields = array($nombre, $descripcion);
        $fases = array('Inicio', 'Elaboración', 'Construción', 'Transición');
        foreach ($fases as $fase){
            $this->fields[] = new TypeInt("Iteraciones{$fase}");
        }
        $this->change();
    }
}
