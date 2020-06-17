<?php
require ('../scripts/comprobaciones.php'); 



//Consulta seleccionar categorías
$select_regiones = $pdo->prepare("SELECT * 
                                 FROM RegionesEnvio re INNER JOIN Ciudades c 
                                 ON re.FK_Ciudad = c.PK_Ciudad INNER JOIN Paises p
                                 ON p.PK_Pais = c.FK_Pais INNER JOIN Tiendas t
                                 ON t.PK_Tienda = re.FK_Tienda 
                                 WHERE FK_Tienda = :FK_Tienda");
$select_regiones->bindParam('FK_Tienda', $_SESSION['PK_Tienda']);                                 
$select_regiones->execute();
$listaRegioes = $select_regiones->fetchAll(PDO::FETCH_ASSOC);

//Consulta seleccionar paises
$select_paises = $pdo->prepare("SELECT * FROM Paises");
$select_paises->execute();
$listaPaises = $select_paises->fetchAll(PDO::FETCH_ASSOC);

//Consulta seleccionar ciudades
$select_ciudades = $pdo->prepare("SELECT * FROM Ciudades");
$select_ciudades->execute();
$listaCiudades = $select_ciudades->fetchAll(PDO::FETCH_ASSOC);

// buscar tienda
$buscar_tienda = $pdo->prepare('SELECT * FROM Tiendas
                                WHERE FK_Usuario = :FK_Usuario');
$buscar_tienda->bindParam('FK_Usuario', $_SESSION['login_user']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$buscar_tienda->execute();
$tienda = $buscar_tienda->fetchAll(PDO::FETCH_ASSOC);


?>
<link href="<?php echo URL_SITIO ?>static/css/regiones_envio.css"rel="stylesheet">
<div  role="alert" data-delay="5000" aria-live="assertive" aria-atomic="true" id="toast_mensaje" class="toast" data-autohide="true">
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
<div class="col-md-8">
    <div class="card mb-3 ">
        <div class="card-header">
            <i class="fas fa-table"></i>
               Regiones de Envío
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead class="text-center">
                        <tr>
                            <!-- <th hidden>ID</th> -->
                            <th scope="col">Pais</th>
                            <th scope="col">Ciudad</th>
                            <th scope="col">Costo adicional de envío</th>
                            <th scope="col" style="color:white;" class="no_border-r"></th>
                            <th scope="col" style="color:white;" class="no_border-l"></th>
                        </tr>
                    </thead>
                    <tbody> 
                        <?php foreach ($listaRegioes as $region) {?>
                            <tr WIDTH="100%">
                                <td  WIDTH="30%"> <input id="pais_<?php echo $region['PK_RegionEnvio']?>" type="hidden" value="<?php echo $region['PK_Pais'] ?>"><?php echo $region['NombrePais'] ?></td>
                                <td  WIDTH="20%"><input id="ciudad_<?php echo $region['PK_RegionEnvio']?>" type="hidden" value="<?php echo $region['PK_Ciudad'] ?>"><?php echo $region['NombreCiudad'] ?></td>
                                <td  WIDTH="40%">$ <input style="border:0px;" disabled id="precioEnvio_<?php echo $region['PK_RegionEnvio']?>"  type="text" value="<?php echo $region['PrecioEnvio'] ?>"></td>
                                <td class="no_border-r"><button onClick="editar(<?php echo $region['PK_RegionEnvio']?>)" type="button" class="btn btn-edit" data-toggle="modal" data-target=".modal-editar"><i class="fas fa-edit"></i></button></td>
                                <td class="no_border-l"><button onClick="eliminar(<?php echo $region['PK_RegionEnvio']?>)" type="button" class="btn btn-eliminar" data-toggle="modal" data-target=".modal-eliminar"><i class="fas fa-trash-alt mr-2"></i></button></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer small text-muted">Listado de Regiones de Envío</div>
    </div>
</div> 
<div class="col-md-2 ">
<div class="card card-right" style="width">
        <div class="card-body">
            <h5 class="card-title">Atajos</h5>
        </div>
        <ul class="">
            <li class=""> <a href="">Usuarios</a> </li>
            <li class=""> <a href="">Paises</a> </li>
            <li class=""> <a href="">Ciudades</a> </li>
        </ul>
    </div>
</div>

<!-- modal para editar -->
<div class="modal fade modal-editar" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class=" md-content col-md-12">
           <br>
                <label class="text-center col-md-12" for=""><strong>Editar region de envío</strong></label>
          
            <div style="height:100%;margin-bottom:60px;" class="col-md-12 offset-md-0 bordered">
                <div class="card">
                <div class="card-body">
                <form id="form-edit" action="<?php echo URL_SITIO?>scripts/registro_datos.php" method="post" enctype="multipart/form-data">
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
                        <?php
                            foreach($listaCiudades as $ciudad){
                                echo "<option value='". $ciudad['PK_Ciudad'] ."' >".$ciudad['NombreCiudad']."</option>";
                            }
                        ?>
                    </select> 
                </div>
            </div>
      
            <div class="form-group">
                <label for="inputAddress2">Precio de envío</label>
                <input type="text" class="form-control solo-numeros" name="input_PrecioEnvio" id="inputPrecioEnvio" placeholder="">
            </div>
            <br>
            <br>
            <br>
            <input type="hidden" value="editar_regionEnvio" name="action">
            <input type="hidden" value="<?php echo $tienda[0]['PK_Tienda'] ?>" name="pk_tienda" id="PK_Tienda">
            <input type="hidden" id="PK_RegionEnvio" name="pk_regionEnvio">
                
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
                <label class="text-center col-md-12" for=""><strong>Eliminar región de envío</strong></label>
          
            <div style="height:100%;margin-bottom:60px;" class="col-md-12 offset-md-0 bordered">
                <div class="card">
                <div class="card-body">
                    <form id="form-eliminar" action="<?php echo URL_SITIO?>scripts/registro_datos.php" method="post" enctype="multipart/form-data">
                      
                        <label class="text-center col-md-12" for="">¿Seguro que desea eliminar la región de envío?</label>

                        <br>
                        <br>
                        <br>
                        <br>

                        <input type="hidden" id="PK_RegionEnvioEl" name="pk_regionEnvio">
                        <input type="hidden" value="eliminar_regionEnvio" name="action">

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
    $('.h2-name').html('<?php echo $lregiones_envio ?>');
    $('#titulo_pagina').html('Shoppingapp | <?php echo $lregiones_envio ?>');

    $('#mensaje-success').hide();
    $('#mensaje-error').hide();

    <?php 
        $msj = (isset($_GET['msj']))?$_GET['msj']:"";
        if( $msj == 'editada'){ 
    ?>
        $('#mensaje-success').html('<i class="fa fa-check"></i>Región actualizada exitosamente');
        $('#mensaje-success').show();
    <?php } elseif( $msj == 'eliminada'){ ?>
        $('#mensaje-success').html('<i class="fa fa-check"></i>Región eliminada.');
        $('#mensaje-success').show();
    <?php } ?>

    $('#btnEditar').click(function(e){
        e.preventDefault();

         // consultar existencia de region envío
        var existe_region;
            $.ajax({
                    type:"POST",
                    async: false,
                    url:"<?php echo URL_SITIO?>scripts/datos_ajax.php",
                    data: {"request" : "verificarRegionEnvio", 
                            "PK_Ciudad" : $('#inputCiudad').val(),
                            "PK_Tienda" : $('#PK_Tienda').val()},
                    success:function(r){
                        existe_region = r;
                }
        });

       
       if($('#inputCiudad').prop('selectedIndex') == 0 || $('#inputPais').prop('selectedIndex') == 0 || $('#inputPrecioEnvio').val() == ''){
            toast('Faltan uno o más campos');
        }else if(existe_region == 1 && $('#inputCiudad').val() != $ciudad_actual  ){
            toast('Tu tienda ya tiene esa región registrada, puedes editarla.');
        }else{    
            $('#form-edit').submit();
        }

    });

     
    $('#btnEliminar').click(function(e){
        e.preventDefault();
        
        $('#form-eliminar').submit();
    })

    function toast(msj){
            $('.toast-body').html(msj);
            $('#toast_mensaje').toast('show');
    }

   var $ciudad_actual;
   var $tienda_actual;

     function editar(pk_region){
         
         $('#inputPais').val($('#pais_'+pk_region).val());
         $.ajax({
			type:"POST",
			url:"<?php echo URL_SITIO?>scripts/datos_ajax.php",
            data: {"request" : "selectCiudades", 
                    "FK_Pais" : $('#inputPais').val()},
			success:function(r){
                //console.log(r);
				$('#cont_cbo_ciudad').html(r);
                $('#inputCiudad').val($('#ciudad_'+pk_region).val());
			}
		});
         
         $('#inputPrecioEnvio').val($('#precioEnvio_'+pk_region).val());

         $('#PK_RegionEnvio').val(pk_region);
        
        $ciudad_actual = $('#ciudad_'+pk_region).val();
        $tienda_actual = <?php echo $tienda[0]['PK_Tienda'] ?>;
     }

     function eliminar(pk_categoria){
         $('#PK_RegionEnvioEl').val(pk_categoria);
     }


    $('#inputPais').change(function(){
		    recargarListaCiudad();
	});
    
    function recargarListaCiudad(){
		$.ajax({
			type:"POST",
			url:"<?php echo URL_SITIO?>scripts/datos_ajax.php",
            data: {"request" : "selectCiudades", 
                    "FK_Pais" : $('#inputPais').val()},
			success:function(r){
                //console.log(r);
				$('#cont_cbo_ciudad').html(r);
			}
		});
	}

</script>
