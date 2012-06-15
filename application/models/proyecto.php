<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Proyecto extends EX_Model {

    protected $tableName = __CLASS__;
    
    protected $fields = array();
    
    public function __construct() {
        parent::__construct();
        $nombre = new TypeString(array('name'=>'nombre'));
        $this->fields = array($nombre);
    }
}
