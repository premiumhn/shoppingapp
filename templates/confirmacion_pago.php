<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include '../global/config.php';
include '../global/conexion.php';
include '../global/const.php';

session_start();

require ('../scripts/comprobaciones.php');



if(!empty($_POST['IDPago']) && !empty($_POST['FK_Pedido']) && !empty($_POST['cid']) ){
    $IDPago = $_POST['IDPago'];
    $fk_pedido = $_POST['FK_Pedido'];
    $cid = $_POST['cid']; 

    

    // nuevos valores
    $select_detalle_pedido = $pdo->prepare("SELECT p.NombreProducto, 
                                        c.Cantidad, 
                                        p.PrecioUnitario, 
                                        p.Imagen, 
                                        p.Descuento, 
                                        (p.PrecioUnitario * c.Cantidad) as 'Subtotal', 
                                        (CAST(p.Descuento as DECIMAL(20,0)) ) as DescuentoDecimal, 
                                        (p.PrecioUnitario * c.Cantidad) - ((p.PrecioUnitario * c.Cantidad) * ((CAST(p.Descuento as DECIMAL(20,0)) )/100)) as 'Total',
                                        ti.NombreTienda,
                                        p.PrecioEnvio,
                                        c.FK_TipoPedido,
                                        c.FK_Destinatario,
                                        c.FK_Producto,
                                        c.FK_Cliente,
                                        p.FK_Tienda,
                                        ti.PK_Tienda,
                                        (SELECT FK_Ciudad FROM Destinatarios WHERE PK_Destinatario = c.FK_Destinatario) as 'FK_Ciudad'
                                        FROM DetallePedidos c INNER JOIN Productos p
                                        ON c.FK_Producto = p.PK_Producto INNER JOIN Clientes cli
                                        ON c.FK_Cliente = cli.PK_Cliente INNER JOIN Usuarios u
                                        ON cli.FK_Usuario = u.PK_Usuario INNER JOIN Tiendas ti
                                        ON p.FK_Tienda = ti.PK_Tienda 
                                        WHERE c.FK_Cliente = :FK_Cliente AND c.FK_Pedido = :FK_Pedido");
    $select_detalle_pedido->bindParam(':FK_Cliente', $cid); 
    $select_detalle_pedido->bindParam(':FK_Pedido', $fk_pedido);                          
    $select_detalle_pedido->execute();
    $lista_detalle = $select_detalle_pedido->fetchAll(PDO::FETCH_ASSOC);

    function obtenerPrecioEnvio($FK_Tienda, $FK_Ciudad){
        global $pdo;
       $sql_precio = $pdo->prepare("SELECT * FROM RegionesEnvio WHERE FK_Tienda = :FK_Tienda and FK_Ciudad = :FK_Ciudad");
       $sql_precio->bindParam(':FK_Tienda', $FK_Tienda);
       $sql_precio->bindParam(':FK_Ciudad', $FK_Ciudad);
       $sql_precio->execute();
       $precio = $sql_precio->fetchAll(PDO::FETCH_ASSOC); 
       
       return $precio[0]['PrecioEnvio'];
    }

    $total_todos = 0; 
    $total_envio = 0; 
    $subtotal_todos = 0;
    foreach($lista_detalle as $detalle){ 
    // calculo subtotal todos 
    $subtotal_todos+= $detalle['Subtotal'] - (($detalle['DescuentoDecimal']!=0)?(($detalle['Subtotal'])/$detalle['DescuentoDecimal']):0) ;

    // calculo total
    $total_todos+= ($detalle['Subtotal']) - (($detalle['DescuentoDecimal']!=0)?(($detalle['Subtotal'])/$detalle['DescuentoDecimal']):0) + (($detalle['FK_TipoPedido']==2)?$detalle['PrecioEnvio'] + obtenerPrecioEnvio($detalle['PK_Tienda'], $detalle['FK_Ciudad']):0) ;

    // calculo total envios
    $total_envio+= ($detalle['FK_TipoPedido']==2)?$detalle['PrecioEnvio'] + obtenerPrecioEnvio($detalle['PK_Tienda'], $detalle['FK_Ciudad']):0;
    }   
        

}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Confirmación de pago</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="<?php echo URL_SITIO?>static/css/styles.css" rel="stylesheet" type="text/css" media="all" />
    <link href="<?php echo URL_SITIO?>static/css/confirmacion_pago.css" rel="stylesheet" type="text/css" media="all" />

	<script src="https://kit.fontawesome.com/b2dbb6a24d.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <?php include 'iconos.php' ?>
 
</head>
<body>




<!-- DIV temporal -->
<br class="br-inicio">
<br class="br-inicio">
<div class="text-center  col-md-10 offset-md-1">
    <div class="card col-md-6 offset-md-3">
        <div class="card-body">
            <div class="row col-md-12">
            <div class="col-md-8 offset-md-2">
                <img src="<?php echo URL_SITIO?>static/img/Logo_shoppingapp_pay.png" width="100%" alt="">
            </div>
            </div>
        </div>

        <div class="card-body gray">
            <div class="row col-md-12">
                <div class="col-md-4 offset-md-4">
                    <img src="<?php echo URL_SITIO?>static/img/checkmark.gif" width="100%" alt="">
                </div>
                <br>
                <div class="col-md-12 text-center text-big">Ha realizado un pago de <span class="text-bold "> $ <?php echo round($total_todos, 2) ?></span></div>
                <label class="text-center col-md-12 text-li" for="">a el comercio Shoppingapp</label>
                <br>
                <br>

                <?php foreach($lista_detalle as $detalle){ ?>
                    <div class="row col-md-12">
                            <div class="col-md-6 text-left text-li" for=""><?php echo $detalle['Cantidad'] ?> x <?php echo $detalle['NombreProducto'] ?></div>
                            <div class="col-md-6 text-right text-bold" for="">$ <?php echo round(($detalle['Subtotal'] - (($detalle['DescuentoDecimal']!=0)?(($detalle['Subtotal'])/$detalle['DescuentoDecimal']):0)), 2) ?> <span class="text-li small" >USD</span></div>
                    </div>
                    <div class="row col-md-12">
                            <div class="col-md-12 text-left text-li small" for=""> <span class="text-bold">N.º de artículo:</span> 1_2020-05-02_16:13:51_2</div>
                    </div>
                    <br>
                    <br>
                <?php } ?>

                <div class="row col-md-12 border-b">
                </div>
                <div class="row col-md-12">
                <hr><hr>
                        <div class="col-md-6 text-left text-li" for="">Subtotal</div>
                        <div class="col-md-6 text-right text-bold" for="">$ <?php echo round($subtotal_todos, 2) ?> <span class="text-li small" >USD</span></div>
                </div>
                <hr>
                <div class="row col-md-12 border-b">
                        <div class="col-md-6 text-left text-li" for="">Envío</div>
                        <div class="col-md-6 text-right text-bold" for="">$ <?php echo round($total_envio, 2) ?> <span class="text-li small" >USD</span></div>
                </div>
                <div class="row col-md-12 text-bold">
                        <div class="col-md-6 text-left" for="">Total</div>
                        <div class="col-md-6 text-right " for="">$ <?php echo round($total_todos, 2) ?> <span class="text-li small" >USD</span></div>
                </div>
                <br>

            </div>
            
        </div>

          <div class="card-body ">
            <div class="row col-md-12">
                <div class="row col-md-12 text-bold">
                  

                    <div class="col-md-10 text-left" for="">Pagado con</div>
                </div>
                <div class="row col-md-12">
                        <div class="col-md-6 text-left text-li" for="">Paypal</div>
                        <div class="col-md-6 text-right text-bold" for="">$ <?php echo round($total_todos, 2) ?> <span class="text-li small" >USD</span> </div>
                </div>
                <br>
                <br>
                <br>
                <div class="row col-md-12 text-bold margin-b">
                    <div class="col-md-12 text-left" for="">Detalles de la compra</div>
                </div>
                <br>
                <div class="row col-md-12 margin-b">
                    <br>
                        <div class="col-md-12 text-left text-li" for="">ID del pago: <span class="text-bold" ><?php echo $IDPago ?></span></div>
                </div>
                <div class="row col-md-12 margin-b">
                    <br>
                        <div class="col-md-12 text-left text-li" for="">Se envió una confirmación al correo de su cuenta PayPal.</div>
                </div>
                <div style="margin-top:20px!important;margin-bottom:30px!important;" class="row col-md-12">
                    <br>
                    <a class="btn-flat col-md-12 text-center" href="<?php echo URL_SITIO ?>Home">Continuar en Shoppingapp</a>
                    <br>
                </div>
            </div>
            
        </div>

    </div>
</div>




</body>
</html>

<script>
if (window.performance) {
  console.info("window.performance works fine on this browser");
}
  if (performance.navigation.type == 1) {
    console.info( "This page is reloaded" );
  } else {
    console.info( "This page is not reloaded");
  }
</script>