<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include '../global/config.php';
include '../global/conexion.php';
include '../global/const.php';

session_start();

require './language/requirelanguage.php';

$actualizar_vistos = $pdo->prepare("UPDATE Pedidos
                                    SET VistoAdmin = 1
									WHERE VistoAdmin = 0");
$actualizar_vistos->execute();

$select_config = $pdo->prepare("SELECT * FROM Configuracion");
$select_config->execute();
$configuracion = $select_config->fetchAll(PDO::FETCH_ASSOC);

if (isset($_SESSION['login_user'])){ //Comprobar si ha iniciado sesión
    $user = $_SESSION['login_user'];
}else{
    header('Location: Login');
    // header('Location: completar_perfil_tienda.php');
}

// Búsqueda
$busqueda = (isset($_REQUEST['input_busqueda']))?$_REQUEST['input_busqueda']:"";

// filtros
$filtro_estado = (isset($_REQUEST['input_estado']))?$_REQUEST['input_estado']:3;
$filtro_desde = (isset($_REQUEST['input_desde']))?$_REQUEST['input_desde']:"";
$filtro_hasta = (isset($_REQUEST['input_hasta']))?$_REQUEST['input_hasta']:"";

if(isset($_REQUEST['input_estado']) AND $filtro_estado != 3){
    $str_filtro_tipo_estado = " AND pe.Estado = :Estado";
}else{
    $str_filtro_tipo_estado = "";
}




date_default_timezone_set('America/Tegucigalpa');
$fecha_actual = date("Y-m-d H:i:s");
$default_desde = date("Y-m-d",strtotime($fecha_actual." - 15 days")) . 'T08:00'; 
$default_hasta = date("Y-m-d",strtotime($fecha_actual)) . 'T08:00';
if($filtro_desde == ''){
    $filtro_desde = date("Y-m-d",strtotime($fecha_actual." - 30 days")) . 'T08:00';
}
if($filtro_hasta == ''){
    $filtro_hasta = date("Y-m-d",strtotime($fecha_actual)) . 'T23:00';
}

$str_filtro_desde_hasta = ($filtro_desde != '' && $filtro_hasta != '')?" AND pe.FechaHoraCompra BETWEEN :FechaHoraDesde AND :FechaHoraHasta":"";
$str_busqueda = ($busqueda != '')?" AND (p.NombreProducto LIKE '%" . $busqueda . "%'
                                    OR cli.PrimerNombre LIKE '%" . $busqueda . "%'
                                    OR cli.PrimerApellido LIKE '%" . $busqueda . "%'
                                    OR pe.NumeroPedido LIKE '%" . $busqueda . "%'
                                    OR c.CodigoDetallePedido LIKE '%" . $busqueda . "%') ":"";
$str_busqueda_pedido = ($busqueda != '')?" AND pe.NumeroPedido LIKE '%" . $busqueda . "%'":"";


$pagina = false;
$items_por_pagina = 5;
 
//examino la pagina a mostrar y el inicio del registro a mostrar
if (isset($_GET["pagina"])) {
    $pagina = $_GET["pagina"];
}

if (!$pagina) {
    $inicio = 0;
    $pagina = 1;
} else {
    $inicio = ($pagina - 1) * $items_por_pagina;
}


