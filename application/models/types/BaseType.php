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
    
    protected $attributes = array();
    
    function getName() {
        return $this->name;
    }
    
    public function __construct($config) {
        if (gettype($config) === 'string') {
            $this->name = $config;
        } else {
            if (isset($config['name'])) $this->name = $config['name'];
            if (isset($config['value'])) $this->value = $config['value'];
            if (isset($config['validator'])) $this->validator = $config['validator'];
            if (isset($config['attributes'])) $this->attributes = $config['attributes'];
        }
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
    
    public function getInputHtml($newAttributes = null){
        
        if ($newAttributes !== null) $attributes = $newAttributes;
        else $attributes = $this->attributes;
        return '<input type="text" '.  HtmlAttributesFromArray($attributes).'.name="'.$this->getName().'" value="'.$this->value.'"></input>';
    }
    
    public function getHtml($newAttributes = null){
        $attributes = $this->selectAttributes($newAttributes);
        return '<span '.HtmlAttributesFromArray($attributes).">$this->value</span>";
    }
    
    protected function selectAttributes($newAttributes){
        if ($newAttributes !== null) return $newAttributes;
        else return $this->attributes;
    }
    
    public function getAttrDescription(){
        
    }
    
    public function getDefaultAttr(){
        
    }
    
    public function setAttr($attributes){
        $this->attributes = $attributes;
    }
}

?>
