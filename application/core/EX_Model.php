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
    
    public function getTableName(){
        return $this->tableName;
    }
    
    public function getCustomFields(){
        return $this->customFields;
    }
    
    public function getFields(){
        return $this->fields;
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
    
    public function DBInsert(){
        $this->db->insert($this->tableName, $this->fields);
        $this->id = $this->db->insert_id();
    }
    
    public function DBdelete(){
        $this->db->delete('mytable', array('id' => $this->id));
    }
    
    public function DBUpdate(){
        $this->db->update($this->tableName, $this->fields, array('id' => $this->id));
    }
}

?>
