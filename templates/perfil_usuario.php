<?php
require ('../scripts/comprobaciones.php');

//Consulta seleccionar usuario
$select_usuario = $pdo->prepare("SELECT * FROM Usuarios WHERE PK_Usuario = :PK_Usuario");
$select_usuario->bindParam(':PK_Usuario', $_SESSION['login_user']);
$select_usuario->execute();
$usuario = $select_usuario->fetchAll(PDO::FETCH_ASSOC);

//Consulta seleccionar cliente
$select_cliente = $pdo->prepare("SELECT * FROM Clientes WHERE FK_Usuario = :PK_Usuario");
$select_cliente->bindParam(':PK_Usuario', $_SESSION['login_user']);
$select_cliente->execute();
$cliente = $select_cliente->fetchAll(PDO::FETCH_ASSOC);

//Consulta seleccionar Idioma
$fk_idioma = $usuario[0]['FK_Idioma'];
$select_idioma = $pdo->prepare("SELECT * FROM Idiomas WHERE PK_Idioma = :FK_Idioma");
$select_idioma->bindParam(':FK_Idioma', $fk_idioma);
$select_idioma->execute();
$idioma = $select_idioma->fetchAll(PDO::FETCH_ASSOC);

//Consulta seleccionar Idiomas
$select_idiomas = $pdo->prepare("SELECT * FROM Idiomas");
$select_idiomas->execute();
$idiomas = $select_idiomas->fetchAll(PDO::FETCH_ASSOC);

//Consulta seleccionar paises
$select_paises = $pdo->prepare("SELECT * FROM Paises");
$select_paises->execute();
$paises = $select_paises->fetchAll(PDO::FETCH_ASSOC);

//Consulta seleccionar ciudad
$fk_ciudad = $cliente[0]['FK_Ciudad'];
$select_ciudades = $pdo->prepare("SELECT * FROM Ciudades WHERE PK_Ciudad = :FK_Ciudad");
$select_ciudades->bindParam(':FK_Ciudad', $fk_ciudad);
$select_ciudades->execute();
$ciudad = $select_ciudades->fetchAll(PDO::FETCH_ASSOC);

//Consulta seleccionar pais
$fk_pais = $ciudad[0]['FK_Pais'];
$select_paises = $pdo->prepare("SELECT * FROM Paises WHERE PK_Pais = :FK_Pais");
$select_paises->bindParam(':FK_Pais', $fk_pais);
$select_paises->execute();
$pais = $select_paises->fetchAll(PDO::FETCH_ASSOC);

//Consulta seleccionar ciudades
$select_cuidades = $pdo->prepare("SELECT * FROM Ciudades WHERE FK_Pais = :FK_Pais");
$select_cuidades->bindParam(':FK_Pais', $fk_pais);
$select_cuidades->execute();
$ciudades = $select_cuidades->fetchAll(PDO::FETCH_ASSOC);




?>

<link href="<?php echo URL_SITIO ?>static/css/perfil_usuario.css" rel="stylesheet" type="text/css" media="all" />
<div  role="alert" data-delay="5000" aria-live="assertive" aria-atomic="true" id="toast_mensaje" class="toast" data-autohide="true">
        <div class="toast-body">
        </div>
</div> 
<div class="col-md-2 data">
            <div class="card card-left">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <?php if($usuario[0]['FK_TipoUsuario'] == 3){?>
                        <form action="<?php echo URL_SITIO ?>Admin" method="POST">
                            <button type="submit" class="col-md-12 btn btn-primary">Dashboard</button>
                        </form>
                        <?php }else{?>
                            <form action="" method="POST">
                                <input type="hidden" name="menu" value="perfil_usuario" />
                                <button type="submit" class="col-md-12 btn btn-primary">Datos personales</button>
                            </form>
                        <?php } ?>
                    </li>
                </ul>
        </div>
