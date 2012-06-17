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

    //private $models = array('Trabajador', 'PlanFases', 'PlanIteraccion', 'TareaPersonal', 'Actividad');
    private $models = array('Proyecto', 'PlanIteracion','Actividad', 'Trabajador', 'TareaPersonal', 'TrabajadoresProyecto');

    function __construct() {
        parent::__construct();
        $this->load->dbforge();
    }
    
    private function addFields($fieldsArray) {
        foreach ($fieldsArray as $field) {
            $this->dbforge->add_field($field->getCIDBcreateData());
        }
    }

    public function crear($drop = false) {
        //$this->db->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 't1')");
        $scriptOut =  "creating database <br />\n";
        $command = "mysql grupo10 --user=" . $this->db->username . " --password=" . 
                $this->db->password . " < " . APPPATH . "helpers/schema.sql 2>&1";
        exec($command, $mysqlOutput, $mysqlRet);
        $scriptOut .= join("<br />\n", $mysqlOutput)."<br />\n".$mysqlRet."<br />\n";
        $scriptOut .= "scripts executed<br />\n";
        log_message('debug', 'scripts executed');
        
        if ($drop){
            foreach (array_reverse($this->models) as $model){
                $this->load->model($model);
                $this->db->query("drop table if exists {$this->$model->getTableName()}");
                log_message('debug', 'table dropped: '.$model);
                $scriptOut .= 'table dropped: '.$model."<br />\n";;
            }
        }

        foreach ($this->models as $model) {
            $this->dbforge->add_field($this->config->item('idCISqlDefinition'));
            $this->addFields($this->$model->getFields());
            $this->addFields($this->$model->getReferences());
            if (isset($model_props['DBCustomFields']) && $model_props['DBCustomFields']) {
                $this->addFields($this->$model->getCustomFields());
            }
            $model_props = $this->$model->getProperties();
            //TODO implementar
            if (isset($model_props['hashed']) && $model_props['hashed']) {
                $this->dbforge->add_field(array('hashId' => array('type' => 'varbinary', 'constraint' => '16')));
            }
            if (isset($model_props['created']) && $model_props['created']) {
                $this->dbforge->add_field('created timestamp NULL');
            }
            if (isset($model_props['updated']) && $model_props['updated']) {
                $this->dbforge->add_field('updated timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP');
            }
            if (isset($model_props['ordered']) && $model_props['ordered']) {
		$this->dbforge->add_field(array('order' => array('type' => 'int', 'constraint' => '8', 'auto_increment'=>true)));
	    }
            $this->dbforge->add_key('id', true);
            
            //crear tabla 
            $this->dbforge->create_table($this->$model->getTableName(), true);
            //anadir constraints foreign keys
            foreach ($this->$model->getReferences() as $reference){
                $this->db->query($reference->getAlterTableFKConstraint());
            }
            //Crear trigger (no soportado en jair)
            if ($model_props['created']) {
            $this->db->query('CREATE TRIGGER insertCreatedTimestampTrigger before INSERT ON '.
                    $this->$model->getTableName().
                    ' FOR EACH ROW SET NEW.created = CURRENT_TIMESTAMP');
            }
            log_message('debug', 'model table created: '.$model);
            $scriptOut .= 'model table created: '.$model."<br />\n";
            $this->smarty->assign("scriptOut", $scriptOut);
            $this->smarty->view('crearBD');
        }
    }

}
