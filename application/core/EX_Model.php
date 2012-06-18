<?php

/**
 * Description of EX_Model
 *
 * @author Mario Marinero <mario.marinero@alumnos.uva.es>
 */
class EX_Model extends CI_Model{
    /**
     * Campos del modelo
     * @var BaseType[]
     */
    protected $fields = array();
    
    protected $customFields = array();
    
    protected $properties = array('hashed'=>false, 'created'=>false, 'updated'=>false, 'ordered'=>false, 'DBCustomFields'=>false);
    
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
    
    public function getAllFields(){
        return array_merge($this->customFields, $this->references, $this->fields);
    }
    
    public function getField($name){
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
    
    public function __construct($fields = array()) {
        parent::__construct();
        $this->fields = $fields;
    }
    
    public function validate(){
        foreach ($this->fields as $field) {
            if(!$field->is_valid()) return false;
        }
        return true;
    }
    public function setValues(array $values){
        foreach($values as $name=>$value) {
            $this->getField($name)->setDBValue($value);
        }
    }
    
    private function varsToDB(){
        return array_map(
                function($type){
                    return $type->getDBValue();
                },
                array_merge($this->fields, $this->references));
    }
    
    public function DBInsert(array $values=array()){
        $this->setValues($values);
        $this->db->insert($this->tableName, $this->varsToDB());
        $this->id = $this->db->insert_id();
    }
    
    public function DBdelete(){
        $this->db->delete('mytable', array('id' => $this->id));
    }
    
    public function DBUpdate($values){
        $this->setValues($values);
        $this->db->update($this->tableName, $this->varsToDB(), array('id' => $this->id));
    }
    public function loadThis($id) {
        $values = $this->db->get($this->tableName, $id);
        $this->setValues($values);
    }

    public function loadArray($where) {
        $valuesArray = $this->db->get($this->tableName, $id);
	foreach($valuesArray as $values){
	    $newModel = new static(); 
	    $newModel->setValues($values);
	    $models[$newModel->id] = $newModel;
	}
	return $models;
    }
}

?>
