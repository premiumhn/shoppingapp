<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include '../global/config.php';
include '../global/conexion.php';
include '../global/const.php';

session_start();

require ('../scripts/comprobaciones.php');


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" id="hl-viewport" content="width=device-width, initial-scale=1, user-scalable=yes, minimum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Shoppingapp</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link href="<?php echo URL_SITIO ?>static/css/styles.css" rel="stylesheet" type="text/css" media="all" />
	<link href="<?php echo URL_SITIO ?>static/css/home_tienda.css" rel="stylesheet" type="text/css" media="all" />

	<script src="https://kit.fontawesome.com/b2dbb6a24d.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

	<?php include 'iconos.php' ?>
 
</head>
<body>

<?php include '../templates/header.php'; ?>
<div id="mensaje-success" class="alert alert-success text-center col-md-10 offset-md-1" role="alert"></div>
<div style="padding:10px 10px 50px 10px;" class="text-center ">
    <div class="container row col-md-10 offset-md-1 cont_menu">
	
		<div class="row ">
			<a href="Pedidos-Tienda" class="card col-md-3 ">
				<img style="width:100%;" src="<?php echo URL_SITIO ?>static/img/icon_pedidos.png" alt="">
				<span class="text_menu">Pedidos</span>
			</a>
			<a href="" class="card col-md-3 ">
				<img style="width:100%;" src="<?php echo URL_SITIO ?>static/img/icon_productos.png" alt="">
				<span class="text_menu">Productos</span>
			</a>
			<a href="Registro-Datos?menu=registro_regionesEnvio" class="card col-md-3 ">
				<img style="width:100%;" src="<?php echo URL_SITIO ?>static/img/icon_regiones_envio.png" alt="">
				<span class="text_menu">Regiones de envío</span>
			</a>
			<a href="" class="card col-md-3 ">
				<img style="width:100%;" src="<?php echo URL_SITIO ?>static/img/icon_estadisticas.png" alt="">
				<span class="text_menu">Estadísticas</span>
			</a>
		</div>

	
		
		
	</div>
</div>
<br>
<br>
<br>
<br>




<?php include '../templates/footer.php'; ?>

</body>
</html>

<script type="text/javascript">


	$('#mensaje-success').hide();
	<?php 
        $msj = (isset($_GET['msj']))?$_GET['msj']:"";
        if( $msj == 'perfilTiendaCompleto'){ 
    ?>
        $('#mensaje-success').html('<i class="fa fa-check"></i>Perfil de la tienda completado.');
        $('#mensaje-success').show();
    <?php } ?>
	
	function verDetalle(pk_producto){
		console.log('#product_'+pk_producto);
		$('#product_'+pk_producto).submit();
	}
</script>