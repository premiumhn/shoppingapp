<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include '../global/config.php';
include '../global/conexion.php';
include '../global/config_paypal.php';
include '../global/const.php';

session_start();

require ('../scripts/comprobaciones.php');

$cantidad = (isset($_POST['input_cantidad'])) ? $_POST['input_cantidad'] : "";
$pk_producto = (isset($_POST['PK_Producto'])) ? $_POST['PK_Producto'] : "";
$talla = (isset($_POST['input_talla'])) ? $_POST['input_talla'] : "";
$color = (isset($_POST['input_color'])) ? $_POST['input_color'] : "";
// $fecha = new Datetime();
// $fecha->format('Y-m-d\TH:i:s.u');
// $fecha_hora_agregado = date('Y-m-d H:i:s');
$adomicilio = (isset($_POST['input_adomicilio'])) ? $_POST['input_adomicilio'] : "";
$destinatario = (isset($_POST['input_destinatario'])) ? $_POST['input_destinatario'] : "";


 
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

  //Consulta seleccionar producto
  $select_producto = $pdo->prepare("SELECT 
                                c.PK_Pago,  
                                 p.NombreProducto, 
                                c.Cantidad, 
                                p.PrecioUnitario, 
                                p.Imagen, 
                                (SELECT Color FROM Colores WHERE PK_Color = c.FK_Talla) as 'Color',
                                (SELECT Talla FROM Tallas WHERE PK_Talla = c.FK_Talla) as 'Talla', 
                                p.Descuento, 
                                (p.PrecioUnitario * c.Cantidad) as 'Subtotal', 
                                (CAST(p.Descuento as DECIMAL(20,0)) ) as DescuentoDecimal, 
                                ti.NombreTienda,
                                p.PrecioEnvio,
                                c.FK_TipoPedido,
                                ti.IDClientePaypal,
                                c.FK_Cliente
                                FROM Pago_solouno_temp c INNER JOIN Productos p
                                ON c.FK_Producto = p.PK_Producto INNER JOIN Clientes cli
                                ON c.FK_Cliente = cli.PK_Cliente INNER JOIN Usuarios u
                                ON cli.FK_Usuario = u.PK_Usuario INNER JOIN Tiendas ti
                                ON p.FK_Tienda = ti.PK_Tienda
                                WHERE c.PK_Pago =  :PK_Pago");
$select_producto->bindParam(':PK_Pago', $_REQUEST['p']);                           
$select_producto->execute();
$producto = $select_producto->fetchAll(PDO::FETCH_ASSOC);


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



     <br>
<div class="col-md-12 ">
    <div class="row col-md-10 offset-md-1  ">
        <div class="row col-md-4">
            <a href="./home.php"><div class=" logo_content" alt=""></div></a>
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
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="input_estado" id="inputRadioInactivo" value="0">
                            <label class="form-check-label" for="inputRadioInactivo">
                                Other
                            </label>
                        </div>
                    </fieldset>
                </div>
                <div style="height:20px;" class="col-md-12 gray_back"></div>

                <?php if($producto[0]['FK_TipoPedido'] == 2){ ?>
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
                        <a href="">Cambiar</a>
                    </div>
                <?php } ?>
                
                <div style="height:20px;" class="col-md-12 gray_back"></div>
                <div class="card">
                    <div class=" form-group">
                        <label clas="row col-md-12" for="inputAddress2"><strong>Revisar articulo</strong> </label>
                        <hr>                               
                       
                            <div class="card">
                            <div class="card-body">
                                
                            <div class="row">
                            <div class="col-md-4 square container temp-border"> <img class="crop col-md-12" src="<?php echo URL_SITIO.$producto[0]['Imagen']?>" alt=""> </div>
                            <div class="col-md-8 temp-border">
                                <div class="detail_up col-md-12 temp-border">
                                    <h4><?php echo $producto[0]['NombreProducto'] ?></h4>
                                    <div class=" text-left row">
                                        <label class="  col-md-12" for="">Tienda : <a href=""><?php echo $producto[0]['NombreTienda'] ?></a> </label>
                                    </div>
                                    <div class="text-left row">
                                        <label class="  col-md-12" for="">Cantidad : <?php echo $producto[0]['Cantidad'] ?></label>
                                    </div>
                                    <div class="text-left row">
                                        <label class="  col-md-12" for="">Precio : $ <?php echo $producto[0]['PrecioUnitario'] ?></label>
                                    </div>
                                </div>
                                <div class="detail_down col-md-12 temp-border">
                                    <div class="text-left row">
                                        <label class="subtotal col-md-12" for="">Subtotal : $ <?php echo $producto[0]['Cantidad'] * $producto[0]['PrecioUnitario'] ?> </label>
                                    </div>
                                    <div class="text-left row">
                                        <label class="descuento col-md-12" for="">Descuento : <?php echo (isset($producto[0]['DescuentoDecimal']))?"-&nbsp$ ".(($producto[0]['Subtotal'])/$producto[0]['DescuentoDecimal']):'&nbsp&nbsp N/A'?></label>
                                    </div>
                                    <div class=" text-left row">
                                        <label class="total col-md-12" for="">Total : $ <?php echo ($producto[0]['Subtotal']) - ((isset($producto[0]['DescuentoDecimal']))?(($producto[0]['Subtotal'])/$producto[0]['DescuentoDecimal']):0) + (($producto[0]['FK_TipoPedido']==2)?$producto[0]['PrecioEnvio']:0) ?> </label>
                                    </div>
                                </div>
                            </div>
                                   
                            </div>
                        </div>
                    </div>
                        <hr>
                </div>
            </div>
                    <br>
                    <br>
    </div>

 <div class=" gray_back col-md-4 ">
        <div class=" card_detail">
            <form action="" method="post">
                <div class="col-md-12">
                    <label for="" class="col-md-12 lbl-detail">
                        Subtotal:
                        <span class="text-right"> $
                        <?php
                            echo $producto[0]['Subtotal'];
                        ?>
                        </span>
                    </label>

                    <?php if($producto[0]['FK_TipoPedido'] == 2){ ?>
                        <label for="" class="col-md-12 lbl-detail">
                            Envío: 
                            <span class="text-right"> $
                            <?php
                                echo ($producto[0]['FK_TipoPedido']==2)?$producto[0]['PrecioEnvio']:0;
                            ?>
                            </span>
                        </label>
                    <?php } ?>

                    <label for="" class="col-md-12 lbl-detail">
                        Descuento: - 
                        <span class="text-right"> $
                        <?php
                            echo ($producto[0]['DescuentoDecimal']!=0)? ($producto[0]['Subtotal']/$producto[0]['DescuentoDecimal']) : 0;
                        ?>
                        </span>
                    </label>
                    <hr>
                    <label for="" class=" total col-md-12 Total">
                        Total:
                        <span class="text-right"> $
                        <?php
                            echo ($producto[0]['Subtotal']) - ((isset($producto[0]['DescuentoDecimal']))?(($producto[0]['Subtotal'])/$producto[0]['DescuentoDecimal']):0) + (($producto[0]['FK_TipoPedido']==2)?$producto[0]['PrecioEnvio']:0);
                        ?>
                        </span>
                    </label>
                </div>

            
                <div class="col-md-12" id="paypal-button"></div>
            </form>
        </div>
    </div>  

            <form action="<?php echo URL_SITIO ?>scripts/confirmacion_pago.php" id="form-c" method="post">
                <input class="form-control" type="hidden" name="PamentID" id="IDPago">
                <input class="form-control" type="hidden" name="cid" id="cid">
                <input class="form-control" type="hidden" name="pkp" id="pkp">
                <input class="form-control" type="hidden" name="tc" id="tc">
            </form>
        </div>
    </div>

<br>
<br>
<?php require ('footer.php') ?>
    
</body>
</html>


<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<script>


paypal.Button.render({
  env: '<?php echo PayPalENV; ?>',
  client: {
	<?php if(ProPayPal) { ?>  
	production: '<?php echo $producto[0]['IDClientePaypal']; ?>'
	<?php } else { ?>
	sandbox: '<?php echo $producto[0]['IDClientePaypal']; ?>'
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
		    total: <?php echo ($producto[0]['Subtotal']) - ((isset($producto[0]['DescuentoDecimal']))?(($producto[0]['Subtotal'])/$producto[0]['DescuentoDecimal']):0) + (($producto[0]['FK_TipoPedido']==2)?$producto[0]['PrecioEnvio']:0) ?>,
		    currency: 'USD',
            details: {
                subtotal: <?php echo $producto[0]['Subtotal'] - (($producto[0]['DescuentoDecimal']!=0)?(($producto[0]['Subtotal'])/$producto[0]['DescuentoDecimal']):0) ?>,
                shipping: <?php echo ($producto[0]['FK_TipoPedido']==2)?$producto[0]['PrecioEnvio']:0; ?>,
        }
		},
        description: 'Pago de carrito de compras de Shoppingapp',
        item_list: {
        items: [  
            {
                name: '<?php echo $producto[0]['NombreProducto'] ?>',
                quantity: <?php echo $producto[0]['Cantidad'] ?>,
                price: <?php echo $producto[0]['PrecioUnitario'] - ((isset($producto[0]['Descuento']))?(($producto[0]['PrecioUnitario'])/$producto[0]['DescuentoDecimal']):0)?>,
                shipping: <?php echo ($producto[0]['FK_TipoPedido']==2)?$producto[0]['PrecioEnvio']:0 ?>,
                
                currency: 'USD'
            } 
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
        $('#cid').val('<?php echo $producto[0]['FK_Cliente']?>');
        $('#pkp').val('<?php echo $_REQUEST['p']?>');
        $('#tc').val('s1');

        $("#form-c").submit();

	  });
  }

}, '#paypal-button');
</script>
