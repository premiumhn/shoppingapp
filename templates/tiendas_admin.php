<?php include 'header_admin.php' ?> 
<?php 
error_reporting(E_ALL);
ini_set('display_errors', '1');
	//include '../scripts/comprobaciones.php';


require ('../scripts/comprobaciones.php'); 



//Consulta seleccionar usuarios admin
$select_tiendas = $pdo->prepare("SELECT t.NombreTienda, t.PK_Tienda, t.Correo, t.Logo, p.NombrePais, t.Estado 
                                FROM Tiendas t INNER JOIN Ciudades c
                                ON t.FK_Ciudad = c.PK_Ciudad INNER JOIN Paises p
                                ON p.PK_Pais = c.FK_Pais
                                ORDER BY t.PK_Tienda DESC");
$select_tiendas->execute();
$lista_tiendas = $select_tiendas->fetchAll(PDO::FETCH_ASSOC);



?>

<link href="<?php echo URL_SITIO ?>static/css/registro_datos.css"rel="stylesheet">
<link href="<?php echo URL_SITIO ?>static/css/tiendas_admin.css"rel="stylesheet">


<div  role="alert" data-delay="5000" aria-live="assertive" aria-atomic="true" id="toast_mensaje" class="toast" data-autohide="true">
        <div class="toast-body">
        </div>
</div> 

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
    
    <div class="col-md-10">
        <div class="alert alert-success" role="alert"></div>
			<div class="card mb-3 ">
	          	<div class="card-header">
	             	<i class="fas fa-table"></i>
					 <?php echo $ltiendas ?>
	          	</div>
            	<div class="card-body">
              		<div class="table-responsive">
                		<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  			<thead class="text-center">
			                    <tr>
                                    <!-- <th hidden>ID</th> -->
                                    <th scope="col"></th>
                                    <th scope="col"><?php echo $tlogo ?></th>
                                    <th scope="col"><?php echo $tnombre_tienda ?></th>
                                    <!-- <th scope="col"><?php echo $tcorreo ?></th> -->
                                    <th scope="col"><?php echo $tpais ?></th>
                                    <th scope="col"><?php echo $testado ?></th>
                                    <!-- <th scope="col" style="color:white;" class="no_border-r"></th> -->
                                    <th scope="col" style="color:white;" class=""></th>
								</tr>
			                </thead>
                			<tbody> 
                				<?php foreach ($lista_tiendas as $tienda) {?>
									<tr WIDTH="100%">
                                        <th scope="col"><a href="Visualizar-Tienda?PK_Tienda=<?php echo $tienda['PK_Tienda'] ?>" type="button" class="btn btn-edit" ><i class="fas fa-eye mr-2"></i></a></th>
                                        <td  WIDTH="30%" ><div class="cont_imagen"><img id="imagen_<?php echo $tienda['PK_Tienda']?>" class="col-md-12 imagen" src="<?php echo URL_SITIO ?>uploads/img/logos/<?php echo ($tienda['Logo'] != "")?$tienda['Logo']:"no-foto.png"; ?>" alt=""></div ></td>
                                        <td id="nombreTienda_<?php echo $tienda['PK_Tienda']?>" WIDTH="20%"><?php echo $tienda['NombreTienda'] ?></td>
                                        <!-- <td id="correo_<?php echo $tienda['PK_Tienda']?>"  WIDTH="40%"><?php echo $tienda['Correo'] ?></td> -->
                                        <td id="pais_<?php echo $tienda['PK_Tienda']?>"  WIDTH="40%"><?php echo $tienda['NombrePais'] ?></td>
                                        
                                        <td id="estado_<?php echo $tienda['PK_Tienda']?>" WIDTH="10%"><?php echo ($tienda['Estado']==1)?'<label class="switch">
                                                                                                                                                            <input onClick="cambiarEstadoTienda('. $tienda["PK_Tienda"] .')" class="check" type="checkbox" checked>
                                                                                                                                                            <span class="slider round"></span>
                                                                                                                                                        </label>':
                                                                                                                                                        '<label class="switch">
                                                                                                                                                            <input onClick="cambiarEstadoTienda('. $tienda["PK_Tienda"] .')" class="check" type="checkbox">
                                                                                                                                                            <span class="slider round"></span>
                                                                                                                                                        </label>'; ?></td>
                                        <!-- <td class="no_border-r"><button onClick="editar(<?php echo $tienda['PK_Tienda'] ?>)" type="button" class="btn btn-edit" data-toggle="modal" data-target=".modal-editar"><i class="fas fa-edit mr-2"></i></button></td> -->
                                        <td class=""><a onClick="eliminar(<?php echo $tienda['PK_Tienda'] ?>)" type="button" class="btn btn-eliminar" data-toggle="modal" data-target=".modal-eliminar"><i class="fas fa-trash-alt mr-2"></i></a></td>
                                    </tr>
								<?php } ?>
							</tbody>
                		</table>
              		</div>
            	</div>
          		<div class="card-footer small text-muted"><?php echo $ltiendas ?></div>
    		</div>
		</div>
</div>



<!-- modal para editar -->
<div class="modal fade modal-editar" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class=" md-content col-md-12">
                <br>
                <label class="text-center col-md-12" for="">Tienda: <strong> <span id="NombreTiendaV"></span></strong></label>
          
            <div style="height:100%;margin-bottom:60px;" class="col-md-12 offset-md-0 bordered">
                <div class="card">
                <div class="card-body">
                    <form id="form-edit" action="<?php echo URL_SITIO ?>scripts/usuarios_admin.php" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="inputAddress"></label>
                            <input type="text" class="form-control" name="input_nombreUsuario" id="inputNombreUsuario" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="inputAddress2"><?php echo $ucorreo ?></label>
                            <input type="text" class="form-control" name="input_correo" id="inputCorreo" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="inputAddress2"><?php echo $ucontrasena ?></label>
                            <input type="text" class="form-control" name="input_contrasena" id="inputContrasena" placeholder="">
                        </div>
                        <br>
                        <label for="inputAddress2"><?php echo $ufoto ?></label>
                        <div class="col-md-6 offset-md-3">
                            <div class="col-md-12" id="cont_imagen">
                                <img id="showImagen" style="width:100%" src="" alt="">
                            </div>
                        </div>
                        <br>
                        <div class="custom-file">
                            <input type="file" accept="image/*" class="custom-file-input" id="inputImagen" name="input_imagen">
                            <label class="custom-file-label" for="customFile"><?php echo $ucambiar_imagen ?></label>
                        </div>
                        <br>
                        <br>
                        <input type="hidden" id="PK_Usuario" name="PK_Usuario">
                        <input type="hidden" value="editar_usuario" name="action">

                        <div class="text-center col-md-12">
                            <button id="btnEditar" type="submit" data-dismiss="modal" class=" btn-modal btn btn-primary col-md-5"><?php echo $btnEditar ?></button>&nbsp&nbsp&nbsp
                            <button id="btnCancelar" type="" data-dismiss="modal" class=" btn-modal btn btn-secondary col-md-5"><?php echo $btnCancelar ?></button>
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
                <label class="text-center col-md-12" for=""><strong>Eliminar tienda</strong></label>
          
            <div style="height:100%;margin-bottom:60px;" class="col-md-12 offset-md-0 bordered">
                <div class="card">
                <div class="card-body">
                    <form id="form-eliminar" action="<?php echo URL_SITIO?>scripts/registro_tienda.php" method="post" enctype="multipart/form-data">
                      
                        <label for="">¿Seguro que desea eliminar la tienda "<span id="nombreTiendaEl"></span>"?</label>
                        
                        <div class="body-eliminar">
                            <p>Esto eliminará también:</p>
                            <ul>
                                <li>Los pedidos que han hecho a esta tienda.</li>
                                <li>Los productos que estén en carrito de compras de algún cliente, los cuales pertenezcan a esta tienda.</li>
                                <li>Los productos de la tienda.</li>
                            </ul>
                        </div>
                        

                        <br>
                        <br>
                        <br>
                        <br>

                        <input type="hidden" id="PK_TiendaEl" name="PK_Tienda">
                        <input type="hidden" value="eliminar_tienda" name="action">

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
    $('.alert-success').hide();

    $('.h2-name').html('<?php echo $lnombre_pagina ?>');
    $('#titulo_pagina').html('Shoppingapp | <?php echo $lnombre_pagina ?>');

    $('#mensaje-success').hide();
    $('#mensaje-error').hide();

    $('.alert-warning').css("display", "none");

    <?php 
        $msj = (isset($_GET['msj']))?$_GET['msj']:"";
        if( $msj == 'registrada'){ 
    ?>
        $('.alert-success').html('<i class="fa fa-check"></i>Tienda registrada exitosamente');
        $('.alert-success').show('slow');
    <?php } elseif( $msj == 'eliminada'){ ?>
        $('.alert-success').html('<i class="fa fa-check"></i>Tienda eliminada.');
        $('.alert-success').show();
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

    function visualizar(pk_tienda){
        $('#NombreTiendaV').html($('#nombreTienda_'+pk_tienda)[0].innerText);
    }

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

     function eliminar(pk_tienda){
         $('#nombreTiendaEl').html($('#nombreTienda_'+pk_tienda)[0].innerText);
         $('#PK_TiendaEl').val(pk_tienda);

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

    function cambiarEstadoTienda(pk_tienda){
         // activar o desactivar tienda
         var response;
            $.ajax({
                    type:"POST",
                    async: false,
                    url:"<?php echo URL_SITIO?>scripts/datos_ajax.php",
                    data: {"request" : "cambiarEstadoTienda", 
                           "PK_Tienda" : pk_tienda},
                    success:function(r){
                        console.log(r);
                    }
            });
    };

</script>


	


<?php include 'footer_admin.php' ?>

