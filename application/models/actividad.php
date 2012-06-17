<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Actividad extends EX_Model {

    protected $tableName = __CLASS__;
    
    protected $fields = array();
    
    public function __construct() {
        parent::__construct();
        $this->fields['nombre'] = new TypeString('nombre');
        $this->fields['descripcion'] = new TypeText('descripcion');
        $this->fields['rol'] = new TypeString('rol');
        $this->references['PlanIteracion'] = new Reference($this, 'PlanIteracion', 'PlanIteracion',array('delete'=>'cascade','update'=>'cascade'));
    }
}
