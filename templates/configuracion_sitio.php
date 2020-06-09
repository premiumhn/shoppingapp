<?php include 'header_admin.php' ?> 
<?php 
error_reporting(E_ALL);
ini_set('display_errors', '1');
	//include '../scripts/comprobaciones.php';


require ('../scripts/comprobaciones.php'); 

$select_id = $pdo->prepare('SELECT * FROM Configuracion');
$select_id->execute();
$datos = $select_id->fetchAll(PDO::FETCH_ASSOC);

?>

<link href="<?php echo URL_SITIO ?>static/css/configuracion_sitio.css"rel="stylesheet">



<div class="contenedor row col-md-12">
<div class="alert alert-success col-md-12"></div>
    <div class="card col-md-12">

           <form id="form-edit" action="<?php echo URL_SITIO?>scripts/configuracion_sitio.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <br>
                    <label for="">ID de cliente paypal</label>
                    <a href="" class="btn btn-edit btn-primary"> <i class="fa fa-edit"></i> Editar</a>
                    <input class="on_view form-control" disabled value="<?php echo $datos[0]['IDClientePaypal'] ?>" >
                    <input type="text" id="inputIdClientePaypal" value="<?php echo $datos[0]['IDClientePaypal'] ?>" name="input_idClientePaypal" class="form-control on_edit">
                    <input type="hidden" name="action" value="editar">
                </div>
                <br>
                <div class="form-group">
                    <label for="">Comisión</label>
                    <div class="row col-md-12">
                        <label class="on_view" style="padding-top: 5px;margin:0px 5px 0px 0px;" for="">$</label>
                        <input style="width: 100px;" class="on_view form-control" disabled value=" <?php echo $datos[0]['Comision'] ?>" > 
                        <label class="on_view" style="padding-top: 5px;margin:0px 10px 0px 10px;" for="">Por Cada</label>
                        <label class="on_view" style="padding-top: 5px;margin:0px 5px 0px 0px;" for="">$</label>
                        <input style="width: 100px;" class="on_view form-control" disabled value=" <?php echo $datos[0]['PorCada'] ?>" > 
                        
                        <label class="on_edit" style="padding-top: 5px;margin:0px 5px 0px 0px;" for="">$</label>
                        <input style="width: 100px;" class="on_edit form-control"  value=" <?php echo $datos[0]['Comision'] ?>" name="input_comision"> 
                        <label class="on_edit" style="padding-top: 5px;margin:0px 10px 0px 10px;" for="">Por Cada</label>
                        <label class="on_edit" style="padding-top: 5px;margin:0px 5px 0px 0px;" for="">$</label>
                        <input style="width: 100px;" class="on_edit form-control"  value=" <?php echo $datos[0]['PorCada'] ?>" name="input_porcada"> 
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <label for="">Cobros por envío</label>
                    <div id="cobrosPorEnvio" WIDTH="10%"><?php echo ($datos[0]['CobrosPorEnvio']==1)?'<label class="switch">
                                                                                                    <input onClick="cambiarEstadoCobros()" class="check" type="checkbox" checked>
                                                                                                    <span class="slider round"></span>
                                                                                                </label>':
                                                                                                '<label class="switch">
                                                                                                    <input onClick="cambiarEstadoCobros()" class="check" type="checkbox">
                                                                                                    <span class="slider round"></span>
                                                                                                </label>'; ?></div>
                </div>
                <br>
                <div class="form-group">
                    <label for="">Fondo del login de tiendas</label>
                    <div class="custom-file on_edit">
                        <input type="file" accept="image/*" class="custom-file-input" id="inputFondoTienda" name="input_fondoTienda" >
                        <label class="custom-file-label" data-browse="Elegir" for="customFile">Seleccione</label>
                    </div>
                    <div class="col-md-6 containerPor cont_tienda">
                        <img class="crop img_por" src="<?php echo URL_SITIO ?>uploads/img/configuracion/<?php echo $datos[0]['FondoLoginTienda'] ?>" />
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <label for="">Fondo del login de clientes</label>
                    <div class="custom-file on_edit">
                        <input type="file" accept="image/*" class="custom-file-input" id="inputFondoCliente" name="input_fondoCliente">
                        <label class="custom-file-label" data-browse="Elegir" for="customFile">Seleccione</label>
                    </div>
                    <div class="col-md-6 containerPor cont_cliente">
                        <img class="crop img_por" src="<?php echo URL_SITIO ?>uploads/img/configuracion/<?php echo $datos[0]['FondoLoginCliente'] ?>" />
                    </div>
                </div>

                <div class="text-center col-md-12">
                    <br>
                    <button id="btnEditar"  class="on_edit btn btn-primary col-md-5">Editar</button>&nbsp&nbsp&nbsp
                    <a id="btnCancelar" href="Admin"  class="on_edit btn btn-secondary col-md-5">Cancelar</a>
                </div>
                
            </form>
    </div>
