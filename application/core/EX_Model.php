<?php

/**
 * Description of EX_Model
 *
 * @author Mario Marinero <mario.marinero@alumnos.uva.es>
 */
class EX_Model extends CI_Model{

    protected $fields = array();
    
    protected $customFields = array();
    
    protected $properties = array('hashed'=>false, 'created'=>false, 'updated'=>false, 'ordered'=>false, 'DBCustomFields'=>false);
    
    protected $tempData = array();
    
    protected $references = array();
    
    protected $id = null;
    
    protected $tableName = null;
    
    protected function change(){
    }
    
    public function getId(){
	return $this->id;
    }

    public function getTableName(){
        return $this->tableName;
    }
    
    public function getCustomFields(){
        return $this->customFields;
    }
    
    public function getFields(){
        return $this->fields;
    }
    
    public function getTempData(){
        return $this->tempData;
    }
    
    public function getTempItem($name){
        return $this->tempData[$name];
    }
    
    public function getAllFields(){
        return array_merge($this->customFields, $this->references, $this->fields);
    }
    
    public function getField($name){
        return $this->get($name);
    }
    
    public function get($name){
        if (isset($this->fields[$name])) return $this->fields[$name];
        if (isset($this->references[$name])) return $this->references[$name];
        if (isset($this->customFields[$name])) return $this->customFields[$name];
    }
    
    public function getReferences(){
        return $this->references;
    }
    
    public function getProperties(){
        return $this->properties;
    }
    
    public function __construct($idOrWhere) {
    //check is int not float
        parent::__construct();
	if(is_numeric($id)){
	    $this->loadFromDB($idOrWhere)
	}else{
	    $this->loadFromDB(null, $idOrWhere)
	}
    }
    
    public function validate(){
        foreach ($this->fields as $field) {
            if(!$field->is_valid()) return false;
        }
        return true;
    }
    public function setValues(array $values){
        foreach($values as $name=>$value) {
            $field = $this->getField($name);
            if(is_object($field)){
                $field->setValue($value);
            } else if($name === 'id') {
		$this->id = $value;
            } else{
                $this->tempData[$name] = $value;
            }
        }
    }
    
    private function varsToDB(){
        return array_map(
                function($type){
                    return $type->val();
                },
                array_merge($this->fields, $this->references));
    }
    
    public function DBInsert(array $values=array()){
        $this->setValues($values);
        $this->db->insert($this->tableName, $this->varsToDB());
        $this->id = $this->db->insert_id();
    }
    
    public function DBdelete($id = null){
        if (is_null($id))  $id = $this->id;
        $this->db->delete($this->tableName, array('id' => $id));
    }
    
    public function DBUpdate($values){
	if (isset($values['id']) unset($values['id'];
        $this->setValues($values);
        $this->db->update($this->tableName, $this->varsToDB(), array('id' => $this->id));
    }
    
    public static function DBIdUpdate($values, $id){
        $this->setValues($values);
        $this->db->update($this->tableName, $this->varsToDB(), array('id' => $this->id));
    }

    protected loadFromDB($id=null, $where=null){
	$thisInstance = new static();
	if (!is_null($id){
	    $result = array_shift($this->db->get_where($this->tableName,array('id' => $id))->result_array());
	} else {
	    $result = array_shift($this->db->get_where($this->tableName,$where)->result_array());
	}
        if(is_null($result)) return null;
        $this->id = $result['id'];
        unset($result['id']);
        $this->setValues($result);
        return $this;
    }

    public function loadId($id) {
	return loadFromDB($id);
    }
    
    public function loadWhere($where) {
	return loadFromDB(null, $where);
    }

    public static function loadWhereArray($where = null, $getDBResult =null, $limit = null, $offset = null) {
	$thisInstance = new static();
        if (is_null($where)) {
            $result = $thisInstance->db->get($thisInstance->tableName, $limit, $offset)->result_array();
        } else {
            $result = $thisInstance->db->get_where($thisInstance->tableName, $where, $limit, $offset)->result_array();
        }
	if($getDBResult) return $result;
	else return static::createFromResult();
    }
    
    public static function loadQueryArray($query, $getDBResult) {
	$thisInstance = new static();
        $result = $thisInstance->db->query($query)->result_array();
	if($getDBResult) return $result;
	else return static::createFromResult($result);
    }

    protected static function createFromArray($result){
        $models = array();
	foreach($result as $values){
	    $newModel = new static();
            $newModel->id = $values['id'];
            unset($values['id']);
	    $newModel->setValues($values);
	    $models[$newModel->id] = $newModel;
	}
	return $models;
    }

		
    }

    public function genericGetForm($action,$options,$html){
	single foreach pass field variable instead of input and outputname
        $form = array();
	$class = isset($options['class']) ? 'class="'.$options['class'].'" ' : '';
	$id = isset($options['id']) ? 'id="'.$options['id'].'" ' : '';
	if (isset($options['custom']) && $options['custom'] === true){
	    $fields = array_merge($this->fields, $this->customFields);
	} else {
	    $fields = $this->fields;
	}
	$form['start'] = '<form action="'.$action.'" method="post" '.$class.$id.'>';
	if ($html) {//finish
        /* @var $field BaseType*/
	foreach ($fields as $field){
	    $form['fields'][$field->getName()]['input'] = $field->getInputHtml();
            $form['fields'][$field->getName()]['name'] = $field->getOutputName();
	}
	foreach ($fields as $field){
            $form['fields'][$field->getName()] = $field;
	}
	$form['end'] = "</form>";
	return isset($options['implode']) ? $form['start'].$options['implode'].
	    join($options['implode'],$form['fields']).
	    $options['implode'].$form['end'] : $form;

    public function getForm($action,$options){
        $form = array();
	$class = isset($options['class']) ? 'class="'.$options['class'].'" ' : '';
	$id = isset($options['id']) ? 'id="'.$options['id'].'" ' : '';
	if (isset($options['custom']) && $options['custom'] === true){
	    $fields = array_merge($this->fields, $this->customFields);
	} else {
	    $fields = $this->fields;
	}
	$form['start'] = '<form action="'.$action.'" method="post" '.$class.$id.'>';
        /* @var $field BaseType*/
	foreach ($fields as $field){
	    $form['fields'][$field->getName()]['input'] = $field->getInputHtml();
            $form['fields'][$field->getName()]['name'] = $field->getOutputName();
	}
	$form['end'] = "</form>";
	return isset($options['implode']) ? $form['start'].$options['implode'].
	    join($options['implode'],$form['fields']).
	    $options['implode'].$form['end'] : $form;
    }
    
    public function getFieldsAndId(){
        $fields = $this->fields;
        $fields['id'] = $this->getId();
        return $fields;
    }
    
    protected function loadSimpleJoin($model, $joinedTablename, $extraFields=array()){
                $fields = array_keys($this->getFieldsAndId());
                foreach ($fields as &$field) {
                    $field = $this->tableName.'.'.$field;  
                }
                $fields = array_merge($fields,$extraFields);
        return $this->loadQueryArray('select '.implode(', ', $fields).
                ' from '.$this->tableName.' join '.$joinedTablename.' on '.
                $this->tableName.".id = ".$this->tableName." where ".
                $joinedTablename.".".$model->getTableName()." = '".$model->getId()."'");
    }
}
