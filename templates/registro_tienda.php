<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include ("../global/config.php");
include ("../global/conexion.php");
include ("../global/const.php");


 //Consulta seleccionar paises
 $select_paises = $pdo->prepare("SELECT * FROM Paises");
 $select_paises->execute();
 $listaPaises = $select_paises->fetchAll(PDO::FETCH_ASSOC);


?>

<style>
    body{
        background: rgba(243, 243, 243, 0.705); 
        /* background-image: url('<?php echo URL_SITIO ?>static/img/crown-group.jpg'); */
        background-size: cover;
    }
</style>

<!-- <div class="cargando col-md-12">
    <br>
    <br>
    <br>
    <div class="col-md-4 offset-md-4">
        <img src="<?php echo URL_SITIO ?>static/img/Logo_shoppingapp_tienda.gif" width="100%" alt="">
    </div>
    <br>
    <div class="col-md-4 offset-md-4">
        <img src="<?php echo URL_SITIO ?>static/img/ajax-loader.gif" width="100%" alt="">
    </div>
</div> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registro de Tiendas</title>

    <!-- Imports -->
    <link href="<?php echo URL_SITIO ?>static/css/register.css" rel="stylesheet" type="text/css" media="all" />
    <link href="<?php echo URL_SITIO ?>static/css/styles.css" rel="stylesheet" type="text/css" media="all" />
    <link href="<?php echo URL_SITIO ?>static/css/toasts.css" rel="stylesheet" type="text/css" media="all" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="<?php echo URL_SITIO ?>static/js/jquery-3.5.0.min.js" ></script>
    <script src="https://kit.fontawesome.com/b2dbb6a24d.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    
    <!-- fuentes -->
    <link href="https://fonts.googleapis.com/css2?family=Lato&family=Roboto&display=swap" rel="stylesheet">

    <?php include 'iconos.php' ?>
</head>
<body>
<div role="alert" data-delay="5000" aria-live="assertive" aria-atomic="true" id="toast_mensaje" class="toast" data-autohide="true">
        
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        <div class="toast-body">
            
        </div>
        
</div> 
 
<div class="row col-md-12">
    <div class="col-md-4 offset-md-4">
        <img src="<?php echo URL_SITIO ?>static/img/Logo_shoppingapp_tienda.gif" width="100%" alt="">
    </div>
