<?php

class Reference implements IDBType{
    protected $model;

    protected $referencedModel;

    protected $referencedColumn;

    protected $referencedId;

    protected $options;

    public function __construct($model, $referencedModel, $referencedColumn, $options){
	$this->model = $model;
	$this->referencedModel = $referencedModel;
	$this->referencedColumn = $referencedColumn;
	$this->options = $options;
    }
    
    public function getId() {
	return $this->referencedId;
    }

    public function setId($id) {
	$this->referencedId = $id;
    }
    
    //TODO model unique load multiple models
    public function getFKConstraint(){
        $optionsString = '';
	foreach ($this->options as $action => $result){
	    $optionsString.= "ON $action $result ";
	}
	return "CONSTRAINT FK_{$this->model->getTableName()}_{$this->referencedModel->getTableName()} ".
               "FOREIGN KEY ({$this->referencedColumn}) REFERENCES ".
               "{$this->referencedModel->getTableName()} (id) $optionsString";	
               
    }

    public function getAlterTableSql() {
        $optionsString = '';
	foreach ($this->options as $action => $result){
	    $optionsString.= "ON $action $result ";
	}
	return "ALTER TABLE {$this->model->getTableName()} ADD CONSTRAINT FK_{$this->model->getTableName()}_{$this->referencedModel->getTableName()} ".
                "FOREIGN KEY ({$this->referencedColumn}) REFERENCES ";
                "{$this->referencedModel->getTableName()} (id) $optionsString;";	
    }

    public function getReferencedModel(){
        $referencedModelClassName = get_class($referencedModel);
	return new $referencedModelClassName($this->id);
    }

    public function getCIDBcreateData() {
        return $this->config->item('idCISqlDefinition');
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
        return true;
    }

    public function setDBValue($value) {
        $this->referencedId;
    }

    public function validateValue() {
        return true;
    }
}
