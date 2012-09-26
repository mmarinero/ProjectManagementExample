<?php

/**
 * PHP query builder 
 */
class Input {
    
    static function get($vars){
        
    }
    
    static function post($vars){
        
    }
    
    static function request($vars){
        
    }
    
  static function assocRequest($params, $func = null) {
    $assocParams = array();
    if (!is_array($params))
        throw new Exception(var_export($params, true) . ' no es un array');
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

}