<?php

class TypeText extends BaseType {
    
    //IDBType implementation
    
    //private $sizeConstraint = 255;
    
    public static function LengthValidator($length) {
        return function($this) use ($length){
            return strlen($this->getValue()) <= $length ? true : false;
        };
    }
    
    public function getCIDBcreateData(){
        return array($this->getName()=>array('type'=>'text', 'null'=>true));
    }
    
    public function getInputHtml($newAttributes = null){
        if ($newAttributes !== null) $attributes = $newAttributes;
        else $attributes = $this->attributes;
        $class = isset($attributes['class']) ? $attributes['class'] : '';
        return '<textarea type="text" class="text '.$class.' "'.  HtmlAttributesFromArray($attributes).' name="'.$this->getName().'" value="'.$this->value.'"></textarea>';
    }
    
}