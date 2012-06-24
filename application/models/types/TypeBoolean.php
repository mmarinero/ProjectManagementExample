<?php

class TypeBoolean extends BaseType {
    
    //IDBType implementation
    
    private $sizeConstraint = 12;
    
    public function getCIDBcreateData(){
        return array($this->getName()=>array('type'=>'boolean', 'null'=>true));
    }
    
    public function getInputHtml($attributes = array()){
        $attributes['class'][] = 'boolean';
        if ($this->val()) $attributes['checked'] = 'checked';
        $attributes['type'] = 'checkbox';
        $attributes['value'] = '1';
        return parent::getInputHtml($attributes);
    }
    
    public function getHtml($attributes = array()){
        if ($this->value) $output='Si';
        else $output='No';
        $attributes = $this->processAttributes($attributes);
        return '<span '.static::htmlAttributesFromArray($attributes).">$output</span>";
    }
    
}