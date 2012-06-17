<?php

/**
 * Panel de control principal
 *
 * @author Mario Marinero <mario.marinero@alumnos.uva.es>
 */
class dashboard extends CI_Controller{
    
    
    
    function index(){
        $this->smarty->assign("message", "hello dashboard");
        $this->smarty->view('dashboard');
    }
}

?>
