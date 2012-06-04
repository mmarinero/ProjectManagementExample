<?php
/**
 * Este script permite ejecutar comandos de svn para desplegar un proyecto a
 * partir de un repositorio de subversion. Posteriormente adapta los ficheros config.php
 * y .htaccess para reconocer el subdirectio donde se ejecuta dentro del servidor web
 * y adaptar los permisos de la carpeta logs y templates_c para que puedan ser escritas
 * por el servidor web.
 * El funcionamiento de este script depende fuertemente del entorno de ejecucion y debera
 * ser adaptado a cada servidor.
 * 
 * svnupdate es un programa en c que debera tener los permisos adecuados (setuid) 
 * para ejecutar las acciones que no puede el usuario del servidor apache
 * ejemplos:
 * svn update
 * http://jair.lab.fi.uva.es/~marmari/svnupdate.php?p=<otramediapass>
 * checkout trunk 
 * http://jair.lab.fi.uva.es/~marmari/svnupdate.php?p=<otramediapass>&checkout=1
 * checkout specific branch
 * http://jair.lab.fi.uva.es/~marmari/svnupdate.php?p=<otramediapass>&checkout=1&branch=branches/codeIgniter
 */
echo "comienzo de la ejecucion<br />\n";
$usuarioJair = 'marmari';
$usuarioAssembla = 'mmarinero';
//es posible poner aqui la primera parte de la clave, el resto se debera pasar con el parametro p en la url.
$mediaPass = 'TlIunw0wbf';
$repositorio = 'https://subversion.assembla.com/svn/seteprosci/';
if (isset($_GET['branch'])) $repositorio .= $_GET['branch'];
else $repositorio .= 'trunk';

$configFile = 'application/config/config.php';
$htaccess = '.htaccess';
if (isset($_GET['checkout']) && $_GET['checkout']){
    exec('./svnupdate checkout /home/grini/'.$usuarioJair.'/.subversion '
            .$usuarioAssembla.' '.$mediaPass.escapeshellarg($_GET['p']).' '.$repositorio.' 2>&1',$svnOutput, $svnReturn);
} else {
    exec('./svnupdate update /home/grini/'.$usuarioJair.'/.subversion '
            .$usuarioAssembla.' '.$mediaPass.escapeshellarg($_GET['p']).' 2>&1',$svnOutput, $svnReturn);
}
if ($svnReturn) echo "El comando svn fallo, la salida se adjunta al final de la pagina<br />\n";
else echo "comando svn ejecutado correctamente<br />\n";

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
else echo 'Se ha modificado en '.$configFile." \$config['base_url'] = ''; por \$config['base_url'] = '/~".$usuarioJair."/';<br /> \n";

$HTstring = file_get_contents($htaccess);
$replacedHTstring = str_replace("RewriteBase /\n","RewriteBase /~".$usuarioJair."/\n",$HTstring);
$fp = popen('./svnupdate writefile htaccess', 'w');
if (!fwrite($fp, $replacedHTstring)) echo 'no se pudo escribir '.$htaccess."<br /> \n";
if(pclose($fp)) echo 'algo ha ido mal escribiendo el fichero '.$htaccess." con svnupdate<br /> \n";
else echo 'Se ha modificado en '.$configFile." RewriteBase / por RewriteBase /~".$usuarioJair."/<br /> \n";

foreach (array('logs', 'templates_c') as $file) {
    echo exec('./svnupdate chmod '.$file.' 2>&1',$chmodOutput, $chmodReturn);
    if ($chmodReturn) echo join("<br />\n",$chmodOutput)."<br />\n";
    else echo "$file chmoded correctamente <br />\n";
}
echo "<br />\nsalida svn:<br />\n&nbsp;&nbsp;&nbsp;&nbsp";
echo join("<br />\n&nbsp;&nbsp;&nbsp;&nbsp;",$svnOutput)."<br />\n";
echo "<br />\nfinal de ejecucion<br />\n";