<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include ("../global/config.php");
include ("../global/conexion.php");
include ("../global/const.php");
session_start();

$sesion = (isset($_REQUEST['sesion']))?$_REQUEST['sesion']:"";


if ($sesion = "cerrar"){
    session_destroy();
}else{
    session_destroy();
}

$p = (isset($_REQUEST['p']))?$_REQUEST['p']:"";
$u = (isset($_REQUEST['u']))?$_REQUEST['u']:"";

$select_id = $pdo->prepare('SELECT * FROM Configuracion');
$select_id->execute();
$datos_config = $select_id->fetchAll(PDO::FETCH_ASSOC);

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>login shop</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="<?php echo URL_SITIO ?>static/css/styles.css" rel="stylesheet" type="text/css" media="all" />
    <link href="<?php echo URL_SITIO ?>static/css/toasts.css" rel="stylesheet" type="text/css" media="all" />
    
    <script src="<?php echo URL_SITIO ?>static/js/jquery-3.5.0.min.js" ></script>

	<script src="https://kit.fontawesome.com/b2dbb6a24d.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
   
    <link href="<?php echo URL_SITIO ?>static/css/login.css" rel="stylesheet" type="text/css" media="all" />
    <?php include 'iconos.php' ?>

    <style>
        body{
            background-image: url(<?php echo URL_SITIO. 'uploads/img/configuracion/' . $datos_config[0]['FondoLoginTienda']?>)!important;
            background-size: cover;
            -webkit-box-shadow: inset 0px 0px 1000px 1000px rgba(0,0,0,0.50);
            -moz-box-shadow: inset 0px 0px 1000px 1000px rgba(0,0,0,0.50);
            box-shadow: inset 0px 0px 1000px 1000px rgba(0,0,0,0.50);
        }
    </style>

</head>
<body id="body-tienda">
<div class="cargando col-md-12">
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <div class="col-md-4 offset-md-4">
        <img src="<?php echo URL_SITIO ?>static/img/Logo_shoppingapp_v2.png" width="100%" alt="">
    </div>
    <div class="col-md-4 offset-md-4">
        <img src="<?php echo URL_SITIO ?>static/img/ajax-loader.gif" width="100%" alt="">
    </div>
</div>

<div role="alert" data-delay="5000" aria-live="assertive" aria-atomic="true" id="toast_mensaje" class="toast" data-autohide="true">
    <div class="toast-body">
    </div>
</div> 

<br>
<div class="col-md-12">
    <div class="col-md-4 offset-md-4">
        <img src="<?php echo URL_SITIO ?>static/img/Logo_shoppingapp_tienda_trazado.png" width="100%" alt="">
    </div>
</div>
<div id="row">
    <div id="cont-login" class="offset-md-3 col-md-6">
    <div class="alert alert-danger" id="mensaje_alert" class="alert-dismissible fade show"></div>
    <div class="alert alert-success" id="mensaje_alert_success" style="display:none;"></div>
 
    <form lang="en" action="<?php echo URL_SITIO ?>scripts/login_tienda.php" method="post" id="form_login">
        
            <div class="form-group col-md-12">
            <input type="hidden" id="inputConf" value="<?php echo $p ?>">
                <label class="el-form col-md-12" for="inputUsername">Correo <span class="text_required">*</span> </label>
                <input class="in-form col-md-12" type="text" class="form-control" name="input_username" value="<?php echo ($p == 'cf')?$u:""; ?>" id="inputUsername" placeholder="">
            </div>
            <br>
            <br>
            <div class="form-group col-md-12">
                <label class="el-form col-md-12" for="inputPassword4">Contraseña <span class="text_required">*</span> </label>
                <div class="row">
                <input class="in-form col-md-12" autocomplete="new-password" type="password" class="form-control" name="input_password" id="inputPassword" placeholder="">

                    <div class="" id="mostrar_pass">
                        <input id="show-pass" type="checkbox" onclick="showpass()">
                        <label id="lbl-show-pass" for="show-pass"> <i class="fa fa-eye"></i> </label>
                    </div>
                </div>

                <br>
                <br>
                <p class="text-right" ><a style="font-size:13px;" href="./cambiar_contrasena.php?accion=camb">Olvidé mi contraseña</a> </p>
            </div>
     
        <br>
        <br>
        <input type="text" hidden name="action" value="login">
        <button type="submit" class="btn-flat col-md-12">Iniciar sesión</button>
        <br>
        <br>
        <p  class="text-center"><a href="Login">Login para clientes</a> </p>
    </form>

    </div>
</div>
<div class="row col-md-12">
    <div class="col-md-4 offset-md-4 text-center">
        <span style="font-size:12px;color:white;">¿No tienes una cuenta?</span>
    </div>
</div>
<div class="row col-md-12">
    <div class="col-md-8 offset-md-2 text-center">
        <span style="color:white;">Envíanos tu información a <strong>shoppingappworld@gmail.com</strong> y solicita crear una cuenta para tu tienda.</span>
        <!-- <a href="Registro-Tienda" class="btn btn-primary btn_registrarse">Registrar tienda</a> -->
    </div>
</div>
<br>
<br>



</body>
</html>

<script type="text/javascript">
    $('#show-pass').hide();

    function showpass() {
        var x = document.getElementById("inputPassword");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }

    }

	$(document).ready(function(){
        $('.cargando').hide();
        
		$('#inputCountry').change(function(){
		    recargarListaCiudad();
		});

        if($('#inputConf').val() == "cf"){
            $("#mensaje_alert_success").css("display", "block");
            $("#mensaje_alert_success").html('Correo confirmado, ya puedes iniciar sesión');
            $('#inputPassword').focus();
        }

        if($('#inputConf').val() == "nc"){
            mostrarMensaje('Tu correo no ha sidoconfirmado, debes confirmar tu correo para iniciar sesión');
            $('#inputUsername').focus();
        }

       
        // validaciones para el formulario de registro
        $('#form_login').on('submit', function (event) {
            event.preventDefault();
            
            // Verificar usuario
            var verificar_usuario;
            $.ajax({
                    type:"POST",
                    async: false,
                    url:"<?php echo URL_SITIO ?>scripts/datos_ajax.php",
                    data: {"request" : "verificarLoginTienda", 
                            "Contrasena" : $('#inputPassword').val(),
                            "NombreUsuario" : $('#inputUsername').val()},
                    success:function(r){
                        verificar_usuario = r;
                    }
            });
            
            $validate_mail = emailIsValid($('#inputEmail').val());
        
             if($('#inputUsername').val() == '' || $('#inputPassword').val() == '' ){
                 toast("Faltan campos.");
            }else if(verificar_usuario == 0){
                toast("Correo o contraseña inválidos.");
             }else if(verificar_usuario == 3){
                toast("Esta correo pertenece a una cuenta de cliente, inicia sesión desde el login de clientes.");
             }else{
                $('#form_login').unbind('submit').submit();
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

        $('#inputCountry').change(function(){
                $("#mensaje_alert").css("visibility", "hidden");
                $("#mensaje_alert").html("");
        }) 
        $('#inputCity').change(function(){
                $("#mensaje_alert").css("visibility", "hidden");
                $("#mensaje_alert").html("");
        }) 

        function emailIsValid(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)
        }
        
	})

    function toast(msj){
            $('.toast-body').html(msj);
            $('#toast_mensaje').toast('show');
        }


</script>