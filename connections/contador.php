<?php
$host = 'localhost';
$dbnombre = 'argis';
$dbusuario = 'Cerrajes';
$dbpass = 'PRAXAIR1';
//Eliminamos los errores de la pantalla
	error_reporting(0);
//Conectamos a MySQL
	$enlace = mysql_connect($host,$dbusuario,$dbpass);
//Seleccionamos la DB
	mysql_select_db($dbnombre,$enlace);
//Conseguimos la informaci&oacute;n del usuario
	$ip = getenv("REMOTE_ADDR");
	$navegador = getenv("HTTP_USER_AGENT");
	$referer = getenv("HTTP_REFERER");
	$uri = getenv("SCRIPT_NAME");
	$t = time();
//Verificamos si es nueva la IP o el tiempo que tiene de inactividad.
	$resultado = mysql_query("SELECT * from estadisticas  where ( ip = '$ip' ) and (tiempo > ($t-1800) )",$enlace);
	if(mysql_num_rows($resultado) == 0){
		$resultado = mysql_query("UPDATE contador set valor=(valor + 1) where id = 1",$enlace);
	}
	$resultado = mysql_query("INSERT INTO estadisticas (ip,navegador,uri,referer,tiempo) VALUES ('$ip','$navegador','$uri','$referer',$t)",$enlace);
//Obtenemos valores
	$resultado = mysql_query("SELECT valor from contador  where id=1",$enlace);
	if($renglon = mysql_fetch_array($resultado))
		$visitas = $renglon['valor'];
	$resultado = mysql_query("SELECT DISTINCT ip from estadisticas  where tiempo > ($t - 1800)",$enlace);
		$online = mysql_num_rows($resultado);
//Borramos estadisticas de mas de 1 semana de edad
	$resultado = mysql_query("DELETE from estadisticas where tiempo < ($t - 5011200)",$enlace);
//Cerramos MySQL
	mysql_close($enlace);
?>