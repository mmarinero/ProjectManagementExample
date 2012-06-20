<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Proyecto extends EX_Model {

    protected $tableName = __CLASS__;
    
    protected $fields = array();

    protected $fases = array(array('inicio','Inicio'), 
        array('elaboracion','Elaboración'), 
        array('construcion','Construción'),
        array('transicion','Transición'));
    
    public function __construct() {
        parent::__construct();
        $this->fields['nombre'] = new TypeString(array('name'=>'nombre','outputName'=>'Nombre'));
        $this->fields['descripcion'] = new TypeText(array('name'=>'descripcion','outputName'=>'Descripción'));
        $this->fields['inicio'] = new TypeDate(array('name'=>'inicio','outputName'=>'Fecha de inicio'));
        $this->fields['fin'] = new TypeDate(array('name'=>'fin','outputName'=>'Fecha de fin'));
        $this->fields['comenzado'] = new TypeBoolean(array('name'=>'comenzado','outputName'=>'Comenzado'));
        $this->fields['cerrado'] = new TypeBoolean(array('name'=>'cerrado','outputName'=>'Cerrado'));
        foreach ($this->fases as $fase){
            $this->fields["iteraciones{$fase[0]}"] = new TypeInt(array('name'=>"iteraciones{$fase[0]}",'outputName'=>"Iteraciones fase de {$fase[1]}"));
        }
    }
    public function DBInsert($values, $createPlanes=false){
	parent::DBInsert($values);
	if ($createPlanes) {
	    foreach($this->fases as $fase) {
                $nIteraciones = $this->fields["iteraciones".$fase[0]];
                $nIteraciones = $nIteraciones->getDBValue();
                for($i = 0; $i<$nIteraciones ;$i++) {
                    $planIteracion = new PlanIteracion();	
                    $planIteracion->DBInsert(array(
                        'nombre'=>"$fase ".$i + 1,
                        'descripcion'=>'Iteración fase '.$fase,
                        'Proyecto'=>$this->getId()
                    ));
                }
            }
	}
    }
}
