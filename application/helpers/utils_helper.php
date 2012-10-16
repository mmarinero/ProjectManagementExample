<?php

/**
* Identity function, avoid temp variable for method call after class instantiation
* in php < 5.4 (language support since that version)
*/
function i($var) {
    return $var;
}

/**
* Dereference function, avoid temp variable to reference array index
* in php < 5.4 (language support since that version)
*/
function d($array, $index) {
    return $array[$index];
}

    function assocRequest($params, $func = null) {
        $assocParams = array();
        if (!is_array($params))
            throw new NdSiteException(var_export($params, true) . ' no es un array');
        if ($func !== null) {
            foreach ($params as $key => $param) {
                if (isset($_REQUEST[$param])) {
                    if (is_int($key))
                        $assocParams[$param] = $func($_REQUEST[$param]);
                    else
                        $assocParams[$key] = $func($_REQUEST[$param]);
                }
            }
        } else {
            foreach ($params as $key => $param) {
                if (isset($_REQUEST[$param])) {
                    if (is_int($key))
                        $assocParams[$param] = $_REQUEST[$param];
                    else
                        $assocParams[$key] = $_REQUEST[$param];
                }
            }
        }
        return $assocParams;
    }