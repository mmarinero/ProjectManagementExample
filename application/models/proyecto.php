<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Proyecto extends EX_Model {

    protected $tableName = __CLASS__;
    
    protected $fields = array();

    protected $fases = array('Inicio', 'Elaboración', 'Construción', 'Transición');
    
    public function __construct() {
        parent::__construct();
        $this->fields['nombre'] = new TypeString(array('name'=>'nombre'));
        $this->fields['descripcion'] = new TypeText(array('name'=>'descripcion'));
        foreach ($this->fases as $fase){
            $this->fields["Iteraciones{$fase}"] = new TypeInt("Iteraciones{$fase}");
        }
    }
    public function DBInsert($values, $createPlanes=false){
	parent::DBInsert($values);
	if ($createPlanes) {
	    foreach($this->fases as $fase) {
	    for($i = 0;$i<$this->fields["Iteraciones$fase"];$i++) {
	    $PlanIteracion = new PlanIteracion();	
	    $PlanIteracion->DBInsert(array(
		'nombre'=>"$fase ".$i + 1,
		'descripcion'=>'Iteración fase '.$fase,
		'Proyecto'=>$this->getId()
            ));
	}
	}
    }
}
