<?php

class Reference {
    protected $model

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

    //TODO id description sql in config.php or something
    //TODO model unique load multiple models
    getFKConstraint(){
	foreach ($options as $action =>$result){
	    $optionsString.= "ON $action $result " 
	}
	return "constraint FK FK_{$model->getTableName()}_{$referencedModel->getTableName()} $optionsString ";	
    }

    getAlterTableSql() {
	return "alter table {$this->model->getName()} constraint ForeignKey";	
    }

    getReferencedModel(){
	return new $referencedModel::__CLASS__($id);
    }
}
