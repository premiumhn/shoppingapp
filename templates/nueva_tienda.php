<?php include 'header_admin.php' ?> 
<?php 
error_reporting(E_ALL);
ini_set('display_errors', '1');
	//include '../scripts/comprobaciones.php';


require ('../scripts/comprobaciones.php'); 


 //Consulta seleccionar paises
 $select_paises = $pdo->prepare("SELECT * FROM Paises");
 $select_paises->execute();
 $listaPaises = $select_paises->fetchAll(PDO::FETCH_ASSOC);

?>

<link href="<?php echo URL_SITIO ?>static/css/toasts.css" rel="stylesheet" type="text/css" media="all" />
<link href="<?php echo URL_SITIO ?>static/css/tiendas_admin.css"rel="stylesheet">

<div class="row col-md-12">


<div class="col-md-2 ">
            <div class="card card-left">
                <ul class="list-group list-group-flush">
                <li class="list-group-item">
                            <a style="border:1px solid #F8F8F8" href="Nueva-Tienda" type="submit" class="col-md-12 btn btn-primary"><?php echo $ubtn_nuevo ?></a>
                    </li>
                    <li class="list-group-item">
                            <a style="border:1px solid #F8F8F8" href="Tiendas-Admin" type="submit" class="col-md-12 btn btn-primary"><?php echo $ubtn_ver_todas ?></a>
                    </li>
                    
                </ul>
            </div>
    </div>
<div id="cont_register" class=" col-md-10">
    <h2 class="text-center"><?php echo $tregistro_de_tiendas ?></h2>
   <div class="alert alert-danger" id="mensaje_alert" class="alert-dismissible fade show"></div>
   <div class="alert alert-warning" class="alert-dismissible fade show"></div>
    <form autocomplete="off" action="<?php echo URL_SITIO ?>scripts/registro_tienda.php" method="post" id="form_register" enctype="multipart/form-data">
        <div class="form-group">
            <label for="inputnombreTienda"><?php echo $tnombre_tienda ?><span class="text_required">*</span> </label>
            <input type="text" class="form-control" name="input_nombreTienda" id="inputnombreTienda" placeholder="">
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputnombreContacto"><?php echo $tnombre_contacto ?><span class="text_required">*</span></label>
                <input type="text" class="form-control" name="input_nombreContacto" id="inputnombreContacto" placeholder="">
            </div>
            <div class="form-group col-md-6">
                <label for="inputApellidoContacto"><?php echo $tapellido_contacto ?><span class="text_required">*</span> </label>
                <input type="text" class="form-control" name="input_apellidoContacto" id="inputApellidoContacto" placeholder="">
            </div>
        </div>
        <div class="form-group">
            <label for="inputCorreo"><?php echo $ucorreo ?> <span class="text_required">*</span> </label>
            <input autocomplete="off" type="text" class="form-control" name="input_correo" id="inputCorreo" placeholder="">
        </div>
        <div class="form-group">
                <label for="inputPassword4"><?php echo $ucontrasena ?>' <span class="text_required">*</span> </label>
                <input  autocomplete="new-password" type="password" class="form-control" name="input_contrasena" id="inputContrasena" placeholder="">
        </div>
        <div class="form-group">
                <label for="inputSamePassword"><?php echo $umisma_contrasena ?><span class="text_required">*</span> </label>
                <input  autocomplete="new-password" type="password" class="form-control" name="input_mismaContrasena" id="inputMismaContrasena" placeholder="">
        </div>
        <div class="col-md-12" id="mostrar_pass">
                <input  id="show-pass" type="checkbox" onclick="showpass()">
                <label style="font-size:15px;color:gray;" for="show-pass"><?php echo $umostrar_contrasena ?></label>
            </div>
        <div class="form-group">
            <label for="inputWebsite"><?php echo $twebsite ?></label>
            <input  type="phone" class="form-control" name="input_website" id="inputWebsite" placeholder="">
        </div>
        <div class="form-group">
            <label for="inputTelefono"><?php echo $ttelefono ?> <span class="text_required">*</span> </label>
            <input  type="phone" class="form-control solo-numeros" name="input_telefono" id="inputTelefono" placeholder="">
        </div>
        <div class="form-group">
            <label for="inputDireccion1"><?php echo $tdireccion ?> 1<span class="text_required">*</span> </label>
            <input  type="text" class="form-control" name="input_direccion1" id="inputDireccion1" placeholder="">
        </div>
        <div class="form-group">
            <label for="inputDireccion2"><?php echo $tdireccion ?> 2</label>
            <input  type="text" class="form-control" name="input_direccion2" id="inputDireccion2" placeholder="">
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputPais"><?php echo $tpais ?> <span class="text_required">*</span> </label>
                <select  id="inputPais" name="input_pais" class="form-control">
                    <option selected>- <?php echo $tseleccione ?> -</option>
                    <?php
                    foreach($listaPaises as $pais){
                        echo "<option value='". $pais['PK_Pais'] ."' >".$pais['NombrePais']."</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="inputCiudad"><?php echo $tciudad ?> <span class="text_required">*</span> </label>
                <div id="cont_cbo_ciudad">
                    <select  id="inputCiudad" name="input_ciudad" class="form-control">
                        <option selected>- <?php echo $tseleccione ?> -</option>
                    </select> 
                </div>
            </div>
           
        </div>
        <fieldset class="form-group">
        <label for="inputAdomicilio"><?php echo $t_tiene_adomicilio_ ?></label>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="input_adomicilio" id="inputRadioYes" value="1" checked>
            <label class="form-check-label" for="inputRadioActivo">
                <?php echo $tsi ?>
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
            <label for=""><?php echo $tlogo ?></label>
            <div class="custom-file">
                <input type="file" accept="image/*" class="custom-file-input" id="inputLogo" name="input_logo">
                <label class="custom-file-label" data-browse="Elegir" for="customFile"><?php echo $uelegir ?></label>
            </div>
        </div>
        <div class="form-group">
            <label for=""><?php echo $tportada ?></label>
            <div class="custom-file">
                <input type="file" accept="image/*" class="custom-file-input" id="inputPortada" name="input_portada">
                <label class="custom-file-label" data-browse="Elegir" for="customFile"><?php echo $uelegir ?></label>
            </div>
        </div>
        <br>
        <br>
        <input type="text" hidden name="action" value="register">
        <button type="submit" class="btn-flat col-md-8 offset-md-2"><?php echo $btnGuardar ?></button>
    </form>

    </div>
    
</div>
<br>
<br>
<script type="text/javascript">

    $('.alert-danger').css("display", "none");  
    $('.alert-warning').hide();               
    $('.h2-name').html('<?php echo $lnombre_pagina ?>');
    $('#titulo_pagina').html('Shoppingapp | <?php echo $lnombre_pagina ?>');

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
        $('.alert-warning').html(msj); 
        $('.alert-warning').show('slow'); 
        $('html, body').animate({scrollTop:0}, 'slow');
    }

    $('.form-control').keypress(function(){
        $('.alert-warning').hide('slow'); 
    });

    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

</script>

<script type="text/javascript">
function googleTranslateElementInit() {
	new google.translate.TranslateElement({pageLanguage: 'es', includedLanguages: 'ca,eu,gl,en,fr,it,pt,de', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, gaTrack: true}, 'google_translate_element');
        }
</script>

<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    </div>


	


<?php include 'footer_admin.php' ?>

