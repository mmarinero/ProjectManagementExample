<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
                $this->load->helper('login_helper');
	}

	function main() {
            
            echo "hello";
            if(userIsLogged()){
                    switch (getUserCategory(0)){
                            case NO_LOGIN:
                                    $category="Sin Loguear";
                                    break;
                            case TRAJABADOR:
                                    $category="Trabajador";
                                    break;
                            case JEFE_PROYECTO:
                                    $category="Jefe Proyecto";
                                    break;
                            case ADMINISTRADOR:
                                    $category="Administrador";
                                    break;
                            default:
                                    $category=" ERROR ";
                    }
                    echo "Logueado como: $category";
            }else {
                    header('Location: login') ;
                    exit();
            }

	}
        
        function login(){
            $feedback = '';
            //if (!strcmp($_POST["submit"], "login")){
                if (userIsLogged()){
                        //Redirect to main
                        header('Location: main') ;
                        exit();
                }else {
                        
                        if (isset($_POST[POST_USERNAME]) && isset($_POST[POST_PASSWORD])) {
                            $login=$_POST[POST_USERNAME];
                            $password=$_POST[POST_PASSWORD];
                            if (strlen($login)<=25 && strlen($password)<=25){
                                    $feedback=login($login, $password);
                            } else {
                                    $feedback= "Error - User o Pass demasiado largos";
                            }
                        }

                        if ($feedback==1){
                                header('Location: main') ;
                        }
                }
            //}
            $data['feedback'] = $feedback;
            $this->load->view('login', $data);
        }
        
        function editar_usuario(){
            $link = @mysql_connect(DB_HOST, DB_USER, DB_PASS);
            if (!$link){
                    $status='ERROR - Sin conexion a la BD';
            }
            @mysql_select_db(DB_NAME);
            if (isset($_POST['username'])) $username=addslashes($_POST['username']);
            else $username = False;

            if (isset($_GET['username'])) $username=addslashes($_GET['username']);
            else $username = False;

            if (isset($_POST['submit']) && $_POST['submit'] == "Salvar"){
                    $nombre=addslashes($_POST['edit_nombre']);
                    $apellido1=addslashes($_POST['edit_apellido1']);
                    $apellido2=addslashes($_POST['edit_apellido2']);
                    $dni=addslashes($_POST['edit_dni']);
                    $telefono=addslashes($_POST['edit_telefono']);
                    $categoria=addslashes($_POST['edit_categoria']);
                    if (!$categoria){
                            $categoria=1;
                    }
                    $query= "UPDATE Trabajadores SET
                    nombre='$nombre',
                    apellido1='$apellido1',
                    apellido2='$apellido2',
                    dni='$dni',
                    telefono='$telefono',
                    categoria='$categoria'
                    WHERE username='$username'";
                    $result =mysql_query($query);
                    if (!$result){
                            $status="Error al editar la entrada";
                            $error=true;
                    }else {
                            $status="Entrada editada correctamente";
                            header('Location: editar_usuarios.php') ;
                            exit();
                    }

            }
            if (!isset($status)) $status = '';

            $query="SELECT * FROM Trabajadores WHERE username='$username'";
            $result=mysql_query($query);
            $array_trabajadores=mysql_fetch_array($result);
            $id=stripslashes($array_trabajadores['id']);
            $nombre=stripslashes($array_trabajadores['nombre']);
            $apellido1=stripslashes($array_trabajadores['apellido1']);
            $apellido2=stripslashes($array_trabajadores['apellido2']);
            $dni=stripslashes($array_trabajadores['dni']);
            $telefono=stripslashes($array_trabajadores['telefono']);
            $categoria=stripslashes($array_trabajadores['categoria']);


            if (isset($error) && $error){
                    $message_color="#FF0000";
            }else {
                    $message_color="#009933";
            }

            $php_self=$_SERVER['PHP_SELF'];
            $formulario_edicion=<<<FORMULARIOEDITARUSUARIO
                            <P><FONT COLOR="$message_color">$status</FONT></P>
                            <P CLASS="bold">Editar Usuario: $username</P>
                            <P>Id: $id</P>
                            <form action="$php_self" method="post">

                                    <p class="bold">Nombre<BR><input type="text" name="edit_nombre" value="$nombre" size="40" maxlength="40"></p>
                                    <p class="bold">Apellidos<BR><input type="text" name="edit_apellido1" value="$apellido1" size="40" maxlength="40">
                                    <input type="text" name="edit_apellido2" value="$apellido2" size="40" maxlength="40"></p>
                                    <p class="bold">DNI<BR><input type="text" name="edit_dni" value="$dni" size="40" maxlength="40"></p>
                                    <p class="bold">Telefono<BR><input type="text" name="edit_telefono" value="$telefono" size="40" maxlength="40"></p>
                                    <p class="bold">Categoria<BR><input type="text" name="edit_categoria" value="$categoria" size="40" maxlength="40"></p>
                                    <input type="hidden" name="username" value="$username">
                                    <p><input type="button" VALUE="Calcelar" onclick="window.location.href='editar_usuarios.php'"><input type="submit" name="submit" VALUE="Salvar"></p>
                            </form>

FORMULARIOEDITARUSUARIO;
            echo $formulario_edicion;
        }
        
        function nuevo_usuario() {
            $link = @mysql_connect(DB_HOST, DB_USER, DB_PASS);
            if (!$link){
                    $status='ERROR - Sin conexion a la BD';
            }
            @mysql_select_db(DB_NAME);
            $username='';
            $password='';
            $nombre='';
            $apellido1='';
            $apellido2='';
            $dni='';
            $telefono='';
            $categoria='';

            if (isset($_POST['submit']) && $_POST['submit'] == "Crear"){
                    $username=addslashes($_POST['create_username']);
                    $password=addslashes($_POST['create_password']);
                    $nombre=addslashes($_POST['create_nombre']);
                    $apellido1=addslashes($_POST['create_apellido1']);
                    $apellido2=addslashes($_POST['create_apellido2']);
                    $dni=addslashes($_POST['create_dni']);
                    $telefono=addslashes($_POST['create_telefono']);
                    $categoria=addslashes($_POST['create_categoria']);
                    if (strlen($username)<4){
                            $status="El username es muy corto";
                            $error=true;
                    } else 	if (strlen($password)<4){
                            $status="El password es muy corto";
                            $error=true;
                    } else 	if (!is_numeric($categoria)){
                            $status="La categoria tiene que ser un numero";
                            $error=true;
                    } else {
                            $query= "INSERT INTO Trabajadores (username,password,nombre,apellido1,apellido2,dni,telefono,categoria) VALUES(
                            '$username',
                            '$password',
                            '$nombre',
                            '$apellido1',
                            '$apellido2',
                            '$dni',
                            '$telefono',
                            '$categoria')";
                            $result =mysql_query($query);
                            if (!$result){
                                    $status="Error al crear la entrada";
                                    $error=true;
                            }else {
                                    $status="Usuario $username creado correctamente";
                                    header('Location: editar_usuarios.php') ;
                                    exit();
                            }
                    }

            }


            if (isset($error) && $error){
                    $message_color="#FF0000";
            }else {
                    $message_color="#009933";
            }
            
            if (!isset($status)) $status = '';
            $php_self=$_SERVER['PHP_SELF'];
            $formulario_edicion=<<<FORMULARIOEDITARUSUARIO
                            <P><FONT COLOR=$message_color">$status</FONT></P>
                            <P CLASS="bold">Nuevo Usuario</P>
                            <form action="$php_self" method="post">

                                    <p class="bold">Username<BR><input type="text" name="create_username" value="$username" size="40" maxlength="40"></p>
                                    <p class="bold">Password<BR><input type="text" name="create_password" value="$password" size="40" maxlength="40"></p>
                                    <p class="bold">Nombre<BR><input type="text" name="create_nombre" value="$nombre" size="40" maxlength="40"></p>
                                    <p class="bold">Apellidos<BR><input type="text" name="create_apellido1" value="$apellido1" size="40" maxlength="40">
                                    <input type="text" name="create_apellido2" value="$apellido2" size="40" maxlength="40"></p>
                                    <p class="bold">DNI<BR><input type="text" name="create_dni" value="$dni" size="40" maxlength="40"></p>
                                    <p class="bold">Telefono<BR><input type="text" name="create_telefono" value="$telefono" size="40" maxlength="40"></p>
                                    <p class="bold">Categoria<BR><input type="text" name="create_categoria" value="$categoria" size="40" maxlength="40"></p>
                                    <input type="hidden" name="username" value="">
                                    <p><input type="button" VALUE="Calcelar" onclick="window.location.href='editar_usuarios.php'"><input type="submit" name="submit" VALUE="Crear"></p>
                            </form>

