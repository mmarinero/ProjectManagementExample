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