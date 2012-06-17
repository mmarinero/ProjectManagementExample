<?php


/**
 * Description of EX_Input
 *
 * @author Mario Marinero <mario.marinero@alumnos.uva.es>
 */
class EX_Exceptions extends CI_Exceptions{
    
    function show_error($heading, $message, $template = 'error_general', $status_code = 500) {
        header('Content-Type: text/html; charset=UTF-8');
        $message = utf8_encode('<p>'.implode('</p><p>', ( ! is_array($message)) ? array($message) : $message).'</p>');
        return parent::show_error($heading, $message, $template, $status_code);
    }
}

?>
