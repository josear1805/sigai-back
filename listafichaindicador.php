<?php
include "cabecera.php";
include "lib/funciones_bd.php";

$arg = getallheaders();
$json_data = json_decode(file_get_contents('php://input'));

$json_data = $json_data ?? '';

$token = $arg["authorization"] ?? $arg["Authorization"] ?? '';
$iduser = sesionactiva($token);

if ($iduser > 0) 
{
	$json_data->idUsuario = $iduser;
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		header("HTTP/1.1 200 OK");		
		$Resultado=listafichaindicador($json_data);
		if($Resultado)
		{
			echo $Resultado;		
			exit();
		} else {
			header("HTTP/1.1 200 OK");		
			$Estatus=3;
			$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";			
			$vacio2= ['id'=>null, 'nb'=>null];
			$json = array("estatus"=>$Estatus, "mensaje"=>$Mensaje, "ficha"=>$vacio2);			
			echo json_encode($json);
			exit();
		}
	}else{
		if ($_SERVER['REQUEST_METHOD'] !== "OPTIONS") {
			header("HTTP/1.1 401 Unauthorized");
		}
		}
} else {
	if ($_SERVER['REQUEST_METHOD'] !== "OPTIONS") {
		header("HTTP/1.1 401 Unauthorized");
	}
}

?>