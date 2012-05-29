<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

//Constantes de usuarios

define('COOKIE_USERNAME', 'username');
define('LOGIN_LEASE_TIME', 10);
define('POST_USERNAME', 'username');
define('POST_PASSWORD', 'password');
define('HEADER_LOGIN', 'login');
define('HEADER_EDIT_USUARIO', 'edit_user');

// Codigos de respuesta
define('QUERY_ERROR', -2);
define('SIN_CONEXION_BD',-1);
define('NO_LOGIN', 0);
define('TRAJABADOR', 1);
define('JEFE_PROYECTO', 2);
define('ADMINISTRADOR', 3);

//base de datos

define("DB_HOST", "localhost");
define("DB_USER", "grupo01");
define("DB_PASS", "avMOyZe7");
define("DB_NAME", "grupo01");

/* End of file constants.php */
/* Location: ./application/config/constants.php */