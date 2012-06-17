<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class PlanIteracion extends EX_Model {

    protected $tableName = __CLASS__;
    
    protected $fields = array();
    
    public function __construct() {
        parent::__construct();
        $this->fields['nombre'] = new TypeString('nombre');
        $this->fields['descripcion'] = new TypeText('descripcion');
        $this->references['Proyecto'] = new Reference($this, 'Proyecto', 'Proyecto',array('delete'=>'cascade','update'=>'cascade'));
    }
}