FORMULARIOEDITARUSUARIO;
            echo $formulario_edicion;
        }

        
        
        function editar_usuarios(){
            $php_self=$_SERVER['PHP_SELF'];

            $link = @mysql_connect(DB_HOST, DB_USER, DB_PASS);
            if (!$link){
                    $status='ERROR - Sin conexion a la BD';
            }
            @mysql_select_db(DB_NAME);
            if (isset($_GET['action']) && !strcmp($_GET['action'],"delete") && strlen($username=$_GET['username'])>0){
                    $query= "DELETE FROM Trabajadores WHERE username='$username'";
                    $result =mysql_query($query);
                    if (!$result){
                            echo "Fallo al borrar el usuario";
                    }else {
                            if (mysql_affected_rows()>0){
                                    echo "Usuario '$username' borrado con �xito";
                            }
                    }

            }

            $query="SELECT id, username,categoria FROM Trabajadores";
            $result2 =mysql_query($query);
            if (!$result2){
                    $status="Error al recuperar los datos";
                    exit();
            }else {
                    $table="<table border cellpadding=3>";
                    while($info = mysql_fetch_array( $result2 ))
                    {
                            $id=$info['id'];
                            $username=stripslashes($info['username']);
                            $edit_button="<a href='editar_usuario.php?username=$username'>Editar</a>";
                            $delete_button="<a href='$php_self?username=$username&action=delete'>Borrar</a>";
                            $table="$table<tr><td>$id</td><td>$username</td><td>$edit_button</td><td>$delete_button</td></tr>";
                    }
                    $table="$table</table>";


            }
            imprimir_cabecera(HEADER_EDIT_USUARIO);

            echo $table;
            echo "<a href='nuevo_usuario.php'>Nuevo Usuario</a>";
        }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */