<?php

class TypeNumber extends BaseType {
    
    //IDBType implementation
    
    private $sizeConstraint = 12;
    
    public function getCIDBcreateData(){
        return array($this->getName()=>array('type'=>'bigint','constraint'=>  $this->sizeConstraint, 'null'=>true));
    }
    
}