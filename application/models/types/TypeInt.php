<?php

class TypeInt extends BaseType {
    
    //IDBType implementation
    
    private $sizeConstraint = 12;
    
    public function getCIDBcreateData(){
        return array($this->getName()=>array('type'=>'int','constraint'=>  $this->sizeConstraint, 'null'=>true));
    }
    
    public function getInputHtml($attributes = array()){
        $attributes['class'][] = 'int';
        $attributes['type'] = 'text';
        return parent::getInputHtml($attributes);
    }
    
}