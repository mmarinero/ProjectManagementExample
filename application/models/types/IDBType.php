<?php

/**
 * Interfaz que representa un tipo de datos respecto a la interfaz de base datos
 * de CodeIgniter
 * @author Mario Marinero <mario.marinero@alumnos.uva.es>
 */
interface IDBType {
    public function getCIDBcreateData();
    public function getCreateSql();
    public function getDBDefaultValue();
    public function getDBValue();
    public function validateValue();
    public function sanitizeValue();

?>
