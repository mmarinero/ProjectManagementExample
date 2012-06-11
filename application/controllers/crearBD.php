<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//esta clase debe permitir crear la base de datos y quizas anadir datos iniciales para simplificar el proceso de modificacion
//los datos para crear cada tabla se definen en su modelo correspondiente y aqui se leen los datos de los modelos listados en 
//el array
//Para la el acceso a la base de datos se debe usar la clase DB_Forge
class CrearBD extends CI_Controller
{ 
	//check table exists information_schema
	//exec mysql < script
	//ICustomType fuera de $fields  los elementos
	//refs array clase ref
	
    private $models = array('Trabajador','PlanFases','PlanIteraccion','TareaPersonal', 'Actividad');
	
	function __construct()
	{
	    parent::__construct();
            $this->load->dbforge();
	}

    public function crear() {
	//$this->db->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 't1')");
	echo exec("mysql grupo10 --user=".$this->db->username." --password=".$this->db->password." < ".APPPATH."helpers/schema.sql 2>&1");
	foreach ($this->models as $model){
	    $this->load->model($model);
	    foreach($this->$model->getFields() $field) {
		$ciFields[] = $field->getCIDBSql()
	    }
	    $ciFields[] = array('id'=>array('type'=>'int', 'constraint'=>'8'));
	    $ciFields[] = array('hashId'=>array('type'=>'varchar', 'constraint'=>'32'));
	    $ciFields[] = array('created'=>array('type'=>'timestamp', 'constraint'=>'8'));
	    $ciFields[] = array('updated'=>array('type'=>'timestamp', 'constraint'=>'8'));
	    $this->dbforge->addField($ciFields);

	}
}
