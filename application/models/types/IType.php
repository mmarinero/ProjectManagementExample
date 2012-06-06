<?php

/**
 * Interfaz que representa un tipo de datos y sus funciones genericas
 * @author Mario Marinero <mario.marinero@alumnos.uva.es>
 */
interface IType {
    /**
     * @return Representacion nativa del tipo de datos 
     */
    public function getRaw();
}