<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class TareaPersonal extends EX_Model {

    protected $tableName = __CLASS__;
    
    protected $fields = array();
    
    public function __construct() {
        parent::__construct();
        $this->fields[] = new TypeString('nombre');
        $this->fields[] = new TypeText('descripcion');
        $this->references[] = new Reference($this, 'Actividad', 'Actividad',array('delete'=>'cascade','update'=>'cascade'));
        $this->references[] = new Reference($this, 'Trabajador', 'Trabajador',array('delete'=>'cascade','update'=>'cascade'));
        $this->change();
    }
}
