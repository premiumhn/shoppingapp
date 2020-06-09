<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include '../global/config.php';
include '../global/conexion.php';
include '../global/const.php';

session_start();

if (isset($_SESSION['login_user'])){ //Comprobar si ha iniciado sesión
    $user = $_SESSION['login_user'];
}else{
    header('Location: login.php');
    // header('Location: completar_perfil_tienda.php');
}

$select_config = $pdo->prepare("SELECT * FROM Configuracion");
$select_config->execute();
$configuracion = $select_config->fetchAll(PDO::FETCH_ASSOC);

// Búsqueda
$busqueda = (isset($_REQUEST['input_busqueda']))?$_REQUEST['input_busqueda']:"";
$str_busqueda = ($busqueda != '')?" AND (p.NombreProducto LIKE '%" . $busqueda . "%'
                                    OR ti.NombreTienda LIKE '%" . $busqueda . "%'":"";

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
                                    c.PK_Carrito,
                                    cli.PK_Cliente,
                                    (SELECT FK_Ciudad FROM Destinatarios WHERE PK_Destinatario = c.FK_Destinatario) as 'FK_Ciudad',
                                    (SELECT CONCAT(NombresDestinatario, ' ', ApellidosDestinatario) FROM Destinatarios WHERE PK_Destinatario = c.FK_Destinatario) as 'NombreDestinatario'
                                    FROM Carrito c INNER JOIN Productos p
                                    ON c.FK_Producto = p.PK_Producto INNER JOIN Clientes cli
                                    ON c.FK_Cliente = cli.PK_Cliente INNER JOIN Usuarios u
                                    ON cli.FK_Usuario = u.PK_Usuario INNER JOIN Tiendas ti
                                    ON p.FK_Tienda = ti.PK_Tienda 
                                    WHERE cli.FK_Usuario = :FK_Usuario" . $str_busqueda ."
                                    AND ti.PK_Tienda = :PK_Tienda
                                     ORDER BY c.PK_Carrito DESC");
 $select_carrito->bindParam(':FK_Usuario', $user); 
 $select_carrito->bindParam(':PK_Tienda', $_SESSION['tienda']);                           
 $select_carrito->execute();
 $lista_carrito = $select_carrito->fetchAll(PDO::FETCH_ASSOC);

 function obtenerPrecioEnvio($FK_Tienda, $FK_Ciudad){
     global $pdo;
    $sql_precio = $pdo->prepare("SELECT * FROM RegionesEnvio WHERE FK_Tienda = :FK_Tienda and FK_Ciudad = :FK_Ciudad");
    $sql_precio->bindParam(':FK_Tienda', $FK_Tienda);
    $sql_precio->bindParam(':FK_Ciudad', $FK_Ciudad);
    $sql_precio->execute();
    $precio = $sql_precio->fetchAll(PDO::FETCH_ASSOC); 
    
    return $precio[0]['PrecioEnvio'];
 }

 $select_tienda = $pdo->prepare("SELECT * FROM Tiendas WHERE PK_Tienda = :PK_Tienda");
 $select_tienda->bindParam(':PK_Tienda', $_SESSION['tienda']);
 $select_tienda->execute();
 $tienda = $select_tienda->fetchAll(PDO::FETCH_ASSOC);
 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Carrito</title>

   <!-- Imports -->
   
    <link href="<?php echo URL_SITIO ?>static/css/styles.css" rel="stylesheet" type="text/css" media="all" />
    <link href="<?php echo URL_SITIO ?>static/css/carrito.css" rel="stylesheet" type="text/css" media="all" />

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="https://kit.fontawesome.com/b2dbb6a24d.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="<?php echo URL_SITIO ?>static/js/jquery-3.5.0.min.js" ></script>
 
    <?php include 'iconos.php' ?>

</head>
<body>
<?php require ('header.php') ?>
<div class="alert alert-secondary">
    Inicio / Carrito de compras de la tienda <strong><?php echo $tienda[0]['NombreTienda'] ?></strong>
    <a class="volver-tienda btn" href="<?php echo URL_SITIO ?>Home?Tienda=<?php echo $_SESSION['tienda'] ?>" class="text-right"> <i class="fa fa-arrow-left"></i> Volver a la tienda</a>
