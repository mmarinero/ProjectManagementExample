<?php

/**
 * Clase abstracta con funciones genericas para implementar los distintos tipos
 *
 * @author Mario Marinero <mario.marinero@alumnos.uva.es>
 */
abstract class BaseType implements IType, IDBType, IHTMLType{
    
    protected $name;
    
    protected $value;
    
    protected $validator;
    
    function getName() {
        return $this->name;
    }
    
    public function __construct($config) {
        if (isset($config['name'])) $this->name = $config['name'];
        if (isset($config['value'])) $this->value = $config['value'];
        if (isset($config['validator'])) $this->validator = $config['validator'];
    }
    
    public function getCreateSql(){
        throw new Exception('Unimplemented');
    }
    
    public function setName($name){
        $this->name = $name;
    }
    
    public function getRaw(){
        return $this->value;
    }
    
    public function setValue($value) {
        $this->value = $value;
    }
    
    public function getDBDefaultValue() {
        return null;
    }
    
    public function getDBValue() {
        return $this->value;
    }
    
    public function setDBValue($value) {
        $this->value = $value;
    }
    
    public function setValidator(callable $validator){
        $this->validator = $validator;
    }
    
    public function isValid() {
        return $this->validator($this);
    }
    
    public function validateValue() {
        return true;
    }
    
    public function sanitizeValue(){
        return true;
    }
}

?>
