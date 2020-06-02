<?php 

require ('../scripts/comprobaciones.php');

//Consulta seleccionar paises
$select_paises = $pdo->prepare("SELECT * FROM Paises");
$select_paises->execute();
$listaPaises = $select_paises->fetchAll(PDO::FETCH_ASSOC);

// buscar tienda
$buscar_tienda = $pdo->prepare('SELECT * FROM Tiendas
                                WHERE FK_Usuario = :FK_Usuario');
$buscar_tienda->bindParam('FK_Usuario', $_SESSION['login_user']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$buscar_tienda->execute();
$tienda = $buscar_tienda->fetchAll(PDO::FETCH_ASSOC);

?>
<link href="<?php echo URL_SITIO ?>static/css/regiones_envio.css"rel="stylesheet">
<div role="alert" data-delay="5000" aria-live="assertive" aria-atomic="true" id="toast_mensaje" class="toast" data-autohide="true">
        <div class="toast-body">
        </div>
</div> 

<div class="col-md-2 ">
            <div class="card card-left">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <form action="Registro-Datos" method="get">
                            <input type="hidden" name="menu" value="registro_regionesEnvio" />
                            <button type="submit" class="col-md-12 btn btn-primary">Nueva</button>
                        </form>
                    </li>
                    <li class="list-group-item">
                        <form action="Registro-Datos" method="get">
                            <input type="hidden" name="menu" value="ver_regionesEnvio" />
                            <button type="submit" class="col-md-12 btn btn-primary">Ver todas</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
<div style="height:100%;margin-bottom:60px;" class="col-md-7 ">
<div id="mensaje-success" class="alert alert-success" role="alert"></div>
    <div class="card">
    <div class="card-body">
        <form id="formRegistrar" action="<?php echo URL_SITIO ?>scripts/registro_datos.php" method="post" enctype="multipart/form-data">
            <label class="instruccion" for="">A continuación registre una nueva región, donde tu tienda pueda realizar envíos a domicilio.</label>
           <br>
           <br>
            <div class="form-group ">
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
            <div class="form-group">
                <label for="inputCiudad">Ciudad<span class="text_required">*</span> </label>
                <div id="cont_cbo_ciudad">
                    <select  id="inputCiudad" name="input_ciudad" class="form-control">
                        <option selected>- Seleccione -</option>
                    </select> 
                </div>
            </div>
      
            <div class="form-group">
                <label for="inputAddress2">Costo adicional de envío</label>
                <label class="instruccion" for="">En caso de que exista un costo adicional por realizar envíos a esta región, puede agragarlo aquí, de no ser así puede dejarlo en blanco.</label>
                <input type="text" class="form-control solo-numeros" name="input_PrecioEnvio" id="inputPrecioEnvio" placeholder="">
            </div>
            <br>
            <br>
            <br>
            <input type="hidden" value="registrar_regionEnvio" name="action">
            <input type="hidden" value="<?php echo $tienda[0]['PK_Tienda'] ?>" name="pk_tienda" id="PK_Tienda">
            <button id="btnAgregar" class="col-md-8 offset-md-2 btn btn-primary">Agregar</button>
        </form>

    </div>
    </div>
</div>
<div class="col-md-3 ">
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
<script>
     $('#mensaje-success').hide();
 

    <?php 
        $msj = (isset($_GET['msj']))?$_GET['msj']:"";
        if( $msj == 'registrada'){ 
    ?>
        $('#mensaje-success').html('<i class="fa fa-check"></i>Region registrada exitosamente');
        $('#mensaje-success').show();
    <?php } ?>

    $('#btnAgregar').click(function(e){
        e.preventDefault();

        // consultar existencia de region envío
        var existe_region;
            $.ajax({
                    type:"POST",
                    async: false,
                    url:"<?php echo URL_SITIO ?>scripts/datos_ajax.php",
                    data: {"request" : "verificarRegionEnvio", 
                            "PK_Ciudad" : $('#inputCiudad').val(),
                            "PK_Tienda" : $('#PK_Tienda').val()},
                    success:function(r){
                        existe_region = r;
                        console.log(r);
                    }
            });


        if($('#inputCiudad').prop('selectedIndex') == 0 || $('#inputPais').prop('selectedIndex') == 0{
            toast('Faltan uno o más campos');
        }else if(existe_region == 1){
            toast('Tu tienda ya tiene esa región registrada, puedes editarla.');
        }else{    
            $('#formRegistrar').submit();
        }

    });

    $('.solo-numeros').keyup(function (){
            this.value = (this.value + '').replace(/[^0-9]/g, '');
        });

     function toast(msj){
            $('.toast-body').html(msj);
            $('#toast_mensaje').toast('show');
    }

    $('#inputPais').change(function(){
		    recargarListaCiudad();
	});
    
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

    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>