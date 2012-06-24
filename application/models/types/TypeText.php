<?php

class TypeText extends BaseType {
    
    //IDBType implementation
    
    //private $sizeConstraint = 255;
    
    public static function LengthValidator($length) {
        return function($this) use ($length){
            return strlen($this->val()) <= $length ? true : false;
        };
    }
    
    public function getCIDBcreateData(){
        return array($this->getName()=>array('type'=>'text', 'null'=>true));
    }
    
    public function getInputHtml($attributes = array()){
        $attributes['class'][] = 'text';
        $attributes = $this->processAttributes($attributes, true);
        return '<textarea '.static::htmlAttributesFromArray($attributes).'>'.$this->val().'</textarea>';
    }
    
    public function getHtml($attributes = array()){
        $attributes = $this->processAttributes($attributes);
        return '<span '.HtmlAttributesFromArray($attributes).">".nl2br($this->val())."</span>";
    }
}