<?php

class TypeFloat extends BaseType {
    
    //IDBType implementation
    
    private $sizeConstraint = 10;
    
    public function getCIDBcreateData(){
        return array($this->getName()=>array('type'=>'double', 'null'=>true));
    }
    
}