<?php

class Reference implements IDBType , IType{
    protected $model;

    protected $name;
    
    protected $referencedTableName;

    protected $referencedId;

    protected $options;
    
    protected $ci;

    protected $CICreateColumnArray;

    public function __construct($model, $referencedTableName, $name=null, $options = array()){
        $this->ci = get_instance();
        $this->CICreateColumnArray = $this->ci->config->item('referenceCISqlDefinition');
	$this->model = $model;
	$this->referencedTableName = $referencedTableName;
        $this->name = !is_null($name) ? $name : $referencedTableName;
	$this->options = $options;
    }
    
    private function genericGetFKConstraint(){
        $optionsString = '';
	foreach ($this->options as $action => $result){
	    $optionsString.= "ON $action $result ";
	}
	return "CONSTRAINT FK_{$this->model->getTableName()}_{$this->referencedTableName} ".
               "FOREIGN KEY ({$this->name}) REFERENCES ".
               "{$this->referencedTableName}(id) $optionsString";	
    }
    
    public function getFKConstraint(){
        return $this->genericGetFKConstraint();  
    }

    public function getAlterTableFKConstraint() {
	return "ALTER TABLE {$this->model->getTableName()} ADD ".$this->genericGetFKConstraint();	
    }

    public function getReferencedModel(){
        $referencedModelClassName = get_class($referencedModel);
	return new $referencedModelClassName($this->id);
    }

    public function getCIDBcreateData() {
        return array($this->name => $this->ci->config->item('referenceCISqlDefinition'));
    }
    
    public function setExternalReferenceCIDB($CICreateColumnArray){
	$this->CICreateColumnArray = $CICreateColumnArray;
    }

    public function getCreateSql() {
        throw new Exception('Unimplemented');
    }

    public function getDBDefaultValue() {
        return null;
    }

    public function getDBValue() {
        return $this->referencedId;
    }

    public function sanitizeValue() {
        throw new Exception('Unimplemented');
    }

    public function setDBValue($value) {
        $this->referencedId = $value;
    }

    public function validateValue() {
        throw new Exception('Unimplemented');
    }

    public function setName($name){
        $this->name = $name;
    }
    
    public function getRaw(){
        return $this->value;
    }
    
    public function setValue($value) {
        $this->referencedId = $value;
    }

    public function getName() {
        return $this->name;
    }
    
}