</div>
<div style="height:100%;margin-bottom:60px;" class="col-md-8 data">
<div id="mensaje-success" class="alert alert-success" role="alert"></div>
<div id="mensaje-error" class="alert alert-danger" role="alert"></div>
    <div class="card">
    <div class="card-body" id="cont-form">
        <form id="formEditar" action="<?php echo URL_SITIO ?>scripts/registro_datos.php" method="post" enctype="multipart/form-data">
            <div class="row col-md-12 form-group">
                <div class="containerImg">
                    <img class="crop img_p" src="<?php echo URL_SITIO?>uploads/img/perfiles/<?php echo $usuario[0]['Foto'] ?>" />
                </div>
                <div class="col-md-8">
                    <div id="contInputImagen" class="custom-file">
                            <input type="file" accept="image/*" class="custom-file-input" id="inputImagen" name="input_imagen">
                            <label class="custom-file-label" for="customFile">Cambiar imagen</label>
                    </div>
                    <label class=" text-bold text-big" id="nombreCliente" for=""><?php echo $cliente[0]['PrimerNombre']." ".$cliente[0]['SegundoNombre']." ".$cliente[0]['PrimerApellido']." ".$cliente[0]['SegundoApellido'] ?></label>
                </div>
            </div>

            <div class="cont col-md-12" >
                <button id="btnEditar" class=" btn btn-primary"> <i class="fa fa-edit"></i>&nbsp;Editar perfil</button>
            </div>
            <div id="contPrimerNombre" class="row form-group">
                <label class="col-md-12 text-bold" for="">Primer nombre</label>
                <input class="col-md-12" id="inputPrimerNombre" name="input_primerNombre" type="text" value="<?php echo $cliente[0]['PrimerNombre']?>">
                <br>
            </div>
            
            <div id="contSegundoNombre" class="row form-group">
                <label class="col-md-12 text-bold" for="">Segundo nombre</label>
                <input class="col-md-12" id="inputSegundoNombre" placeholder="Segundo nombre" name="input_segundoNombre" type="text" value="<?php echo $cliente[0]['SegundoNombre']?>">
                <br>
            </div>
            
            <div id="contPrimerApellido" class="row form-group">
                <label class="col-md-12 text-bold" for="">Primer apellido</label>
                <input class="col-md-12" id="inputPrimerApellido" name="input_primerApellido" type="text" value="<?php echo $cliente[0]['PrimerApellido']?>">
                <br>
            </div>
            
            <div id="contSegundoApellido" class="row form-group">
                <label class="col-md-12 text-bold" for="">Segundo apellido</label>
                <input class="col-md-12" id="inputSegundoApellido" placeholder="Segundo apellido"  name="input_segundoApellido" type="text" value="<?php echo $cliente[0]['SegundoApellido']?>">
           <br>
            </div>
            <br>
            <div class="row form-group">
                <label class="col-md-12 text-bold" for="inputAddress2">Nombre de usuario</label>
                <label class="col-md-12" id="nombreUsuario" for=""><?php echo $usuario[0]['NombreUsuario']?></label>
                <input class="col-md-12" id="inputNombreUsuario" name="input_nombreUsuario" type="text" value="<?php echo $usuario[0]['NombreUsuario']?>">
            </div>
            <br>
            <div class="row form-group">
                <label class="col-md-12 text-bold" for="inputAddress2">Correo</label>
                <label class="col-md-12" id="correo" for=""><?php echo $usuario[0]['Correo']?></label>
                <input class="col-md-12" id="inputCorreo" name="input_correo" type="text" value="<?php echo $usuario[0]['Correo']?>">
            </div>
            <br>
            <div class="row form-group">
                <label class="col-md-12 text-bold" for="inputAddress2">Contraseña</label>
                <label class="col-md-12" id="contrasena" for=""><?php $cont=0; $len=strlen($usuario[0]['Contrasena']); while( $len > $cont){ echo '*'; $cont+=1;}; ?></label>
                <input class="col-md-12" id="inputContrasena" name="input_contrasena" type="password" value="<?php echo $usuario[0]['Contrasena']?>">
               
                <div class="col-md-12" id="mostrar_pass">
                <input  id="show-pass" type="checkbox" onclick="showpass()">
                <label for="show-pass">Mostrar contraseña</label>
                </div>
                
            </div>
            <br>
            <div class="row form-group">
                <label class="col-md-12 text-bold" for="inputAddress2">Idioma por defecto</label>
                <label class="col-md-12" id="idioma" for=""><?php echo $idioma[0]['idioma']?></label>
                    <select class="col-md-12 form-control" id="inputIdioma" name="input_idioma" >
                        <?php foreach($idiomas as $i){ ?>
                            <option <?php echo ($i['PK_Idioma'] == $idioma[0]['PK_Idioma'])?"selected":""; ?> value="<?php echo $i['PK_Idioma'] ?>" ><?php echo $i['idioma'] ?></option>
                        <?php } ?>
                    </select>
            </div>
            <br>
            <div class="row form-group">
                <label class="col-md-12 text-bold" for="inputAddress2">Dirección 1</label>
                <label class="col-md-12" id="direccion1" for=""><?php echo $cliente[0]['Direccion1']?></label>
                <input class="col-md-12" id="inputDireccion1" name="input_direccion1" type="text" value="<?php echo $cliente[0]['Direccion1']?>">
            </div>
            <br>
            <div class="row form-group">
                <label class="col-md-12 text-bold" for="inputAddress2">Dirección 2</label>
                <label class="col-md-12" id="direccion2" for=""><?php echo $cliente[0]['Direccion2']?></label>
                <input class="col-md-12" id="inputDireccion2" name="input_direccion2" type="text" value="<?php echo $cliente[0]['Direccion2']?>">
            </div>
            <br>
            <div class="row form-group">
                <label class="col-md-12 text-bold" for="inputAddress2">Teléfono</label>
                <label class="col-md-12" id="telefono" for=""><?php echo $cliente[0]['Telefono']?></label>
                <input class="col-md-12 solo-numeros" id="inputTelefono" name="input_telefono" type="tel" value="<?php echo $cliente[0]['Telefono']?>">
            </div>
            <br>
            <div class="row form-group">
                <label class="col-md-12 text-bold" for="inputAddress2">País</label>
                <label class="col-md-12" id="pais" for=""><?php echo $pais[0]['NombrePais']?></label>
                    <select class="col-md-12 form-control" id="inputPais" name="input_pais" >
                        <?php foreach($paises as $p){ ?>
                            <option <?php echo ($p['PK_Pais'] == $pais[0]['PK_Pais'])?"selected":""; ?> value="<?php echo $p['PK_Pais'] ?>" ><?php echo $p['NombrePais'] ?></option>
                        <?php } ?>
                    </select>
            </div>
            <br>
            <div class="row form-group">
                <label class="col-md-12 text-bold" for="inputAddress2">Ciudad</label>
                <label class="col-md-12" id="ciudad" for=""><?php echo $ciudad[0]['NombreCiudad']?></label>
                <div class="sinCambiarPais" style="width:100%;" id="">
                    <select class="col-md-12 form-control" id="inputCiudad" name="input_ciudad" >
                        <?php foreach($ciudades as $c){ ?>
                            <option <?php echo ($c['PK_Ciudad'] == $ciudad[0]['PK_Ciudad'])?"selected":""; ?> value="<?php echo $c['PK_Ciudad'] ?>" ><?php echo $c['NombreCiudad'] ?></option>
                        <?php } ?>
                    </select>
                </div> 
                <div class="alCambiarPais" style="width:100%;" id="cont_cbo_ciudad">
                    <select class="col-md-12 form-control" id="inputCiudad" name="input_ciudad" >
                        <?php foreach($ciudades as $c){ ?>
                            <option <?php echo ($c['PK_Ciudad'] == $ciudad[0]['PK_Ciudad'])?"selected":""; ?> value="<?php echo $c['PK_Ciudad'] ?>" ><?php echo $c['NombreCiudad'] ?></option>
                        <?php } ?>
                    </select>
                </div> 
            </div>
            <br>

            <div class="col-md-12 form-group text-center">
                <input type="hidden" name="action" value="editar_usuario">
                <input style="padding:10px;border-radius:5px!important;" type="button" class="btn btn-primary col-md-4" id="btnActualizar" value="Actualizar">
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input style="padding:10px;" type="button" class="btn btn-secondary col-md-4" id="btnCancelar" value="Cancelar">
           </div>

        </form>

    </div>
    </div>
