<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include '../global/config.php';
include '../global/conexion.php';
include '../global/const.php';

session_start();


$select_config = $pdo->prepare("SELECT * FROM Configuracion");
$select_config->execute();
$configuracion = $select_config->fetchAll(PDO::FETCH_ASSOC);

 //Consulta seleccionar paises
 $select_paises = $pdo->prepare("SELECT * FROM Paises");
 $select_paises->execute();
 $listaPaises = $select_paises->fetchAll(PDO::FETCH_ASSOC);

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
 
 
//  if($usuario[0]['EstadoCorreo'] == 0){
//      header('Location: Login?p=nc');
//  }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Complete profile</title>

   <!-- Imports -->
   <link href="<?php echo URL_SITIO ?>static/css/styles.css" rel="stylesheet" type="text/css" media="all" />
    <link href="<?php echo URL_SITIO ?>static/css/registro_datos.css" rel="stylesheet" type="text/css" media="all" />
    <link href="<?php echo URL_SITIO ?>static/css/completar_perfil.css" rel="stylesheet" type="text/css" media="all" />
    <link href="<?php echo URL_SITIO ?>static/css/toasts.css" rel="stylesheet" type="text/css" media="all" />
    <script src="<?php echo URL_SITIO ?>static/js/jquery-3.5.0.min.js" ></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
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

    <div class="col-md-2">
        <!-- <div class="card card-left">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <form action="" method="POST">
                        <input type="hidden" name="menu" value="registro_categoria" />
                        <button type="submit" class="col-md-12 btn btn-primary">Completar perfil</button>
                    </form>
                </li>
            </ul>
        </div> -->
    </div>

    <div style="height:100%;margin-bottom:60px;" class="col-md-8 ">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title text-right">Complete su perfil</h5>
                <br>
                <form action="<?php echo URL_SITIO ?>scripts/completar_perfil_cliente.php" id="form_completar_cliente" method="post" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputPrimerNombre">Primer nombre <span class="text_required">*</span> </label>
                            <input type="text" class="form-control" name="input_primerNombre" id="inputPrimerNombre" placeholder="">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputSegundoNombre">Segundo nombre</label>
                            <input type="text" class="form-control" name="input_segundoNombre" id="inputSegundoNombre" placeholder="">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputPrimerApellido">Primer apellido <span class="text_required">*</span> </label>
                            <input  type="text" class="form-control" name="input_primerApellido" id="inputPrimerApellido" placeholder="">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputSegundoApellido">Segundo apellido</label>
                            <input type="text" class="form-control" name="input_segundoApellido"  id="inputSegundoApellido" placeholder="">
                        </div>
                    </div>
                    <div class="form-group">
                            <label for="inputAddress">Telefono <span class="text_required">*</span> </label>
                            <input  type="phone" class="form-control solo-numeros" name="input_telefomo" id="inputTelefono" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="inputDireccion">Dirección 1<span class="text_required">*</span> </label>
                            <input  type="text" class="form-control" name="input_direccion1" id="inputDireccion1" placeholder="">
                        </div> 
                    <div class="form-group">
                            <label for="inputDireccion2">Dirección 2</label>
                            <input type="text" class="form-control" name="input_direccion2" id="inputDireccion2" placeholder="">
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputPais">País<span class="text_required">*</span> </label>
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
                                <label for="inputCiudad">Ciudad <span class="text_required">*</span> </label>
                                <div id="cont_cbo_ciudad">
                                    <select  id="inputCiudad" name="input_ciudad" class="form-control">
                                        <option selected>- Seleccione -</option>
                                    </select> 
                                </div>
                            </div>
                        </div>
                    <label for="inputAddress2">Foto de perfil</label>
                    <div class="custom-file">
                        <input type="file" accept="image/*" class="custom-file-input" id="inputImagen" name="input_imagen">
                        <label class="custom-file-label" for="customFile">Seleccionar archivo</label>
                    </div>
                    <br>
                    <br>
                    <div class="col-md-12 cont-acuerdo">
                        Shoppingapp cobrará una comisión de $<?php echo $configuracion[0]['Comision'] ?> por cada compra de $0 a $<?php echo $configuracion[0]['PorCada'] ?>
                        <br><br>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="inputAcuerdo">
                            <label class="form-check-label" for="inputAcuerdo"> Estoy de acuerdo</label>
                        </div>
                    </div>
                    <br>
                    <input type="hidden" name="action" value="completar" >
                    <button type="submit" class="btn-flat col-md-8 offset-md-2">Completar</button>
                </form>

            </div>
        </div>
    </div>
    
</div>    



<script type="text/javascript">

    

    $("#mensaje_alert").css("visibility", "hidden");

    $('#inputPais').change(function(){
		    recargarListaCiudad();
	});

    $('.solo-numeros').keyup(function (){
        this.value = (this.value + '').replace(/[^0-9]/g, '');
    });


    if($('#perfil_incompleto').val() == 1){
        mostrarMensaje("Debe completar su perfil.");
        ocultarMensaje();
    }

    $('#inputImagen').bind('change', function() {
            var peso = this.files[0].size/1024/1024;
            if(peso > 5){
                toast('Imagen demasiado pesada, debe pesar menos de 5 MB');
                this.val("");
            };
    });

    $('#form_completar_cliente').on('submit', function (event) {
            event.preventDefault();

        if( $('#inputPrimerNombre').val() == '' || $('#inputPrimerApellido').val() == '' || $('#inputTelefono').val() == '' || $('#inputDireccion1').val() == '' || $('#inputPais')[0].selectedIndex == 0 || $('#inputCiudad')[0].selectedIndex == 0 ){
            toast("Falta uno o más campos");
        }else if($('#inputAcuerdo').prop("checked") == false){
            toast("Debes aceptar el acuerdo de comisión");
        }else{
            $('#form_completar_cliente').unbind('submit').submit();
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

    $('.nav-link').click(function(e){
        e.preventDefault();
    });
    $('.dropdown-menu').hide()


      // Add the following code if you want the name of the file appear on select
      $(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

</script>

<?php require ('footer.php') ?>
</body>
</html>