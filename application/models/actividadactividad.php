<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class ActividadActividad extends EX_Model {
    
    protected $fields = array();
    
    protected function initModel() {
        $this->references['Precedente'] = new Reference(__CLASS__, 'Actividad',
                array('update'=>'cascade','delete'=>'cascade', 'cyclic' => 'Precedente'));
        $this->references['Posterior'] = new Reference(__CLASS__, 'Actividad',
                array('update'=>'cascade','delete'=>'cascade', 'cyclic' => 'Posterior'));
    }
}