// Consulta para obtener detalles de pedidos
$select_detalle_pedidos = $pdo->prepare("SELECT p.NombreProducto, 
                                        c.Cantidad, 
                                        p.PrecioUnitario, 
                                        p.Imagen, 

                                        (SELECT Color From Colores WHERE PK_Color = c.FK_Color) as 'Color',
                                        (SELECT Talla From Tallas WHERE PK_Talla = c.FK_Talla) as 'Talla',

                                        p.Descuento, 
                                        (p.PrecioUnitario * c.Cantidad) as 'Subtotal', 
                                        (CAST(p.Descuento as DECIMAL(20,0)) ) as DescuentoDecimal, 
                                        (p.PrecioUnitario * c.Cantidad) - ((p.PrecioUnitario * c.Cantidad) * ((CAST(p.Descuento as DECIMAL(20,0)) )/100)) as 'Total',
                                        ti.NombreTienda,
                                        p.PrecioEnvio,
                                        c.FK_TipoPedido,
                                        c.PK_DetallePedido,
                                        c.FK_Pedido,
                                        c.Estado,
                                        cli.PrimerNombre,
                                        cli.PrimerApellido,
                                        pe.NumeroPedido,
                                        c.CodigoDetallePedido,
                                        c.Precio,
                                        c.Descuento,
                                        (SELECT NombresDestinatario FROM Destinatarios WHERE PK_Destinatario = c.FK_Destinatario) as 'NombresDestinatario',
                                        (SELECT ApellidosDestinatario FROM Destinatarios WHERE PK_Destinatario = c.FK_Destinatario) as 'ApellidosDestinatario',
                                        DATE_FORMAT(pe.FechaHoraCompra, '%d %m %Y ') as 'FechaCompra',
                                        DATE_FORMAT(pe.FechaHoraCompra, '%H:%i ') as 'HoraCompra',
                                        ti.PK_Tienda,
                                        (SELECT FK_Ciudad FROM Destinatarios WHERE PK_Destinatario = c.FK_Destinatario) as 'FK_Ciudad'


                                        FROM DetallePedidos c INNER JOIN Productos p
                                        ON c.FK_Producto = p.PK_Producto INNER JOIN Clientes cli
                                        ON c.FK_Cliente = cli.PK_Cliente INNER JOIN Usuarios u
                                        ON cli.FK_Usuario = u.PK_Usuario INNER JOIN Tiendas ti
                                        ON p.FK_Tienda = ti.PK_Tienda INNER JOIN Pedidos pe
                                        ON pe.PK_Pedido = c.FK_Pedido 
                                        " . $str_filtro_desde_hasta . $str_busqueda);
 

 if($filtro_desde != '' && $filtro_hasta != ''){
    $select_detalle_pedidos->bindParam(':FechaHoraDesde', $filtro_desde); 
    $select_detalle_pedidos->bindParam(':FechaHoraHasta', $filtro_hasta); 
 }



 $select_detalle_pedidos->execute();
 $lista_detalle_pedidos = $select_detalle_pedidos->fetchAll(PDO::FETCH_ASSOC);


// consulta para los total de pedidos
$select_pedidos_total = $pdo->prepare("SELECT pe.PK_Pedido, pe.NumeroPedido, pe.Comision, pe.Estado, DATE_FORMAT(pe.FechaHoraCompra ,'%d-%m-%Y') as 'FechaHoraCompra' FROM Pedidos pe INNER JOIN Tiendas t
                                ON t.PK_Tienda = pe.FK_Tienda
                                " . $str_filtro_desde_hasta . $str_busqueda_pedido . $str_filtro_tipo_estado);
 
 
 //if($filtro_desde != '' && $filtro_hasta != ''){
    $select_pedidos_total->bindParam(':FechaHoraDesde', $filtro_desde); 
    $select_pedidos_total->bindParam(':FechaHoraHasta', $filtro_hasta); 
 //}

 if($str_filtro_tipo_estado != ""){
    $select_pedidos_total->bindParam(':Estado', $filtro_estado); 
 }

 $select_pedidos_total->execute();
 $lista_pedidos_total = $select_pedidos_total->fetchAll(PDO::FETCH_ASSOC);

 //calculo el total de paginas
 $total_pages = ceil(count($lista_pedidos_total) / $items_por_pagina);

 function obtenerPrecioEnvio($FK_Tienda, $FK_Ciudad){
    global $pdo;
   $sql_precio = $pdo->prepare("SELECT * FROM RegionesEnvio WHERE FK_Tienda = :FK_Tienda and FK_Ciudad = :FK_Ciudad");
   $sql_precio->bindParam(':FK_Tienda', $FK_Tienda);
   $sql_precio->bindParam(':FK_Ciudad', $FK_Ciudad);
   $sql_precio->execute();
   $precio = $sql_precio->fetchAll(PDO::FETCH_ASSOC); 
   
   return $precio[0]['PrecioEnvio'];
}
// consulta para los  pedidos
$select_pedidos = $pdo->prepare("SELECT pe.PK_Pedido, pe.NumeroPedido, pe.Comision, pe.Estado, DATE_FORMAT(pe.FechaHoraCompra ,'%d-%m-%Y') as 'FechaHoraCompra' FROM Pedidos pe INNER JOIN Tiendas t
                                ON t.PK_Tienda = pe.FK_Tienda
                                " . $str_filtro_desde_hasta . $str_busqueda_pedido . $str_filtro_tipo_estado ."
                                ORDER BY pe.PK_Pedido DESC LIMIT ". $inicio .", " . $items_por_pagina . "");
 
 
 //if($filtro_desde != '' && $filtro_hasta != ''){
    $select_pedidos->bindParam(':FechaHoraDesde', $filtro_desde); 
    $select_pedidos->bindParam(':FechaHoraHasta', $filtro_hasta); 
 //}
 if($str_filtro_tipo_estado != ""){
    $select_pedidos->bindParam(':Estado', $filtro_estado); 
 }
 $select_pedidos->execute();
 $pedidos = $select_pedidos->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title id="titulo_pagina">Pedidos</title>

   <!-- Imports -->
   
    <link href="<?php echo URL_SITIO ?>static/css/pedidos.css" rel="stylesheet" type="text/css" media="all" /> 
    <link href="<?php echo URL_SITIO ?>static/css/pedidos_admin.css" rel="stylesheet" type="text/css" media="all" />

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="https://kit.fontawesome.com/b2dbb6a24d.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="<?php echo URL_SITIO ?>static/js/jquery-3.5.0.min.js" ></script>
 
    <?php include 'iconos.php' ?>

</head>
<body>
<?php require ('header_admin.php') ?>
 <div class="row" style="width:100%;margin:0px;">

 <!-- <div class="col-md-2">
            <div class="card card-left">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <form action="Pedidos-Tienda" method="get">
                            <button type="submit" class="col-md-12 btn btn-primary">Pendientes</button>
                        </form>
                    </li>
                    <li class="list-group-item">
                        <form action="Pedidos-Completados-Tienda" method="get">
                            <button type="submit" class="col-md-12 btn btn-primary">Completados</button>
                        </form>
                    </li>
                </ul>
            </div>
            <br>
</div> -->
<div class="cont-filtros col-md-12">
    <form action="Pedidos-Admin" method="GET">
        <div class="row col-md-12">
            
                <div class="col-md-10">
                    <input class="form-control" style="width:100%" type="text" placeholder="Búsqueda por código de pedido" value="<?php echo $busqueda ?>" name="input_busqueda">
                </div>
                <div class="col-md-2">
                    <input class="btn btn-primary btn-flat" style="width:100%" type="submit" value="Buscar" name="action">
                </div>
          
        </div>
    </form>
    <br>
        <form action="Pedidos-Admin" method="get">
        <div class="row col-md-12">
                <div class="col-md-2">
                    Estado
                    <select class="form-control" id="inputEstado" name="input_estado">
                        <option <?php echo ($filtro_estado == 3)?"selected":"";?>  value="3">Todos</option>    
                        <option <?php echo ($filtro_estado == 1)?"selected":""; ?> value="1">Entregado</option>
                        <option <?php echo ($filtro_estado == 0)?"selected":""; ?>  value="0">No entregado</option>
                    </select>                
                </div>
                <div class="col-md-4">
                    Desde
                    <input id="party" type="datetime-local" class="form-control" name="input_desde" value="<?php echo ($filtro_desde != '')?$filtro_desde:$default_desde; ?>">
                </div>
                <div class="col-md-4">
                    Hasta
                    <input id="party" type="datetime-local" class="form-control" name="input_hasta" value="<?php echo ($filtro_hasta != '')?$filtro_hasta:$default_hasta; ?>">
                </div>
                <div class="col-md-2">
                    <input class="btn btn-primary btn-filtrar" style="width:100%" type="submit" value="Filtrar" name="action">
                </div>
          
        </div>
        </form>
    </div>
    <div style="height:100%;margin-bottom:60px;" class="col-md-10 offset-md-1 bordered">
    
    <div id="mensaje-success" class="alert alert-success" role="alert"></div>
    <div id="mensaje-error" class="alert alert-danger" role="alert"></div>
        <div class="text-center" ><?php echo (count($pedidos) == 0) ? 'No hay productos pedidos.': ""; ?> </div>


<?php foreach($pedidos as $pedido){ ?>
    
    <div class="row  card-detalle">
            
            <div class="row col-md-12 head-detalle">
                <div class="col-md-6 text-left text-li text-bold" for=""><span class="text-li">Pedido:</span> <?php echo $pedido['NumeroPedido']?></div>
                <div class="col-md-6 text-right text-li" for=""><span class="text-li">Fecha de pedido:</span> <?php echo $pedido['FechaHoraCompra'] ?></div>
                <div class="col-md-6 text-left text-li" for="">
                    <span   class="text-li">Estado:</span>
                    <?php if($pedido['Estado'] == 1){ ?>
                        <span class="lbl_verde" id="lbl_entregado_<?php echo $pedido['PK_Pedido'] ?>" >Entregado</span>
                    <?php }else{ ?>
                        <span class="lbl_rojo" id="lbl_noEntregado_<?php echo $pedido['PK_Pedido'] ?>" >No entregado</span>
                        <span class="lbl_verde lbl_aux" id="lbl_entregado_aux_<?php echo $pedido['PK_Pedido'] ?>" >Entregado</span>
                    <?php } ?>    

                </div>
            </div>
            <br>
            <div class="row col-md-12 cont-detalles">
     
                <?php
                    $total_todos = 0; 
                    $total_envio = 0; 
                    $subtotal_todos = 0;
                    $descuentos_todos = 0;
                ?>
                <?php foreach($lista_detalle_pedidos as $detalle){ ?>
                    
                    <?php if($detalle['FK_Pedido'] == $pedido['PK_Pedido']){ ?>

                    <?php 
                         $subtotal_todos+= $detalle['Subtotal'] ;
                         
                            // calculo total
                         if($configuracion[0]['CobrosPorEnvio'] == 1){ 
                             $total_todos+= ($detalle['Subtotal']) - ($detalle['Descuento'] * $detalle['Cantidad']) + (($detalle['FK_TipoPedido']==2)?$detalle['PrecioEnvio'] + obtenerPrecioEnvio($detalle['PK_Tienda'], $detalle['FK_Ciudad']):0) ;
                         }else{
                             $total_todos+= ($detalle['Subtotal']) - ($detalle['Descuento'] * $detalle['Cantidad'])  ;
                         }
                        // calculo total envios
                        $total_envio+= ($detalle['FK_TipoPedido']==2)?$detalle['PrecioEnvio'] + obtenerPrecioEnvio($detalle['PK_Tienda'], $detalle['FK_Ciudad']):0;
                             
                        if($detalle['Descuento']!=0){ 
                            $descuentos_todos+= ($detalle['Descuento'] * $detalle['Cantidad']) ;
                        }
                    ?>
                    <div onclick="verDetalle(<?php echo $detalle['PK_DetallePedido'] ?>)" data-toggle="modal" data-target=".modal-mostrar-detalle" class="block-detalle row col-md-12">
                            <div class="  col-md-8 text-left text-li" for="">
                                <div class="col-md-12 col-sin-pad-izquierdo">
                                    <?php echo $detalle['Cantidad'] ?> x <?php echo $detalle['NombreProducto'] ?> 
                                    <?php if($detalle['FK_TipoPedido'] == 2){ ?>
                                        <span class="lbl-tipo-pedido"><i class="fa fa-truck"></i> a domicilio
                                        </span>
                                    <?php }else{ ?>
                                        <span class="lbl-tipo-pedido"><i class="fa fa-home"></i> en tienda</span>
                                    <?php } ?> 
                                </div>
                                <div class="col-sin-pad-izquierdo col-md-12 text-left text-li small" for=""> 
                                    <span class="text-bold">tienda: </span><?php echo $detalle['NombreTienda'] ?>
                                    <span class="text-bold">comprador: </span><?php echo $detalle['PrimerNombre'] . ' ' . $detalle['PrimerApellido'] ?>
                                </div>
                            </div>
                            <div class="col-sin-pad-derecho  row col-md-4 text-right text-bold" for="">
                                <div class="col-sin-pad-derecho col-md-12 text-right"> 
                                    $ <?php echo round(($detalle['Cantidad'] * $detalle['Precio']), 2) ?> <span class="text-li small" >USD</span>
                                </div>
                                <?php if($detalle['DescuentoDecimal']!=0){ ?>
                                    <div class="col-sin-pad-derecho col-md-12 text-right" for="">
                                    <span class="text-li small" > </span> - $ <?php echo round($detalle['Descuento'] * $detalle['Cantidad'], 2) ?> <span class="text-li small" >USD</span>
                                    </div>
                                <?php } ?>
                            </div>
                    </div>
                    <br>
                    <?php } ?>
                <?php } ?> 
            </div>
            <br>
            <div class="row col-md-12 totales">
                    <div class="row col-md-12">
                            <div class="col-md-6 text-left text-li" for="">Subtotal</div>
                            <div class="col-md-6 text-right text-bold" for="">$ <?php echo round($subtotal_todos, 2) ?> <span class="text-li small" >USD</span></div>
                    </div>
                    <div class="row col-md-12">
                            <div class="col-md-6 text-left text-li" for="">Descuentos</div>
                            <div class="col-md-6 text-right text-bold" for="">- $ <?php echo round($descuentos_todos, 2) ?> <span class="text-li small" >USD</span></div>
                    </div>
                    <?php if($configuracion[0]['CobrosPorEnvio'] == 1){  ?>
                    <div class="row col-md-12 border-b">
                            <div class="col-md-6 text-left text-li" for="">Envío</div>
                            <div class="col-md-6 text-right text-bold" for="">$ <?php echo round($total_envio, 2) ?> <span class="text-li small" >USD</span></div>
                    </div>
                    <?php } ?>
                    <div class="row col-md-12">
                            <div class="col-md-6 text-left text-li" for="">Comisión</div>
                            <div class="col-md-6 text-right text-bold" for=""> $ <?php echo round($pedido['Comision'], 2) ?> <span class="text-li small" >USD</span></div>
                    </div>
                    <hr>
                    <div class="row col-md-12 text-bold">
                            <div class="col-md-6 text-left" for="">Total</div>
                            <div class="col-md-6 text-right " for="">$ <?php echo round($total_todos + $pedido['Comision'], 2) ?> <span class="text-li small" >USD</span></div>
                    </div>
                    <br>
                    <?php if($pedido['Estado'] == 0){ ?>
                        <div class="col-md-12 text-right">
                            <br>
                                <button id="btn_pedido_<?php echo $pedido['PK_Pedido'] ?>" data-toggle="modal" data-target=".modal-confirmar" onClick="confirmar(<?php echo $pedido['PK_Pedido'] ?>)" type="button" class="btn btn-finalizado" > Marcar como entregado </button>
                            <br>
                        </div>
                    <?php } ?>

                </div>
               
            
        </div>
        <br>
<?php } ?>   


        <!--    
              
                        <?php foreach($lista_pedidos as $detalle_pedido){ ?>
                            <?php if($detalle_pedido['Estado'] == 0){ ?>
                            <div class="card">
                            <div class="card-body">
                                
                            <div class="row">
                            <div class="col-md-4 square container temp-border"> <img class="crop col-md-12" src="<?php echo URL_SITIO.$detalle_pedido['Imagen'] ?>" alt=""> </div>
                            <div class="col-md-8 temp-border">
                                <div class="detail_up col-md-12 temp-border">
                                    <h4 class=""><?php echo $detalle_pedido['NombreProducto'] ?>  
                                        <?php if($detalle_pedido['FK_TipoPedido']==2){ ?>
                                            <img src="<?php echo URL_SITIO ?>static/img/icon_adomicilio.png" class="info_tipo" alt=""><span class="info_tipo_letras">Adomicilio</span>
                                        <?php }else{ ?>
                                            <img src="<?php echo URL_SITIO ?>static/img/icon_entienda.png" class="info_tipo" alt=""><span class="info_tipo_letras">En tienda</span>
                                        <?php } ?>
                                    </h4>
                                    <div class=" text-left row">
                                        <label class="  col-md-12" for="">Comprador &nbsp&nbsp&nbsp&nbsp: <a href=""><?php echo $detalle_pedido['PrimerNombre'] . ' ' . $detalle_pedido['PrimerApellido'] ?></a> </label>
                                    </div>
                                    <div class="text-left row">
                                        <label class="  col-md-12" for="">Cantidad &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp: <?php echo $detalle_pedido['Cantidad'] ?></label>
                                    </div>
                                    <div class="text-left row">
                                        <label class="  col-md-12" for="">Precio &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp: $ <?php echo $detalle_pedido['PrecioUnitario'] ?></label>
                                    </div>
                                        <div class="text-left row">
                                            <label class="subtotal col-md-12" for="">Subtotal  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp: $ <?php echo $detalle_pedido['Subtotal'] ?> </label>
                                        </div>
                                        <div class="text-left row">
                                            <label class="descuento  col-md-12" for="">Descuento  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp:<?php echo (isset($detalle_pedido['DescuentoDecimal']))?"-&nbsp$ ".round((($detalle_pedido['Subtotal'])/$detalle_pedido['DescuentoDecimal']), 2):'&nbsp&nbsp N/A';  ?></label>
                                        </div>
                                        <?php if($detalle_pedido['FK_TipoPedido']==2){ ?>
                                            <div class="text-left row">
                                                <label class=" subtotal col-md-12" for="">Precio envío &nbsp&nbsp&nbsp: $ <?php echo (($detalle_pedido['FK_TipoPedido']==2)?$detalle_pedido['PrecioEnvio'] + obtenerPrecioEnvio($detalle_pedido['PK_Tienda'], $detalle_pedido['FK_Ciudad']):'N/A') ?></label>
                                            </div>
                                        <?php } ?>
                                        <div class=" text-left row">
                                            <label class="total text-bold col-md-12" for="">Total  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp: $ <?php echo round((($detalle_pedido['Subtotal']) - ((isset($detalle_pedido['DescuentoDecimal']))?(($detalle_pedido['Subtotal'])/$detalle_pedido['DescuentoDecimal']):0) + (($detalle_pedido['FK_TipoPedido']==2)?$detalle_pedido['PrecioEnvio'] + obtenerPrecioEnvio($detalle_pedido['PK_Tienda'], $detalle_pedido['FK_Ciudad']):0)), 2)  ?> </label>
                                        </div>
                                        <hr>
                                        <div class="text-left row">
                                            <label class="descuento  col-md-12" for="">Fecha de compra  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp:&nbsp<?php echo $detalle_pedido['FechaCompra'] . 'a las ' . $detalle_pedido['HoraCompra']; ?></label>
                                        </div>
                                        <div class="text-left row">
                                            <label class="descuento  col-md-12" for="">Estado de pedido  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp:
                                                <?php 
                                                    if($detalle_pedido['Estado'] == 0){
                                                        if($detalle_pedido['FK_TipoPedido']==1){
                                                            echo 'No reclamado (aún en tienda)';
                                                        }elseif($detalle_pedido['FK_TipoPedido']==2){
                                                            echo 'Enviado, no recibido';
                                                        }
                                                    }
                                                ?>
                                            </label>
                                        </div>
                                        <?php if($detalle_pedido['FK_TipoPedido']==2){ ?>
                                            <div class="text-left row">
                                                <label class=" subtotal col-md-12" for="">Enviado a <span class="text-gray"><?php echo $detalle_pedido['NombresDestinatario'] . ' ' . $detalle_pedido['ApellidosDestinatario'] ?></span></label>
                                            </div>
                                        <?php } ?>
                                        <br>
                                        <div class="text-left row">
                                                <label style="font-size:13px;color:gray;" class=" subtotal col-md-12" for="">ID de pago: <span class="text-bold"><?php echo $detalle_pedido['CodigoDetallePedido'] ?></span></label>
                                        </div>
                                        <div class="text-left row">
                                                <label style="font-size:13px;color:gray;" class=" subtotal col-md-12" for="">Pago PayPal: <span class="text-bold"><?php echo $detalle_pedido['NumeroPedido'] ?></span></label>
                                        </div>
                                        

                                </div>
                               
                               
                            </div>
                            
                            </div>
                        </div>
                        <?php //if($detalle_pedido['FK_TipoPedido']==1){ ?>
                            <h4 class="col-md-2 offset-md-10">
                                <button data-toggle="modal" data-target=".modal-confirmar" onClick="confirmar(<?php echo $detalle_pedido['PK_DetallePedido'] ?>)" type="button" class="btn btn_completado" > Finalizado </button>
                            </h4> 
                        <?php //} ?>
                        <br> 
                    </div>
                        <br>
                        <?php } ?>
                    <?php }?> -->
                    
                    










<?php 
    echo '<nav class="col-md-12">';
    echo '<ul class="pagination" >';

    if ($total_pages > 1) {
        if ($pagina != 1) {
            echo '<li class="page-item"><a class="page-link" href="Pedidos-Admin?pagina='.($pagina-1).'"><span aria-hidden="true">&laquo;</span></a></li>';
        }

        for ($i=1;$i<=$total_pages;$i++) {
            if ($pagina == $i) {
                echo '<li class="page-item active"><a class="page-link" href="#">'.$pagina.'</a></li>';
            } else {
                echo '<li class="page-item"><a class="page-link" href="Pedidos-Admin?pagina='.$i.'">'.$i.'</a></li>';
            }
        }

        if ($pagina != $total_pages) {
            echo '<li class="page-item"><a class="page-link" href="Pedidos-Admin?pagina='.($pagina+1).'"><span aria-hidden="true">&raquo;</span></a></li>';
        }
    }
    echo '</ul>';
    echo '</nav>';
?>
            
    </div>

</div>    

<!-- modal para confirmar -->
<div class="modal fade modal-confirmar" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class=" md-content col-md-12">
                <br>
                <label class="text-center col-md-12" for=""><strong>Confirmar</strong></label>
          
            <div style="height:100%;margin-bottom:60px;" class="col-md-12 offset-md-0 bordered">
                <div class="">
                <div class="card-body">
                    <form id="form-confirmar" action="<?php echo URL_SITIO ?>scripts/pedidos_tienda.php" method="post" enctype="multipart/form-data">
                      
                        <label for="">¿Desea confirmar la entrega del pedido?</label>

                        <br>
                        <br>
                        <br>
                        <br>

                        <input type="hidden" id="PK_Pedido" name="pk_pedido">
                        <input type="hidden" value="confirmar_pedido" name="action">

                        <div class="text-center col-md-12">
                        <button id="btnAceptar" type="submit" data-dismiss="modal" class=" btn-modal btn btn-primary col-md-5">Aceptar</button>&nbsp&nbsp&nbsp
                        <button id="btnCancelar" type="" data-dismiss="modal" class=" btn-modal btn btn-secondary col-md-5">Cancelar</button>
                        </div>
                        
                    </form>

                </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>



<!-- modal para mostrar detalle -->
<div class="modal fade modal-mostrar-detalle col-md-12" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class=" md-content col-md-12">
                <br>
                <div class="">
                            <div class="card-body">
                                
                            <div class="row">
                            <div class="col-md-4 square container temp-border"> <img id="modal_imagen" class="crop col-md-12" src="" alt=""> </div>
                            <div class="col-md-8 temp-border">
                                <div class="detail_up col-md-12 temp-border">
                                    
                                    <h4 class="">
                                        <span id="modal_nombreProducto"></span>  
                                        <br>
                                        <img id="modal_img_adomicilio" src="" class="info_tipo" alt=""><span id="modal_lbl_tipoPedido" class="info_tipo_letras"></span>
                                    </h4>
                                    <div class=" text-left row">
                                        <label class="  col-md-12" for="">Tienda &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp: <a href=""><span id="modal_nombreTienda"></span></a> </label>
                                    </div>
                                    <div class=" text-left row">
                                        <label class="  col-md-12" for="">Comprador &nbsp&nbsp&nbsp&nbsp: <a href=""><span id="modal_nombreComprador"></span></a> </label>
                                    </div>
                                    <div class="text-left row">
                                        <label class="  col-md-12" for="">Cantidad &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp: <span id="modal_cantidad"></span></label>
                                    </div>
                                    <div class="text-left row">
                                        <label class="  col-md-12" for="">Precio &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp: $ <span id="modal_precioUnitario"></span></label>
                                    </div>
                                        <div class="text-left row">
                                            <label class="subtotal col-md-12" for="">Subtotal  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp: $ <span id="modal_subtotal"></span> </label>
                                        </div>
                                        <div class="text-left row">
                                            <label class="descuento  col-md-12" for="">Descuento  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp: <span id="modal_descuento"></span></label>
                                        </div>
                                            <div class="text-left row">
                                                <label class=" subtotal col-md-12" for="">Precio envío &nbsp&nbsp&nbsp: $ <span id="modal_precioEnvio"></span></label>
                                            </div>
                                        <div class=" text-left row">
                                            <label class="total text-bold col-md-12" for="">Total  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp: $ <span id="modal_total"></span> </label>
                                        </div>
                                        <hr>
                                        <div class="text-left row">
                                            <label class="descuento  col-md-12" for="">Fecha de compra  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp:&nbsp <span id="modal_fechaCompra"></span></label>
                                       </div>
                                        <!--  <div class="text-left row">
                                            <label class="descuento  col-md-12" for="">Estado de pedido  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp:
                                                <span id="modal_estadoDetallePedido"></span>
                                            </label>
                                        </div> -->
                                        <div class="text-left row">
                                            <span id="modal_destinatario"></span>                                        
                                        </div>
                                        <br>
                                        <div class="text-left row">
                                                <label style="font-size:13px;color:gray;" class=" subtotal col-md-12" for="">ID de pago: <span id="modal_idPago"></span></span></label>
                                        </div>
                                        <div class="text-left row">
                                                <label style="font-size:13px;color:gray;" class=" subtotal col-md-12" for="">Pago PayPal: <span id="modal_numeroPedido"></span></span></label>
                                        </div>
                                </div>
                            </div>
                            </div>
                        </div>
                            <!-- <h4 class="col-md-2 offset-md-10">
                                <button data-toggle="modal" data-target=".modal-confirmar" onClick="confirmar(<?php echo $detalle_pedido['PK_DetallePedido'] ?>)" type="button" class="btn btn_completado" > Recibido </button>
                            </h4>  -->
                        <br> 
                    </div>
            <br>
            <button style="border-radius: 7px!important;" id="btnCancelar" type="" data-dismiss="modal" class=" btn-modal btn btn-secondary col-md-4 offset-md-4">Cerrar</button>
            <br><br>
        </div>
        </div>
    </div>
</div>




<script type="text/javascript">

    $('.h2-name').html('Pedidos');
    $('#titulo_pagina').html('Shoppingapp | Pedidos');

    $("#mensaje_alert").css("visibility", "hidden");

    $('#mensaje-success').hide();
    $('#mensaje-error').hide();
    $('.lbl_aux').css('display', 'none');

    <?php 
        $msj = (isset($_GET['msj']))?$_GET['msj']:"";
        if( $msj == 'pedido_confirmado'){ 
    ?>
        $('#mensaje-success').html('<i class="fa fa-check"></i>Recepción confirmada.');
        $('#mensaje-success').show();
    <?php } ?>


    function mostrarMensaje(msj){
        $("#mensaje_alert").css("visibility", "visible");
        $("#mensaje_alert").html(msj);
    }

    function ocultarMensaje(){
        $('.form-control').keypress(function(){
            $("#mensaje_alert").css("visibility", "hidden");
            $("#mensaje_alert").html("");
        });
    }

    function confirmar(pk_pedido){
         $('#PK_Pedido').val(pk_pedido);
     }

    $('#btnAceptar').click(function(e){
        e.preventDefault();
        
        var pedido_entregado;
            $.ajax({
                type:"POST",
                async: false,
                url:"<?php echo URL_SITIO ?>scripts/pedidos_tienda.php",
                data: {"action" : "confirmar_pedido", 
                        "pk_pedido" : $('#PK_Pedido').val()},
                success:function(r){
                    pedido_entregado = r;
                    console.log(r);
                }
        });

        if(pedido_entregado == 1){
            $('#btn_pedido_' + $('#PK_Pedido').val()).hide();
            $('#lbl_noEntregado_' + $('#PK_Pedido').val()).hide();
            $('#lbl_entregado_aux_' + $('#PK_Pedido').val()).css('display', '');
        }

    })

    function verDetalle(pk_detalle_pedido){
        var detalle_pedido;
            $.ajax({
                type:"POST",
                async: false,
                url:"<?php echo URL_SITIO ?>scripts/datos_ajax.php",
                data: {"request" : "obtenerDetallePedido", 
                        "PK_DetallePedido" : pk_detalle_pedido},
                success:function(r){
                    detalle_pedido = JSON.parse(r);
                    console.log(detalle_pedido);
                }  
             });

             var config;
             $.ajax({
                type:"POST",
                async: false,
                url:"<?php echo URL_SITIO ?>scripts/datos_ajax.php",
                data: {"request" : "obtenerConfiguracion"},
                success:function(r){
                    config = JSON.parse(r);
                    console.log(config);
                }  
             });
        var subtotal = detalle_pedido[0].Precio * detalle_pedido[0].Cantidad;     
        if(config[0].CobrosPorEnvio === 1){
            var precio_envio = detalle_pedido[0].PrecioEnvio;
            var total = subtotal - detalle_pedido[0].Descuento + precio_envio;
        }else{
            var precio_envio = 'N/A';
            var total = subtotal - detalle_pedido[0].Descuento;
        }   

        if(detalle_pedido[0].Estado == 0){
            var estado = 'No entregado';
        }else if(detalle_pedido[0].Estado == 1){
            var estado = 'Entregado';
        }

        $('#modal_nombreProducto').html(detalle_pedido[0].NombreProducto);
        $('#modal_imagen').prop('src', detalle_pedido[0].Imagen);
        $('#modal_nombreComprador').html(detalle_pedido[0].NombreCliente);
        $('#modal_nombreTienda').html(detalle_pedido[0].NombreTienda);
        $('#modal_cantidad').html(detalle_pedido[0].Cantidad);
        $('#modal_precioUnitario').html(detalle_pedido[0].Precio);
        $('#modal_subtotal').html(subtotal);
        $('#modal_descuento').html(detalle_pedido[0].Descuento);
        $('#modal_precioEnvio').html(precio_envio);
        $('#modal_total').html(total);
        $('#modal_descuento').html(detalle_pedido[0].Descuento);
        $('#modal_fechaCompra').html(detalle_pedido[0].FechaCompra);
        //$('#modal_estadoDetallePedido').html(estado);

        if(detalle_pedido[0].FK_TipoPedido == 2){
            $('#modal_destinatario').html('<br><p class="col-md-12"><strong>Destinatario </strong><br>' +
                                            '<span>' + detalle_pedido[0].NombresDestinatario + ' ' + detalle_pedido[0].ApellidosDestinatario + '</span><br>' +
                                            '<span>' + detalle_pedido[0].Direccion1Destinatario + ', ' + detalle_pedido[0].Direccion2Destinatario + '</span><br>' +
                                            '<span>' + detalle_pedido[0].DepartamentoDestinatario + ', ' + detalle_pedido[0].PaisDestinatario + '</span><br>' +
                                            '<span>Código Postal: ' + detalle_pedido[0].CodigoPostalDestinatario +'</span><br>' +
                                            '<span>Tel. ' + detalle_pedido[0].TelefonoDestinatario +'</span>' +
                                            '<span></span>' +
                                        '</p>');
            $('#modal_img_adomicilio').prop('src', '<?php echo URL_SITIO ?>static/img/icon_adomicilio.png');
            $('#modal_lbl_tipoPedido').html('A domicilio');
        }else{
            $('#modal_img_adomicilio').prop('src', '<?php echo URL_SITIO ?>static/img/icon_entienda.png');
            $('#modal_lbl_tipoPedido').html('En tienda');
        }                      
        $('#modal_idPago').html(detalle_pedido[0].CodigoDetallePedido);    
        $('#modal_numeroPedido').html(detalle_pedido[0].NumeroPedido);                      
        
    }

    


</script>

<?php require ('footer_admin.php') ?>
</body>
</html>