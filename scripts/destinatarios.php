<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include '../global/config.php';
include '../global/conexion.php';
include '../global/const.php';

session_start();


$sql_pais=$pdo->prepare("SELECT * from Paises");
$sql_pais->execute();
$listPais=$sql_pais->fetchAll(PDO::FETCH_ASSOC);


	$txtID=(isset($_POST['PK_Ciudad']))?$_POST["PK_Ciudad"]:"";
	$accion=(isset($_POST['accion']))?$_POST["accion"]:"";


	$txtNombres=(isset($_POST['NombresDestinatario']))?$_POST["NombresDestinatario"]:"";
	$txtApellidos=(isset($_POST['ApellidosDestinatario']))?$_POST["ApellidosDestinatario"]:"";
	$txtTelefono=(isset($_POST['Telefono']))?$_POST["Telefono"]:"";
	$txtDepartamento=(isset($_POST['Departamento']))?$_POST["Departamento"]:"";
	$txtDireccion1=(isset($_POST['Direccion']))?$_POST["Direccion"]:"";
	$txtDireccion2=(isset($_POST['Direccion2']))?$_POST["Direccion2"]:"";
	$txtCodigoPostal=(isset($_POST['CP']))?$_POST["CP"]:"";
	$txtCliente=(isset($_POST['Cliente']))?$_POST["Cliente"]:"";
    $txtCiudad=(isset($_POST['input_ciudad']))?$_POST["input_ciudad"]:"";
    
    // Buscar cliente
    $buscar_cliente = $pdo->prepare("SELECT * FROM Clientes WHERE FK_Usuario = :PK_Usuario");
    $buscar_cliente->bindParam(":PK_Usuario", $_SESSION['login_user']);
    $buscar_cliente->execute();
    $cliente = $buscar_cliente->fetchAll(PDO::FETCH_ASSOC);
   

	

	switch ($accion) {
		case 'agregar':
					
					$sentencia=$pdo->prepare("INSERT into destinatarios(NombresDestinatario,ApellidosDestinatario,Telefono,Departamento,Direccion1,Direccion2,CodigoPostal,FK_Cliente,FK_Ciudad) values(:NombresDestinatario,:ApellidosDestinatario,:Telefono,:Departamento,:Direccion1,:Direccion2,:CodigoPostal,:FK_Cliente,:FK_Ciudad) ");
					$sentencia->bindParam(':NombresDestinatario',$txtNombres);
					$sentencia->bindParam(':ApellidosDestinatario',$txtApellidos);
					$sentencia->bindParam(':Telefono',$txtTelefono);
					$sentencia->bindParam(':Departamento',$txtDepartamento);
					$sentencia->bindParam(':Direccion1',$txtDireccion1);
					$sentencia->bindParam(':Direccion2',$txtDireccion2);
					$sentencia->bindParam(':CodigoPostal',$txtCodigoPostal);
					$sentencia->bindParam(':FK_Cliente',$txtCliente);
					$sentencia->bindParam(':FK_Ciudad',$txtCiudad);
					if($sentencia->execute()){
                        header('Location: ../Ver-Destinatarios?msj=agregado');
                    }else{
                        header('Location: ../Ver-Destinatarios?msj=error_noagregado');
                    }
				break;
		case 'editar':
					
			break;
        case 'eliminar':
                   $pk_destinatario = (isset($_POST['PK_Destinatario'])?$_POST['PK_Destinatario']:"");
                   $eliminar = $pdo->prepare("DELETE FROM Destinatarios WHERE PK_Destinatario = :PK_Destinatario");
                   $eliminar->bindParam(":PK_Destinatario", $pk_destinatario);
                   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    if($eliminar->execute()){
                        header('Location: ../Ver-Destinatarios?msj=eliminado');
                    }else{
                        header('Location: ../Ver-Destinatarios?msj=error_noeliminado');
                    }
			break;
		case 'cancelar':
		
			break;
		
		default:
			# code...
			break;
	}

?> 