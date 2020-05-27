<?php include 'header_admin.php' ?> 
<?php 
error_reporting(E_ALL);
ini_set('display_errors', '1');


require ('../scripts/comprobaciones.php'); 

$pk_usuario = isset($_REQUEST['PK_Usuario'])?$_REQUEST['PK_Usuario']:"";


$select_usuario = $pdo->prepare("SELECT * FROM Usuarios WHERE PK_Usuario = :PK_Usuario");
$select_usuario->bindParam('PK_Usuario', $pk_usuario);
$select_usuario->execute();
$usuario = $select_usuario->fetchAll(PDO::FETCH_ASSOC);

?>

<link href="<?php echo URL_SITIO ?>static/css/registro_datos.css"rel="stylesheet">
<link href="<?php echo URL_SITIO ?>static/css/usuarios_admin.css"rel="stylesheet">


<div  role="alert" data-delay="5000" aria-live="assertive" aria-atomic="true" id="toast_mensaje" class="toast" data-autohide="true">
        <div class="toast-body">
        </div>
</div> 

<div class="row col-md-12">
<div class="alert alert-warning col-md-12"></div>
<div id="mensaje-success" class="alert alert-success col-md-12" role="alert"></div>
<div id="mensaje-error" class="alert alert-danger col-md-12" role="alert"></div>
    <div class="card">
        <div class="card-body">
            <form id="form-edit" action="<?php echo URL_SITIO ?>scripts/usuarios_admin.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="inputAddress">Nombre del usuario</label>
                    <input type="text" class="form-control" name="input_nombreUsuario" id="inputNombreUsuario" placeholder="">
                </div>
                <div class="form-group">
                    <label for="inputAddress2">Correo</label>
                    <input type="text" class="form-control" name="input_correo" id="inputCorreo" placeholder="">
                </div>
                <div class="form-group">
                    <label for="inputAddress2">Contraseña</label>
                    <input type="text" class="form-control" name="input_contrasena" id="inputContrasena" placeholder="">
                </div>
                <br>
                <label for="inputAddress2">Imagen</label>
                <div class="col-md-6 offset-md-3">
                    <div class="col-md-12" id="cont_imagen">
                        <img id="showImagen" style="width:100%" src="" alt="">
                    </div>
                </div>
                <br>
                <div class="custom-file">
                    <input type="file" accept="image/*" class="custom-file-input" id="inputImagen" name="input_imagen">
                    <label class="custom-file-label" for="customFile">Cambiar imagen</label>
                </div>
                <br>
                <br>
                <input type="hidden" id="PK_Usuario" name="PK_Usuario">
                <input type="hidden" value="editar_mi_usuario" name="action">

                <div class="text-center col-md-12">
                    <button id="btnEditar"  class=" btn btn-primary col-md-5">Editar</button>&nbsp&nbsp&nbsp
                    <a id="btnCancelar" href="Admin"  class=" btn btn-secondary col-md-5">Cancelar</a>
                </div>
                
            </form>

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
        if( $msj == 'editado'){ 
    ?>
        $('#mensaje-success').html('<i class="fa fa-check"></i>Usuario actualizado exitosamente');
        $('#mensaje-success').show();
    <?php } elseif( $msj == 'eliminada'){ ?>
        $('#mensaje-success').html('<i class="fa fa-check"></i>Categoría eliminada.');
        $('#mensaje-success').show();
    <?php } elseif( $msj == 'muypesada'){ ?>
        $('#mensaje-error').html('Imagen demasiado pesada. La imagen debe pesar menos de 3 MB.');
        $('#mensaje-error').show();
    <?php } ?>


    $('#inputNombreUsuario').val('<?php echo $usuario[0]['NombreUsuario'] ?>');
    $('#inputCorreo').val('<?php echo $usuario[0]['Correo'] ?>');
    $('#inputContrasena').val('<?php echo openssl_decrypt($usuario[0]['Contrasena'], COD, KEY) ?>');
    $('#showImagen').attr('src', '<?php echo URL_SITIO .'uploads/img/perfiles/'. $usuario[0]['Foto'] ?>');
    $('#PK_Usuario').val(<?php echo $usuario[0]['PK_Usuario'] ?>);

    var nombre_actual = '<?php echo $usuario[0]['NombreUsuario'] ?>';

   
    $('#btnEditar').click(function(e){
        e.preventDefault();

        //consultar existencia de correo
        var existe_usuario;
        var nombre_usuario = $('#inputNombreUsuario').val();
            $.ajax({
                    type:"POST",
                    async: false,
                    url:"<?php echo URL_SITIO?>scripts/datos_ajax.php",
                    data: {"request" : "verificarUsuario", 
                            "NombreUsuario" : nombre_usuario},
                    success:function(r){
                        existe_usuario = r;
                    }
        });

       
        if($('#inputNombreUsuario').val() == "" || $('#inputCorreo').val() == "" || $('#inputContrasena').val() == "" ){
            toast('Faltan uno o más campos');
        }else if(existe_usuario == 1 && $('#inputNombreUsuario').val() != nombre_actual){
            toast('Ya existe un usuario con ese nombre');
        }else{    
            $('#form-edit').submit();
        }

    });

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

   
   
     function eliminar(pk_categoria){
         $('#nombreCategoriaEl').html($('#nombreCategoria_'+pk_categoria)[0].innerText);
         $('#PK_CategoriaEl').val(pk_categoria);

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

    function cambiarEstadoUsuario(pk_usuario){
         // activar o desactivar usuario
         var response;
            $.ajax({
                    type:"POST",
                    async: false,
                    url:"<?php echo URL_SITIO?>scripts/datos_ajax.php",
                    data: {"request" : "cambiarEstadoUsuario", 
                           "PK_Usuario" : pk_usuario},
                    success:function(r){
                        console.log(r);
                    }
            });
    };

</script>


	


<?php include 'footer_admin.php' ?>

