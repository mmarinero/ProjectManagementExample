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
        $referencedColumn = $this->referencedTableName;
        $cyclic = '';
        if (!is_null($this->cyclic)) {
            $cyclic = "_{$this->cyclic}";
            $referencedColumn = $this->cyclic;
        }
	return "CONSTRAINT FK_{$origin}_{$referenced}{$cyclic} ".
               "FOREIGN KEY ({$referencedColumn}) REFERENCES ".
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
    
    /**
     *  Devuelve array creación de referencia en formato CodeIgniter
     * @return array
     */
    public function getCIDBcreateData() {
        $columnName= is_null($this->cyclic) ? $this->referencedTableName : $this->cyclic;
        return array($columnName => $this->ci->config->item('referenceCISqlDefinition'));
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
        if (!is_null($this->cyclic)) return $this->cyclic;
        return $this->referencedTableName;
    }
    
    public function loadReferredArray($referedId, $whereArray = array(), $loadModels=true){
        $result =$this->ci->db->get_where($this->modelTableName,
                array_merge($whereArray, array($this->referencedTableName=>$referedId)))->result_array();
        if (!$loadModels) return $result;
        else return static::createFromResult($result, $this->modelClass);
    }
    
    private static function createFromResult($result, $modelClass){
        $models = array();
	foreach($result as $values){
	    $newModel = new $modelClass();
	    $newModel->setValues($values);
	    $models[$newModel->getId()] = $newModel;
	}
	return $models;
    }
    
}
