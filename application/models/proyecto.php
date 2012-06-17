<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Proyecto extends EX_Model {

    protected $tableName = __CLASS__;
    
    protected $fields = array();
    
    public function __construct() {
        parent::__construct();
        $this->fields['nombre'] = new TypeString(array('name'=>'nombre'));
        $this->fields['descripcion'] = new TypeText(array('name'=>'descripcion'));
        $fases = array('Inicio', 'Elaboración', 'Construción', 'Transición');
        foreach ($fases as $fase){
            $this->fields["Iteraciones{$fase}"] = new TypeInt("Iteraciones{$fase}");
        }
        $this->change();
    }
}
