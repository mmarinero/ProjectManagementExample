<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trabajador extends EX_Model {
    protected $tableName = __CLASS__;
    
    protected $fields = array();
    
    public static function rolValidator(){
        $roles = static::getRoles();
        return function($thisObj) use ($roles){
            if (isset($roles[$thisObj->getValue()])) return true;
            else return false;
        };
    }
    
    public function __construct() {
        parent::__construct();
        $this->fields['nombre'] = new TypeString(array('name'=>'nombre'));
        $this->fields['rol'] = new TypeString(array('name'=>'rol'));
        $tempRol = $this->fields['rol'];
        $callable =$this->rolValidator();
        $tempRol->setValidator($callable);
        $this->fields['descripcion'] = new TypeString(array('name'=>'descripcion'));
	$this->references['users'] = new Reference($this, 'users', 'users');
	//$usersReference = $this->references['users']; 
	//$usersReference->setCIcreateColumnArray(array('type' => 'int', 'constraint' => '11', 'null'=>true));
    }
    
    public static function getRoles(){
        return array(
        'admin' => 0,
        'Jefe de proyecto' => 1,
        'Analista'=>2,
        'Diseñador'=>3,
        'Analista-programador'=>3,
        'Responsable equipo de pruebas'=>3,
        'Programador'=>4,
        'Probador'=>4);
    }
    
    public $roles =array(
        'admin' => 0,
        'Jefe de proyecto' => 1,
        'Analista'=>2,
        'Diseñador'=>3,
        'Analista-programador'=>3,
        'Responsable equipo de pruebas'=>3,
        'Programador'=>4,
        'Probador'=>4);
    
    public static function filterRoles($trabajadores, $roles, $in = true){
        return array_filter($trabajadores,function($trabajador) use ($roles, $in){
            $result = in_array($trabajador->get('rol')->getDBValue(), $roles);
            return $in ? $result: !$result;
        });
    }
    
    public static function filterLevel($trabajadores, $levels, $in = true){
        $allRoles = static::getRoles();
        return array_filter($trabajadores,function($trabajador) use ($levels, $in, $allRoles){
            $result = in_array($allRoles[$trabajador->get('rol')->getDBValue()], $levels);
            return $in ? $result: !$result;
        });
    }
    
    public function esTrabajadorNormal(){
        return ($this->roles[$this->get('rol')] > 1) ? true : false;
    }
    
    public static function fromTankAuth($id) {
        $trabajador = new static();
        return array_shift($trabajador->loadArray(array('users'=>$id)));
    }
    
    public function filterProyecto($proyecto){
        return $this->loadSimpleJoin($proyecto, 'TrabajadoresProyecto', array('porcentaje'));
    }
}
