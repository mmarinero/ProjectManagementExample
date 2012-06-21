<?php

/**
 * Clase abstracta con funciones genericas para implementar los distintos tipos
 *
 * @author Mario Marinero <mario.marinero@alumnos.uva.es>
 */
abstract class BaseType implements IType, IDBType, IHTMLType{
    
    protected $name;
    
    protected $outputName;
    
    protected $value;
    
    protected $validator;
    
    protected $attributes = array();
    
    public function __construct($config) {
        if (gettype($config) === 'string') {
            $this->name = $config;
        } else {
            if (isset($config['name'])) $this->name = $config['name'];
            if (isset($config['outputName'])) $this->outputName = $config['outputName'];
            if (isset($config['value'])) $this->value = $config['value'];
            if (isset($config['validator'])) $this->validator = $config['validator'];
            if (isset($config['attributes'])) $this->attributes = $config['attributes'];
        }
    }
    
    function getName() {
        return $this->name;
    }
    
    public function setName($name){
        $this->name = $name;
    }
    
    public function setOutputName($name){
        $this->outputName = $name;
    }
    
    public function getOutputName(){
        return $this->outputName ?:$this->name;
    }

    
     /**
     * Detección de un error común al olvidar usar getValue() o getHtml()
     * Se puede eliminar sin consecuencias o devolver automaticamente el valor.
     * @throws Exception 
     */
    public function __toString() {
        throw new Exception($this->getName());
    }
    
    public function getValue() {
        return $this->value;
    }
    
    public function setValue($value) {
        $this->value = $value;
    }
    
    public function setValidator($validator){
        $this->validator = $validator;
    }
    
    public function isValid() {
        return $this->validator($this);
    }
    
    public function getInputHtml($newAttributes = null){
        $attributes = $this->selectAttributes($newAttributes);
        $nameAppend= isset($attributes['nameAppend']) ? $attributes['nameAppend'] : '';
        $class = isset($attributes['class']) ? $attributes['class'].' ': '';
        return '<input type="text" class="'.$class.'"'.  HtmlAttributesFromArray($attributes).' name="'.$this->getName().$nameAppend.'" value="'.$this->value.'"></input>';
    }
    
    public function getHtml($newAttributes = null){
        $attributes = $this->selectAttributes($newAttributes);
        return '<span '.HtmlAttributesFromArray($attributes).">$this->value</span>";
    }
    
    /**
     * Función de conveniencia para seleccionar los atributos pasados o los ya presentes
     * @param type $newAttributes
     * @return type 
     */
    protected function selectAttributes($newAttributes){
        if ($newAttributes !== null) return $newAttributes;
        else return $this->attributes;
    }
    
    
    public function setAttributes($attributes){
        $this->attributes = $attributes;
    }
    
}
