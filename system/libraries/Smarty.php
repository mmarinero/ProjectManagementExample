<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Smarty Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Smarty
 * @author		Kepler Gelotte
 * @link		http://www.coolphptools.com/codeigniter-smarty
 */
require_once( BASEPATH.'libraries/Smarty/libs/Smarty.class.php' );

class CI_Smarty extends Smarty {
    
        private $extension = '.tpl';

	function __construct()
	{
		parent::__construct();

		$this->compile_dir = APPPATH . "views/templates_c";
		$this->template_dir = APPPATH . "views/templates";
		$this->assign( 'APPPATH', APPPATH );
		$this->assign( 'BASEPATH', BASEPATH );

		// Assign CodeIgniter object by reference to CI
		if ( method_exists( $this, 'assignByRef') ) {
			$ci =& get_instance();
			$this->assignByRef("ci", $ci);
		}

		log_message('debug', "Smarty Class Initialized");
	}
        
        /**
         * Set extension of the actual view filename, the dot is automatically
         * inserted.
         * If null is passed the full filename should be used in the view call.
         * This extension only affects the view method for CI views emulation.
         * @param string $extension 
         */
        public function setExtension($extension) {
            if ($extension !== null) $this->extension = '.'.$extension;
            else $this->extension = '';
        }


	/**
	 *  Parse a template using the Smarty engine
	 *
	 * This is a convenience method that combines assign() and
	 * display() into one step. 
	 *
	 * Values to assign are passed in an associative array of
	 * name => value pairs.
	 *
	 * If the output is to be returned as a string to the caller
	 * instead of being output, pass true as the third parameter.
	 *
	 * @access	public
	 * @param	string
	 * @param	array
	 * @param	bool
	 * @return	string
	 */
	function view($templateNoExtension, $data = array(), $return = FALSE) {
                header('Content-Type: text/html; charset=UTF-8');
                $template = $templateNoExtension.$this->extension;
		foreach ($data as $key => $val) {
			$this->assign($key, $val);
		}
		
		if ($return == FALSE) {
			$CI =& get_instance();
			if (method_exists( $CI->output, 'set_output' )) {
				$CI->output->set_output( $this->fetch($template) );
			} else {
				$CI->output->final_output = $this->fetch($template);
			}
			return;
		} else {
			return $this->fetch($template);
		}
	}
}
// END Smarty Class
