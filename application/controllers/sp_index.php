<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sp_index extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
                $this->load->helper('url');
	}

	function index()
	{
            redirect('/auth/index');

            //$this->load->view('welcome_message');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */