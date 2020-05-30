<?php include 'header_admin.php' ?> 
<?php 
error_reporting(E_ALL);
ini_set('display_errors', '1');
	//include '../scripts/comprobaciones.php';


require ('../scripts/comprobaciones.php'); 

//Consulta seleccionar categorías
$select_categorias = $pdo->prepare("SELECT * FROM Categorias");
$select_categorias->execute();
$listaCategorias = $select_categorias->fetchAll(PDO::FETCH_ASSOC);

?>

<link href="<?php echo URL_SITIO ?>static/css/registro_datos.css"rel="stylesheet">
<link href="<?php echo URL_SITIO ?>static/css/usuarios_admin.css"rel="stylesheet">



<div  role="alert" data-delay="5000" aria-live="assertive" aria-atomic="true" id="toast_mensaje" class="toast" data-autohide="true">
        <div class="toast-body">
        </div>
</div> 



<div class="row col-md-12">
<div class="col-md-2 ">
            <div class="card card-left">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                            <a href="Nuevo-Usuario-Admin" style="border:1px solid #F8F8F8" type="submit" class="col-md-12 btn btn-primary"><?php echo $ubtn_nuevo ?></a>
                    </li>
                    <li class="list-group-item">
                            <a href="Usuarios-Admin" style="border:1px solid #F8F8F8" type="submit" class="col-md-12 btn btn-primary"><?php echo $ubtn_ver_todas ?></a>
                    </li>
                </ul>
            </div>
        </div>
    <div style="height:100%;margin-bottom:60px;" class="col-md-10 ">
    <div class=" alert alert-warning" role="alert"></div>
    <div class="card">
        <div class="card-body">
        <form autocomplete="off" lang="en" action="<?php echo URL_SITIO ?>scripts/usuarios_admin.php" method="post" id="form_register" enctype="multipart/form-data">
            <div class="form-group">
                    <label for="inputUsername"><?php echo $unombre_usuario ?> <span class="text_required">*</span> </label>
                    <input type="text" class="form-control" name="input_nombreUsuario" id="inputUsername" placeholder="">
                </div>
                <div class="form-group">
                    <label for="inputEmail"><?php echo $ucorreo ?> <span class="text_required">*</span> </label>
                    <input  type="text" class="form-control" name="input_correo" id="inputEmail" placeholder="">
                </div>
                <div class="form-group">
                    <label for="inputPassword4"><?php echo $ucontrasena ?> <span class="text_required">*</span> </label>
                    <input  autocomplete="new-password" type="password" class="form-control" name="input_contrasena" id="inputPassword" placeholder="">
                </div>
                <div class="form-group">
                    <label for="inputSamePassword"> <?php echo $umisma_contrasena ?> <span class="text_required">*</span> </label>
                    <input  autocomplete="new-password" type="password" class="form-control" name="input_samePassword" id="inputSamePassword" placeholder="">
                </div>
                <div class="col-md-12" id="mostrar_pass">
                    <input  id="show-pass" type="checkbox" onclick="showpass()">
                    <label style="font-size:15px;color:gray;" for="show-pass"><?php echo $umostrar_contrasena ?></label>
                </div>
                <label for="inputAddress2"><?php echo $ufoto_perfil ?></label>
                <div class="custom-file">
                    <input type="file" accept="image/*" class="custom-file-input" id="inputImagen" name="input_imagen">
                    <label class="custom-file-label" data-browse="Elegir" for="customFile"><?php echo $useleccionar_archivo ?></label>
                </div>
            <input type="text" hidden name="action" value="register">
            <br>
            <button type="submit" class="btn-primary btn_registrar_usuario col-md-4 offset-md-4"><?php echo $ubtn_registrar_usuario ?></button>
        </form>
        </div>
    </div>
    </div>
</div>





<script type="text/javascript">

    $('.h2-name').html('Usuarios');

    $('#mensaje-success').hide();
    $('#mensaje-error').hide();

    $('.alert-warning').css("display", "none");

    <?php 
        $msj = (isset($_GET['msj']))?$_GET['msj']:"";
        if( $msj == 'editada'){ 
    ?>
        $('#mensaje-success').html('<i class="fa fa-check"></i>Categoría actualizada exitosamente');
        $('#mensaje-success').show();
    <?php } elseif( $msj == 'eliminada'){ ?>
        $('#mensaje-success').html('<i class="fa fa-check"></i>Categoría eliminada.');
        $('#mensaje-success').show();
    <?php } elseif( $msj == 'muypesada'){ ?>
        $('#mensaje-error').html('Imagen demasiado pesada. La imagen debe pesar menos de 3 MB.');
        $('#mensaje-error').show();
    <?php } ?>

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
                            "NombreUsuario" : $('#inputUsername').val()},
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
                            "Correo" : $('#inputEmail').val()},
                    success:function(r){
                        existe_correo = r;
                    }
            });

            // verifical longitud de contraseña
            var str_contrasena = $("#inputPassword").val()
            var letras_contrasena = str_contrasena.length;

            $validate_mail = emailIsValid($('#inputEmail').val());
        
             if( $('#inputUsername').val() == '' || $('#inputEmail').val() == '' || $('#inputPassword').val() == '' || $('#inputSamePassword').val() == ''){
                 toast("Faltan uno o más campos.");
            }else if(existe_usuario == 1){
                toast("Ya existe ese nombre de usuario");
             }else if($validate_mail == false){
                toast("Debe ingresar una dirección de correo electrónico válida");
             }else if(existe_correo == 1){
                toast("Ya existe una cuenta con esa dirección de correo electrónico");
             }else if($('#inputPassword').val() != $('#inputSamePassword').val()){
                toast("Las contraseñas no coinciden");
             }else if(letras_contrasena < 8){
                toast('La contraseña es muy corta');
             }else{
                $('#form_register').unbind('submit').submit();
             }
             
		});

    function emailIsValid(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)
    }

    function showpass() {
            var x = document.getElementById("inputPassword");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }

             var y = document.getElementById("inputSamePassword");
            if (y.type === "password") {
                y.type = "text";
            } else {
                y.type = "password";
            }
        }
        


    

    $('#inputImagen').bind('change', function() {
            var peso = this.files[0].size/1024/1024;
            if(peso > 5){
                toast('Imagen demasiado pesada, debe pesar menos de 5 MB');
                this.val("");
            };
    });

     

    function toast(msj){
            $('.alert-warning').html(msj);
            $('.alert-warning').css("display", "");
    }

    

     function vistaPrevia(input){
        if(input.files && input.files[0]){
            var reader = new FileReader();
            
           
            reader.onload = function(e){
                $('#cont_imagen').html("<img style='width:100%' id='showImagen' src='"+ e.target.result +"' >");
                console.log(e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#inputImagen').change(function(){
        vistaPrevia(this);
    });

    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

</script>


	


<?php include 'footer_admin.php' ?>

