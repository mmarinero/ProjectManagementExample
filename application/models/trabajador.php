<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trabajador extends EX_Model {
    protected $tableName = __CLASS__;
    
    protected $fields = array();
    
    public function __construct() {
        parent::__construct();
        $this->fields[] = new TypeString(array('name'=>'nombre'));
        $this->fields[] = new TypeString(array('name'=>'rol'));
        $this->fields[] = new TypeString(array('name'=>'descripcion'));
        $this->change();
    }
        
}