</div>
<div id="back row">
    <div id="cont-register" class="offset-md-3 col-md-6">
    <h2 class="text-center">Registro de tiendas</h2>
    <p>SI QUIERES REGISTRARTE COMO CLIENTE HAZ CLIC AQUÍ:  <a href="Registro-Usuario">Registro para usuarios</a> </p>
    <div class="alert alert-danger" id="mensaje_alert" class="alert-dismissible fade show"></div>
    <form autocomplete="off" action="<?php echo URL_SITIO ?>scripts/registro_tienda.php" method="post" id="form_register">
        <div class="form-group">
            <label for="inputnombreTienda">Nombre de la tienda<span class="text_required">*</span> </label>
            <input type="text" class="form-control" name="input_nombreTienda" id="inputnombreTienda" placeholder="">
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputnombreContacto">Nombre del contacto <span class="text_required">*</span></label>
                <input type="text" class="form-control" name="input_nombreContacto" id="inputnombreContacto" placeholder="">
            </div>
            <div class="form-group col-md-6">
                <label for="inputApellidoContacto">Apellido del contacto<span class="text_required">*</span> </label>
                <input type="text" class="form-control" name="input_apellidoContacto" id="inputApellidoContacto" placeholder="">
            </div>
        </div>
        <div class="form-group">
            <label for="inputCorreo">Correo <span class="text_required">*</span> </label>
            <input autocomplete="off" type="text" class="form-control" name="input_correo" id="inputCorreo" placeholder="">
        </div>
        <div class="form-group">
                <label for="inputPassword4">Contraseña <span class="text_required">*</span> </label>
                <input  autocomplete="new-password" type="password" class="form-control" name="input_contrasena" id="inputContrasena" placeholder="">
        </div>
        <div class="form-group">
                <label for="inputSamePassword">Misma contraseña <span class="text_required">*</span> </label>
                <input  autocomplete="new-password" type="password" class="form-control" name="input_mismaContrasena" id="inputMismaContrasena" placeholder="">
        </div>
        <div class="col-md-12" id="mostrar_pass">
                <input  id="show-pass" type="checkbox" onclick="showpass()">
                <label style="font-size:15px;color:gray;" for="show-pass">Mostrar contraseñas</label>
            </div>
        <div class="form-group">
            <label for="inputWebsite">Sitio web (opcional) </label>
            <input  type="phone" class="form-control" name="input_website" id="inputWebsite" placeholder="">
        </div>
        <div class="form-group">
            <label for="inputTelefono">Telefono <span class="text_required">*</span> </label>
            <input  type="phone" class="form-control solo-numeros" name="input_telefono" id="inputTelefono" placeholder="">
        </div>
        <div class="form-group">
            <label for="inputDireccion1">Dirección 1<span class="text_required">*</span> </label>
            <input  type="text" class="form-control" name="input_direccion1" id="inputDireccion1" placeholder="">
        </div>
        <div class="form-group">
            <label for="inputDireccion2">Dirección 2</label>
            <input  type="text" class="form-control" name="input_direccion2" id="inputDireccion2" placeholder="">
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputPais">País <span class="text_required">*</span> </label>
                <select  id="inputPais" name="input_pais" class="form-control">
                    <option selected>- Seleccione -</option>
                    <?php
                    foreach($listaPaises as $pais){
                        echo "<option value='". $pais['PK_Pais'] ."' >".$pais['NombrePais']."</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="inputCiudad">Ciudad<span class="text_required">*</span> </label>
                <div id="cont_cbo_ciudad">
                    <select  id="inputCiudad" name="input_ciudad" class="form-control">
                        <option selected>- Seleccione -</option>
                    </select> 
                </div>
            </div>
           
        </div>
        <br>
        <br>
        <input type="text" hidden name="action" value="register">
        <button type="submit" class="btn-flat col-md-8 offset-md-2">Registrar tienda</button>
    </form>

    </div>
</div>
<br>
<div class="row col-md-12">
    <div class="col-md-4 offset-md-4 text-center">
        <span style="font-size:12px;color:gray;">¿Ya tienes una cuenta?</span>
    </div>
</div>
<div class="row col-md-12">
    <div class="col-md-4 offset-md-4 text-center">
        <a href="Login-Tienda" class="btn btn-primary btn_registrarse">Iniciar sesión</a>
    </div>
</div>    
<br>

</body>
</html>

<script type="text/javascript">
//  $("#mensaje_alert").css("visibility", "hidden");

         function showpass() {
            var x = document.getElementById("inputContrasena");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }

             var y = document.getElementById("inputMismaContrasena");
            if (y.type === "password") {
                y.type = "text";
            } else {
                y.type = "password";
            }
        }

	$(document).ready(function(){
		$('#inputPais').change(function(){
		    recargarListaCiudad();
		});

        $('#inputContrasena').keypress(function(tecla){
            if(tecla.charCode == 32)
            {
                return false;
            }
        });
        $('#inputMismaContrasena').keypress(function(tecla){
            if(tecla.charCode == 32)
            {
                return false;
            }
        });

        $('.solo-numeros').keyup(function (){
            this.value = (this.value + '').replace(/[^0-9]/g, '');
        });

       
        // validaciones para el formulario de registro
        $('#form_register').on('submit', function (event) {
            event.preventDefault();

            // consultar existencia de usuario
            var existe_usuario;
            $.ajax({
                    type:"POST",
                    async: false,
                    url:"<?php echo URL_SITIO ?>scripts/datos_ajax.php",
                    data: {"request" : "verificarUsuario", 
                            "NombreUsuario" : $('#inputCorreo').val()},
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
                            "Correo" : $('#inputCorreo').val()},
                    success:function(r){
                        existe_correo = r;
                    }
            });

             // verifical longitud de contraseña
             var str_contrasena = $("#inputContrasena").val()
             var letras_contrasena = str_contrasena.length;

            $validate_mail = emailIsValid($('#inputCorreo').val());
        
             if($('#inputnombreTienda').val() == '' || $('#inputnombreContacto').val() == '' || $('#inputApellidoContacto').val() == ''  || $('#inputCorreo').val() == '' || $('#inputContrasena').val() == '' || $('#inputMismaContrasena').val() == '' || $('#inputTelefono').val() == '' || $('#inputDireccion1').val() == '' || $('#inputPais').prop('selectedIndex') == 0 || $('#inputCiudad').prop('selectedIndex') == 0){
                 toast("Faltan uno o más campos.");
            }else if(existe_usuario == 1){
                toast("Ese usuario ya existe");
             }else if($validate_mail == false){
                toast("Debe ingresar una dirección de correo electrónico válida");
             }else if(existe_correo == 1){
                toast("Ya existe una cuenta con esa dirección de correo electrónico");
             }else if($('#inputContrasena').val() != $('#inputMismaContrasena').val()){
                toast("Las contraseñas no coinciden");
             }else if(letras_contrasena < 8){
                toast('La contraseña es muy corta');
             }else{
                $('#form_register').unbind('submit').submit();
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

	function recargarListaCiudad(){
		$.ajax({
			type:"POST",
			url:"<?php echo URL_SITIO ?>scripts/datos_ajax.php",
            data: {"request" : "selectCiudades", 
                    "FK_Pais" : $('#inputPais').val()},
			success:function(r){
                //console.log(r);
				$('#cont_cbo_ciudad').html(r);
			}
		});
	}

    function toast(msj){
            $('.toast-body').html(msj);
            $('#toast_mensaje').toast('show');
    }

</script>


