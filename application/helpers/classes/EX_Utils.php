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
class EX_Utils {

    private static function genericAssignProps($props, $params, $null){
        foreach($props as $name => &$prop){
            if (isset($params[$name])){
                $prop = $params[$name];
            } else if($null) {
                $prop = null;
            }
        }
    }
    
    static function assignPropsNull($props, $params){
        genericAssignProps($props, $params, true);
    }
    
    static function assignProps($props, $params){
        genericAssignProps($props, $params, false);
    }
}

?>
