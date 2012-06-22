<?php

class Reference implements IDBType , IType{
    protected $model;

    protected $name;
    
    protected $referencedTableName;

    protected $referencedId;

    protected $options;
    
    protected $ci;

    protected $CICreateColumnArray;

    public function __construct($model, $referencedTableName, $name=null, $options = null){
        $this->ci = get_instance();
        $this->CICreateColumnArray = $this->ci->config->item('referenceCISqlDefinition');
	$this->model = $model;
	$this->referencedTableName = $referencedTableName;
        $this->name = !is_null($name) ? $name : $referencedTableName;
	$this->options = static::proccessOptions($options);
    }
    
    private static function proccessOptions($options){
        if (is_null($options)){
            return '';
        }else if (is_string($options)) {
            return "on delete $options on cascade $options";
        } else if (is_array($options)){
            $optionsString = '';
            if (isset($options['delete'])) $optionsString.$options['delete'];
            if (isset($options['update'])) $optionsString.$options['update'];
            return $optionsString;
        }
    }
    
    private function genericGetFKConstraint(){
	return "CONSTRAINT FK_{$this->model->getTableName()}_{$this->referencedTableName}_{$this->name} ".
               "FOREIGN KEY ({$this->name}) REFERENCES ".
               "{$this->referencedTableName}(id) $this->options";	
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
    
    public function val() {
        return $this->referencedId;
    }

    public function setName($name){
        $this->name = $name;
    }
    
    public function setValue($value) {
        $this->referencedId = $value;
    }

    public function getName() {
        return $this->name;
    }
    
}
