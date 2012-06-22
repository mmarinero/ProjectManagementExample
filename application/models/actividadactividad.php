<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class ActividadActividad extends EX_Model {

    protected $tableName = __CLASS__;
    
    protected $fields = array();
    
    protected function init() {
        $this->references['Precedente'] = new Reference($this, 'Actividad', 'Precedente',array('delete'=>'cascade','update'=>'cascade'));
        $this->references['Posterior'] = new Reference($this, 'Actividad', 'Posterior',array('delete'=>'cascade','update'=>'cascade'));
    }
}
