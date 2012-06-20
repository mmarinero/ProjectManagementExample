<?php

class TypeBoolean extends BaseType {
    
    //IDBType implementation
    
    private $sizeConstraint = 12;
    
    public function getCIDBcreateData(){
        return array($this->getName()=>array('type'=>'boolean', 'null'=>true));
    }
    
    public function getInputHtml($newAttributes = null){
        if ($newAttributes !== null) $attributes = $newAttributes;
        else $attributes = $this->attributes;
        $checked = '';
        if ($this->value) $checked = 'checked="checked" ';
        $class = isset($attributes['class']) ? $attributes['class'] : '';
        return '<input type="checkbox" class="boolean '.$class.' "'.
                HtmlAttributesFromArray($attributes).' name="'.
                $this->getName().'" '.$checked.'value="1"></input>';
    }
    
    public function getHtml($newAttributes = null){
        if ($this->value) $output='Si';
        else $output='No';
        $attributes = $this->selectAttributes($newAttributes);
        return '<span '.HtmlAttributesFromArray($attributes).">$output</span>";
    }
    
}