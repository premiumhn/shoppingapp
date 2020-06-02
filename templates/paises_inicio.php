<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include '../global/config.php';
include '../global/conexion.php';
include '../global/const.php';

session_start();

unset($_SESSION['pais']);

require ('../scripts/comprobaciones.php');

// Consultar el tipo de usuario
$consulta_tipo_usuario = $pdo->prepare("SELECT * FROM Usuarios
										WHERE PK_Usuario = :PK_Usuario;");
$consulta_tipo_usuario->bindParam(':PK_Usuario', $_SESSION['login_user']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$consulta_tipo_usuario->execute();
$usuario = $consulta_tipo_usuario->fetchAll(PDO::FETCH_ASSOC);

if($usuario[0]['FK_TipoUsuario'] == 2){
	header('Location: Home-Tienda');
}else if($usuario[0]['FK_TipoUsuario'] == 3){
    header('Location: Admin');
}



$busqueda = (isset($_POST['busqueda'])) ? $_POST['busqueda'] : "";

$str_busqueda = '';
if($busqueda!=''){
    $str_busqueda = " WHERE NombrePais LIKE '%" . $busqueda . "%'";
}

	// $sentenc = $pdo->prepare("SELECT p.PK_Pais,p.NombrePais,p.Logo
	// 						FROM Paises p inner JOIN Ciudades c
	// 						ON p.PK_Pais=c.FK_Pais INNER JOIN Tiendas t
	// 						ON c.PK_Ciudad=t.FK_Ciudad
	// 						ORDER BY p.NombrePais asc");
	$sentenc = $pdo->prepare("SELECT p.PK_Pais,p.NombrePais,p.Logo
							FROM Paises p
							". $str_busqueda ."
							ORDER BY p.NombrePais asc");
	$sentenc->execute();
	$listaPaises = $sentenc->fetchAll(PDO::FETCH_ASSOC);

 
?> 
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Inicio</title>

	 <!-- Imports -->
	 <link href="<?php echo URL_SITIO ?>static/css/styles.css" rel="stylesheet" type="text/css" media="all" />
	 <link href="<?php echo URL_SITIO ?>static/css/paises_inicio.css" rel="stylesheet" type="text/css" media="all" />

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
	<?php include './header.php'; ?>
				<div class="col-md-12 text-center titulo">
					<h2>Países</h2>
				</div>	
				<div class="alert col-md-12 alert-danger text-center"></div>
				<br>
	<div class="cont_paises container height-full">
		<div class="row">
			
			<?php foreach ($listaPaises as $pais){?>
												
				<div  class="col-xl-3 col-sm-6 mb-4 ">
					<div id="new" class="card text-white bg-muted o-hidden h-100">
						<div class="card-body">
							<div class="card-body-icon">
								<img src="<?php echo URL_SITIO ?>uploads/img/paises/<?php echo $pais["Logo"] ?>" alt="<?php echo $pais["NombrePais"] ?>" style="border-radius: 7px;">
							</div>
							<div class="mr-5 text-center">
							
							</div>  
						</div>
						<a class="card-footer  clearfix small z-1" href="<?php echo URL_SITIO ?>Tiendas?idPais=<?php echo $pais["PK_Pais"] ?>" >
							<span class="float-left" style="font-size: 1rem;">
							<?php echo $tiendasde ?><strong class="text-uppercase"><?php echo $pais["NombrePais"] ?></strong>  
							</span>
							<span class="float-right">
								<i class="fas fa-angle-right"></i>
							</span>
						</a>
					</div>
				</div>
			<?php } ?> 
		</div>	
	</div>




	</div> <!-- row.// -->
	</div>
	<!-- FIN DIV Temporal -->

	<?php include 'footer.php'; ?>
</body>
</html>

<script type="text/javascript">
	$('.alert-danger').hide();

	<?php if(isset($_GET['msj'])){ 
			if($_GET['msj']=='nopais'){?>

				$('.alert-danger').html('Debes seleccionar primero un país').show();

	<?php }
	} ?>
</script>