</div>
<div class="col-md-2 data">
    <div class="card card-right" style="width">
        <div class="card-body">
            <h5 class="card-title">Atajos</h5>
        </div>
        <ul class="">
            <li class=""> <a href="<?php echo URL_SITIO ?>Home">Inicio</a> </li>
            <li class=""> <a href="<?php echo URL_SITIO ?>Pedidos">Pedidos</a> </li>
            <li class=""> <a href="<?php echo URL_SITIO ?>Carrito">Carrito</a> </li>
        </ul>
    </div>
</div>
<br><br><br><br><br><br><br><br>







<script type="text/javascript">
    $('#lbl-carrito').hide();
    $('#mensaje-success').hide();
    $('#mensaje-error').hide();

    <?php 
        $msj = (isset($_GET['msj']))?$_GET['msj']:"";
        if( $msj == 'editado'){ 
    ?>
        $('#mensaje-success').html('<i class="fa fa-check"></i>Perfil actualizado exitosamente');
        $('#mensaje-success').show();
    <?php } elseif( $msj == 'muypesada'){ ?>
        $('#mensaje-error').html('Imagen demasiado pesada. La imagen debe pesar menos de 3 MB.');
        $('#mensaje-error').show();
    <?php } ?>


    $('#inputContrasena').keypress(function(tecla){
        if(tecla.charCode == 32)
        {
            return false;
        }
    });

    $('.solo-numeros').keyup(function (){
        this.value = (this.value + '').replace(/[^0-9]/g, '');
      });



    $('#inputNombreUsuario').hide();
    $('#inputContrasena').hide();
    $('#mostrar_pass').hide();
    $('#inputCorreo').hide();
    $('#inputIdioma').hide();
    $('#inputDireccion1').hide();
    $('#inputDireccion2').hide();
    $('#inputTelefono').hide();
    $('#inputPais').hide();
    $('#inputCiudad').hide();
    $('#contPrimerNombre').hide();
    $('#contSegundoNombre').hide();
    $('#contPrimerApellido').hide();
    $('#contSegundoApellido').hide();
    $('#btnActualizar').hide();
    $('#btnCancelar').hide();
    $('#contInputImagen').hide();
    $('.alCambiarPais').hide()

    


    $('#btnEditar').click(function(e){
        e.preventDefault();
        $('#mostrar_pass').show();

        $('#contPrimerNombre').show();
        $('#contSegundoNombre').show();
        $('#contPrimerApellido').show();
        $('#contSegundoApellido').show();

        $('#inputNombreUsuario').show();
        $('#nombreUsuario').hide();

        $('#inputContrasena').show();
        $('#contrasena').hide();

        $('#inputCorreo').show();
        $('#correo').hide();

        $('#inputIdioma').show();
        $('#idioma').hide();

        $('#inputDireccion1').show();
        $('#direccion1').hide();

        $('#inputDireccion2').show();
        $('#direccion2').hide();

        $('#inputTelefono').show();
        $('#telefono').hide();

        $('#inputPais').show();
        $('#pais').hide();

        $('#inputCiudad').show();
        $('#ciudad').hide();

        $('#btnCancelar').show();
        $('#btnActualizar').show();
        $('#btnEditar').hide();

        $('#contInputImagen').show();
        $('#nombreCliente').hide();

    });

    $('#btnCancelar').click(function(e){
        e.preventDefault();
        $('#mostrar_pass').hide();

        $('#contPrimerNombre').hide();
        $('#contSegundoNombre').hide();
        $('#contPrimerApellido').hide();
        $('#contSegundoApellido').hide();

        $('#inputNombreUsuario').hide();
        $('#nombreUsuario').show();

        $('#inputContrasena').hide();
        $('#contrasena').show();

        $('#inputCorreo').hide();
        $('#correo').show();

        $('#inputIdioma').hide();
        $('#idioma').show();

        $('#inputDireccion1').hide();
        $('#direccion1').show();

        $('#inputDireccion2').hide();
        $('#direccion2').show();

        $('#inputTelefono').hide();
        $('#telefono').show();

        $('#inputPais').hide();
        $('#pais').show();

        $('#inputCiudad').hide();
        $('#ciudad').show();

        $('#btnCancelar').hide();
        $('#btnActualizar').hide();
        $('#btnEditar').show();

        $('#contInputImagen').hide();
        $('#nombreCliente').show();
    });

    $('#btnActualizar').click(function(e){
        e.preventDefault()

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

        // consultar existencia de telefono
        var existe_telefono;
        $.ajax({
                type:"POST",
                async: false,
                url:"<?php echo URL_SITIO ?>scripts/datos_ajax.php",
                data: {"request" : "verificarTelefono", 
                        "Telefono" : $('#inputTelefono').val()},
                success:function(r){
                    existe_telefono = r;
                }
        });

        // verifical longitud de contraseña
        var str_contrasena = $("#inputContrasena").val()
        var letras_contrasena = str_contrasena.length;


        var usuario_actual = '<?php echo $usuario[0]['NombreUsuario'] ?>';
        
        if($('#inputPrimerNombre').val() == "" || $('#inputPrimerApellido').val() == "" || $('#inputNombreUsuario').val() == "" || $('#inputCorreo').val() == "" || $('#inputContrasena').val() == "" || $('#inputDireccion1').val() == "" || $('#inputDireccion2').val() == "" || $('#inputTelefono').val() == ""){
            toast('Faltan uno o más campos');
        }else if(existe_usuario == 1 && $('#inputNombreUsuario').val() != usuario_actual){
            toast('Ya existe una cuenta con ese nombre de usuario');
        }else if(existe_correo == 1){
            toast('Ya existe una cuenta con ese correo electrónico');
        }else if(existe_telefono == 1){
            toast('Ya existe una cuenta con ese número de teléfono');
        }else if(letras_contrasena < 8){
            toast('La contraseña es muy corta');
        }else{
            $('#formEditar').submit()
        }

        $('#btnEditar').show();
    });

    function showpass() {
        var x = document.getElementById("inputContrasena");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }

    function toast(msj){
        $('.toast-body').html(msj);
        $('#toast_mensaje').toast('show');
    }

    function vistaPrevia(input){
        if(input.files && input.files[0]){
            var reader = new FileReader();
            
           
            reader.onload = function(e){
                $('.containerImg').html("<img class='crop img_p' src='"+ e.target.result +"' >");
                console.log(e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#inputImagen').change(function(){
        vistaPrevia(this);
    });

    $('#inputPais').change(function(){
        
        if($('#inputPais').val() == <?php echo $pais[0]['PK_Pais'] ?>){
            $('.alCambiarPais').hide()
            $('.sinCambiarPais').show()
        }else{
            recargarListaCiudad();
            $('.alCambiarPais').show()
            $('.sinCambiarPais').hide()
        }

    });

    function recargarListaCiudad(){
		$.ajax({
			type:"POST",
			url:"<?php echo URL_SITIO ?>scripts/datos_ajax.php",
            data: {"request" : "selectCiudades", 
                    "FK_Pais" : $('#inputPais').val()},
			success:function(r){
				$('#cont_cbo_ciudad').html(r);
			}
		});
	}


    // 
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>