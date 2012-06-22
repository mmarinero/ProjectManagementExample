<?php

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
        unset($attributesArray['class']);
        foreach($attributesArray as $name => $value){
	   $attributesString.= "$name=\"$value\" ";
	}
        return $attributesString;
    }
    
    function assocRequest($params, $func = null) {
        $assocParams = array();
        if (!is_array($params))
            throw new NdSiteException(var_export($params, true) . ' no es un array');
        if ($func !== null) {
            foreach ($params as $key => $param) {
                if (isset($_REQUEST[$param])) {
                    if (is_int($key)) {
                        $assocParams[$param] = $func($_REQUEST[$param]);
                    }else{
                        $assocParams[$key] = $func($_REQUEST[$param]);
                    }
                } else {
                    if (is_int($key)) {
                        $assocParams[$param] = null;
                    }else{
                        $assocParams[$key] = null;
                    }
                }
            }
        } else {
            foreach ($params as $key => $param) {
                if (isset($_REQUEST[$param])) {
                    if (is_int($key)){
                        $assocParams[$param] = $_REQUEST[$param];
                    }else{
                        $assocParams[$key] = $_REQUEST[$param];
                    }
                } else {
                    if (is_int($key)) {
                        $assocParams[$param] = null;
                    }else{
                        $assocParams[$key] = null;
                    }
                }
            }
        }
        return $assocParams;
    }
    
    function is_assoc($array) {
        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }

    /**
     * Identity function, avoid temp variable for method call after class instantiation
     * php < 5.4
     */
    function i($var) {
	return $var;
    }

    /**
     * Dereference function, avoid temp variable to reference array index
     * php < 5.4
     */
    function d($array, $index) {
	return $array[$index];
    }


?>
