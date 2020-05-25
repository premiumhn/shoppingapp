<?php

require ('../scripts/comprobaciones.php');

//Consulta seleccionar usuario
$select_usuario = $pdo->prepare("SELECT * FROM Usuarios WHERE PK_Usuario = :PK_Usuario");
$select_usuario->bindParam(':PK_Usuario', $_SESSION['login_user']);
$select_usuario->execute();
$usuario = $select_usuario->fetchAll(PDO::FETCH_ASSOC);

//Consulta seleccionar Tienda
$select_tienda = $pdo->prepare("SELECT * FROM Tiendas WHERE FK_Usuario = :PK_Usuario");
$select_tienda->bindParam(':PK_Usuario', $_SESSION['login_user']);
$select_tienda->execute();
$tienda = $select_tienda->fetchAll(PDO::FETCH_ASSOC);

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
$fk_ciudad = $tienda[0]['FK_Ciudad'];
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

<link href="<?php echo URL_SITIO ?>static/css/perfil_tienda.css" rel="stylesheet" type="text/css" media="all" />
<div  role="alert" data-delay="5000" aria-live="assertive" aria-atomic="true" id="toast_mensaje" class="toast" data-autohide="true">
        <div class="toast-body">
        </div>
</div> 
<div class="col-md-2 data">
            <div class="card card-left">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <form action="" method="POST">
                            <input type="hidden" name="menu" value="perfil_tienda" />
                            <button type="submit" class="col-md-12 btn btn-primary">Datos de la tienda</button>
                        </form>
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
                    <img class="crop img_p" src="<?php echo URL_SITIO ?>uploads/img/logos/<?php echo $tienda[0]['Logo'] ?>" />
                </div>
                <div class="col-md-8">
                    <div id="contInputImagen" class="custom-file onEdit">
                        <input type="file" accept="image/*" class="custom-file-input" id="inputLogo" name="input_logo">
                        <label class="custom-file-label" data-browse="Elegir" for="customFile">Cambiar logo</label>
                    </div>
                    <label class=" text-bold text-big onInfo" id="nombreCliente" for=""><?php echo $tienda[0]['NombreTienda'] ?></label>
                </div>
            </div>
            <div class="cont col-md-12" >
                <button id="btnEditar" class=" btn btn-primary onInfo"> <i class="fa fa-edit"></i>&nbsp;Editar perfil</button>
            </div>

            <div class="row form-group">
                <label class="col-md-12 text-bold" for="inputAddress2">Nombre del contacto</label>
                <label class="col-md-12 onInfo" id="nombreContacto" for=""><?php echo $tienda[0]['NombreContacto']?></label>
                <input class="col-md-12 onEdit" id="inputNombreContacto" name="input_nombreContacto" type="text" value="<?php echo $tienda[0]['NombreContacto']?>">
            </div>
            <br>
            <div class="row form-group">
                <label class="col-md-12 text-bold" for="inputAddress2">Apellido del contacto</label>
                <label class="col-md-12 onInfo" id="apellidoContacto" for=""><?php echo $tienda[0]['ApellidoContacto']?></label>
                <input class="col-md-12 onEdit" id="inputapellidoContacto" name="input_apellidoContacto" type="text" value="<?php echo $tienda[0]['ApellidoContacto']?>">
            </div>
            <br>
            <div class="row form-group">
                <label class="col-md-12 text-bold" for="inputAddress2">Correo</label>
                <label class="col-md-12 onInfo" id="correo" for=""><?php echo $usuario[0]['Correo']?></label>
                <input class="col-md-12 onEdit" id="inputCorreo" name="input_correo" type="text" value="<?php echo $usuario[0]['Correo']?>">
            </div>
            <br>
            <div class="row form-group">
                <label class="col-md-12 text-bold" for="inputAddress2">Contraseña</label>
                <label class="col-md-12 onInfo" id="contrasena" for=""><?php $cont=0; $len=strlen($usuario[0]['Contrasena']); while( $len > $cont){ echo '*'; $cont+=1;}; ?></label>
                <input class="col-md-12 onEdit" id="inputContrasena" name="input_contrasena" type="password" value="<?php echo $usuario[0]['Contrasena']?>">
               
                <div class="col-md-12 onEdit" id="mostrar_pass">
                <input  id="show-pass" type="checkbox" onclick="showpass()">
                <label for="show-pass">Mostrar contraseña</label>
                </div>
                
            </div>
            <br>
            <div class="row form-group">
                <label class="col-md-12 text-bold" for="">ID Cliente PayPal</label>
                <label class="col-md-12 onInfo" id="idClientePaypal" for=""><?php echo $tienda[0]['IDClientePaypal']?></label>
                <input class="col-md-12 onEdit" id="inputIdClientePaypal" name="input_idClientePaypal" type="text" value="<?php echo $tienda[0]['IDClientePaypal']?>">
            </div>
            <br>
            <div class="row form-group">
                <label class="col-md-12 text-bold" for="">Servicio a domicilio</label>
                <label class="col-md-12 onInfo" id="idClientePaypal" for=""><?php echo (($tienda[0]['Adomicilio'] == 1)?"Si":"No") ?></label>
                <div class=" form-check onEdit">
                    <input <?php echo (($tienda[0]['Adomicilio'] == 1)?"checked":"") ?> class=" col-md-12 form-check-input" type="radio" name="input_adomicilio" id="inputRadioSi" value="1">
                    <label class="form-check-label" for="inputRadioSi">
                        Si
                    </label>
                </div>
                <div class="form-check onEdit">
                    <input <?php echo (($tienda[0]['Adomicilio'] == 0)?"checked":"") ?> class="col-md-12 form-check-input" type="radio" name="input_adomicilio" id="inputRadioNo" value="0">
                    <label class="form-check-label" for="inputRadioNo">
                        No
                    </label>
                </div>
            </div>
            <br>
            <div class="row form-group">
                <label class="col-md-12 text-bold" for="inputAddress2">Idioma por defecto</label>
                <label class="col-md-12 onInfo" id="idioma" for=""><?php echo $idioma[0]['idioma']?></label>
                    <select class="col-md-12 form-control onEdit" id="inputIdioma" name="input_idioma" >
                        <?php foreach($idiomas as $i){ ?>
                            <option <?php echo ($i['PK_Idioma'] == $idioma[0]['PK_Idioma'])?"selected":""; ?> value="<?php echo $i['PK_Idioma'] ?>" ><?php echo $i['idioma'] ?></option>
                        <?php } ?>
                    </select>
            </div>
            <br>
            <div class="row form-group">
                <label class="col-md-12 text-bold" for="inputAddress2">Dirección 1</label>
                <label class="col-md-12 onInfo" id="direccion1" for=""><?php echo $tienda[0]['Direccion1']?></label>
                <input class="col-md-12 onEdit" id="inputDireccion1" name="input_direccion1" type="text" value="<?php echo $tienda[0]['Direccion1']?>">
            </div>
            <br>
            <div class="row form-group">
                <label class="col-md-12 text-bold" for="inputAddress2">Dirección 2</label>
                <label class="col-md-12 onInfo" id="direccion2" for=""><?php echo $tienda[0]['Direccion2']?></label>
                <input class="col-md-12 onEdit" id="inputDireccion2" name="input_direccion2" type="text" value="<?php echo $tienda[0]['Direccion2']?>">
            </div>
            <br>
            <div class="row form-group">
                <label class="col-md-12 text-bold" for="inputAddress2">Teléfono</label>
                <label class="col-md-12 onInfo" id="telefono" for=""><?php echo $tienda[0]['Telefono']?></label>
                <input class="col-md-12 solo-numeros onEdit" id="inputTelefono" name="input_telefono" type="tel" value="<?php echo $tienda[0]['Telefono']?>">
            </div>
            <br>
            <div class="row form-group">
                <label class="col-md-12 text-bold" for="">Sitio web</label>
                <label class="col-md-12 onInfo" id="sitioweb" for=""><?php echo $tienda[0]['SitioWeb']?></label>
                <input class="col-md-12 onEdit" id="inputsitioWeb" name="input_sitioWeb" type="text" value="<?php echo $tienda[0]['SitioWeb']?>">
            </div>
            <br>
            <div class="row form-group">
                <label class="col-md-12 text-bold" for="inputAddress2">País</label>
                <label class="col-md-12 onInfo" id="pais" for=""><?php echo $pais[0]['NombrePais']?></label>
                    <select class="col-md-12 form-control onEdit" id="inputPais" name="input_pais" >
                        <?php foreach($paises as $p){ ?>
                            <option <?php echo ($p['PK_Pais'] == $pais[0]['PK_Pais'])?"selected":""; ?> value="<?php echo $p['PK_Pais'] ?>" ><?php echo $p['NombrePais'] ?></option>
                        <?php } ?>
                    </select>
            </div>
            <br>
            <div class="row form-group">
                <label class="col-md-12 text-bold" for="inputAddress2">Ciudad</label>
                <label class="col-md-12 onInfo" id="ciudad" for=""><?php echo $ciudad[0]['NombreCiudad']?></label>
                <div class="onEdit" style="width:100%">
                    <div class="sinCambiarPais" style="width:100%;" id="">
                        <select class="col-md-12 form-control " id="inputCiudad" name="input_ciudad" >
                            <?php foreach($ciudades as $c){ ?>
                                <option <?php echo ($c['PK_Ciudad'] == $ciudad[0]['PK_Ciudad'])?"selected":""; ?> value="<?php echo $c['PK_Ciudad'] ?>" ><?php echo $c['NombreCiudad'] ?></option>
                            <?php } ?>
                        </select>
                    </div> 
                    <div class="alCambiarPais" style="width:100%;" id="cont_cbo_ciudad">
                        <select class="col-md-12 form-control " id="inputCiudad" name="input_ciudad" >
                            <?php foreach($ciudades as $c){ ?>
                                <option <?php echo ($c['PK_Ciudad'] == $ciudad[0]['PK_Ciudad'])?"selected":""; ?> value="<?php echo $c['PK_Ciudad'] ?>" ><?php echo $c['NombreCiudad'] ?></option>
                            <?php } ?>
                        </select>
                    </div> 
                </div>
                
            </div>
            <br>
            <div class="row form-group">
            <label class="col-md-12 text-bold" for="inputAddress2">Portada</label>
                <div id="" class="custom-file onEdit">
                        <input type="file" accept="image/*" class="custom-file-input" id="inputPortada" name="input_portada">
                        <label class="custom-file-label" data-browse="Elegir" for="customFile">Cambiar portada</label>
                </div>
                <br>
                <div class="col-md-12 containerPor">
                    <img class="crop img_por" src="<?php echo URL_SITIO ?>uploads/img/portadas/<?php echo $tienda[0]['Portada'] ?>" />
                    <div class="col-md-8">
                </div>
                </div>
            </div>
            <br>
            <br>
          <div class="col-md-12 form-group text-center">
                <input type="hidden" name="action" value="editar_tienda">
                <input style="padding:10px;border-radius:5px!important;" type="button" class="btn btn-primary col-md-4 onEdit" id="btnActualizar" value="Actualizar">
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input style="padding:10px;" type="button" class="btn btn-secondary col-md-4 onEdit" id="btnCancelar" value="Cancelar">
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
            <li class=""> <a href="<?php echo URL_SITIO ?>Home">Productos</a> </li>
            <li class=""> <a href="<?php echo URL_SITIO ?>Registro-Datos?menu=registro_regionesEnvio">Regiones de envío</a> </li>
        </ul>
    </div>
