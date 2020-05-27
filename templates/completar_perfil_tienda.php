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

 //Consulta seleccionar paises
 $select_paises = $pdo->prepare("SELECT * FROM Paises");
 $select_paises->execute();
 $listaPaises = $select_paises->fetchAll(PDO::FETCH_ASSOC);

 $consulta_tipo_usuario = $pdo->prepare("SELECT * FROM Usuarios
                                        WHERE PK_Usuario = :PK_Usuario;");
$consulta_tipo_usuario->bindParam(':PK_Usuario', $_SESSION['login_user']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$consulta_tipo_usuario->execute();
$usuario = $consulta_tipo_usuario->fetchAll(PDO::FETCH_ASSOC);


// if($usuario[0]['EstadoCorreo'] == 0){
//     header('Location: login_tienda.php?p=nc');
// }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Completar perfil</title>

   <!-- Imports -->
    <link href="<?php echo URL_SITIO ?>static/css/styles.css" rel="stylesheet" type="text/css" media="all" />
    <link href="<?php echo URL_SITIO ?>static/css/registro_datos.css" rel="stylesheet" type="text/css" media="all" />
    <link href="<?php echo URL_SITIO ?>static/css/completar_perfil.css" rel="stylesheet" type="text/css" media="all" />
    <link href="<?php echo URL_SITIO ?>static/css/toasts.css" rel="stylesheet" type="text/css" media="all" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="<?php echo URL_SITIO ?>static/js/jquery-3.5.0.min.js" ></script>
    <script src="https://kit.fontawesome.com/b2dbb6a24d.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    
    <?php include 'iconos.php' ?>
 

</head>
<body>
<div role="alert" data-delay="5000" aria-live="assertive" aria-atomic="true" id="toast_mensaje" class="toast" data-autohide="true">
     
        <div class="toast-body">
            
        </div>
        
</div> 
<?php require ('header.php') ?>

<input type="hidden" id="perfil_incompleto" value="<?php echo $_SESSION['perfil_incompleto'] ?>">
 <div class="alert text-center alert-danger" id="mensaje_alert" class="alert-dismissible fade show"></div>
 <div class="row" style="width:100%;margin:0px;">

    <div class="col-md-2 ">
        <div class="card card-left">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <form action="" method="POST">
                        <input type="hidden" name="menu" value="registro_categoria" />
                        <button type="submit" class="col-md-12 btn btn-primary">Completar perfil</button>
                    </form>
                </li>
            </ul>
        </div>
        <br>
    </div>

    <div style="height:100%;margin-bottom:60px;" class="col-md-7 bordered">
    <div id="mensaje-error" class="alert alert-danger" role="alert"></div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-right">Completar perfil</h5>
                <br>
                <form action="<?php echo URL_SITIO ?>scripts/completar_perfil_tienda.php" id="form_completar_tienda" method="post" enctype="multipart/form-data">
                
                    <!-- <div class="form-group">
                        <label for="inputCorreoPaypal"><img class="col-md-3" src="<?php echo URL_SITIO ?>static/img/PayPal-Logo.png" alt="logo paypal"> ID de Cliente PayPal <span class="text_required">*</span> </label>
                        <div class="row col-md-12">
                            <input type="text" class="form-control col-md-11" name="input_idClientePaypal" id="inputIDClientePaypal" placeholder="Ejemplo: AfD5UDBgvoCWjA2v1oEmxVJgBUqDo_bSB6ywQcs71MG6NTe64DTomwuf9Obw35BgjsmPsZQM_hUPMPk_">
                            <i class=" i-check col-md-1 fa fa-check-circle"></i>
                            <i class=" i-error col-md-1 fa fa-times-circle"></i>
                        </div>
                        
                    </div> -->
                    
                    <fieldset class="form-group">
                    <label for="inputAdomicilio">¿Tiene servicio a domicilio?</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="input_adomicilio" id="inputRadioYes" value="1" checked>
                        <label class="form-check-label" for="inputRadioActivo">
                            Si
                        </label>
                        </div>
                        <div class="form-check">
                        <input class="form-check-input" type="radio" name="input_adomicilio" id="inputRadioNo" value="0">
                        <label class="form-check-label" for="inputRadioInactivo">
                            No
                        </label>
                        </div>
                    </fieldset>
                    <div class="form-group">
                        <label for="">Logo de la empresa</label>
                        <div class="custom-file">
                            <input type="file" accept="image/*" class="custom-file-input" id="inputLogo" name="input_logo">
                            <label class="custom-file-label" data-browse="Elegir" for="customFile">Seleccione</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Portada para la página</label>
                        <div class="custom-file">
                            <input type="file" accept="image/*" class="custom-file-input" id="inputPortada" name="input_portada">
                            <label class="custom-file-label" data-browse="Elegir" for="customFile">Seleccione</label>
                        </div>
                    </div>
                    
                    <br>
                    <br>
                    <br>
                    <input type="hidden" name="action" value="completar" >
                    <button type="submit" id="btnCompletar" class="btn-flat col-md-8 offset-md-2">Completar</button>
                </form>

            </div>
        </div>
    </div>
    <div class="col-md-3 bordered">
    <div class="card ">
        <div class="card-body">
            <h5 class="card-title text-right">Atajos</h5>
        </div>
    </div>
    </div>
    
</div>    



<script type="text/javascript">
    $("#mensaje_alert").css("visibility", "hidden");
    $('#mensaje-error').hide();
    $('.i-check').hide();
    $('.i-error').hide();


     $('#inputPortada').bind('change', function() {
            var peso = this.files[0].size/1024/1024;
            if(peso > 5){
                toast('Imagen de portada demasiado pesada, debe pesar menos de 5 MB');
                this.val("");
            };
    });

    $('#inputLogo').bind('change', function() {
            var peso = this.files[0].size/1024/1024;
            if(peso > 5){
                toast('Imagen de logo demasiado pesada, debe pesar menos de 5 MB');
                this.val("");
            };
    });


    <?php 
        $msj = (isset($_GET['msj']))?$_GET['msj']:"";
        $tipo = (isset($_GET['tipo']))?$_GET['tipo']:"";
        if( $msj == 'muypesada'){ ?>
        $('#mensaje-error').html('Imagen de "' + <?php echo $tipo ?>  + '" demasiado pesada. La imagen debe pesar menos de 5 MB.');
        $('#mensaje-error').show();
    <?php } ?>



    if($('#perfil_incompleto').val() == 1){
        mostrarMensaje("Debes completar el perfil de tu tienda.");
        ocultarMensaje();
    }

    // var estado_id_cliente;
    // $('#inputIDClientePaypal').focusout(function(){
       
    //     var respuesta;
    //     $.ajax({
    //             type:"POST",
    //             async: false,
    //             url:"<?php echo URL_SITIO ?>scripts/datos_ajax.php",
    //             data: {"request" : "probarURL", 
    //                     "client_id" : $('#inputIDClientePaypal').val()},
    //             success:function(r){
    //                 respuesta = r;
    //             }
    //     });
        
    //     if($('#inputIDClientePaypal').val() != ''){
            
    //         var n = respuesta.search("client-id not recognized");
    //         if(n != -1){
    //             toast('ID de cliente PayPal inválido');
    //             $('.i-error').show();
    //             $('.i-check').hide();
    //             estado_id_cliente = 0;
    //         }else{
    //             $('.i-check').show();
    //             $('.i-error').hide();
    //             estado_id_cliente = 1;
    //         }
    //     }
        
    // });
    
    // $('#btnCompletar').click(function (e) {
    //     e.preventDefault();

    //     if( $('#inputIDClientePaypal').val() == '' ){
    //         toast("Falta uno o más campos");
    //     }else if(estado_id_cliente == 0){
    //         toast("Ingrese un ID de cliente PayPal válido");
    //     }else{
    //         $('#form_completar_tienda').submit();
    //     }

    // });

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

    function toast(msj){
            $('.toast-body').html(msj);
            $('#toast_mensaje').toast('show');
    }


     
  

 
    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

</script>

<?php require ('footer.php') ?>
</body>
</html>