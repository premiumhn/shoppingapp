<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include '../global/config.php';
include '../global/conexion.php';
include '../global/config_paypal.php';
include '../global/const.php';

session_start();
require 'language/requirelanguage.php';
include '../scripts/comprobaciones.php';

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
                                    c.FK_Cliente,
                                    ti.PK_Tienda,
                                    (SELECT FK_Ciudad FROM Destinatarios WHERE PK_Destinatario = c.FK_Destinatario) as 'FK_Ciudad'
                                    FROM Carrito c INNER JOIN Productos p
                                    ON c.FK_Producto = p.PK_Producto INNER JOIN Clientes cli
                                    ON c.FK_Cliente = cli.PK_Cliente INNER JOIN Usuarios u
                                    ON cli.FK_Usuario = u.PK_Usuario INNER JOIN Tiendas ti
                                    ON p.FK_Tienda = ti.PK_Tienda 
                                    WHERE cli.FK_Usuario = :FK_Usuario AND ti.PK_Tienda = :PK_Tienda
                                    ORDER BY c.PK_Carrito DESC");
$select_carrito->bindParam(':FK_Usuario', $_SESSION['login_user']);
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

 //Consulta seleccionar destinatario
 $select_destinatario = $pdo->prepare("SELECT d.NombresDestinatario, d.ApellidosDestinatario, d.Telefono, d.Departamento, d.Direccion1, d.Direccion2, d.CodigoPostal, ciu.NombreCiudad, p.NombrePais
                                    FROM Destinatarios d INNER JOIN Clientes c
                                    ON d.FK_Cliente = c.PK_Cliente INNER JOIN Usuarios u
                                    ON c.FK_Usuario = u.PK_Usuario INNER JOIN Ciudades ciu
                                    ON ciu.PK_Ciudad = d.FK_Ciudad INNER JOIN Paises p
                                    ON p.PK_Pais = ciu.FK_Pais
                                    WHERE u.PK_Usuario = :FK_Usuario");
 $select_destinatario->bindParam(':FK_Usuario', $_SESSION['login_user']);                           
 $select_destinatario->execute();
 $destinatario = $select_destinatario->fetchAll(PDO::FETCH_ASSOC);

 //Consulta seleccionar usuario
 $select_usuario = $pdo->prepare("SELECT * FROM Usuarios WHERE PK_Usuario = :FK_Usuario");
 $select_usuario->bindParam(':FK_Usuario', $_SESSION['login_user']);                           
 $select_usuario->execute();
 $usuario = $select_usuario->fetchAll(PDO::FETCH_ASSOC);

 $select_config = $pdo->prepare("SELECT * FROM Configuracion");
 $select_config->execute();
 $configuracion = $select_config->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pago</title>

     <!-- Imports -->
   
    <link href="<?php echo URL_SITIO ?>static/css/registro_datos.css" rel="stylesheet" type="text/css" media="all" />
    <link href="<?php echo URL_SITIO ?>static/css/pago.css" rel="stylesheet" type="text/css" media="all" />
    <link href="<?php echo URL_SITIO ?>static/css/styles.css" rel="stylesheet" type="text/css" media="all" />

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="https://kit.fontawesome.com/b2dbb6a24d.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="<?php echo URL_SITIO ?>static/js/jquery-3.5.0.min.js" ></script>

    <?php include 'iconos.php' ?>
</head>
<body>



<div class="col-md-12 ">
    <div class="row col-md-10 offset-md-1  ">
        <div class="row col-md-4">
            <a href="<?php echo URL_SITIO ?>"><div class=" logo_content" alt=""></div></a>
        </div>
    </div>
</div>
<br>
    <div class="gray_back col-md-12 ">
        <div class="row gray_back col-md-10 offset-md-1  ">
            <div class=" gray_back col-md-8 ">
                <div class="card">
                    <fieldset class="form-group">
                        <label for="inputAddress2"><strong>Tipo de pago</strong> </label>
                        <hr>
                        <br>                                
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="input_estado" id="inputRadioActivo" value="1" checked>
                            <label class="form-check-label" for="inputRadioActivo">
                                <img width="100px" src="<?php echo URL_SITIO ?>static/img/PayPal-Logo.png" alt=""> 
                            </label>
                        </div>
                        <br>
                        <!-- <div class="form-check">
                            <input class="form-check-input" type="radio" name="input_estado" id="inputRadioInactivo" value="0">
                            <label class="form-check-label" for="inputRadioInactivo">
                                Other
                            </label>
                        </div> -->
                    </fieldset>
                </div>
                <div style="height:20px;" class="col-md-12 gray_back"></div>
                <!-- <div class="card">
                    <div class=" form-group">
                        <label clas="row col-md-12" for="inputAddress2"><strong>Articulos no disponibles para envío</strong> </label>
                        <hr>                               
                        <div clas="row col-md-12" for=""><?php echo $destinatario[0]['NombresDestinatario']." ".$destinatario[0]['ApellidosDestinatario']  ?></div>
                        <div clas="row col-md-12" for=""><?php echo $destinatario[0]['NombreCiudad'].", ".$destinatario[0]['NombrePais'] ?></div>
                        <div clas="row col-md-12" for=""><?php echo $destinatario[0]['Direccion1'] ?></div>
                        <div clas="row col-md-12" for=""><?php echo $destinatario[0]['Direccion2'] ?></div>
                        <div clas="row col-md-12" for=""><?php echo $destinatario[0]['Telefono'] ?></div>
                    </div>
                    <a href="">Change</a>
                </div> -->
                <!-- <div style="height:20px;" class="col-md-12 gray_back"></div>
                <div class="card">
                    <div class=" form-group">
                        <label clas="row col-md-12" for="inputAddress2"><strong>Enviar a</strong> </label>
                        <hr>                               
                        <div clas="row col-md-12" for=""><?php echo $destinatario[0]['NombresDestinatario']." ".$destinatario[0]['ApellidosDestinatario']  ?></div>
                        <div clas="row col-md-12" for=""><?php echo $destinatario[0]['NombreCiudad'].", ".$destinatario[0]['NombrePais'] ?></div>
                        <div clas="row col-md-12" for=""><?php echo $destinatario[0]['Direccion1'] ?></div>
                        <div clas="row col-md-12" for=""><?php echo $destinatario[0]['Direccion2'] ?></div>
                        <div clas="row col-md-12" for=""><?php echo $destinatario[0]['Telefono'] ?></div>
                    </div>
                    <a href="">Change</a>
                </div> -->
                <div style="height:20px;" class="col-md-12 gray_back"></div>
                <div class="card">
                    <div class=" form-group">
                        <label clas="row col-md-12" for="inputAddress2"><strong>Revisar artículos</strong> </label>
                        <hr>                               
                        <?php foreach($lista_carrito as $carrito){ ?>
                            <div class="">
                            <div class="card-body">
                                
                            <div class="row">
                            <div class="col-md-4 square container temp-border"> <img class="crop col-md-12" src="<?php echo URL_SITIO.$carrito['Imagen'] ?>" alt=""> </div>
                            <div class="col-md-8 temp-border">
                                <div class="detail_up col-md-12 temp-border">
                                    <h4><?php echo $carrito['NombreProducto'] ?></h4>
                                    <div class=" text-left row">
                                        <label class="  col-md-12" for="">Tienda : <a href=""><?php echo $carrito['NombreTienda'] ?></a> </label>
                                    </div>
                                    <div class="text-left row">
                                        <label class="  col-md-12" for="">Cantidad : <?php echo $carrito['Cantidad'] ?></label>
                                    </div>
                                    <div class="text-left row">
                                        <label class="  col-md-12" for="">Precio : $ <?php echo $carrito['PrecioUnitario'] ?></label>
                                    </div>
                                </div>
                                <div class="detail_down col-md-12 temp-border">
                                    <div class="text-left row">
                                        <label class="subtotal col-md-12" for="">Subtotal : $ <?php echo $carrito['Subtotal'] ?> </label>
                                    </div>
                                    <div class="text-left row">
                                        <label class="descuento col-md-12" for="">Descuento : <?php echo (isset($carrito['DescuentoDecimal']))?"-&nbsp$ ".round((($carrito['Subtotal'])/$carrito['DescuentoDecimal']), 2):'&nbsp&nbsp N/A'?></label>
                                    </div>
                                    <?php if($configuracion[0]['CobrosPorEnvio'] == 1){ ?>
                                        <div class="text-left row">
                                            <label class="descuento col-md-12" for="">Envío : $ <?php echo (($carrito['FK_TipoPedido']==2)?$carrito['PrecioEnvio'] + obtenerPrecioEnvio($carrito['PK_Tienda'], $carrito['FK_Ciudad']):'N/A') ?></label>
                                        </div>
                                        <div class=" text-left row">
                                            <label class="total col-md-12" for="">Total : $ <?php echo round((($carrito['Subtotal']) - ((isset($carrito['DescuentoDecimal']))?(($carrito['Subtotal'])/$carrito['DescuentoDecimal']):0) + (($carrito['FK_TipoPedido']==2)?$carrito['PrecioEnvio']:0)), 2) ?> </label>
                                        </div>
                                    <?php }else{ ?>
                                        <div class=" text-left row">
                                            <label class="total col-md-12" for="">Total : $ <?php echo round((($carrito['Subtotal']) - ((isset($carrito['DescuentoDecimal']))?(($carrito['Subtotal'])/$carrito['DescuentoDecimal']):0)), 2) ?> </label>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                                   
                            </div>
                        </div>
                    </div>
                        <hr>
                    <?php }?>
                </div>
            </div>
                    <br>
                    <br>
    </div>

 <div class=" gray_back col-md-4 ">
        <div class=" card_detail">
            <form action="pago_proceso.php" method="post">
                <div class="col-md-12">
                    <label for="" class="col-md-12 lbl-detail">
                        Subtotal:
                        <span class="text-right"> $
                        <?php
                        $sub_total_todos = 0; 
                        foreach($lista_carrito as $carrito){ 
                            $sub_total_todos+= $carrito['Subtotal'];
                        } 
                        echo $sub_total_todos;
                        ?>
                        </span>
                    </label>
                    <?php if($configuracion[0]['CobrosPorEnvio'] == 1){ ?>
                        <label for="" class="col-md-12 lbl-detail">
                            Envío: 
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
                            $total_descuentos+= ($carrito['Descuento']!=0)?(($carrito['Subtotal'])/$carrito['DescuentoDecimal']):0;
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
                                $total_todos+= ($carrito['Subtotal']) - ((isset($carrito['DescuentoDecimal']))?(($carrito['Subtotal'])/$carrito['DescuentoDecimal']):0) + (($carrito['FK_TipoPedido']==2)?$carrito['PrecioEnvio']+ obtenerPrecioEnvio($carrito['PK_Tienda'], $carrito['FK_Ciudad']):0) ;
                            }else{
                                $total_todos+= ($carrito['Subtotal']) - ((isset($carrito['DescuentoDecimal']))?(($carrito['Subtotal'])/$carrito['DescuentoDecimal']):0) ;  
                            }
                        } 
                        echo round($total_todos + $comision, 2);
                        ?>
                        </span>
                    </label>
                </div>

                <input type="hidden" name="total" value="<?PHP echo $total_todos; ?>" >

            
                <!-- <input type="hidden" name="action" value="completar" >
                <button type="submit" id="paypal-button" class="btn-flat col-md-12">Pagar</button>
                <div id="paypal-button-container"></div> -->

                <div class="col-md-12" id="paypal-button"></div>




            </form>

            <form action="<?php echo URL_SITIO ?>scripts/confirmacion_pago.php" id="form-c" method="post">
                <input class="form-control" type="hidden" name="PamentID" id="IDPago">
                <input class="form-control" type="hidden" name="cid" id="cid">
                <input class="form-control" type="hidden" name="tc" id="tc">
            </form>



            
        </div>
    </div>  
    
   


        </div>
    </div>

<br>
<br>
<?php require ('footer.php') ?>
    
</body>
</html>

<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<script>



<?php
   
    $total_todos = 0; 
    $total_envio = 0; 
    $subtotal_todos = 0;
    foreach($lista_carrito as $carrito){ 
        // calculo subtotal todos 
        $subtotal_todos+= $carrito['Subtotal'] - (($carrito['DescuentoDecimal']!=0)?(($carrito['Subtotal'])/$carrito['DescuentoDecimal']):0) ;

        // calculo total
        if($configuracion[0]['CobrosPorEnvio'] == 1){ 
            $total_todos+= ($carrito['Subtotal']) - (($carrito['DescuentoDecimal']!=0)?(($carrito['Subtotal'])/$carrito['DescuentoDecimal']):0) + (($carrito['FK_TipoPedido']==2)?$carrito['PrecioEnvio']+ obtenerPrecioEnvio($carrito['PK_Tienda'], $carrito['FK_Ciudad']):0) ;
        }else{
            $total_todos+= ($carrito['Subtotal']) - (($carrito['DescuentoDecimal']!=0)?(($carrito['Subtotal'])/$carrito['DescuentoDecimal']):0);
        }
        // calculo total envios
        if($configuracion[0]['CobrosPorEnvio'] == 1){
            $total_envio+= ($carrito['FK_TipoPedido']==2)?$carrito['PrecioEnvio'] + obtenerPrecioEnvio($carrito['PK_Tienda'], $carrito['FK_Ciudad']):0;
        }else{
            $total_envio+= 0;
        }
    } 
?>    

paypal.Button.render({
  env: '<?php echo PayPalENV; ?>',
  client: {
	<?php if(ProPayPal) { ?>  
	production: '<?php echo PayPalClientId ?>'
	<?php } else { ?>
	sandbox: '<?php echo PayPalClientId ?>'
	<?php } ?>	
  },
  style: {
    size: 'responsive',
    color: 'gold',
    shape: 'pill',
    label: 'checkout',
    tagline: 'true',
    fundingicons: 'true'
    },
  payment: function (data, actions) {
	return actions.payment.create({
	  transactions: [{

		amount: {
		    total: <?php echo $total_todos + $comision ?>,
		    currency: 'USD',
            details: {
                subtotal: <?php echo $subtotal_todos + $comision?>,
                shipping: <?php echo $total_envio ?>,
        }
		},
        description: 'Pago de carrito de compras de Shoppingapp',
        item_list: {
        items: [
            {
                name: 'Comisión',
                quantity: 1,
                price: <?php echo $comision ?>,
                currency: 'USD'
            },
        <?php $cont=0; foreach($lista_carrito as $carrito){ ?>    
            {
                name: '<?php echo $carrito['NombreProducto'] ?>',
                quantity: <?php echo $carrito['Cantidad'] ?>,
                price: <?php echo $carrito['PrecioUnitario'] - ((isset($carrito['Descuento']))?(($carrito['PrecioUnitario'])/$carrito['DescuentoDecimal']):0)?>,
                <?php if($configuracion[0]['CobrosPorEnvio'] == 1){ ?>
                shipping: <?php echo ($carrito['FK_TipoPedido']==2)?$carrito['PrecioEnvio'] + obtenerPrecioEnvio($carrito['PK_Tienda'], $carrito['FK_Ciudad']):0 ?>,
                <?php }else{ ?>
                shipping: 0,
                <?php } ?>
                currency: 'USD'
                <?php $cont += 1; ?>
            } <?php echo (count($lista_carrito) > $cont)?',':''; ?>
        <?php } ?>
        ]
        }
        
        }],

	});
  },
  onAuthorize: function (data, actions) {
	return actions.payment.execute()
	  .then(function () {
        console.log(data);  
        
        $('#IDPago').val(data.paymentID);
        $('#cid').val('<?php echo $lista_carrito[0]['FK_Cliente']?>');
        $('#tc').val('car');

        $("#form-c").submit();
         
	  });
  }

}, '#paypal-button');



  
</script>

