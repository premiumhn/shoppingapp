<?php 
error_reporting(E_ALL);
ini_set('display_errors', '1');
include '../global/config.php';
include '../global/conexion.php';

session_start();


    if(!empty($_POST['PamentID']) && !empty($_POST['cid']) && !empty($_POST['tc']) ){
        $IDPago = $_POST['PamentID'];
        $cid = $_POST['cid']; 
        $tc = $_POST['tc']; 


        switch($tc){
            case'car':
                //Consulta seleccionar carrito
                $select_carrito = $pdo->prepare("SELECT p.NombreProducto, 
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
                        ti.IDClientePaypal,
                        c.FK_Destinatario,
                        c.FK_Producto,
                        c.FK_Talla,
                        c.FK_Color,
                        c.FK_Cliente,
                        p.FK_Tienda
                        FROM Carrito c INNER JOIN Productos p
                        ON c.FK_Producto = p.PK_Producto INNER JOIN Clientes cli
                        ON c.FK_Cliente = cli.PK_Cliente INNER JOIN Usuarios u
                        ON cli.FK_Usuario = u.PK_Usuario INNER JOIN Tiendas ti
                        ON p.FK_Tienda = ti.PK_Tienda 
                        WHERE c.FK_Cliente = :FK_Cliente");
                $select_carrito->bindParam(':FK_Cliente', $cid);                           
                $select_carrito->execute();
                $lista_carrito = $select_carrito->fetchAll(PDO::FETCH_ASSOC);


                // insertar pedido
                $fk_cliente = $lista_carrito[0]['FK_Cliente'];
                $fk_tienda = $lista_carrito[0]['FK_Tienda'];
                $numero_pedido = $IDPago;
                $fecha_hora_orden = date('Y-m-d H:i:s');
                $fecha_hora_compra = date('Y-m-d H:i:s');
                $fecha_hora_envio = NULL;
                $fecha_hora_entrega = NULL;
                $estado = 0;

                $insert_pedido = $pdo->prepare("INSERT INTO `Pedidos` (`PK_Pedido`, `FK_Cliente`, `FK_Tienda`, `NumeroPedido`, `FechaHoraOrden`, `FechaHoraCompra`, `FechaHoraEnvio`, `FechaHoraEntrega`, `Estado`) 
                        VALUES (NULL, :FK_Cliente, :FK_Tienda, :NumeroPedido, :FechaHoraOrden, :FechaHoraCompra, :FechaHoraEnvio, :FechaHoraEntrega, :Estado);");

                $insert_pedido->bindParam(':FK_Cliente', $fk_cliente);
                $insert_pedido->bindParam(':FK_Tienda', $fk_tienda);
                $insert_pedido->bindParam(':NumeroPedido', $numero_pedido);
                $insert_pedido->bindParam(':FechaHoraOrden', $fecha_hora_orden);
                $insert_pedido->bindParam(':FechaHoraCompra', $fecha_hora_compra);
                $insert_pedido->bindParam(':FechaHoraEnvio', $fecha_hora_envio);
                $insert_pedido->bindParam(':FechaHoraEntrega', $fecha_hora_entrega);
                $insert_pedido->bindParam(':Estado', $estado);

                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $insert_pedido->execute();

                // buscar Pedido
                $buscar_pedido = $pdo->prepare("SELECT * FROM Pedidos WHERE NumeroPedido = :NumeroPedido ");
                $buscar_pedido->bindParam(':NumeroPedido', $numero_pedido);
                $buscar_pedido->execute();
                $pedido = $buscar_pedido->fetchAll(PDO::FETCH_ASSOC);

                // insertar detalle de pedido
                foreach($lista_carrito as $carrito){
                $cantidad = $carrito['Cantidad'];
                $pk_producto = $carrito['FK_Producto'];
                $talla = $carrito['FK_Talla'];
                $color = $carrito['FK_Color'];
                $fecha_hora_agregado = date('Y-m-d H:i:s');
                $tipo_pedido = $carrito['FK_TipoPedido'];
                $destinatario = $carrito['FK_Destinatario'];
                $fk_pedido = $pedido[0]['PK_Pedido'];
                $estado_detalle_pedido = 0;

                // MILLISECONDS
                $currentNanoSecond = (int) (microtime(true) * 1000000000);
                $nano = $currentNanoSecond.PHP_EOL;

                $cod_detalle_pedido = 'P' . $_SESSION['login_user'] . '-' . $pk_producto . date('smH') . 'Y' . substr(date('Y'), -2) . 'D' . date('md') . substr($nano, -5);



                $insert_detalle_pedido = $pdo->prepare("INSERT INTO `DetallePedidos` (`PK_DetallePedido`, `FK_Pedido`, `Cantidad`, `FK_Producto`, `FK_Talla`, `FK_Color`, `FechaHoraAgregado`, `FK_Cliente`, `FK_TipoPedido`, `FK_Destinatario`, `Estado`, `CodigoDetallePedido`) 
                        VALUES (NULL, :FK_Pedido, :Cantidad, :FK_Producto, :FK_Talla, :FK_Color, :FechaHoraAgregado, :FK_Cliente, :FK_TipoPedido, :FK_Destinatario, :Estado, :CodigoDetallePedido);");

                $insert_detalle_pedido->bindParam(':FK_Pedido', $fk_pedido);
                $insert_detalle_pedido->bindParam(':Cantidad', $cantidad);
                $insert_detalle_pedido->bindParam(':FK_Producto', $pk_producto);
                $insert_detalle_pedido->bindParam(':FK_Talla', $talla);
                $insert_detalle_pedido->bindParam(':FK_Color', $color);
                $insert_detalle_pedido->bindParam(':FechaHoraAgregado', $fecha_hora_agregado);
                $insert_detalle_pedido->bindParam(':FK_Cliente', $cid);
                $insert_detalle_pedido->bindParam(':FK_TipoPedido', $tipo_pedido);
                $insert_detalle_pedido->bindParam(':FK_Destinatario', $destinatario);
                $insert_detalle_pedido->bindParam(':Estado', $estado_detalle_pedido);
                $insert_detalle_pedido->bindParam(':CodigoDetallePedido', $cod_detalle_pedido);


                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $insert_detalle_pedido->execute(); 
                }


                //    eliminar carrito
                $eliminar_carrito = $pdo->prepare("DELETE FROM Carrito WHERE FK_Cliente = :FK_Cliente");
                $eliminar_carrito->bindParam(':FK_Cliente', $cid);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $eliminar_carrito->execute(); 

                //reducir del inventario
                foreach($lista_carrito as $carrito){
                    $eliminar_temp = $pdo->prepare("SET @cant = (SELECT UnidadesDisponibles FROM Productos WHERE PK_Producto = :PK_Producto);
                                                    UPDATE Productos
                                                    SET UnidadesDisponibles = (  @cant - :Cantidad ) 
                                                    WHERE PK_Producto  = :PK_Producto;");

                    $eliminar_temp->bindParam(':PK_Producto', $carrito['FK_Producto']);
                    $eliminar_temp->bindParam(':Cantidad', $carrito['Cantidad']);

                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $eliminar_temp->execute(); 
                }

                
               

            break;
            case's1':

                $pk_pago = $_POST['pkp'];
                 //Consulta seleccionar producto
                $select_producto = $pdo->prepare("SELECT   
                p.NombreProducto, 
                c.Cantidad, 
                p.PrecioUnitario, 
                p.Imagen, 
                (SELECT Color FROM Colores WHERE PK_Color = c.FK_Color),
                (SELECT Talla FROM Tallas WHERE PK_Talla = c.FK_Talla), 
                p.Descuento, 
                (p.PrecioUnitario * c.Cantidad) as 'Subtotal', 
                (CAST(p.Descuento as DECIMAL(20,0)) ) as DescuentoDecimal, 
                (p.PrecioUnitario * c.Cantidad) - ((p.PrecioUnitario * c.Cantidad) * ((CAST(p.Descuento as DECIMAL(20,0)) )/100)) as 'Total',
                ti.NombreTienda,
                p.PrecioEnvio,
                c.FK_TipoPedido,
                ti.IDClientePaypal,
                c.FK_Cliente,
                p.FK_Tienda,
                c.FK_Producto,
                c.FK_Talla,
                c.FK_Color,
                c.FK_Destinatario
                FROM Pago_solouno_temp c INNER JOIN Productos p
                ON c.FK_Producto = p.PK_Producto  INNER JOIN Clientes cli
                ON c.FK_Cliente = cli.PK_Cliente INNER JOIN Usuarios u
                ON cli.FK_Usuario = u.PK_Usuario INNER JOIN Tiendas ti
                ON p.FK_Tienda = ti.PK_Tienda
                WHERE c.PK_Pago = :PK_Pago");
                $select_producto->bindParam(':PK_Pago', $pk_pago);                           
                $select_producto->execute();
                $producto = $select_producto->fetchAll(PDO::FETCH_ASSOC);
               

                // insertar pedido
                $fk_cliente = $producto[0]['FK_Cliente'];
                $fk_tienda = $producto[0]['FK_Tienda'];
                $numero_pedido = $IDPago;
                $fecha_hora_orden = date('Y-m-d H:i:s');
                $fecha_hora_compra = date('Y-m-d H:i:s');
                $fecha_hora_envio = NULL;
                $fecha_hora_entrega = NULL;
                $estado = 0;

                $insert_pedido = $pdo->prepare("INSERT INTO `Pedidos` (`PK_Pedido`, `FK_Cliente`, `FK_Tienda`, `NumeroPedido`, `FechaHoraOrden`, `FechaHoraCompra`, `FechaHoraEnvio`, `FechaHoraEntrega`, `Estado`) 
                        VALUES (NULL, :FK_Cliente, :FK_Tienda, :NumeroPedido, :FechaHoraOrden, :FechaHoraCompra, :FechaHoraEnvio, :FechaHoraEntrega, :Estado);");

                $insert_pedido->bindParam(':FK_Cliente', $fk_cliente);
                $insert_pedido->bindParam(':FK_Tienda', $fk_tienda);
                $insert_pedido->bindParam(':NumeroPedido', $numero_pedido);
                $insert_pedido->bindParam(':FechaHoraOrden', $fecha_hora_orden);
                $insert_pedido->bindParam(':FechaHoraCompra', $fecha_hora_compra);
                $insert_pedido->bindParam(':FechaHoraEnvio', $fecha_hora_envio);
                $insert_pedido->bindParam(':FechaHoraEntrega', $fecha_hora_entrega);
                $insert_pedido->bindParam(':Estado', $estado);

                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $insert_pedido->execute();

                // buscar Pedido
                $buscar_pedido = $pdo->prepare("SELECT * FROM Pedidos WHERE NumeroPedido = :NumeroPedido ");
                $buscar_pedido->bindParam(':NumeroPedido', $numero_pedido);
                $buscar_pedido->execute();
                $pedido = $buscar_pedido->fetchAll(PDO::FETCH_ASSOC);

                // insertar detalle de pedido
                $cantidad = $producto[0]['Cantidad'];
                $pk_producto = $producto[0]['FK_Producto'];
                $talla = $producto[0]['FK_Talla'];
                $color = $producto[0]['FK_Color'];
                $fecha_hora_agregado = date('Y-m-d H:i:s');
                $tipo_pedido = $producto[0]['FK_TipoPedido'];
                $destinatario = $producto[0]['FK_Destinatario'];
                $fk_pedido = $pedido[0]['PK_Pedido'];
                $estado_detalle_pedido = 0;

                // MILLISECONDS
                $currentNanoSecond = (int) (microtime(true) * 1000000000);
                $nano = $currentNanoSecond.PHP_EOL;

                $cod_detalle_pedido = 'P' . $_SESSION['login_user'] . '-' . $pk_producto . date('smH') . 'Y' . substr(date('Y'), -2) . 'D' . date('md') . substr($nano, -5);

                $insert_detalle_pedido = $pdo->prepare("INSERT INTO `DetallePedidos` (`PK_DetallePedido`, `FK_Pedido`, `Cantidad`, `FK_Producto`, `FK_Talla`, `FK_Color`, `FechaHoraAgregado`, `FK_Cliente`, `FK_TipoPedido`, `FK_Destinatario`, `Estado`, `CodigoDetallePedido`) 
                        VALUES (NULL, :FK_Pedido, :Cantidad, :FK_Producto, :FK_Talla, :FK_Color, :FechaHoraAgregado, :FK_Cliente, :FK_TipoPedido, :FK_Destinatario, :Estado, :CodigoDetallePedido);");

                $insert_detalle_pedido->bindParam(':FK_Pedido', $fk_pedido);
                $insert_detalle_pedido->bindParam(':Cantidad', $cantidad);
                $insert_detalle_pedido->bindParam(':FK_Producto', $pk_producto);
                $insert_detalle_pedido->bindParam(':FK_Talla', $talla);
                $insert_detalle_pedido->bindParam(':FK_Color', $color);
                $insert_detalle_pedido->bindParam(':FechaHoraAgregado', $fecha_hora_agregado);
                $insert_detalle_pedido->bindParam(':FK_Cliente', $cid);
                $insert_detalle_pedido->bindParam(':FK_TipoPedido', $tipo_pedido);
                $insert_detalle_pedido->bindParam(':FK_Destinatario', $destinatario);
                $insert_detalle_pedido->bindParam(':Estado', $estado_detalle_pedido);
                $insert_detalle_pedido->bindParam(':CodigoDetallePedido', $cod_detalle_pedido);


                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $insert_detalle_pedido->execute(); 

                //    eliminar producto de tabla temporal
                $eliminar_temp = $pdo->prepare("DELETE FROM Pago_solouno_temp WHERE PK_Pago = :PK_Pago");

                $eliminar_temp->bindParam(':PK_Pago', $pk_pago);

                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $eliminar_temp->execute(); 

                //reducir del inventario
                $reducir = $pdo->prepare("SET @cant = (SELECT UnidadesDisponibles FROM Productos WHERE PK_Producto = :PK_Producto);
                                                SET @ven = (SELECT UnidadesVendidas FROM Productos WHERE PK_Producto = :PK_Producto);
                                                UPDATE Productos
                                                SET UnidadesDisponibles = UnidadesDisponibles - :Cantidad ,
                                                UnidadesVendidas = UnidadesVendidas + :Cantidad 
                                                WHERE PK_Producto  = :PK_Producto;");

                $reducir->bindParam(':PK_Producto', $pk_producto);
                $reducir->bindParam(':Cantidad', $cantidad);

                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $reducir->execute(); 

                //Añadir unidad vendida
                $actualizar_vendidas = $pdo->prepare("SET @cant = (SELECT UnidadesVendidas FROM Productos WHERE PK_Producto = :PK_Producto);
                                                UPDATE Productos
                                                SET UnidadesVendidas = (  @cant + :Cantidad ) 
                                                WHERE PK_Producto  = :PK_Producto;");

                $actualizar_vendidas->bindParam(':PK_Producto', $pk_producto);
                $actualizar_vendidas->bindParam(':Cantidad', $cantidad);

               // $actualizar_vendidas->execute(); 

                   
                
            break;
        }
        
        
  
    } else {
        header('Location: ../Index');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <title>Document</title>
</head>
<body>
<form action="../Confirmacion-Pago" id="form-info" method="post">
    <input type="hidden" name="FK_Pedido" id="FK_Pedido">
    <input type="hidden" name="cid" id="cid" >
    <input type="hidden" name="IDPago" id="IDPago">
</form>
</body>
</html>



<script type="text/javascript">
    $(document).ready(function () {
        $('#IDPago').val('<?php echo $IDPago ?>');
        $('#cid').val('<?php echo $cid ?>');
        $('#FK_Pedido').val(<?php echo $fk_pedido ?>);

        $("#form-info").submit();
    });
</script>