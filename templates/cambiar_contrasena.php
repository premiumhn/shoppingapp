<?php 
    include '../global/const.php';
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    $accion = (isset($_REQUEST['accion']))?$_REQUEST['accion']:"";
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cambiar contraseña</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	
    <script src="https://kit.fontawesome.com/b2dbb6a24d.js" crossorigin="anonymous"></script>
    <link href="<?php echo URL_SITIO ?>static/css/toasts.css" rel="stylesheet" type="text/css" media="all" />
    <script src="<?php echo URL_SITIO ?>static/js/jquery-3.5.0.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    

    <link href="<?php echo URL_SITIO ?>static/css/cambiar_contrasena.css" rel="stylesheet" type="text/css" media="all" />
 
    <?php include 'iconos.php' ?>

</head>
<div role="alert" data-delay="5000" aria-live="assertive" aria-atomic="true" id="toast_mensaje" class="toast" data-autohide="true">
        
        <div class="toast-body">
            
        </div>
        
</div>
<body>
    <?php if($accion == 'camb'){ ?>
        <div class="cargando col-md-12">
            <br>
            <br>
            <div class="col-md-4 offset-md-4">
                <img src="<?php echo URL_SITIO ?>static/img/Logo_shoppingapp_v2.png" width="100%" alt="">
            </div>
            <br>
            <div class="col-md-4 offset-md-4 card">
                <form id="form-camb" action="<?php echo URL_SITIO ?>scripts/cambiar_contrasena.php" method="post">
                    <div class="form-group">
                        <label for="">Ingrese su usuario o correo</label>
                        <input name="input_usuarioCorreo" id="inputUsuarioCorreo" type="text" class="form-control">
                        <br>
                        <p class="col-md-12 instrucciones">Enviaremos un enlace a tu cuenta de correo electrónico para que puedas cambiar tu contraseña</p>
                    </div>
                    <br>
                    <div class="form-group">
                        <input type="hidden" name="accion" value="camb">
                        <input name="btn_submit" value="Enviar" id="btn_submit_camb"  type="button" class="btn btn-primary col-md-6 offset-md-3">
                    </div>
                    
                </form>
            </div>
        </div>
    <?php } ?>

    <?php if($accion == 'rest'){ ?>
        <div class="cargando col-md-12">
            <br>
            <br>
            <div class="col-md-4 offset-md-4">
                <img src="<?php echo URL_SITIO ?>static/img/Logo_shoppingapp_v2.png" width="100%" alt="">
            </div>
            <br>
            <div class="col-md-4 offset-md-4 card">
                <form id="form-rest" action="<?php echo URL_SITIO ?>scripts/cambiar_contrasena.php" method="post">
                    <div class="form-group">
                        <label for="">Nueva contraseña</label>
                        <input name="input_nuevaContrasena" id="inputNuevaContrasena" type="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Nueva contraseña de nuevo</label>
                        <input name="input_mismaNuevaContrasena" id="inputMismaNuevaContrasena"  type="password" class="form-control">
                    </div>
                    <div class="form-froup" id="mostrar_pass">
                        <input  id="show-pass" type="checkbox" onclick="showpass()">
                        <label for="show-pass">Mostrar contraseña</label>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="accion" value="rest">
                        <input type="hidden" name="pk_usuario" value="<?php echo $_POST['usuario'] ?>">
                        <input name="btn_submit" value="Cambiar" id="btn_submit_rest"  type="button" class="btn btn-primary col-md-6 offset-md-3">
                    </div>
                    
                </form>
            </div>
        </div>
    <?php } ?>

<?php if($accion == 'wait'){ ?>
    <div class="row col-md-12">
       
        <div class="col-md-4 offset-md-4 ">
            <img style="width:100%" src="<?php echo URL_SITIO ?>static/img/Logo_shoppingapp_v2.png" alt="">
        </div>
    </div>

    <div id="row">
        <br>
        <br>
        <br>
        <div class="col-md-8 offset-md-2">
            <h4 style="color:gray">Hemos enviado un enlace a tu correo electónico. Revisa la bandeja de entrada de tu correo electorónico.</h4>
        </div>
        <div class="row col-md-12">
            <div class="col-md-6 offset-md-3 ">
                <img style="width:100%" src="<?php echo URL_SITIO ?>static/img/ajax-loader.gif" alt="">
            </div>
        </div>

    </div>
    </div>
    <br>
 <?php } ?>

</body>
</html>

<script type="text/javascript">

    $('#btn_submit_camb').click(function(e){
        e.preventDefault()

        // consultar existencia de usuario
        var existe_usuario;
        $.ajax({
                type:"POST",
                async: false,
                url:"<?php echo URL_SITIO ?>scripts/datos_ajax.php",
                data: {"request" : "verificarUsuario", 
                        "NombreUsuario" : $('#inputUsuarioCorreo').val()},
                success:function(r){
                    existe_usuario = r;
                }
        });

        // consultar existencia de correo
        var existe_correo;
        $.ajax({
                type:"POST",
                async: false,
                url:"<?php echo URL_SITIO ?>scripts/datos_ajax.php",
                data: {"request" : "verificarCorreo", 
                        "Correo" : $('#inputUsuarioCorreo').val()},
                success:function(r){
                    existe_correo = r;
                }
        });

        if($('inputUsuarioCorreo').val() == ''){
            toast('Debe ingresar un usuario o correo.');
        }else if(existe_usuario == 0 && existe_correo == 0){
            toast('No existe una cuenta con ese usuario o correo.');
        }else{
            $('#form-camb').submit();
        }

    });

    $('#btn_submit_rest').click(function(e){
        e.preventDefault()

        // verifical longitud de contraseña
        var str_contrasena = $("#inputNuevaContrasena").val()
        var letras_contrasena = str_contrasena.length;

        if($('#inputNuevaContrasena').val() == '' || $('#inputMismaNuevaContrasena').val() == ''){
            toast('Faltan campos.');
        }else if($('#inputNuevaContrasena').val() != $('#inputMismaNuevaContrasena').val()){
            toast('Las contraseñas no coinciden');
        }else if(letras_contrasena < 8){
            toast('La contraseña es muy corta');
        }else{
            $('#form-rest').submit();
        }
    });

     $('#inputNuevaContrasena').keypress(function(tecla){
            if(tecla.charCode == 32)
            {
                return false;
            }
    });
    $('#inputMismaNuevaContrasena').keypress(function(tecla){
        if(tecla.charCode == 32)
        {
            return false;
        }
    });

    function showpass() {
        var x = document.getElementById("inputNuevaContrasena");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }

        var y = document.getElementById("inputMismaNuevaContrasena");
        if (y.type === "password") {
            y.type = "text";
        } else {
            y.type = "password";
        }
    }


   

    

    function toast(msj){
        $('.toast-body').html(msj);
        $('#toast_mensaje').toast('show');
    }


</script>