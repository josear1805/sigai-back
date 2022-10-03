<?php
include "cabecera.php";
include "lib/funciones_bd.php";

$arg = getallheaders();
$json_data = json_decode(file_get_contents('php://input'));

if (!isset($json_data)) {
	$Estatus=3;
	$Mensaje="Error al tratar de consumir el recurso, No ha suministrado idCategoria";	
	$json = array("Estatus"=>$Estatus, "Mensaje"=>$Mensaje, "categoria"=>null);
	echo json_encode($json);
	exit();	
}

$token = $arg["authorization"] ?? $arg["Authorization"] ?? '';
$iduser = sesionactiva($token);
$iduser=1;
if ($iduser > 0) 
{
	$json_data->idUsuario = $iduser;
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		header("HTTP/1.1 200 OK");		
		$Resultado=modcategoriasindicadores($json_data);
		if($Resultado)
		{
			echo $Resultado;		
			exit();
		} else {
			header("HTTP/1.1 200 OK");		
			$Estatus=3;
			$Mensaje="Error al tratar de consumir el recurso, por favor consulte con el administrador";			
			$json = array("estatus"=>$Estatus, "mensaje"=>$Mensaje, "categoria"=>null);
			echo json_encode($json);
			exit();
		}
	}else{
		if ($_SERVER['REQUEST_METHOD'] !== "OPTIONS") {
			//header("HTTP/1.1 400 Bad Request");
		}
		}
} else {
	if ($_SERVER['REQUEST_METHOD'] !== "OPTIONS") {
		header("HTTP/1.1 400 Bad Request");
	}
}

?>
