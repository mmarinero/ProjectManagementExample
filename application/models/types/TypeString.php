<?php

class TypeString extends BaseType {
    
    //IDBType implementation
    
    private $sizeConstraint = 255;
    
    public static function LengthValidator($length) {
        return function($thisObj) use ($length){
            return strlen($thisObj->val()) <= $length ? true : false;
        };
    }
    
    public function getCIDBcreateData(){
        return array($this->getName()=>array('type'=>'varchar','constraint'=>  $this->sizeConstraint, 'null'=>true));
    }
    
    public function getInputHtml($attributes = array()){
        $attributes['class'][] = 'string';
        $attributes['type'] = 'text';
        return parent::getInputHtml($attributes);
    }
}