</div>
<?php 
    $total_todos_monto = 0;
    foreach($lista_carrito as $carrito){
        if($configuracion[0]['CobrosPorEnvio'] == 1){ 
            $total_todos_monto+= ($carrito['Subtotal']) - ((isset($carrito['DescuentoDecimal']))?(($carrito['Subtotal'])/$carrito['DescuentoDecimal']):0) + (($carrito['FK_TipoPedido']==2)?$carrito['PrecioEnvio']  + obtenerPrecioEnvio($carrito['PK_Tienda'], $carrito['FK_Ciudad']) :0) ;
        }else{
            $total_todos_monto+= ($carrito['Subtotal']) - ((isset($carrito['DescuentoDecimal']))?(($carrito['Subtotal'])/$carrito['DescuentoDecimal']):0) ;
        }
    } 

    $cliente = (count($lista_carrito)>0)?$lista_carrito[0]['PK_Cliente']:"";

    $articulos_adomicilio = "";
    if($cliente != ""){
        $sql_articulos_adomicilio = $pdo->prepare("SELECT c.PK_Carrito FROM Carrito c INNER JOIN Productos p
                                                ON c.FK_Producto = p.PK_Producto INNER JOIN Tiendas t
                                                ON p.FK_Tienda = t.PK_Tienda INNER JOIN Usuarios u
                                                WHERE t.PK_Tienda = :FK_Tienda AND c.FK_Cliente = :FK_Cliente AND c.FK_TipoPedido = 2
                                                GROUP BY c.PK_Carrito");
        $sql_articulos_adomicilio->bindParam(':FK_Tienda', $_SESSION['tienda']);
        $sql_articulos_adomicilio->bindParam(':FK_Cliente', $cliente);
        $sql_articulos_adomicilio->execute();
        $articulos_adomicilio = $sql_articulos_adomicilio->fetchAll(PDO::FETCH_ASSOC); 
    }
?>

<?php if(($total_todos_monto < $tienda[0]['MontoMinimoEnvio']) AND (count($articulos_adomicilio)>0)){ ?>
    <div class="alert alert-danger alert_monto_minimo">No se enviarán tus productos a domicilio, el monto de los articulos deve ser mayor a $<?php echo $tienda[0]['MontoMinimoEnvio'] ?> </div>
<?php } ?>
 <div class="row" style="width:100%;margin:0px;">


    <div style="height:100%;margin-bottom:60px;" class="col-md-9 bordered">
    <div id="mensaje-success" class="alert alert-success" role="alert"></div>
    <div id="mensaje-error" class="alert alert-danger" role="alert"></div>
    
        <div class="text-center" ><?php echo (count($lista_carrito) == 0) ? 'No hay productos en el carrito de compras.': ""; ?> </div>
   
              
                        <?php foreach($lista_carrito as $carrito){ ?>
                            <div class="card">
                            <div class="card-body">
                                
                            <div class="row">
                            <div class="col-md-4 square container temp-border"> <img class=" col-md-12" src="<?php echo URL_SITIO.$carrito['Imagen'] ?>" alt=""> </div>
                            <div class="col-md-8 temp-border">
                                <div class="detail_up col-md-12 temp-border">
                                
                                    <h4 class=" btn-eliminar">
                                        <button onClick="eliminar(<?php echo $carrito['PK_Carrito'] ?>)" type="button" class="btn btn-eliminar" data-toggle="modal" data-target=".modal-eliminar"> <h4><i style="color:red;" class="fas fa-trash-alt mr-2"></i></h4> </button>
                                    </h4>
                                    <h4 class=""><?php echo $carrito['NombreProducto'] ?> <a href="">  </h4>
                                    <div class=" text-left row">
                                        <label class="  col-md-12" for="">Tienda &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp: <a href="<?php echo URL_SITIO ?>Home?Tienda=<?php echo $_SESSION['tienda'] ?>"><?php echo $carrito['NombreTienda'] ?></a> </label>
                                    </div>
                                    <div class="text-left row">
                                        <label class="  col-md-12" for="">Cantidad &nbsp&nbsp&nbsp&nbsp&nbsp: <?php echo $carrito['Cantidad'] ?></label>
                                    </div>
                                    <div class="text-left row">
                                        <label class="  col-md-12" for="">Precio &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp: $ <?php echo $carrito['PrecioUnitario'] ?></label>
                                    </div>
                                    <div class="text-left row">
                                        <label class="  col-md-12" for="">Tipo de pedido: <?php echo ($carrito['FK_TipoPedido'] == 1)?"En tienda":"A domicilio"; ?></label>
                                    </div>
                                    <?php if($carrito['FK_TipoPedido'] == 2){ ?>
                                    <div class="text-left row">
                                        <label class="  col-md-12" for="">Destinatario: <?php echo $carrito['NombreDestinatario']; ?></label>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="detail_down col-md-12 temp-border">
                                    <div class="col-md-5 offset-md-7">
                                        <div class="text-left row">
                                            <label class="subtotal col-md-12" for="">Subtotal  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp &nbsp$ <?php echo $carrito['Subtotal'] ?> </label>
                                        </div>

                                        
                                        <div class="text-left row">
                                            <label class="descuento col-md-12" for="">Descuento  &nbsp&nbsp&nbsp<?php echo (isset($carrito['DescuentoDecimal']))?"-&nbsp$ ".round((($carrito['Subtotal'])/$carrito['DescuentoDecimal']), 2):'&nbsp&nbsp N/A';  ?></label>
                                        </div>
                                        
                                        <?php if($configuracion[0]['CobrosPorEnvio'] == 1){ ?>
                                            <?php if($carrito['FK_TipoPedido']==2){ ?>
                                                <div class="text-left row">
                                                    <label class=" subtotal col-md-12" for="">Precio envío &nbsp&nbsp&nbsp&nbsp$ <?php echo (($carrito['FK_TipoPedido']==2)?$carrito['PrecioEnvio'] + obtenerPrecioEnvio($carrito['PK_Tienda'], $carrito['FK_Ciudad']):'N/A') ?></label>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                        
                                        <?php if($configuracion[0]['CobrosPorEnvio'] == 1){ ?>
                                            <div class=" text-left row">
                                                <label class="total col-md-12" for="">Total  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp &nbsp$ <?php echo round((($carrito['Subtotal']) - ((isset($carrito['DescuentoDecimal']))?(($carrito['Subtotal'])/$carrito['DescuentoDecimal']):0) + (($carrito['FK_TipoPedido']==2)?$carrito['PrecioEnvio']:0)), 2)  ?> </label>
                                            </div>
                                        <?php }else{ ?>
                                            <div class=" text-left row">
                                                <label class="total col-md-12" for="">Total  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp &nbsp$ <?php echo round((($carrito['Subtotal']) - ((isset($carrito['DescuentoDecimal']))?(($carrito['Subtotal'])/$carrito['DescuentoDecimal']):0)), 2)  ?> </label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                   
                                </div>
                            </div>
                                   
                            </div>
                        </div>
                    </div>
                        <br>
                    <?php }?>
                    
                    <br>
                    <br>
                    

            
    </div>

     <div id="card_pago" class="col-md-3  bordered">
        <div class=" card_detail">
            <form action="Pago" method="post">
                <div class="col-md-12">
                    <label for="" class="col-md-12 lbl-detail">
                        Artículos(<?php echo count($lista_carrito)?>):
                        <span class="text-right"> $
                        <?php
                        $total_todos = 0; 
                        foreach($lista_carrito as $carrito){ 
                            $total_todos+= $carrito['Subtotal']  ;
                        } 
                        echo $total_todos;
                        ?>
                        </span>
                    </label>
                    <?php if($configuracion[0]['CobrosPorEnvio'] == 1){ ?>
                        <label for="" class="col-md-12 lbl-detail">
                            Envios: 
                            <span class="text-right"> $
                            <?php
                            $total_envio = 0; 
                            foreach($lista_carrito as $carrito){ 
                                $total_envio+= ($carrito['FK_TipoPedido']==2)?$carrito['PrecioEnvio'] + obtenerPrecioEnvio($carrito['PK_Tienda'], $carrito['FK_Ciudad']):0;
                            } 
                            echo $total_envio;
                            ?>
                            </span>
                        </label>
                    <?php } ?>
                    <label for="" class="col-md-12 lbl-detail">
                        Descuentos: - 
                        <span class="text-right"> $
                        <?php
                        $total_descuentos = 0; 
                        foreach($lista_carrito as $carrito){ 
                            $total_descuentos+= (isset($carrito['Descuento']))?(($carrito['Subtotal'])/$carrito['DescuentoDecimal']):0;
                        } 
                        echo round($total_descuentos, 2);
                        ?>
                        </span>
                    </label>
                    <label for="" class="col-md-12 lbl-detail">
                        Comisión: 
                        <span class="text-right"> $
                        <?php
                            $total_todos = 0; 
                            foreach($lista_carrito as $carrito){
                                if($configuracion[0]['CobrosPorEnvio'] == 1){ 
                                    $total_todos+= ($carrito['Subtotal']) - ((isset($carrito['DescuentoDecimal']))?(($carrito['Subtotal'])/$carrito['DescuentoDecimal']):0) + (($carrito['FK_TipoPedido']==2)?$carrito['PrecioEnvio']  + obtenerPrecioEnvio($carrito['PK_Tienda'], $carrito['FK_Ciudad']) :0) ;
                                }else{
                                    $total_todos+= ($carrito['Subtotal']) - ((isset($carrito['DescuentoDecimal']))?(($carrito['Subtotal'])/$carrito['DescuentoDecimal']):0) ;
                                }
                            } 
                            if($total_todos > $configuracion[0]['PorCada']){
                                $numero_cobros = (int)($total_todos/$configuracion[0]['PorCada']) + 1;
                                $comision = $numero_cobros * $configuracion[0]['Comision'];
                            }else{
                                $comision = $configuracion[0]['Comision'];
                            }
                            echo round($comision, 2);
                        ?>
                        </span>
                    </label>
                    <hr>
                    <label for="" class=" total col-md-12 Total">
                        Total:
                        <span class="text-right"> $
                        <?php
                        $total_todos = 0; 
                        foreach($lista_carrito as $carrito){
                            if($configuracion[0]['CobrosPorEnvio'] == 1){ 
                                $total_todos+= ($carrito['Subtotal']) - ((isset($carrito['DescuentoDecimal']))?(($carrito['Subtotal'])/$carrito['DescuentoDecimal']):0) + (($carrito['FK_TipoPedido']==2)?$carrito['PrecioEnvio']  + obtenerPrecioEnvio($carrito['PK_Tienda'], $carrito['FK_Ciudad']) :0) ;
                            }else{
                                $total_todos+= ($carrito['Subtotal']) - ((isset($carrito['DescuentoDecimal']))?(($carrito['Subtotal'])/$carrito['DescuentoDecimal']):0) ;
                            }
                        } 
                        echo round($total_todos + $comision, 2);
                        ?>
                        </span>
                    </label>
                </div>
                <input type="hidden" name="action" value="completar" >
                <input type="hidden" name="p" value="<?php echo $_REQUEST['p'] ?>">
                <button <?php echo (count($lista_carrito) == 0) ? 'disabled': ""; ?> type="submit" class="btn-flat col-md-12">Completar</button>
            </form>
        </div>
    </div>
</div>    

<!-- modal para eliminar -->
<div class="modal fade modal-eliminar" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class=" md-content col-md-12">
                <br>
                <label class="text-center col-md-12" for=""><strong>Eliminar</strong></label>
          
            <div style="height:100%;margin-bottom:60px;" class="col-md-12 offset-md-0 bordered">
                <div class="card">
                <div class="card-body">
                    <form id="form-eliminar" action="<?php echo URL_SITIO ?>scripts/carrito.php" method="post" enctype="multipart/form-data">
                      
                        <label for="">¿Seguro que desea eliminar este producto del carrito ?</label>

                        <br>
                        <br>
                        <br>
                        <br>

                        <input type="hidden" id="PK_Carrito" name="pk_carrito">
                        <input type="hidden" value="eliminar_carrito" name="action">

                        <div class="text-center col-md-12">
                        <button id="btnEliminar" type="submit" data-dismiss="modal" class=" btn-modal btn btn-danger col-md-5">Eliminar</button>&nbsp&nbsp&nbsp
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




<script type="text/javascript">
    $("#mensaje_alert").css("visibility", "hidden");

    $('#mensaje-success').hide();
    $('#mensaje-error').hide();

    $('#lbl-carrito').hide();

    <?php 
        $msj = (isset($_GET['msj']))?$_GET['msj']:"";
        if( $msj == 'eliminado'){ 
    ?>
        $('#mensaje-success').html('<i class="fa fa-check"></i>Producto eliminado del carrito.');
        $('#mensaje-success').show();
    <?php } ?>


    $('#form_completar_cliente').on('submit', function (event) {
            event.preventDefault();

        if( $('#inputPrimerNombre').val() == '' || $('#inputPrimerApellido').val() == '' || $('#inputTelefono').val() == '' || $('#inputDireccion1').val() == '' || $('#inputDireccion2').val() == '' || $('#inputPais')[0].selectedIndex == 0 || $('#inputCiudad')[0].selectedIndex == 0 ){
            mostrarMensaje("One or more required field(s) are missing.");
            ocultarMensaje();
        }else{
            $('#form_completar_cliente').unbind('submit').submit();
        }

    });

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

    function eliminar(pk_carrito){
         $('#PK_Carrito').val(pk_carrito);
     }

    $('#btnEliminar').click(function(e){
        e.preventDefault();
        
        $('#form-eliminar').submit();
    })

    $('#search-form').hide();


</script>

<?php require ('footer.php') ?>
</body>
</html>