</div>




<script type="text/javascript">
$('.alert-success').hide();
$('.btn-toolbar').hide();

    <?php if(isset($_REQUEST['msj'])){ ?>
        <?php if($_REQUEST['msj'] == 'editado'){ ?> 
            $('.alert-success').html('Configuración actualizada');
            $('.alert-success').show('slow');
        <?php } ?>
    <?php } ?>

    $('.h2-name').html('Configuración del sitio');
    $('#titulo_pagina').html('Shoppingapp | Configuración del sitio');

    

   $('.on_edit').hide();

   $('.btn-edit').click(function(e){
       e.preventDefault();
        $('.on_view').hide();
        $('.on_edit').show();
        $('.btn-edit').hide();
   });

   $('#btnCancelar').click(function(e){
       e.preventDefault();
        $('.on_view').show();
        $('.on_edit').hide();
        $('.btn-edit').show();
   });

   $('.form-control').keypress(function(e){
    $('.alert-success').hide('slow');
   });

   $('#inputFondoTienda').bind('change', function() {
            var peso = this.files[0].size/1024/1024;
            if(peso > 5){
                $('.alert-success').html('Imagen demasiado pesada, debe pesar menos de 5 MB');
                $('.alert-success').show('slow');
                $('#inputFondoTienda').val("");
                $('html, body').animate({scrollTop:0}, 'slow');
            };
    });
    $('#inputFondoCliente').bind('change', function() {
            var peso = this.files[0].size/1024/1024;
            if(peso > 5){
                $('.alert-success').html('Imagen demasiado pesada, debe pesar menos de 5 MB');
                $('.alert-success').show('slow');
                $('#inputFondoCliente').val("");
                $('html, body').animate({scrollTop:0}, 'slow');
            };
    });

    function vistaPrevia(input){
        if(input.files && input.files[0]){
            var reader = new FileReader();
            
           
            reader.onload = function(e){
                $('.cont_tienda').html("<img style='width:100%' id='showImagen' src='"+ e.target.result +"' >");
                console.log(e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#inputFondoTienda').change(function(){
        vistaPrevia(this);
    });

    function vistaPreviaC(input){
        if(input.files && input.files[0]){
            var reader = new FileReader();
            
           
            reader.onload = function(e){
                $('.cont_cliente').html("<img style='width:100%' id='showImagen' src='"+ e.target.result +"' >");
                console.log(e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    function cambiarEstadoCobros(){
         // activar o desactivar usuario
         var response;
            $.ajax({
                    type:"POST",
                    async: false,
                    url:"<?php echo URL_SITIO?>scripts/datos_ajax.php",
                    data: {"request" : "cambiarEstadoCobrosEnvio"},
                    success:function(r){
                        console.log(r);
                    }
            });
    };

    $('#inputFondoCliente').change(function(){
        vistaPreviaC(this);
    });


// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>


	


<?php include 'footer_admin.php' ?>

