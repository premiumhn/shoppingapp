<?php


// Consultar el tipo de usuario



	/*$slq = $pdo->prepare("SELECT * FROM Usuarios WHERE PK_Usuario = :PK_Usuario;");
	$slq->bindParam(':PK_Usuario', $_SESSION['login_user']);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$slq->execute();
	$usuario = $slq->fetchAll(PDO::FETCH_ASSOC);

	if($usuario[0]["FK_Idioma"]==1){
		$_SESSION["language"]="en";
	}else{
		$_SESSION["language"]="es";
	}
	*/


	if (empty($_SESSION["language"])) {
		$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		$_SESSION["language"]=$lang;
	}

	if (isset($_SESSION["language"])){
			$lang=$_SESSION["language"]; 
	}


switch ($lang){
	case "es":
		include("es.php");
		break;
	case "en":
		include("en.php");
		break; 
	default:
		include("en.php");
		break;
}