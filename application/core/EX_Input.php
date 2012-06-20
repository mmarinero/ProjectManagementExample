<?php


/**
 * Description of EX_Input
 *
 * @author Mario Marinero <mario.marinero@alumnos.uva.es>
 */
class EX_Input extends CI_Input{
    
    static function assocRequest($params, $func = null) {
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
    //put your code here
}

?>
