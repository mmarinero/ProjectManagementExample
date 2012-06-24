<?php

/**
 * Interfaz que representa un tipo de datos respecto a la generacion de HTML
 * @author Mario Marinero <mario.marinero@alumnos.uva.es>
 */
interface IHTMLType {
    
    /**
     * Html para el tipo de acuerdo a los atributos pasados o previamente establecidos
     * @param $attributes atributos especificos para esta llamada @see setAttributes()
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
     * @param $attributes atributos especificos para esta llamada @see setAttributes()
     * @return Html del tipo de datos para ser editado
     */
    public function getInputHtml($attributes = null);
    
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
    public function setAttributes($attributes);
    
    /**
     * Establece un validador asociado que se empleara para comprobar si los datos introducidos
     * son correctos
     */
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
