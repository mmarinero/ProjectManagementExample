<?php

class Reference implements IDBType , IType{
    private $modelClass;
    
    private $modelTableName;
    
    private $referencedModelClass;
    
    private $referencedTableName;

    private $referencedId;

    private $options;
    
    private $external = false;
    
    private $cyclic = null;
    
    private $ci;

    private $CICreateColumnArray;

    public function __construct($modelClass, $referencedModelClass, $options = null){
        $this->proccessOptions($options);
        $this->ci = get_instance();
        $this->CICreateColumnArray = $this->ci->config->item('referenceCISqlDefinition');
	$this->modelClass = $modelClass;
        $this->modelTableName = $modelClass::getTableName();
	$this->referencedModelClass = $referencedModelClass;
        if(!$this->external) {
            $this->referencedTableName = $referencedModelClass::getTableName();
        }else{
            $this->referencedTableName = $referencedModelClass;
        }
	$this->options = static::proccessOptions($options);
    }
    
    private function proccessOptions($options){
        if (is_null($options)){
            $result= '';
        }else if (is_string($options)) {
            $result= "on delete $options on update $options";
        } else if (is_array($options)){
            $optionsString = '';
            if (isset($options['delete'])) $optionsString.$options['delete'];
            if (isset($options['update'])) $optionsString.$options['update'];
            if (isset($options['external'])) $this->external = $options['external'];
            if (isset($options['cyclic'])) $this->cyclic = $options['cyclic'];
            $result = $optionsString;
        }
        $this->options = $result;
    }
    
    private function genericGetFKConstraint(){
        $origin = $this->modelTableName;
        $referenced = $this->referencedTableName;
        $cyclic = !is_null($this->cyclic) ? "_{$this->cyclic}" : '';
	return "CONSTRAINT FK_{$origin}_{$referenced}{$cyclic} ".
               "FOREIGN KEY ({$referenced}) REFERENCES ".
               "{$referenced}(id) $this->options";	
    }
    
    public function getFKConstraint(){
        return $this->genericGetFKConstraint();  
    }

    public function getAlterTableFKConstraint() {
	return "ALTER TABLE $this->modelTableName ADD ".$this->genericGetFKConstraint();	
    }

    public function getReferencedModel(){
	return new ${$this->referencedModelClass}($this->id);
    }

    public function getCIDBcreateData() {
        return array($this->referencedTableName => 
                $this->ci->config->item('referenceCISqlDefinition'));
    }
    
    public function setExternalReferenceCIDB($CICreateColumnArray){
	$this->CICreateColumnArray = $CICreateColumnArray;
    }
    
    public function val() {
        return $this->referencedId;
    }
    
    public function setValue($value) {
        $this->referencedId = $value;
    }

    public function getName() {
        return $this->referencedTableName;
    }
    
    public function loadReferredArray($referedId, $loadModels){
        $result =$this->ci->db->get_where($this->modelTableName,
                array($this->referencedTableName=>$referedId))->result_array();
        if (!$loadModels) return result;
        else return $this->createFromResult($result, $this->modelClass);
    }
    
    private static function createFromResult($result, $modelClass){
        $models = array();
	foreach($result as $values){
	    $newModel = new $modelClass();
	    $newModel->setValues($values);
	    $models[$newModel->id] = $newModel;
	}
	return $models;
    }
    
}
