<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trabajador extends EX_Model {
    
    protected $fields = array();
    
    public static function rolValidator(){
        $roles = static::$roles;
        return function($thisObj) use ($roles){
            if (isset($roles[$thisObj->val()])) return true;
            else return false;
        };
    }
    
    protected function initModel() {
        $this->fields['nombre'] = new TypeString(array('name'=>'nombre'));
        $this->fields['rol'] = new TypeString(array('name'=>'rol'));
        $tempRol = $this->fields['rol'];
        $tempRol->setValidator($this->rolValidator());
        $this->fields['descripcion'] = new TypeString(array('name'=>'descripcion'));
	$this->references['users'] = new Reference(__CLASS__, 'users', 
                array('update'=>'cascade','delete'=>'cascade', 'external' => true));
    }
    
    public static $roles =array(
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
            $result = in_array($trabajador->get('rol')->val(), $roles);
            return $in ? $result: !$result;
        });
    }
    
    public static function filterLevel($trabajadores, $levels, $in = true){
        $allRoles = static::$roles;
        return array_filter($trabajadores,function($trabajador) use ($levels, $in, $allRoles){
            $result = in_array($allRoles[$trabajador->get('rol')->val()], $levels);
            return $in ? $result: !$result;
        });
    }
    
    public function esTrabajadorNormal(){
        return ($this->roles[$this->get('rol')] > 1) ? true : false;
    }
    
    public static function fromTankAuth($id) {
        return new static(array('users'=>$id));
    }
    
    public function filterProyecto($proyecto){
        return $this->loadSimpleJoin($proyecto, 'TrabajadoresProyecto', array('porcentaje'));
    }
    
    public function auth($rol){
         return $rol === $this->get('rol')->val() ? true : show_error('No autorizado', 403);
    }
    
    public function authLevel($level){
        return $level === static::$roles[$this->get('rol')->val()] ? true : show_error('No autorizado', 403);
    }
    
    public function authSuperiorLevel($level){
        return $level >= static::$roles[$this->get('rol')->val()] ? true : show_error('No autorizado', 403);
    }
    
    /**
     *
     * @param boolean $redirectIfNot
     * @param string $url
     * @return Trabajador 
     */
    public static function loggedTrabajador($redirectIfNot = true, $url = 'auth/login') {
        $thisInstance = new static();
        if (!$thisInstance->tank_auth->is_logged_in()) {
            if ($redirectIfNot) redirect($url);
	}
        return Trabajador::fromTankAuth($thisInstance->tank_auth->get_user_id());
    }
}
