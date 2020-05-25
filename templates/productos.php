<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include '../global/config.php';
include '../global/conexion.php';
include '../global/const.php';

session_start();

require ('../scripts/comprobaciones.php');

    $id = (isset($_GET['Tienda'])) ? $_GET['Tienda'] : 0;
	$sentenc = $pdo->prepare("SELECT PK_Producto,Imagen,NombreProducto,PrecioUnitario
								FROM productos
								WHERE FK_Tienda=".$id."");
	$sentenc->execute();
	$productos = $sentenc->fetchAll(PDO::FETCH_ASSOC);

	// consultar nombre tienda
	$tiendas = $pdo->prepare("SELECT NombreTienda
								FROM Tiendas
								WHERE PK_Tienda=".$id."");
	$tiendas->execute();
	$tienda = $tiendas->fetchAll(PDO::FETCH_ASSOC);
?> 

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title><?php echo $tienda[0]['NombreTienda'] ?></title>

	 <!-- Imports -->
	<link href="<?php echo URL_SITIO ?>static/css/registro_datos.css" rel="stylesheet" type="text/css" media="all" />
    <link href="<?php echo URL_SITIO ?>static/css/pedidos.css" rel="stylesheet" type="text/css" media="all" />
    <link href="<?php echo URL_SITIO ?>static/css/styles.css" rel="stylesheet" type="text/css" media="all" />

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="https://kit.fontawesome.com/b2dbb6a24d.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="<?php echo URL_SITIO ?>static/js/jquery-3.5.0.min.js" ></script>
	<?php include 'iconos.php' ?>
	 <!-- Imports -->
	 
</head>
<body>
	<?php include 'header.php'; ?>

	<div style="padding:50px 20px 50px 20px;" class="text-center ">

		<div class="container row col-md-10 offset-md-1">
			<?php foreach($productos as $producto){ ?>
				<div class="col-md-4">
					<figure class="card card-product">
						<div class="img-wrap"><img src="<?php echo URL_SITIO.$producto['Imagen'] ?>"></div>
							<figcaption class="info-wrap">
								<h4 class="title"><?php echo $producto['NombreProducto'] ?></h4>
								<p class="desc">Descripción del producto</p>
								<div class="rating-wrap">
									<div class="label-rating">132 reviews</div>
									<div class="label-rating">154 orders </div>
								</div> <!-- rating-wrap.// -->
							</figcaption>
						<div class="bottom-wrap">
							<a href="/shoppingapp/Producto-Detalle/?producto=<?php echo $producto['PK_Producto'] ?>" class="btn btn-sm btn-primary float-right">Ordenar</a>	
							<div class="price-wrap h5">
								<span class="price-new">$ <?php echo $producto['PrecioUnitario'] ?></span> <del class="price-old">$1980</del>
							</div> <!-- price-wrap.// -->
						</div> <!-- bottom-wrap.// -->
					</figure>
				</div> <!-- col // -->
			<?php } ?>
		</div>
	</div>
	<?php include 'footer.php'; ?>
</body>
</html>



