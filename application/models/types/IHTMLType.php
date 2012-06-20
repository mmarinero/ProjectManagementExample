<?php

/**
 * Interfaz que representa un tipo de datos respecto a la generacion de HTML
 * @author Mario Marinero <mario.marinero@alumnos.uva.es>
 */
interface IHTMLType {
    
    /**
     * Html para el tipo de acuerdo a los atributos pasados o previamente establecidos
     * @param $attributes atributos especificos para esta llamada
     * @return Html del tipo de datos 
     */
    public function getHtml($attributes = null);
    
     /**
     * Nombre de los campo de cara a su salida por pantalla
     * @param $name nombre a ser establecido 
     */
    public function setOutputName($name);
    
     /**
     * Nombre de los campo de cara a su salida por pantalla
     */
    public function getOutputName();
    
    /**
     * Html para que un tipo de datos pueda ser editado, alguna forma de input
     * destinado a formularios Html.
     * @param $attributes atributos especificos para esta llamada
     * @return Html del tipo de datos para ser editado
     */
    public function getInputHtml($attributes = null);
    
    /**
     * Atributos posibles para el tipo de datos Html, esta funcion proporciona
     * datos orientativos, atributos no incluidos pueden ser establecidos pero el 
     * resultado final puede no ser el deseado
     * @return  Atributos del elemento
     */
    public function getAttrDescription();
    
    public function getDefaultAttr();
    
    public function setAttr($attributes);
    
    public function setValidator($validator);
    
    /**
     * Valida el tipo de datos con el valor actual
     * @return El tipo de datos es valido 
     */
    public function isValid();
    
    //$attributes = array('name'=>'div', 'class'=>'genericInput', 'id'=>'test', 'children'=>array(),'data'=>array()); 
    //default styles 
    //default style constructor
    //a nivel de modelo establecer estilo global por defecto para estas clases. Clase ModelAttributes?
    
}
