<?php

class TypeDate extends BaseType {
    
    //IDBType implementation
    
    //private $sizeConstraint = 12;
    
    public function getCIDBcreateData(){
        return array($this->getName()=>array('type'=>'datetime','null'=>true));
    }
    
    public function getInputHtml($newAttributes = null){
        if ($newAttributes !== null) $attributes = $newAttributes;
        else $attributes = $this->attributes;
        $nameAppend= isset($attributes['nameAppend']) ? $attributes['nameAppend'] : '';
        $class = isset($attributes['class']) ? $attributes['class'] : '';
        $date = implode('/', array_reverse(explode('-',$this->value)));
        return '<input type="text" class="date '.$class.' "'.  HtmlAttributesFromArray($attributes).' name="'.$this->getName().$nameAppend.'" value="'.$date.'"></input>';
    }
    
    public function getHtml($newAttributes = null){
        $attributes = $this->selectAttributes($newAttributes);
        $date = implode('/', array_reverse(explode('-',$this->value)));
        return '<span '.HtmlAttributesFromArray($attributes).">".$date."</span>";
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