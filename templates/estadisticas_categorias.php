<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include '../global/config.php';
include '../global/conexion.php';
include '../global/const.php';

session_start();

require ('../scripts/comprobaciones.php');

$filtro_desde = (isset($_REQUEST['input_desde']))?$_REQUEST['input_desde']:"";
$filtro_hasta = (isset($_REQUEST['input_hasta']))?$_REQUEST['input_hasta']:"";
$str_filtro_desde_hasta = ($filtro_desde != '' && $filtro_hasta != '')?" AND pe.FechaHoraCompra >= :FechaHoraDesde AND pe.FechaHoraCompra <= :FechaHoraHasta":"";

date_default_timezone_set('America/Tegucigalpa');
$fecha_actual = date("Y-m-d H:i:s");
$default_desde = date("Y-m-d",strtotime($fecha_actual." - 30 days")) . 'T08:00'; 
$default_hasta = date("Y-m-d",strtotime($fecha_actual)) . 'T23:00'; 

// consulta ventas por categoria
$sql_ventas_por_categoria = $pdo->prepare('SELECT ca.NombreCategoria, SUM(c.Cantidad) as UnidadesVendidas 
										FROM DetallePedidos c INNER JOIN Productos p
										ON c.FK_Producto = p.PK_Producto INNER JOIN Clientes cli
										ON c.FK_Cliente = cli.PK_Cliente INNER JOIN Usuarios u
										ON cli.FK_Usuario = u.PK_Usuario INNER JOIN Tiendas ti
										ON p.FK_Tienda = ti.PK_Tienda INNER JOIN Pedidos pe
										ON pe.PK_Pedido = c.FK_Pedido INNER JOIN Categorias ca
										ON ca.PK_Categoria = p.FK_Categoria
										WHERE ti.PK_Tienda = :PK_Tienda'. $str_filtro_desde_hasta .'
										GROUP BY ca.NombreCategoria');
$sql_ventas_por_categoria->bindParam(':PK_Tienda', $_SESSION['PK_Tienda']);
if($filtro_desde != '' && $filtro_hasta != ''){
    $sql_ventas_por_categoria->bindParam(':FechaHoraDesde', $filtro_desde); 
    $sql_ventas_por_categoria->bindParam(':FechaHoraHasta', $filtro_hasta); 
 }
$sql_ventas_por_categoria->execute();
$ventas_por_categoria = $sql_ventas_por_categoria->fetchAll(PDO::FETCH_ASSOC);
$labels_ventas_categorias = [];
for( $i = 0; $i<=count($ventas_por_categoria) -1 ; $i++){
	array_push($labels_ventas_categorias, $ventas_por_categoria[$i]['NombreCategoria']);
}
$datos_ventas_categorias = [];
for( $i = 0; $i<=count($ventas_por_categoria) -1 ; $i++){
	array_push($datos_ventas_categorias, $ventas_por_categoria[$i]['UnidadesVendidas']);
}
// FIN consulta ventas por categoria

// consulta ventas por país
$sql_ventas_por_pais = $pdo->prepare('SELECT pa.NombrePais, SUM(c.Cantidad) as UnidadesVendidas 
										FROM DetallePedidos c INNER JOIN Productos p
										ON c.FK_Producto = p.PK_Producto INNER JOIN Clientes cli
										ON c.FK_Cliente = cli.PK_Cliente INNER JOIN Usuarios u
										ON cli.FK_Usuario = u.PK_Usuario INNER JOIN Tiendas ti
										ON p.FK_Tienda = ti.PK_Tienda INNER JOIN Pedidos pe
										ON pe.PK_Pedido = c.FK_Pedido INNER JOIN Categorias ca
										ON ca.PK_Categoria = p.FK_Categoria INNER JOIN Ciudades ciu
										ON ciu.PK_Ciudad = cli.FK_Ciudad INNER JOIN Paises pa
										ON ciu.FK_Pais = pa.PK_Pais
										WHERE ti.PK_Tienda = :PK_Tienda'. $str_filtro_desde_hasta .'
										GROUP BY pa.NombrePais');
$sql_ventas_por_pais->bindParam(':PK_Tienda', $_SESSION['PK_Tienda']);
if($filtro_desde != '' && $filtro_hasta != ''){
    $sql_ventas_por_pais->bindParam(':FechaHoraDesde', $filtro_desde); 
    $sql_ventas_por_pais->bindParam(':FechaHoraHasta', $filtro_hasta); 
 }
$sql_ventas_por_pais->execute();
$ventas_por_pais = $sql_ventas_por_pais->fetchAll(PDO::FETCH_ASSOC);
$labels_ventas_pais = [];
for( $i = 0; $i<=count($ventas_por_pais) -1 ; $i++){
	array_push($labels_ventas_pais, $ventas_por_pais[$i]['NombrePais']);
}
$datos_ventas_pais = [];
for( $i = 0; $i<=count($ventas_por_pais) -1 ; $i++){
	array_push($datos_ventas_pais, $ventas_por_pais[$i]['UnidadesVendidas']);
}
// FIN consulta ventas por país
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
	<link href="<?php echo URL_SITIO ?>static/css/estadisticas.css" rel="stylesheet" type="text/css" media="all" />
	<link href="<?php echo URL_SITIO ?>static/css/chart.min.css" rel="stylesheet" type="text/css" media="all" />
	<script src="<?php echo URL_SITIO ?>static/js/chart.min.js" crossorigin="anonymous"></script>
	<script src="https://kit.fontawesome.com/b2dbb6a24d.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

	<?php include 'iconos.php' ?>
 
</head>
<body>

<?php include '../templates/header.php'; ?>
<div id="mensaje-success" class="alert alert-success text-center col-md-12" role="alert"></div>
<div style="padding:10px 10px 50px 10px;" class="text-center ">
    <div class="container row col-md-12 cont_menu">
	<a class="btn-volver btn" href="Estadisticas"> <i class="fa fa-arrow-left"></i> Menú estadísticas</a>
		<div class="col-md-12">
		<br>
		<h2 style="background-color: #F8F8F8;border-radius:20px;">Estadísticas categorías</h2>
		<br>
			<form action="Estadisticas-Categorias" method="GET">
				<div class="row col-md-8 offset-md-2">
					<div class="col-md-5 text-left">
						Desde
						<input id="party" type="datetime-local" class="form-control" name="input_desde" value="<?php echo ($filtro_desde != '')?$filtro_desde:$default_desde; ?>">
					</div>
					<div class="col-md-5 text-left">
						Hasta
						<input id="party" type="datetime-local" class="form-control" name="input_hasta" value="<?php echo ($filtro_hasta != '')?$filtro_hasta:$default_hasta; ?>">
					</div>
					<div class="col-md-2">
						<label for=""></label>
						<input class="btn btn-primary btn-filtrar" style="width:100%" type="submit" value="Filtrar" name="action">
					</div>
				
				</div>
			</form>
			<br>
			<br>
			<div class="cont-chart">
				<h4 style="color: gray;" class="col-md-12">Ventas por categoría</h2>
				<br>
				<canvas class=" chart" id="chartVentasPorCategoria" width="400" height="400"></canvas>
			</div>
			<br>
			<br>
			<div class="cont-chart">
				<h4 style="color: gray;" class="col-md-12">Ventas por país</h2>
				<br>
				<canvas class=" chart" id="chartVentasPorPais" width="400" height="400"></canvas>
			</div>
			<br>
			<br>
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

	var colors= ['rgba(47, 162, 228, 0.678)', 
				 'rgba(47, 207, 228, 0.678)', 
				 'rgba(47, 228, 47, 0.678)',
				 'rgba(228, 68, 47, 0.678)',
				 'rgba(228, 225, 47, 0.678)',
				 'rgba(128, 228, 47, 0.678)',
				 'rgba(198, 47, 228, 0.678)',
				 'rgba(228, 47, 137, 0.678)',
				 'rgba(131, 47, 228, 0.678)',
				 'rgba(47, 207, 228, 0.678)',
				 'rgba(47, 228, 183, 0.678)',
				 'rgba(134, 228, 47, 0.678)',
				 'rgba(47, 156, 228, 0.678)',
				 'fuchsia', 
				 'gray', 
				 'green', 
				 'lime', 
				 'maroon', 
				 'navy', 
				 'olive', 
				 'orange', 
				 'purple', 
				 'red', 
				 'silver', 
				 'teal', 
				 'white', 
				 'yellow', 
				 'aqua', 
				 'black'];
	
	// chart ventas por categoria
	var ctx = document.getElementById('chartVentasPorCategoria').getContext('2d');
	var myChart = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: <?php echo json_encode($labels_ventas_categorias) ?>,
			datasets: [{
				label: 'Número de ventas',
				data: <?php echo json_encode($datos_ventas_categorias) ?>,
				backgroundColor: colors,
				borderColor: 'white',
				borderWidth: 1
			}]
		},
		options: {
			responsive: false,
       		maintainAspectRatio: true,
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true,
						precision:0
					}
				}]
			}
		}
	});
	// FIN chart ventas por categoria

	// chart ventas por pais
	var cpais = document.getElementById('chartVentasPorPais').getContext('2d');
	var chartVentasPais = new Chart(cpais, {
		type: 'pie',
		data: {
			labels: <?php echo json_encode($labels_ventas_pais) ?>,
			datasets: [{
				label: 'Número de ventas',
				data: <?php echo json_encode($datos_ventas_pais) ?>,
			backgroundColor: colors,
				borderColor: 'white',
				borderWidth: 1
			}]
		},
		options: {
			responsive: false,
       		maintainAspectRatio: true
		}
	});

	function getRandomColor() {
		var letters = '0123456789ABCDEF';
		var color = '#';
		for (var i = 0; i < 6; i++) {
			color += letters[Math.floor(Math.random() * 16)];
		}
		return color;
	}
	// FIN chart ventas por pais

</script>