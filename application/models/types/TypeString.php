<?php

class TypeString extends BaseType {
    
    //IDBType implementation
    
    public function getCIDBcreateData(){
        
    }
    
    public function getCreateSql(){
        
    }
    
    //IHTMLType implementation
    
    public function getHtml($attributes = null){
        return $this->value;
    }
    
    public function getInputHtml($attributes = null){
        return '<input type="text" name="'.$this->getName().'" value="'.$this->value.'"></input>';
    }
    
    public function getAttrDescription(){
        
    }
    
    public function getDefaultAttr(){
        
    }
    
    public function setAttr(){
        
    }
    
}