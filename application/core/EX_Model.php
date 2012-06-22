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
    
    public function getId(){
	return $this->id;
    }

    public static function getTableName(){
        return get_called_class();
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
    
    public function __construct($idOrWhere=null, $initParams = array()) {
    //check is int not float
        parent::__construct();
        $this->initModel($initParams);
	if(is_numeric($idOrWhere)){
	    $this->loadFromDB($idOrWhere);
        }else if (!is_null ($idOrWhere)){
	    $this->loadFromDB(null, $idOrWhere);
	}
    }
    
    protected function initModel($initParams) {}
    
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
        $this->db->insert(static::getTableName(), $this->varsToDB());
        $this->id = $this->db->insert_id();
    }
    
    public static function DBIdInsert(array $values=array()){
        $thisInstance = new static();
        $thisInstance->setValues($values);
        $thisInstance->db->insert(static::getTableName(), $thisInstance->varsToDB());
        $thisInstance->id = $thisInstance->db->insert_id();
        return $thisInstance;
    }
    
    public function DBDelete($id = null){
        if (is_null($id))  $id = $this->id;
        $this->db->delete(static::getTableName(), array('id' => $id));
    }
    
    public static function DBIdDelete($id){
        i(new static())->db->delete(static::getTableName(), array('id' => $id));
    }
    
    public function DBUpdate($values){
	if (isset($values['id'])) unset($values['id']);
        $this->setValues($values);
        $this->db->update(static::getTableName(), $this->varsToDB(), array('id' => $this->id));
    }
    
    public static function DBIdUpdate($values, $id=null){
        $thisInstance = new static();
        $thisInstance->setValues($values);
        if ($id !== null){
            $thisInstance->id = $id;
        }
        $thisInstance->db->update(static::getTableName(), $thisInstance->varsToDB(), array('id' => $thisInstance->id));
        return $thisInstance;
    }

    protected function loadFromDB($id=null, $where=null){
	if (!is_null($id)){
	    $result = array_shift($this->db->get_where(static::getTableName(),array('id' => $id))->result_array());
	} else {
	    $result = array_shift($this->db->get_where(static::getTableName(),$where)->result_array());
	}
        if(is_null($result)) return null;
        $this->id = $result['id'];
        unset($result['id']);
        $this->setValues($result);
        return $this;
    }

    public function loadId($id) {
	return $this->loadFromDB($id);
    }
    
    public function loadWhere($where) {
	return $this->loadFromDB(null, $where);
    }

    public static function loadArray($where = null, $getDBResult = false, $limit = null, $offset = null) {
	$thisInstance = new static();
        if (is_null($where)) {
            $result = $thisInstance->db->get(static::getTableName(), $limit, $offset)->result_array();
        } else {
            $result = $thisInstance->db->get_where(static::getTableName(), $where, $limit, $offset)->result_array();
        }
	if($getDBResult) return $result;
	else return static::createFromResult($result);
    }
    
    public static function loadQueryArray($query, $getDBResult = false) {
	$thisInstance = new static();
        $result = $thisInstance->db->query($query)->result_array();
	if($getDBResult) return $result;
	else return static::createFromResult($result);
    }

    protected static function createFromResult($result){
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

    protected function genericGetForm($action,$options,$html){
        $form = array();
	$class = isset($options['class']) ? 'class="'.$options['class'].'" ' : '';
	$id = isset($options['id']) ? 'id="'.$options['id'].'" ' : '';
	if (isset($options['custom']) && $options['custom'] === true){
	    $fields = array_merge($this->fields, $this->customFields);
	} else {
	    $fields = $this->fields;
	}
	$form['start'] = '<form action="'.$action.'" method="post" '.$class.$id.'>';
	if ($html) {
            /* @var $field BaseType*/
            foreach ($fields as $field){
                $form['fields'][$field->getName()]['input'] = $field->getInputHtml();
                $form['fields'][$field->getName()]['name'] = $field->getOutputName();
            }
        } else {
            foreach ($fields as $field){
                $form['fields'][$field->getName()] = $field;
            }
        }
	$form['end'] = "</form>";
        if (isset($options['implode'])) {
            $imp = $options['implode'];
            return $form['start'].$imp.join($imp,$form['fields']).$imp.$form['end'];
        } else {
            return $form;
        }
    }

    public function getForm($action,$options){
        return $this->genericGetForm($action, $options, true);
    }
    
    public function getFieldsForm($action,$options){
        return $this->genericGetForm($action, $options, false);
    }
    
    public function getFieldsAndId(){
        $fields = $this->fields;
        $fields['id'] = $this->getId();
        return $fields;
    }
    
    protected function loadSimpleJoin($model, $joinedTablename, $extraFields=array()){
                $fields = array_keys($this->getFieldsAndId());
                foreach ($fields as &$field) {
                    $field = static::getTableName().'.'.$field;  
                }
                $fields = array_merge($fields,$extraFields);
        return $this->loadQueryArray('select '.implode(', ', $fields).
                ' from '.static::getTableName().' join '.$joinedTablename.' on '.
                static::getTableName().".id = ".static::getTableName()." where ".
                $joinedTablename.".".$model->getTableName()." = '".$model->getId()."'");
    }
}
