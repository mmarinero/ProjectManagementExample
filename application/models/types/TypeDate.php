<?php

class TypeDate extends BaseType {
    
    //IDBType implementation
    
    //private $sizeConstraint = 12;
    
    public function getCIDBcreateData(){
        return array($this->getName()=>array('type'=>'datetime','null'=>true));
    }
    
    public function getInputHtml($attributes = array()){
        $attributes['class'][] = 'date';
        $attributes['type'] = 'text';
        $attributes['value'] = implode('/', array_reverse(explode('-',$this->value)));
        return parent::getInputHtml($attributes);
    }
    
    public function getHtml($attributes = array()){
        $attributes['class'][] = 'date';
        $attributes = $this->processAttributes($attributes);
        $date = implode('/', array_reverse(explode('-',$this->value)));
        return '<span '.static::htmlAttributesFromArray($attributes).">".$date."</span>";
    }
    
    
    public function setValue($value) {
        $date = explode('/',$value);
        if (count($date)!=3){
            $date = explode(' ',$value);
            if (isset($date[0])) $date = $date[0];
            $date = explode('-',$date);
            if (count($date)!=3) $this->value = null;
            else $this->value = implode('-',$date);
        }
        else {
            $this->value = implode('-',array_reverse($date));
        }
    }
    
}
