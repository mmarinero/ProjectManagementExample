<?php

class TypeString extends BaseType {
    
    //IDBType implementation
    
    private $sizeConstraint = 255;
    
    public static function LengthValidator($length) {
        return function($this) use ($length){
            return strlen($this->getValue()) <= $length ? true : false;
        };
    }
    
    public function getCIDBcreateData(){
        return array($this->getName()=>array('type'=>'varchar','constraint'=>  $this->sizeConstraint, 'null'=>true));
    }
    
}