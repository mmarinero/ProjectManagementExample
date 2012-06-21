<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Actividad extends EX_Model {

    protected $tableName = __CLASS__;
    
    protected $fields = array();
    
    public function __construct() {
        parent::__construct();
        $this->fields['nombre'] = new TypeString(array('name'=>'nombre','outputName'=>'Nombre'));
        $this->fields['descripcion'] = new TypeText(array('name'=>'descripcion','outputName'=>'Descripción'));
        $this->fields['horas'] = new TypeInt(array('name'=>"horas",'outputName'=>"Horas/persona"));
        $this->fields['rol'] = new TypeString('rol');
        $this->references['PlanIteracion'] = new Reference($this, 'PlanIteracion', 'PlanIteracion',array('delete'=>'cascade','update'=>'cascade'));
//        $this->references['Trabajador1'] = new Reference($this, 'Trabajador', 'Trabajador1',array('delete'=>'cascade','update'=>'cascade'));
//        $this->references['Trabajador2'] = new Reference($this, 'Trabajador', 'Trabajador2',array('delete'=>'cascade','update'=>'cascade'));
//        $this->references['Trabajador3'] = new Reference($this, 'Trabajador', 'Trabajador3',array('delete'=>'cascade','update'=>'cascade'));
//        $this->references['Trabajador4'] = new Reference($this, 'Trabajador', 'Trabajador4',array('delete'=>'cascade','update'=>'cascade'));
//        $this->references['Trabajador5'] = new Reference($this, 'Trabajador', 'Trabajador5',array('delete'=>'cascade','update'=>'cascade'));
//        $this->references['Trabajador6'] = new Reference($this, 'Trabajador', 'Trabajador6',array('delete'=>'cascade','update'=>'cascade'));
//        $this->references['Trabajador7'] = new Reference($this, 'Trabajador', 'Trabajador7',array('delete'=>'cascade','update'=>'cascade'));
//        $this->references['Trabajador8'] = new Reference($this, 'Trabajador', 'Trabajador8',array('delete'=>'cascade','update'=>'cascade'));
//        $this->references['Trabajador9'] = new Reference($this, 'Trabajador', 'Trabajador9',array('delete'=>'cascade','update'=>'cascade'));
//        $this->references['Trabajador10'] = new Reference($this, 'Trabajador', 'Trabajador10',array('delete'=>'cascade','update'=>'cascade'));
    }
}
