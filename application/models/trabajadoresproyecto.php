<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class TrabajadoresProyecto extends EX_Model {

    protected $tableName = __CLASS__;
    
    protected $fields = array();
    
    public function __construct() {
        parent::__construct();
        $this->references['Proyecto'] = new Reference($this, 'Proyecto', 'Proyecto');
        $this->references['Trabajador'] = new Reference($this, 'Trabajador', 'Trabajador');
        $this->change();
    }
}
