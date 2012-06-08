<?php

/**
 * Interfaz que representa un tipo de datos respecto a la generacion de HTML
 * @author Mario Marinero <mario.marinero@alumnos.uva.es>
 */
interface IHTMLType {
    public function getHtml($attributes = null);
    public function getInputHtml($attributes = null);
    public function getStyleDescription();
    $attributes = array('name'=>'div', 'class'=>'genericInput', 'id'=>'test', 'children'=>array()),'data'=>array(), 'optional'=>array()); 
    //default styles 
    //default style constructor
    //a nivel de modelo establecer estilo global por defecto para estas clases. Clase ModelAttributes?
    
}
