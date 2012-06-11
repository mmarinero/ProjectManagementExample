<?php

/**
 * Interfaz que representa un tipo de datos y sus funciones genericas
 * @author Mario Marinero <mario.marinero@alumnos.uva.es>
 */
interface IType {
    
    /**
     * @return string El nombre del campo
     */
    public function getName();
    
    /**
     * @param string Establece el nombre del campo 
     */
    public function setName($name);
    
    /**
     * @return mixed Representacion nativa del tipo de datos 
     */
    public function getRaw();
    
    /**
     * @param Valor que se asignara al campo
     */
    public function setValue($value);
    
     /**
     * @param Valor que se asignara al campo
     */
    public function setValue($value);
}