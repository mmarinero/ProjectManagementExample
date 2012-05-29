<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Trabajador extends EX_Model // clase todavia no definida para poner los metodos genericos de insertar y recuperar valores
{
    private $tablename = 'trabajador';
    
    //definicion de los campos del modelo para crear la tabla en la base de datos e instanciar cada campo con su tipo
    //deberia accederse del el metodo que se definira en SP_Model getFields()
    private $fields = array(
                    'nombre' => array('type'=>'string', 'DBtype'=>'varchar', 'DBconstraint'=>100, 'key'=> 'unique'),
                    'id' => array('type'=>'int', 'DBtype'=>'int', 'DBconstraint'=>100, 'key'=> 'primary'),
                    'password' => array('type'=>'string', 'DBtype'=>'varchar', 'DBtypeConstraint'=>100)
                );
    
    //Aqui van los metodos especificos para manejar los trabajadores respecto a la base de datos
        

}
/* End of file trabajador.php */
/* Location: ./application/models/trabajador.php */