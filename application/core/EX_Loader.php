<?php


/**
 * Description of EX_Input
 *
 * @author Mario Marinero <mario.marinero@alumnos.uva.es>
 */
class EX_Loader extends CI_Loader{
    public function view($view, $vars = array(), $return = FALSE) {
        header('Content-Type: text/html; charset=UTF-8');
        parent::view($view, $vars, $return);
    }
}

?>
