<?php include 'header_admin.php' ?> 
<?php 
error_reporting(E_ALL);
ini_set('display_errors', '1');
	//include '../scripts/comprobaciones.php';


require ('../scripts/comprobaciones.php'); 


$pagina = false;
$items_por_pagina = 2;

if (isset($_GET["pagina"])) {
    $pagina = $_GET["pagina"];
}

if (!$pagina) {
    $inicio = 0;
    $pagina = 1;
} else {
    $inicio = ($pagina - 1) * $items_por_pagina;
}

//Consulta seleccionar usuarios admin
$select_usuarios_admin_total = $pdo->prepare("SELECT * FROM Usuarios WHERE FK_TipoUsuario = 3");
$select_usuarios_admin_total->execute();
$lista_usuarios_admin_total = $select_usuarios_admin_total->fetchAll(PDO::FETCH_ASSOC);


//Consulta seleccionar usuarios admin
$select_usuarios_admin = $pdo->prepare("SELECT * 
                                        FROM Usuarios 
                                        WHERE FK_TipoUsuario = 3 
                                        ORDER BY PK_Usuario DESC LIMIT ". $inicio .", " . $items_por_pagina . "");
$select_usuarios_admin->execute();
$lista_usuarios_admin = $select_usuarios_admin->fetchAll(PDO::FETCH_ASSOC);

//calculo el total de paginas
$total_pages = ceil(count($lista_usuarios_admin_total) / $items_por_pagina);


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
                            <a style="border:1px solid #F8F8F8" href="Nuevo-Usuario-Admin" type="submit" class="col-md-12 btn btn-primary">Nuevo</a>
                    </li>
                    <li class="list-group-item">
                            <a style="border:1px solid #F8F8F8" href="Usuarios-Admin" type="submit" class="col-md-12 btn btn-primary">Ver todos</a>
                    </li>
                    
                </ul>
            </div>
        </div>
<div style="height:100%;margin-bottom:60px;" class="col-md-10 ">
<div id="mensaje-success" class="alert alert-success" role="alert"></div>
<div id="mensaje-error" class="alert alert-danger" role="alert"></div>
<div id="mensaje-warning" class="alert alert-warning" role="alert"></div>
    <div class="card">
    <div class="card-body">
        
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Foto</th>
                    <th scope="col">Usuario</th>
                    <th scope="col">Correo</th>
                    <th scope="col">Estado</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($lista_usuarios_admin as $usuario_Admin){ ?>
                <tr WIDTH="100%">
                    <td  WIDTH="30%" ><div class="cont_imagen"><img id="imagen_<?php echo $usuario_Admin['PK_Usuario']?>" class="col-md-12 imagen" src="<?php echo URL_SITIO ?>uploads/img/perfiles/<?php echo ($usuario_Admin['Foto'] != "")?$usuario_Admin['Foto']:"no-foto.png"; ?>" alt=""></div ></td>
                    <td id="nombreUsuario_<?php echo $usuario_Admin['PK_Usuario']?>" WIDTH="20%"><?php echo $usuario_Admin['NombreUsuario'] ?></td>
                    <td id="correo_<?php echo $usuario_Admin['PK_Usuario']?>"  WIDTH="40%"><?php echo $usuario_Admin['Correo'] ?></td>
                    <input type="hidden" id="contrasena_<?php echo $usuario_Admin['PK_Usuario']?>"  WIDTH="40%" value="<?php echo openssl_decrypt($usuario_Admin['Contrasena'], COD, KEY)?>" >
                    
                    <td id="estado_<?php echo $usuario_Admin['PK_Usuario']?>" WIDTH="10%"><?php echo ($usuario_Admin['Estado']==1)?'<label class="switch">
                                                                                                                                        <input onClick="cambiarEstadoUsuario('. $usuario_Admin["PK_Usuario"] .')" class="check" type="checkbox" checked>
                                                                                                                                        <span class="slider round"></span>
                                                                                                                                    </label>':
                                                                                                                                    '<label class="switch">
                                                                                                                                        <input onClick="cambiarEstadoUsuario('. $usuario_Admin["PK_Usuario"] .')" class="check" type="checkbox">
                                                                                                                                        <span class="slider round"></span>
                                                                                                                                    </label>'; ?></td>
                    <td><button onClick="editar(<?php echo $usuario_Admin['PK_Usuario'] ?>)" type="button" class="btn btn-edit" data-toggle="modal" data-target=".modal-editar"><i class="fas fa-edit mr-2"></i></button></td>
                    <td><button onClick="eliminar(<?php echo $usuario_Admin['PK_Usuario'] ?>)" type="button" class="btn btn-eliminar" data-toggle="modal" data-target=".modal-eliminar"><i class="fas fa-trash-alt mr-2"></i></button></td>
                </tr>
                <?php }?>
            </tbody>
        </table>

    </div>
    </div>
    <br>
    <?php 

echo '<nav class="col-md-12">';
echo '<ul class="pagination" >';

if ($total_pages > 1) {
    if ($pagina != 1) {
        echo '<li class="page-item"><a class="page-link" href="Usuarios-Admin?pagina='.($pagina-1).'"><span aria-hidden="true">&laquo;</span></a></li>';
    }

    for ($i=1;$i<=$total_pages;$i++) {
        if ($pagina == $i) {
            echo '<li class="page-item active"><a class="page-link" href="#">'.$pagina.'</a></li>';
        } else {
            echo '<li class="page-item"><a class="page-link" href="Usuarios-Admin?pagina='.$i.'">'.$i.'</a></li>';
        }
    }

    if ($pagina != $total_pages) {
        echo '<li class="page-item"><a class="page-link" href="Usuarios-Admin?pagina='.($pagina+1).'"><span aria-hidden="true">&raquo;</span></a></li>';
    }
}
echo '</ul>';
echo '</nav>';

?> 
</div>
</div>



<!-- modal para editar -->
<div class="modal fade modal-editar" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class=" md-content col-md-12">
                <br>
                <label class="text-center col-md-12" for=""><strong>Editar Usuario</strong></label>
          
            <div style="height:100%;margin-bottom:60px;" class="col-md-12 offset-md-0 bordered">
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
                        <input type="hidden" value="editar_usuario" name="action">

                        <div class="text-center col-md-12">
                            <button id="btnEditar" type="submit" data-dismiss="modal" class=" btn-modal btn btn-primary col-md-5">Editar</button>&nbsp&nbsp&nbsp
                            <button id="btnCancelar" type="" data-dismiss="modal" class=" btn-modal btn btn-secondary col-md-5">Cancelar</button>
                        </div>
                        
                    </form>

                </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

<!-- modal para eliminar -->
<div class="modal fade modal-eliminar" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class=" md-content col-md-12">
                <br>
                <label class="text-center col-md-12" for=""><strong>Eliminar usuario</strong></label>
          
            <div style="height:100%;margin-bottom:60px;" class="col-md-12 offset-md-0 bordered">
                <div class="card">
                <div class="card-body">
                    <form id="form-eliminar" action="<?php echo URL_SITIO?>scripts/usuarios_admin.php" method="post" enctype="multipart/form-data">
                      
                        <label for="">¿Seguro que desea eliminar el usuario "<span id="nombreUsuarioEl"></span>"?</label>

                        <br>
                        <br>
                        <br>
                        <br>

                        <input type="hidden" id="PK_UsuarioEl" name="PK_Usuario">
                        <input type="hidden" value="eliminar_usuario" name="action">

                        <div class="text-center col-md-12">
                        <button id="btnEliminar" type="submit" data-dismiss="modal" class=" btn-modal btn btn-danger col-md-5">Eliminar</button>&nbsp&nbsp&nbsp
                        <button id="btnCancelar" type="" data-dismiss="modal" class=" btn-modal btn btn-secondary col-md-5">Cancelar</button>
                        </div>
                        
                    </form>

                </div>
                </div>
            </div>
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
        if( $msj == 'editado'){ 
    ?>
        $('#mensaje-success').html('<i class="fa fa-check"></i>Usuario actualizado exitosamente');
        $('#mensaje-success').show();
    <?php } elseif( $msj == 'eliminado'){ ?>
        $('#mensaje-success').html('<i class="fa fa-check"></i>Usuario eliminado.');
        $('#mensaje-success').show();
    <?php } elseif( $msj == 'error_1'){ ?>
        $('#mensaje-error').html('No se puede eliminar el usuario porque es el único administrador');
        $('#mensaje-error').show();
    <?php } ?>

    $('#btnEditar').click(function(e){
        e.preventDefault();

        // consultar existencia de correo
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

     
    $('#btnEliminar').click(function(e){
        e.preventDefault();
        
        $('#form-eliminar').submit();
    })

    function toast(msj){
        $('.alert-warning').html(msj);
        $('.alert-warning').css("display", "");
    }

    var nombre_actual;
     function editar(pk_usuario){
         var src_img = $('#imagen_' + pk_usuario).attr('src');
         $('#inputNombreUsuario').val($('#nombreUsuario_'+pk_usuario)[0].innerText);
         $('#inputCorreo').val($('#correo_'+pk_usuario)[0].innerText);
         $('#inputContrasena').val($('#contrasena_'+pk_usuario).val());
         $('#showImagen').attr('src', src_img);
         $('#PK_Usuario').val(pk_usuario);

         nombre_actual = $('#nombreUsuario_'+pk_usuario)[0].innerText;
     }

     function eliminar(pk_usuario){
         $('#nombreUsuarioEl').html($('#nombreUsuario_'+pk_usuario)[0].innerText);
         $('#PK_UsuarioEl').val(pk_usuario);

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

