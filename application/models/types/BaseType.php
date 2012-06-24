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
        if (is_string($config)) {
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
    
    public function getInputHtml($attributes = array()){
        $attributes = $this->processAttributes($attributes, true, true);
        return '<input '. static::htmlAttributesFromArray($attributes).'></input>';
    }
    
    public function getHtml($attributes = array()){
        $attributes = $this->processAttributes($attributes);
        return '<span '. static::htmlAttributesFromArray($attributes).">{$this->val()}</span>";
    }
    
    protected function processAttributes($attributes, $name=false, $value=false){
        $class = array_merge(isset($this->attributes['class']) ? 
                $this->attributes['class'] : array(), 
                isset($attributes['class']) ? $attributes['class'] : array());
        $processedAttributes = array_merge($this->attributes, $attributes);
        if (!empty($class)) $processedAttributes['class'] = implode (' ', $class);
        if ($name){
            $processedAttributes['name'] = isset($processedAttributes['name']) ?
                $processedAttributes['name']:$this->getName();
        }
        if ($value){
            $processedAttributes['value'] = isset($processedAttributes['value']) ?
                $processedAttributes['value']:$this->val();
        }
        if (isset($processedAttributes['appendName'])) {
            $processedAttributes['name'] = $processedAttributes['name'].$processedAttributes['appendName'];
        }
        return $processedAttributes;
    }
    
    public static function htmlAttributesFromArray($attributesArray) {
        $attributesString = '';
        foreach($attributesArray as $name => $value){
            $attributesString.= "$name=\"$value\" ";
        }
        return $attributesString;
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
    
    /**
     * Establece los atributos de este elemento, los atributos seran procesados 
     * tras mezclarse con los pasados en las llamadas a los metodos de obtención de html (los 
     * pasados en las llamadas tienen prioridad
     * 4 atributos especiales:
     * class: los elementos de este array formaran las clases del html resultante
     * nameAppend : se concatena con el nombre por defecto del tipo (construcción de listas)
     * name: Sustituye al name predefinido
     * children: No implementado aún, permite añadir atributos a elementos hijos
     * value: Sustituye al value predefinido
     * @param $attributes atributos establecidos
     */
    public function setAttributes($attributes){
        $this->attributes = $attributes;
    }
    
}
