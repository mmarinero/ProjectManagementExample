<?php

function userIsLogged(){
	if (isset ($_COOKIE[COOKIE_USERNAME])){
		return true;
	} else {
		return false;
	}
}

function getUserCategory($projectId){
	$username=$_COOKIE[COOKIE_USERNAME];
	if ($username){
		$link = @mysql_connect(DB_HOST, DB_USER, DB_PASS);
		if (!$link){
			return SIN_CONEXION_BD;
		}
		//DEBUG CODE
		if (!strcmp($username, "admin")){
			return ADMINISTRADOR;
		}
		
		//END DEBUG CODE
		
		@mysql_select_db(DB_NAME);
		$query="SELECT categoria FROM Trabajadores WHERE username='$username'";
		if ($result=mysql_query($query)){
			$row=mysql_fetch_array($result);
			return $row['categoria'];
		}
		return QUERY_ERROR;
	}
}


function login($username,$password) {
	if (!$username || !$password){
		$feedback='ERROR - Falta el login o el password';
		return $feedback;
	} else {
		$username=strtolower($username);
		$link = @mysql_connect(DB_HOST, DB_USER, DB_PASS);
		if (!$link){
			$feedback='ERROR - Sin conexion a la BD';
			return $feedback;
		}
		@mysql_select_db(DB_NAME);

		$query="SELECT username FROM Trabajadores WHERE username='$username' AND password='$password'";
		$result=mysql_query($query);
		if (!$result){
			$feedback='ERROR - Error en la consulta';
			return $feedback;
		}else {
			if( mysql_num_rows($result)<1 ){
				$feedback='ERROR - Usuario inexistente';
				return $feedback;
			}
			marcar_loggeado($username);
			return 1;
		}
	}

}

function marcar_loggeado($username){
	if (!$username){
		return false;
	}
	$username=strtolower($username);
	setcookie(COOKIE_USERNAME,$username,(time()+LOGIN_LEASE_TIME),'/','',0);
}

function logout(){
	setcookie(COOKIE_USERNAME,'',time(),'/','',0);
}

?>