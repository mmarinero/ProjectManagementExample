<?php

class TypeInt extends BaseType {
    
    //IDBType implementation
    
    private $sizeConstraint = 12;
    
    public function getCIDBcreateData(){
        return array($this->getName()=>array('type'=>'int','constraint'=>  $this->sizeConstraint));
    }
    
}