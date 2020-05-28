<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include '../global/config.php';
include '../global/conexion.php';
include '../global/const.php';

session_start();

require ('../scripts/comprobaciones.php');

$pk_producto = $_GET['producto'];

// select tallas disponibles
$select_tallas = $pdo->prepare("SELECT t.PK_Talla, t.Talla FROM Productos p INNER JOIN DetalleProducto dp 
                                ON p.PK_Producto = dp.FK_Producto INNER JOIN Tallas t
                                ON t.PK_Talla = dp.FK_Talla
                                WHERE p.PK_Producto = :PK_Producto");
$select_tallas->bindParam(':PK_Producto', $pk_producto);
$select_tallas->execute();
$tallas = $select_tallas->fetchAll(PDO::FETCH_ASSOC);


// Select colores disponibles
$select_colores = $pdo->prepare("SELECT c.PK_Color, c.Color FROM Productos p INNER JOIN DetalleProducto dp 
                                ON p.PK_Producto = dp.FK_Producto INNER JOIN Colores c
                                ON c.PK_Color = dp.FK_Color
                                WHERE p.PK_Producto = :PK_Producto");
$select_colores->bindParam(':PK_Producto', $pk_producto);
$select_colores->execute();
$colores = $select_colores->fetchAll(PDO::FETCH_ASSOC);


$select_producto = $pdo->prepare("SELECT p.Ranking, p.Adomicilio as 'pAdomicilio', p.PK_Producto, p.NombreProducto, t.NombreTienda, p.PrecioUnitario, p.Descuento, t.Adomicilio, p.PrecioEnvio, p.UnidadesDisponibles, p.Imagen, c.NombreCategoria, p.Descripcion FROM 
                                Productos p INNER JOIN Tiendas t 
                                ON p.FK_Tienda = t.PK_Tienda INNER JOIN Categorias c
                                ON c.PK_Categoria = p.FK_Categoria
                                WHERE p.PK_Producto = :PK_Producto");
$select_producto->bindParam(':PK_Producto', $pk_producto);
$select_producto->execute();
$productos = $select_producto->fetchAll(PDO::FETCH_ASSOC);

$select_destinatarios = $pdo->prepare("SELECT  d.PK_Destinatario, u.PK_Usuario, d.NombresDestinatario, d.ApellidosDestinatario, d.Telefono, d.Departamento, d.Direccion1, d.Direccion2, d.CodigoPostal, ciu.NombreCiudad, p.NombrePais
                                    FROM Destinatarios d INNER JOIN Clientes cli
                                    ON d.FK_Cliente = cli.PK_Cliente INNER JOIN Usuarios u
                                    ON u.PK_Usuario = cli.FK_Usuario INNER JOIN Ciudades ciu
                                    ON ciu.PK_Ciudad = d.FK_Ciudad INNER JOIN RegionesEnvio re
                                    ON re.FK_Ciudad = ciu.PK_Ciudad INNER JOIN Tiendas ti
                                    ON ti.PK_Tienda = re.FK_Tienda INNER JOIN Productos pro
                                    ON pro.FK_Tienda = ti.PK_Tienda INNER JOIN Paises p
                                    ON p.PK_Pais = ciu.FK_Pais
                                    WHERE pro.PK_Producto = :PK_Producto and u.PK_Usuario = :PK_Usuario
                                    GROUP BY d.PK_Destinatario");
$select_destinatarios->bindParam(':PK_Producto', $pk_producto);
$select_destinatarios->bindParam(':PK_Usuario', $_SESSION['login_user']);
$select_destinatarios->execute();
$destinatarios = $select_destinatarios->fetchAll(PDO::FETCH_ASSOC);




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" id="hl-viewport" content="width=device-width, initial-scale=1, user-scalable=yes, minimum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Shoppingapp</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="<?php URL_SITIO ?>static/css/styles.css" rel="stylesheet" type="text/css" media="all" />
    <link href="<?php URL_SITIO ?>static/css/detalle_producto.css" rel="stylesheet" type="text/css" media="all" />
    <link href="<?php URL_SITIO ?>static/css/toasts.css" rel="stylesheet" type="text/css" media="all" />
    
    <script src="<?php URL_SITIO ?>static/js/jquery-3.5.0.min.js" ></script>
	<script src="https://kit.fontawesome.com/b2dbb6a24d.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
   
    <?php include 'iconos.php' ?>
</head>
<body>
   
   

<?php include '../templates/header.php'; ?>

<!-- DIV temporal -->
<div style="" class="text-center">
<div style="font-size:15px;color:gray;" class="alert text-left alert-secondary"><?php echo $productos[0]['NombreTienda'] ?> / <?php echo $productos[0]['NombreCategoria'] ?> / <?php echo $productos[0]['NombreProducto'] ?> </div>

    <div class="no-padding-both no_padding_both container">
  
        <br>
        <div class="row">
            <div class="col-md-5 bordered">
                <div class="col-md-12" style="content:url('<?php echo URL_SITIO.$productos[0]['Imagen'] ?>')" ></div>
            </div>
            <div class="col-md-7 bordered">
            <form id="form_detalle_producto" action="<?php echo URL_SITIO ?>scripts/detalle_producto.php" method="post" enctype="multipart/form-data">
                <div clas="row">
                    <h4 class="nombre_producto"><?php echo $productos[0]['NombreProducto'] ?></h4>
                </div>
                <div class="">
                    <div id="form" class="col-md-12">
                        <p class="valoracion">
                            <?php 
                            $cont = 1;
                            $ranking = $productos[0]['Ranking'];

                            
                            for($i = 1; $i <= 5; $i++){ 
                                if($cont <= $ranking){
                                ?>
                                    <label for="radio1" class="orange">★</label>
                                <?php 
                                $cont+=1;
                                }else{?>
                                    <label for="radio1" class="">★</label>
                                <?php }
                                
                            } ?>


                        <span class="col-md-12" for=""> Valoración del artículo</span>
                        </p>
                    </div>
                </div>
                <hr>
                <div class="row col-md-12">
                    <label for="col-md-12">Tienda: <a href=""> <?php echo $productos[0]['NombreTienda'] ?></a> </label>
                </div>
                <div class="row col-md-12">
                    <div class="link no_padding_left right-border"> <a href="">Contactar tienda</a> </div>
                    <div class="link right-border"> <a href=""> Visitar tienda </a> </div>
                    <div class="link right-border"> <a href=""> Visitar sitio web de la tienda </a> </div>
                </div>
                <br>
                <div class="detail-cont row col-md-12">
                    <div class=" col-md-12 no_padding_both text-left">
                        <h4 class="precio">$  <?php echo $productos[0]['PrecioUnitario'] ?></h4>
                    </div>
                    <div class="col-md-12 no_padding_both text-left">
                        <label for="">Descuento <label class="text-bold" for="">- <?php echo $productos[0]['Descuento'] ?>%</label></label>
                    </div>
                </div>
                <hr>
                <br>
                <div class="detail-cont row col-md-12">
                    <?php if(count($tallas)>0){ ?>
                        <div class=" col-md-4 no_padding_both text-left">
                            <div class=" no_padding_both form-group col-md-12">
                                <label for="inputTalla">Talla: <span class="text_required">*</span> </label>
                                <select  id="inputTalla" name="input_talla" class="form-control">
                                    <option selected>- Seleccione -</option>
                                    <?php
                                        foreach($tallas as $talla){
                                            echo "<option value='". $talla['PK_Talla'] ."' >".$talla['Talla']."</option>";
                                        }
                                        ?>
                                </select>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group col-md-4 text-left">
                        <label for="inputCantidad">Cantidad:</label><label class="text-bold unidades_disponibles" for="" >(<?php echo $productos[0]['UnidadesDisponibles'] ?> disponibles)</label>
                        <input class="col-md-12 frm_ctrl form-control" type="number" id="inputCantidad" name="input_cantidad" min="1" max="<?php echo $productos[0]['UnidadesDisponibles'] ?>">
                    </div>
                    <?php if(count($colores)>0){ ?>
                        <div class="form-group col-md-4 no_padding_both  text-left">
                            <label for="" >Color: </label>
                            <select  id="inputColor" name="input_color" class="form-control">
                                    <option selected>- Seleccione -</option>
                                    <?php
                                        foreach($colores as $color){
                                            echo "<option value='". $color['PK_Color'] ."' >".$color['Color']."</option>";
                                        }
                                    ?>
                            </select>
                        </div>
                    <?php } ?>
                </div>

                <fieldset class="text-left form-group">
                    <div class="form-check" id="contInputOnShop">
                        <input class="form-check-input" type="radio" name="input_adomicilio" id="inputRadioOnShop" value="1" checked>
                        <label class="inputRadioOnShop form-check-label" for="inputRadioOnShop">
                            En tienda
                        </label>
                    </div>
                    <?php if($productos[0]['pAdomicilio'] == 1){ ?>
                    <div class="form-check" id="contInputHomeSi">
                        <input class="form-check-input" type="radio" name="input_adomicilio" id="inputHomeSi" value="2">
                        <label class="form-check-label" for="inputHomeSi">
                            A domicilio
                        </label>
                    </div>
                    <?php }else{?>
                        <div class="form-check" id="contInputHomeNo">
                            <input class="form-check-input" type="radio" disabled name="input_adomicilio" id="inputHomeNo" value="2">
                            <label class="form-check-label" for="inputHomeNo">
                             A domicilio
                            </label>
                        </div>
                    <?php } ?>
                </fieldset>
               
                <br>
                <div class="col-md-12 card card-shipment">
                <?php if($productos[0]['pAdomicilio'] == 1){ ?>
                        <div class="detail-cont row col-md-12">
                            <div class=" col-md-4 no_padding_both text-left" id="box_destinatario">
                                <div class=" no_padding_both form-group col-md-12">
                                    <label for="">Destinatario:</label>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">Seleccionar</button>
                                    <label for="" id="inputDestinatario" class="output"></label>

                                    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class=" md-content col-md-12">
                                                <label for=""><strong>Seleccione el destinatario</strong></label>
                                            <fieldset class="text-left form-group">
                                                <?php 
                                                foreach($destinatarios as $destinatario){
                                                   
                                                    ?>
                                                    <br>
                                                    <hr>
                                                <div class="form-check">
                                                    <input style="vertial-align:middle" class="form-check-input" type="radio" name="input_destinatario" id="inputDestinatario_<?PHP echo $destinatario['PK_Destinatario'] ?>" value="<?PHP echo $destinatario['PK_Destinatario'] ?>">
                                                    <label class="form-check-label" for="inputDestinatario_<?PHP echo $destinatario['PK_Destinatario'] ?>">
                                                        <div clas="row col-md-12" for=""><?php echo $destinatario['NombresDestinatario']." ".$destinatario['ApellidosDestinatario']  ?></div>
                                                        <div clas="row col-md-12" for=""><?php echo $destinatario['NombreCiudad'].", ".$destinatario['NombrePais'] ?></div>
                                                        <div clas="row col-md-12" for=""><?php echo $destinatario['Direccion1'] ?></div>
                                                        <div clas="row col-md-12" for=""><?php echo $destinatario['Direccion2'] ?></div>
                                                        <div clas="row col-md-12" for=""><?php echo $destinatario['Telefono'] ?></div>
                                                    </label>
                                                </div>
                                                <?php 
                                                        //} 
                                                    } 
                                                  ?>
                                            </fieldset>
                                            <!-- <button id="btn_aceptar" type="button" data-dismiss="modal" class="close btn btn-primary">Aceptar</button> -->
                                            
                                             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">Aceptar</span>
                                            </button>
                                        </div>
                                            
                                        </div>
                                    </div>
                                    </div>
                                    <!-- <select  id="inputTalla" name="input_talla" class="form-control">
                                        <option selected>- Seleccione -</option>
                                        <?php
                                            foreach($tallas as $talla){
                                                echo "<option value='". $talla['PK_Talla'] ."' >".$talla['Talla']."</option>";
                                            }
                                            ?>
                                    </select> -->
                                </div>
                            </div>
                        <label for="" class="text-left text-bold col-md-12 text_envios">Se hacen envíos a domicilio</label>
                        <label for='' class='text-left col-md-12'>Precio del envío: $ <?php echo $productos[0]['PrecioEnvio']?></label>
                    <?php }else{ ?>
                        <label for="" class="text-left text-bold col-md-12">No se hacen envíos</label>
                    
                    <?php } ?>
                </div>
                <br>
                <div class="row col-md-12">
                    <input type="hidden" name="PK_Producto" id="PK_Producto" value="<?php echo $productos[0]['PK_Producto'] ?>">
                    <div class="col-md-4">
                        <button value="comprar" name="action" type="" id="btn-buy" class="btn col-md-12">Comprar</button>
                    </div>
                    <div class="col-md-4">
                        <button value="agregar_carrito"  name="action"  type="" id="btn-add-to-cart" class="btn col-md-12">Agregar al carrito</button>
                    </div>
                    <div class="carrito-btn col-md-4 text-left">
                         <a style="text-decoration:none;font-size:20px;" href="<?php echo URL_SITIO ?>Carrito"> <li class="fa fa-shopping-cart"></li> Carrito</a>                   
                    </div>
                </div>
                <br>
                <br>
            </form>
            <!-- <form id="form_solouno" action="./pago_solouno.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="PK_Producto" id="PK_Producto" value="<?php echo $productos[0]['PK_Producto'] ?>">
            </form> -->

            
            </div>
        </div>
        <br>
        <br>

    </div>
        <div class="container cont-descripcion white-back internal-padding text-left ">
            <h4 class="col-md-12">Descripcción del artículo</h4>
            <br>
            <textarea class="text-descripccion" disabled class="col-md-12">
                <?php echo $productos[0]['Descripcion'] ?>
            </textarea>
        </div>
    </div>
    
    <br>
    <br>
        <?php include '../templates/footer.php'; ?>
</div>


<div>

</div>

<div role="alert" data-delay="5000" aria-live="assertive" aria-atomic="true" id="toast_mensaje" class="toast" data-autohide="true">
        <div class="toast-body">
        </div>
</div>
</body>
</html>

<script type="text/javascript">
$('#box_destinatario').hide();

$('#lbl-carrito').hide();

    $('#btn-buy').click(function(e){
        var unidades_disponibles;
        $.ajax({
                type:"POST",
                async: false,
                url:"<?php echo URL_SITIO ?>scripts/datos_ajax.php",
                data: {"request" : "unidadesDisponibles", 
                        "PK_Producto" : <?php echo $productos[0]['PK_Producto'] ?>},
                success:function(r){
                    unidades_disponibles = r;
                }
        });

       
        var  solicita=parseInt($('#inputCantidad').val());
        if(solicita > parseInt(unidades_disponibles)){
            e.preventDefault();
            var pide=$('#inputCantidad').val();
            $('.toast-body').html('No hay suficientes unidades dispobibles de este producto: '+unidades_disponibles);
            $('#toast_mensaje').toast('show');
        }

    });

    $('#btn-add-to-cart').click(function(e){
        var unidades_disponibles;
        $.ajax({
                type:"POST",
                async: false,
                url:"<?php echo URL_SITIO ?>scripts/datos_ajax.php",
                data: {"request" : "unidadesDisponibles", 
                        "PK_Producto" : <?php echo $productos[0]['PK_Producto'] ?>},
                success:function(r){
                    unidades_disponibles = r;
                }
        });

        if($('#inputCantidad').val() > unidades_disponibles){
            e.preventDefault();
            $('.toast-body').html('No hay suficientes unidades dispobibles de este producto');
            $('#toast_mensaje').toast('show');
        }
        
    });

	$(document).ready(function(){

		// $('#btn-buy').on('click', function (event) {
        //     event.preventDefault();
           
        //         url = 'pago_solouno.php?p=' + '<?php echo $productos[0]['PK_Producto'] ?>' 
        //                                     + '&t=' + $('#inputTalla').val() 
        //                                     + '&co=' + $('#inputColor').val()
        //                                     + '&ca=' + $('#inputCantidad').val()
        //                                     + '&d=' + $('#inputDestinatario').val();
        //         window.location = url;

             
        // });

      

        $('#inputCantidad').val('1');
        $('#inputTalla').val('1');
        $('#inputColor').val('1');

		// $('#btn-add-to-cart').on('click', function (event) {
        //     event.preventDefault();

        //     submitForm($('#form_detalle_producto'), '<?php echo URL_SITIO ?>scripts/detalle_producto.php'))
            
        // });

        $('#contInputHomeNo').on('click', function(event){
            toast('No se hacen envíos a domicilio para este producto');
        });

        $('#inputHomeSi').on('click', function(event){
            $('#box_destinatario').show();
        });

         $('#inputRadioOnShop').on('click', function(event){
            $('#box_destinatario').hide();
        });

        $('#btn_aceptar').on('click', function(event){
            $('#box_destinatario').hide();
        });

        // Check the radio button value. 
        $('.close').on('click', function() { 
            output =  
              $('input[name=input_destinatario]:checked', 
                '#form_detalle_producto').val(); 

            var nombre_destinatario;
            $.ajax({
                    type:"POST",
                    async: false,
                    url:"<?php echo URL_SITIO ?>scripts/datos_ajax.php",
                    data: {"request" : "obtenerNombreDestinatario", 
                            "PK_Destinatario" : output},
                    success:function(r){
                        nombre_destinatario = r;
                    }
            });

            document.querySelector( 
              '.output').textContent ='Destinatario seleccionado: ' + nombre_destinatario; 
        }); 
        


        function toast(msj){
            $('.toast-body').html(msj);
            $('#toast_mensaje').toast('show');
        }


        function submitForm(form, p_url){
            var url = p_url;
            var formData = $(form).serializeArray();
            formData.push({'name':'action','value': 'agregar_carrito'})
            $.post(url, formData).done(function (data) {
                console.log(url);
                console.log(formData);
                console.log(data);
            });
        }

    });

  
</script>