</div>
<br><br><br><br><br><br><br><br>

<script type="text/javascript">

    $('#mensaje-success').hide();
    $('#mensaje-error').hide();

    <?php 
        $msj = (isset($_GET['msj']))?$_GET['msj']:"";
        if( $msj == 'editada'){ 
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


    $('.alCambiarPais').hide()
    $('.onEdit').hide();

    


    $('#btnEditar').click(function(e){
        e.preventDefault();
       
        $('.onInfo').hide();
        $('.onEdit').show();

    });

    $('#btnCancelar').click(function(e){
        e.preventDefault();
       
        $('.onInfo').show();
        $('.onEdit').hide();
    });

    $('#btnActualizar').click(function(e){
        e.preventDefault()


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
        
        if($('#inputNombreContacto').val() == "" || $('#inputApellidoContacto').val() == "" || $('#inputCorreo').val() == "" || $('#inputContrasena').val() == "" || $('#inputIdClientePaypal').val() == "" || $('#inputDireccion1').val() == "" || $('#inputTelefono').val() == ""){
            toast('Faltan uno o más campos');
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

    // vista previa imagen logo
    function vistaPrevia(input){
        if(input.files && input.files[0]){
            var reader = new FileReader();
            
           
            reader.onload = function(e){
                $('.containerImg').html("<img class='crop img_p' src='"+ e.target.result +"' >");
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#inputLogo').change(function(){
        vistaPrevia(this);
    });

    // vista previa imagen portada
    function vistaPreviaPor(input){
        if(input.files && input.files[0]){
            var reader = new FileReader();
            
           
            reader.onload = function(e){
                $('.containerPor').html("<img class='crop img_por' src='"+ e.target.result +"' >");
               
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    

    $('#inputPortada').change(function(){
        vistaPreviaPor(this);
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