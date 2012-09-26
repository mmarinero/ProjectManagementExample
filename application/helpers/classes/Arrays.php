<?php

/**
 * Description of ArrayUtils
 *
 * @author Mario Marinero <mario.marinero@alumnos.uva.es>
 */
class Arrays {
    
    /**
     * Changes the $original array order based on the $order array
     * @param array $original
     * @param array $order 
     * @return array 
     */
    static function reorder($original, $order){
        return $original;
    }
    
    static function rename($original, $renamer){
        return $original;
    }

    private function genericAssignProps($props, $params, $null){
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

    function map_func($func, $array){
        return array_map(function($object) use ($func){
            return $object->$func();
        }, $array);
    }


    /**
    * checks if an array has at least one string key
    * @param array $array
    * @return bool 
    */
    function array_is_assoc($array) {
        //slow implementation (bool)count(array_filter(array_keys($array), 'is_string'));
        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }

}