<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class TrabajadoresProyecto extends EX_Model {
    
    protected $fields = array();
    
    protected function initModel() { 
        $this->references['Proyecto'] = new Reference(__CLASS__, 'Proyecto', 'cascade');
        $this->references['Trabajador'] = new Reference(__CLASS__, 'Trabajador', 'cascade');
        $this->fields['porcentaje'] = new TypeInt(array('name'=>'porcentaje','outputName'=>'Porcentaje'));
        $this->fields['jefe'] = new TypeBoolean(array('name'=>'jefe','outputName'=>'Jefe de proyecto'));
    }
    
    public static function filterTrabajadores($proyecto, $trabajadores){
        return array_filter($trabajadores,function($proyecto){
            $result = in_array($trabajador->get('rol')->val(), $roles);
            return $in ? $result: !$result;
        });
    }
    
    public static function filterProyectos($trabajador, $proyectos){
        return array_filter($proyectos,function($trabajador){
            $result = in_array($allRoles[$trabajador->get('rol')->val()], $levels);
            return $in ? $result: !$result;
        });
    }
}
