<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Trabajador extends EX_Model {
    protected $tableName = 'trabajador';
    
    protected $fields = array();
    
    
    
    public function __construct() {
        parent::__construct();
        $nombre = new TypeString(array('name'=>'nombre'));
        $descripcion = new TypeString(array('name'=>'descripcion'));
        $this->fields = array($nombre, $descripcion);
    }


    //Aqui van los metodos especificos para manejar los trabajadores respecto a la base de datos
        

}
/* End of file trabajador.php */
/* Location: ./application/models/trabajador.php */