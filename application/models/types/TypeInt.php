<?php

class TypeString extends BaseType {
    
    //IDBType implementation
    
    private $sizeConstraint = 255;
    
    public function getCIDBcreateData(){
        return array($this->getName()=>array('type'=>'varchar','constraint'=>  $this->sizeConstraint));
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
    
    public function setAttr($attributes){
        
    }
    
}