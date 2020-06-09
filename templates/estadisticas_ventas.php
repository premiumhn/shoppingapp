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

// consulta ventas al año
$sql_ventas_por_anio = $pdo->prepare('SELECT DATE_FORMAT(pe.FechaHoraCompra, "%Y") as "Anio", SUM(c.Cantidad) as UnidadesVendidas 
										FROM DetallePedidos c INNER JOIN Productos p
										ON c.FK_Producto = p.PK_Producto INNER JOIN Clientes cli
										ON c.FK_Cliente = cli.PK_Cliente INNER JOIN Usuarios u
										ON cli.FK_Usuario = u.PK_Usuario INNER JOIN Tiendas ti
										ON p.FK_Tienda = ti.PK_Tienda INNER JOIN Pedidos pe
										ON pe.PK_Pedido = c.FK_Pedido INNER JOIN Categorias ca
										ON ca.PK_Categoria = p.FK_Categoria
										WHERE ti.PK_Tienda = :PK_Tienda
										GROUP BY DATE_FORMAT(pe.FechaHoraCompra, "%Y")');
$sql_ventas_por_anio->bindParam(':PK_Tienda', $_SESSION['PK_Tienda']);
$sql_ventas_por_anio->execute();
$ventas_por_anio = $sql_ventas_por_anio->fetchAll(PDO::FETCH_ASSOC);
$labels_ventas_por_anio = [];
for( $i = 0; $i<=count($ventas_por_anio) -1 ; $i++){
	array_push($labels_ventas_por_anio, $ventas_por_anio[$i]['Anio']);
}
$datos_ventas_por_anio = [];
for( $i = 0; $i<=count($ventas_por_anio) -1 ; $i++){
	array_push($datos_ventas_por_anio, $ventas_por_anio[$i]['UnidadesVendidas']);
}
// FIN consulta ventas al año

// consulta ventas al mes
$sql_ventas_por_mes = $pdo->prepare('SELECT DATE_FORMAT(pe.FechaHoraCompra, "%M") as "Mes", SUM(c.Cantidad) as UnidadesVendidas 
										FROM DetallePedidos c INNER JOIN Productos p
										ON c.FK_Producto = p.PK_Producto INNER JOIN Clientes cli
										ON c.FK_Cliente = cli.PK_Cliente INNER JOIN Usuarios u
										ON cli.FK_Usuario = u.PK_Usuario INNER JOIN Tiendas ti
										ON p.FK_Tienda = ti.PK_Tienda INNER JOIN Pedidos pe
										ON pe.PK_Pedido = c.FK_Pedido INNER JOIN Categorias ca
										ON ca.PK_Categoria = p.FK_Categoria
										WHERE ti.PK_Tienda = :PK_Tienda AND DATE_FORMAT(pe.FechaHoraCompra, "%Y") =  DATE_FORMAT(NOW(), "%Y")
										GROUP BY DATE_FORMAT(pe.FechaHoraCompra, "%M")');
$sql_ventas_por_mes->bindParam(':PK_Tienda', $_SESSION['PK_Tienda']);
$sql_ventas_por_mes->execute();
$ventas_por_mes = $sql_ventas_por_mes->fetchAll(PDO::FETCH_ASSOC);
$labels_ventas_por_mes = [];
for( $i = 0; $i<=count($ventas_por_mes) -1 ; $i++){
	array_push($labels_ventas_por_mes, $ventas_por_mes[$i]['Mes']);
}
$datos_ventas_por_mes = [];
for( $i = 0; $i<=count($ventas_por_mes) -1 ; $i++){
	array_push($datos_ventas_por_mes, $ventas_por_mes[$i]['UnidadesVendidas']);
}
// FIN consulta ventas al mes

// consulta ventas al dia
$sql_ventas_por_dia = $pdo->prepare('SELECT DATE_FORMAT(pe.FechaHoraCompra, "%d-%m-%Y") as "dia", SUM(c.Cantidad) as UnidadesVendidas 
										FROM DetallePedidos c INNER JOIN Productos p
										ON c.FK_Producto = p.PK_Producto INNER JOIN Clientes cli
										ON c.FK_Cliente = cli.PK_Cliente INNER JOIN Usuarios u
										ON cli.FK_Usuario = u.PK_Usuario INNER JOIN Tiendas ti
										ON p.FK_Tienda = ti.PK_Tienda INNER JOIN Pedidos pe
										ON pe.PK_Pedido = c.FK_Pedido INNER JOIN Categorias ca
										ON ca.PK_Categoria = p.FK_Categoria
										WHERE ti.PK_Tienda = :PK_Tienda ' . $str_filtro_desde_hasta . '
										GROUP BY DATE_FORMAT(pe.FechaHoraCompra, "%d-%m-%Y")');
$sql_ventas_por_dia->bindParam(':PK_Tienda', $_SESSION['PK_Tienda']);
if($filtro_desde != '' && $filtro_hasta != ''){
    $sql_ventas_por_dia->bindParam(':FechaHoraDesde', $filtro_desde); 
    $sql_ventas_por_dia->bindParam(':FechaHoraHasta', $filtro_hasta); 
 }
$sql_ventas_por_dia->execute();
$ventas_por_dia = $sql_ventas_por_dia->fetchAll(PDO::FETCH_ASSOC);
$labels_ventas_por_dia = [];
for( $i = 0; $i<=count($ventas_por_dia) -1 ; $i++){
	array_push($labels_ventas_por_dia, $ventas_por_dia[$i]['dia']);
}
$datos_ventas_por_dia = [];
for( $i = 0; $i<=count($ventas_por_dia) -1 ; $i++){
	array_push($datos_ventas_por_dia, $ventas_por_dia[$i]['UnidadesVendidas']);
}
// FIN consulta ventas al dia
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
	<link href="<?php echo URL_SITIO ?>static/css/Chart.min.css" rel="stylesheet" type="text/css" media="all" />
	<script src="<?php echo URL_SITIO ?>static/js/Chart.min.js" crossorigin="anonymous"></script>
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
		<h2 style="background-color: #F8F8F8;">Estadísticas ventas en el tiempo</h2>
			<br>
			<br>
			<div class="cont-chart">
				<h4 style="color: gray;" class="col-md-12">Ventas por año</h2>
				<br>
				<canvas class=" chart" id="chartVentasPorAnio" width="400" height="400"></canvas>
			</div>
			<br>
			<br>
			<div class="cont-chart">
				<h4 style="color: gray;" class="col-md-12">Ventas por mes</h2>
				<br>
				<canvas class=" chart" id="chartVentasPorMes" width="400" height="400"></canvas>
			</div>
			<br>
			<br>
			<div class="cont-chart">
				<h4 style="color: gray;" class="col-md-12">Ventas por día</h2>
				<br>
				<form action="Estadisticas-Ventas" method="GET">
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
				<canvas class=" chart" id="chartVentasPorDia" width="400" height="400"></canvas>
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
	
	// chart ventas por año
	var ctx = document.getElementById('chartVentasPorAnio').getContext('2d');
	var myChart = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: <?php echo json_encode($labels_ventas_por_anio) ?>,
			datasets: [{
				label: 'Número de ventas',
				data: <?php echo json_encode($datos_ventas_por_anio) ?>,
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
	// FIN chart ventas por año

	// chart ventas por mes
	var cventas = document.getElementById('chartVentasPorMes').getContext('2d');
	var chartVentasPorMes = new Chart(cventas, {
		type: 'bar',
		data: {
			labels: <?php echo json_encode($labels_ventas_por_mes) ?>,
			datasets: [{
				label: 'Número de ventas',
				data: <?php echo json_encode($datos_ventas_por_mes) ?>,
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
	// FIN chart ventas por mes

	// chart ventas por dia

	var cventasDia = document.getElementById('chartVentasPorDia').getContext('2d');
	var chartVentasPorMes = new Chart(cventasDia, {
		type: 'bar',
		data: {
			labels: <?php echo json_encode($labels_ventas_por_dia) ?>,
			datasets: [{
				label: 'Número de ventas',
				data: <?php echo json_encode($datos_ventas_por_dia) ?>,
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
  
	// FIN chart ventas por dia



</script>