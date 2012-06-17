<?php

/*
 * 
 * 
 */

/**
 * Description of ArrayUtils
 *
 * @author Mario Marinero <mario.marinero@alumnos.uva.es>
 */

    function genericAssignProps($props, $params, $null){
        foreach($props as $name => &$prop){
            if (isset($params[$name])){
                $prop = $params[$name];
            } else if($null) {
                $prop = null;
            }
        }
    }
    
    function assignPropsNull($props, $params){
        genericAssignProps($props, $params, true);
    }
    
    function assignProps($props, $params){
        genericAssignProps($props, $params, false);
    }
    
    function propIndexedArray(array $objectsArray, $method){
        $resultArray = array();
	foreach($objectsArray as $object){
	   $resultArray[$object->$method()] = $object;
	}
        return $resultArray;
    }
    
    function HtmlAttributesFromArray($attributesArray) {
        $attributesString = '';
        foreach($attributesArray as $name => $value){
	   $attributesString.= "$name=\"$value\" ";
	}
        return $attributesString;
    }

?>
