<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Actividad extends EX_Model {

    protected $tableName = __CLASS__;
    
    protected $fields = array();
    
    public function __construct() {
        parent::__construct();
        $this->fields[] = new TypeString('nombre');
        $this->fields[] = new TypeText('descripcion');
        $this->fields[] = new TypeString('rol');
        $this->references[] = new Reference($this, 'PlanIteracion', 'PlanIteracion',array('delete'=>'cascade','update'=>'cascade'));
        $this->change();
    }
}
