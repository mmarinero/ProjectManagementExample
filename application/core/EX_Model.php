<?php

/**
 * Description of EX_Model
 *
 * @author Mario Marinero <mario.marinero@alumnos.uva.es>
 */
abstract class EX_Model extends CI_Model{
    /**
     * Campos del modelo
     * @var BaseType[]
     */
    protected $fields;
    
    protected $id;
    
    protected $tableName;
    
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
