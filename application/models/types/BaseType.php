<?php

/**
 * Clase abstracta con funciones genericas para implementar los distintos tipos
 *
 * @author Mario Marinero <mario.marinero@alumnos.uva.es>
 */
abstract class BaseType implements IType, IDBType, IHTMLType{
    
    protected $name;
    
    protected $outputName = null;
    
    protected $value;
    
    protected $validator = null;
    
    protected $attributes = array();
    
    public function __construct($config) {
        if (gettype($config) === 'string') {
            $this->name = $config;
        } else {
            $options = array('name','outputName','value','validator','attributes');
            if (isset($config[0])) { //asumir no es asociativo y va por orden
                foreach ($config as $value) {
                    $property = $options[$value];
                    $this->$property = $value;
                }
            }else{
                foreach ($options as $option) {
                    if (isset($config[$option])) $this->$option = $config[$option];
                }
            }
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
        return !is_null($this->outputName) ? $this->outputName : $this->name;
    }

    
     /**
     * Detección de un error común al olvidar usar val() o getHtml()
     * la función se puede eliminar sin consecuencias o devolver automaticamente el valor.
     * @throws Exception 
     */
    public function __toString() {
        throw new Exception($this->getName());
    }
    
    public function val() {
        return $this->value;
    }
    
    public function setValue($value) {
        $this->value = $value;
        return $this->isValid();
    }
    
    public function setValidator($validator){
        $this->validator = $validator;
    }
    
    public function isValid() {
        if ($this->validator !== null) {
            $validator = $this->validator;
            return $validator($this);
        }else{
            return null;
        }
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
