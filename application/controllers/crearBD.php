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
    private $models = array('Proyecto','PlanFases','PlanIteracion','Actividad','ActividadActividad', 'Trabajador', 'TareaPersonal', 'TrabajadoresProyecto');

    function __construct() {
        parent::__construct();
        $this->load->dbforge();
        $this->load->library('tank_auth');
        if ($this->tank_auth->get_role_name()==='admin') {									// logged in
            show_error('', 403);
            exit();
	}
        //$trabajador = Trabajador::fromTankAuth($this->tank_auth->get_user_id());
        //if (!is_null($trabajador)) $this->smarty->assign('trabajador',$trabajador);
        //$proyectoLoader = new Proyecto();
        //$this->smarty->assign('proyectos', $proyectoLoader->loadArray());
        $this->smarty->assign('this', $this);
    }
    
    private function addFields($fieldsArray) {
        foreach ($fieldsArray as $field) {
            $this->dbforge->add_field($field->getCIDBcreateData());
        }
    }

    public function crear($drop = true) {
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
        }
        $this->smarty->assign("scriptOut", $scriptOut);
        $this->smarty->view('crearBD');
        $this->populateDB();
    }
    
    private function populateDB(){
        $users = array("Administrador", 
            "Jefep", 
            'Analista',
            'Diseñador',
            'AnalistaProgramador', 
            'ResponsablePruebas',
            'Programador',
            'Probador');
        $roles = array_keys(Trabajador::getRoles());
        foreach($users as $index => $user) {
            $this->tank_auth->create_user(
            str_replace(' ','',$user),
                    "$user@setepros.es",
                    '1234',
                    false,
                    $user,
                    $roles[$index]);
        }
        
        $proyecto = new Proyecto();
        $proyecto->DBInsert(array(
            'nombre'=>'Proyecto prueba',
            'descripcion'=>'Proyecto de pruebas creado automaticamente',
            'inicio'=>'1/6/1988'));
        $planFases = new PlanFases();
        $planFases->DBInsert(array(
            'Proyecto'=>$proyecto->getId(),
            'iteracionesinicio'=>1,
            'iteracioneselaboracion'=>2,
            'iteracionesconstrucion'=>2,
            'iteracionestransicion'=>1),true);
//        $planIteracion = new PlanIteracion();
//        $planIteracion->DBInsert(array(
//            'nombre'=>'Iteración prueba',
//            'descripcion'=>'Iteración de pruebas creado automaticamente',
//            'Proyecto'=>$proyecto->getId()
//            ));
//        $Actividad = new Actividad();
//        $Actividad->DBInsert(array(
//            'nombre'=>'Actividad prueba',
//            'descripcion'=>'Actividad de pruebas creado automaticamente',
//            'PlanIteracion'=>$planIteracion->getId()
//            ));
    }
}
