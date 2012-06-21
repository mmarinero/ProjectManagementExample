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
    
    public function getInputHtml($newAttributes = null){
        if ($newAttributes !== null) $attributes = $newAttributes;
        else $attributes = $this->attributes;
        $class = isset($attributes['class']) ? $attributes['class'] : '';
        return '<input type="text" class="string '.$class.' "'.  HtmlAttributesFromArray($attributes).' name="'.$this->getName().'" value="'.$this->value.'"></input>';
    }
}