<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include '../global/config.php';
include '../global/conexion.php';
include '../global/const.php';

session_start();
require 'language/requirelanguage.php';

require ('../scripts/comprobaciones.php');
// tipo de registro
$form = (isset($_REQUEST['menu']))? $_REQUEST['menu'] : "";
$buscar_usuario = $pdo->prepare("SELECT * FROM Usuarios
                                WHERE PK_Usuario = :PK_Usuario");
$buscar_usuario->bindParam('PK_Usuario', $_SESSION['login_user']);
$buscar_usuario->execute();
$usuario = $buscar_usuario->fetchAll(PDO::FETCH_ASSOC);  




?>
<!DOCTYPE html>
<html lang="en">
<head>

<meta http-equiv='cache-control' content='no-cache'>
<meta http-equiv='expires' content='0'>
<meta http-equiv='pragma' content='no-cache'>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gestión</title>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    

    <script src="<?php echo URL_SITIO ?>static/js/jquery-3.5.0.min.js" ></script>
    <?php if($usuario[0]['FK_TipoUsuario']!=3){ ?>
        <link href="<?php echo URL_SITIO ?>static/css/styles.css" rel="stylesheet" type="text/css" media="all" /> 
    <?php } ?>
    <link href="<?php echo URL_SITIO ?>static/css/registro_datos.css" rel="stylesheet" type="text/css" media="all" /> 
    <link href="<?php echo URL_SITIO ?>static/css/toasts.css" rel="stylesheet" type="text/css" media="all" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="https://kit.fontawesome.com/b2dbb6a24d.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  

    <?php include 'iconos.php' ?>

</head>
<body>
    <?php if($usuario[0]['FK_TipoUsuario'] == 3 and ($form == 'registro_categoria' or $form == 'ver_categorias' ) ){ 
        include ("../templates/header_admin.php"); 
    }else{
        include ("../templates/header.php");
    }?>

    <div class="row" style="width:100%;margin:0px;">
        
        
        <?php 
            switch($form){
                case 'registro_categoria':
                    require ('./registro_categoria.php');
                break;
                case 'ver_categorias':
                    require ('./ver_categorias.php');
                break;
                case 'perfil_usuario':
                    require ('./perfil_usuario.php');
                break;
                case 'perfil_tienda':
                    require ('./perfil_tienda.php');
                break;
                case 'registro_regionesEnvio':
                    require ('./regiones_envio.php');
                break;
                case 'ver_regionesEnvio':
                    require ('./ver_regionesEnvio.php');
                break;
            }
        ?>
       
       
        
    </div>
    
    
    <?php if($usuario[0]['FK_TipoUsuario'] == 3){ 
        include ("../templates/footer_admin.php"); 
    }else{
        include ("../templates/footer.php");
    }?>
</body>
</html>

<script type="text/javascript">
 $('#search-form').hide();

 <?php if($usuario[0]['FK_TipoUsuario'] == 3 and ($form == 'registro_categoria' or $form == 'ver_categorias' ) ){ ?>
    $('.cont_segundo_nav').css('display', 'none');
 <?php } ?>
</script>

