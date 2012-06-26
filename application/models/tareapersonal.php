<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class TareaPersonal extends EX_Model {
    
    protected $fields = array();
    
    protected function initModel() {
        $this->fields['nombre'] = new TypeString(array('name'=>'nombre', "outputName"=>'Nombre'));
        $this->fields['descripcion'] = new TypeText(array('name'=>'descripcion', "outputName"=>'Descripción'));
        $this->fields['seguimiento'] = new TypeText(array('name'=>'seguimiento', "outputName"=>'Seguimiento'));
        $this->fields['tipo'] = new TypeString('tipo');
        $this->fields['estado'] = new TypeString('estado');
        $this->fields['horas'] = new TypeString('horas');
        $this->references['Actividad'] = new Reference(__CLASS__, 'Actividad', 'cascade');
        $this->references['Trabajador'] = new Reference(__CLASS__, 'Trabajador', 'cascade');
    }
    
    static $tipos = array(
        'Trato con usuarios', 
        'Reuniones Externas', 
        'Reuniones internas', 
        'Lectura de especificaciones y documentación', 
        'Elaboración de documentación', 
        'Desarrollo de programas', 
        'Revisión de informes, programas...', 
        'Verificación de programas', 
        'Formación de usuarios', 
        'Varios');
    
    static $estados = array(
        'Pendiente',
        'Enviada');
}
