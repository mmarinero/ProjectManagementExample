<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class PlanFases extends EX_Model {

    protected $tableName = __CLASS__;
    
    protected $fields = array();

    protected $fases = array(array('inicio','Inicio'), 
        array('elaboracion','Elaboración'), 
        array('construcion','Construción'),
        array('transicion','Transición'));
    
    public function __construct() {
        parent::__construct();
        $this->customFields['cerrado'] = new TypeBoolean(array('name'=>'cerrado','outputName'=>'Plan cerrado'));
        $this->references['Proyecto'] = new Reference($this, 'Proyecto', 'Proyecto',array('delete'=>'cascade','update'=>'cascade'));
        foreach ($this->fases as $fase){
            $this->fields["iteraciones{$fase[0]}"] = new TypeInt(array('name'=>"iteraciones{$fase[0]}",'outputName'=>"Iteraciones fase de {$fase[1]}"));
        }
    }
    public function DBInsert($values, $createPlanes=false){
	parent::DBInsert($values);
	if ($createPlanes) {
	    foreach($this->fases as $fase) {
                $nIteraciones = $this->fields["iteraciones".$fase[0]];
                $nIteraciones = $nIteraciones->getValue();
                for($i = 0; $i<$nIteraciones ;$i++) {
                    $planIteracion = new PlanIteracion();	
                    $planIteracion->DBInsert(array(
                        'nombre'=>"$fase[0] ".($i + 1),
                        'descripcion'=>'Iteración fase '.$fase[1],
                        'PlanFases'=>$this->getId()
                    ));
                }
            }
	}
    }
}