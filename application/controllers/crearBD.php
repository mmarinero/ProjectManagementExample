<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//esta clase debe permitir crear la base de datos y quizas anadir datos iniciales para simplificar el proceso de modificacion
//los datos para crear cada tabla se definen en su modelo correspondiente y aqui se leen los datos de los modelos listados en 
//el array
//Para la el acceso a la base de datos se debe usar la clase DB_Forge
class CrearDB extends CI_Controller
{
        private $models = array('Trabajador');
	function __construct()
	{
		parent::__construct();
                $this->load->dbforge();
	}

	public function crear()
	{

	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */