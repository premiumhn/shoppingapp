<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include ("../global/config.php");
include ("../global/conexion.php");
include ("../global/const.php");

session_start();

$codigo = (isset($_REQUEST['c']))?$_REQUEST['c']:"";
$correo_destino = (isset($_REQUEST['m']))?$_REQUEST['m']:"";


$estado = (isset($_REQUEST['estado']))?$_REQUEST['estado']:"";
if($estado == 'confirmado'){
   
    $codigo_confirmado = (isset($_REQUEST['codigo_confirmado']))?$_REQUEST['codigo_confirmado']:"";
    
    $buscar_usuario = $pdo->prepare("SELECT * FROM Usuarios WHERE CodigoConfirmacion = :CodigoConfirmacion");
    $buscar_usuario->bindParam(':CodigoConfirmacion', $codigo_confirmado);
    $buscar_usuario->execute();
    $usuario = $buscar_usuario->fetchAll(PDO::FETCH_ASSOC);

    // actualizar usuario confirmado

    if(count($usuario)==0){
        header('Location: Login');
    }
   
    if($usuario[0]['Correo'] == $correo_destino){
        $actualizar = $pdo->prepare("UPDATE Usuarios 
                                    SET EstadoCorreo = 1
                                    WHERE PK_Usuario = :PK_Usuario");
        $actualizar->bindParam(':PK_Usuario', $usuario[0]['PK_Usuario']);                          
        $actualizar->execute();
        header('Location: Login?p=cf&u='.$usuario[0]['NombreUsuario']);

    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Confirmación correo</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link href="<?php echo URL_SITIO ?>static/css/styles.css" rel="stylesheet" type="text/css" media="all" />

	<script src="https://kit.fontawesome.com/b2dbb6a24d.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="<?php echo URL_SITIO ?>static/js/jquery-3.5.0.min.js" ></script>
 
    <link href="<?php echo URL_SITIO ?>static/css/confirmacion_correo.css" rel="stylesheet" type="text/css" media="all" />
    <?php include 'iconos.php' ?>


    

</head>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v7.0&appId=606527096633387&autoLogAppEvents=1"></script>
<body>
   
<br>
<div class="row col-md-12">
    <div class="col-md-6 offset-md-3 logo">
    </div>
</div>

<div id="row">
    <br>
    <br>
    <br>
    <div class="col-md-8 offset-md-2">
        <h5 style="color:gray">Hemos enviado un correo de confirmación a <?php echo $correo_destino ?>. Revisa la bandeja de entrada de tu correo electorónico.</h5>
    </div>
    <div class="row col-md-12">
        <div class="col-md-6 offset-md-3 waiter">
    </div>
</div>

</div>
</div>
<br>
<div class="row col-md-12">
    <div class="col-md-4 offset-md-4 text-center">
        <span style="font-size:18px;color:gray;">¿No lo has recibido?</span>
    </div>
</div>
<div class="row col-md-12">
    <div class="col-md-4 offset-md-4 text-center">
        <a href="location: <?php echo URL_SITIO ?> scripts/email.php?c=<?php echo $codigo.'&m='.$correo_destino?>" class="btn btn-primary btn_registrarse">Reenviar correo</a>
    </div>
</div>
<br>

</body>
</html>

