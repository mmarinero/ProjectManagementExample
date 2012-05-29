<?php
//svnupdate es un programa en c que debera tener los permisos adecuados (setuid) 
//para ejecutar las acciones que no puede el usuario del servidor apache

$usuarioJair = 'marmari';
$usuarioAssembla = 'mmarinero';
//es posible poner aqui la primera parte de la clave, el resto se debera pasar con el parametro p en la url.
$mediaPass = 'TlIunw0wbf';
$repositorio = 'https://subversion.assembla.com/svn/setepros/';
if (isset($_GET['branch'])) $repositorio .= $_GET['branch'];
else $repositorio .= 'trunk';

$configFile = 'application/config/config.php';
$htaccess = '.htaccess';
if (isset($_GET['checkout']) && $_GET['checkout']){
    exec('./svnupdate checkout /home/grini/'.$usuarioJair.'/.subversion '.$usuarioAssembla.' '.$mediaPass.escapeshellarg($_GET['p']).' '.$repositorio.' 2>&1',$output);
} else {
    exec('./svnupdate update /home/grini/'.$usuarioJair.'/.subversion '.$usuarioAssembla.' '.$mediaPass.escapeshellarg($_GET['p']).' 2>&1',$output);
}
echo join("<br />\n",$output)."<br />\n";

//Parece haber algun problema con file_get_contents aunque de momento funciona
//$fh = fopen($configFile, 'r');
//$CFstring = fread($fh, 32768);
//fclose($fh);
$CFstring = file_get_contents($configFile);
$replacedCFstring = str_replace("\$config['base_url']	= '';","\$config['base_url']	= '/~".$usuarioJair."/';",$CFstring);
$fp = popen('./svnupdate writefile config', 'w');
//pasamos por stdin el fichero a c
if (!fwrite($fp, $replacedCFstring)) echo 'no se pudo escribir '.$configFile."<br /> \n";
if(pclose($fp)) echo 'algo ha ido mal escribiendo el fichero '.$configFile." con svnupdate<br /> \n";

$HTstring = file_get_contents($htaccess);
$replacedHTstring = str_replace("RewriteBase /\n","RewriteBase /~".$usuarioJair."/\n",$HTstring);
$fp = popen('./svnupdate writefile htaccess', 'w');
if (!fwrite($fp, $replacedHTstring)) echo 'no se pudo escribir '.$htaccess."<br /> \n";
if(pclose($fp)) echo 'algo ha ido mal escribiendo el fichero '.$htaccess." con svnupdate<br /> \n";
?>
