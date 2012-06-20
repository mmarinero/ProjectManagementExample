<?php

class TypeInt extends BaseType {
    
    //IDBType implementation
    
    private $sizeConstraint = 12;
    
    public function getCIDBcreateData(){
        return array($this->getName()=>array('type'=>'int','constraint'=>  $this->sizeConstraint, 'null'=>true));
    }
    
    public function getInputHtml($newAttributes = null){
        if ($newAttributes !== null) $attributes = $newAttributes;
        else $attributes = $this->attributes;
        $class = isset($attributes['class']) ? $attributes['class'] : '';
        return '<input type="text" class="int '.$class.' "'.  HtmlAttributesFromArray($attributes).' name="'.$this->getName().'" value="'.$this->value.'"></input>';
    }
    
}