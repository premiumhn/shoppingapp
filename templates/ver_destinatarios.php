<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include '../global/config.php';
include '../global/conexion.php';
include '../global/const.php';

session_start();



    
    // Buscar cliente
    $buscar_cliente = $pdo->prepare("SELECT * FROM Clientes WHERE FK_Usuario = :PK_Usuario");
    $buscar_cliente->bindParam(":PK_Usuario", $_SESSION['login_user']);
    $buscar_cliente->execute();
    $cliente = $buscar_cliente->fetchAll(PDO::FETCH_ASSOC);
   
    $sql_destinatarios = $pdo->prepare("SELECT *  
                                        FROM Destinatarios d INNER JOIN Ciudades ciu
                                        ON ciu.PK_Ciudad = d.FK_Ciudad INNER JOIN Paises p
                                        ON p.PK_Pais = ciu.FK_Pais
                                        WHERE FK_Cliente = :FK_Cliente 
                                        ORDER BY PK_Destinatario DESC");
    $sql_destinatarios->bindParam(":FK_Cliente", $cliente[0]['PK_Cliente']);
    $sql_destinatarios->execute();
    $destinatarios = $sql_destinatarios->fetchAll(PDO::FETCH_ASSOC);

?> 

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Shoppingapp | Destinatario</title>

	<link href="<?php echo URL_SITIO ?>static/css/styles.css" rel="stylesheet" type="text/css" media="all" />
    <link href="<?php echo URL_SITIO ?>static/css/pedidos.css" rel="stylesheet" type="text/css" media="all" />
    <link href="<?php echo URL_SITIO ?>static/css/ver_destinatarios.css" rel="stylesheet" type="text/css" media="all" />

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="https://kit.fontawesome.com/b2dbb6a24d.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="<?php echo URL_SITIO ?>static/js/jquery-3.5.0.min.js" ></script>
	<?php include './iconos.php' ?>
</head>
<body>
    <?php include "header.php" ?>
    <br>
	<div class="row col-md-12">
    <div class="col-md-2 ">
            <div class="card card-left">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <form action="Destinatarios" method="get">
                            <button type="submit" class="col-md-12 btn btn-primary">Nuevo</button>
                        </form>
                    </li>
                    <li class="list-group-item">
                        <form action="Ver-Destinatarios" method="get">
                            <button type="submit" class="col-md-12 btn btn-primary">Ver todos</button>
                        </form>
                    </li>
                </ul>
            </div>
    </div>
    <div class="col-md-9">
        <div class="alert alert-success"></div>
        <div class="alert alert-danger"></div>
        <?php foreach($destinatarios as $destinatario){ ?>
            <div class="card col-md-12">
                <h4 class=" btn-eliminar">
                    <button onClick="eliminar(<?php echo $destinatario['PK_Destinatario'] ?>)" type="button" class="btn btn-eliminar" data-toggle="modal" data-target=".modal-eliminar"> <h4><i style="color:red;" class="fas fa-trash-alt mr-2"></i></h4> </button>
                </h4>
                <label for=""><strong>Nombre destinatario:</strong> <?php echo $destinatario['NombresDestinatario'] . ' ' . $destinatario['ApellidosDestinatario'] ?></label>
                <label for=""><strong>Dirección 1:</strong> <?php echo $destinatario['Direccion1'] ?></label>
                <label for=""><strong>Dirección 2:</strong> <?php echo $destinatario['Direccion2'] ?></label>
                <label for=""><strong>Ciudad:</strong> <?php echo $destinatario['NombreCiudad'] ?></label>
                <label for=""><strong>Departamento, estado o provincia:</strong> <?php echo $destinatario['NombreCiudad'] ?></label>
                <label for=""><strong>País:</strong> <?php echo $destinatario['NombrePais'] ?></label>
                <label for=""><strong>Teléfono:</strong> <?php echo $destinatario['Telefono'] ?></label>
                <label for=""><strong>Código Postal:</strong> <?php echo $destinatario['CodigoPostal'] ?></label>
            </div>
        <?php } ?>
    </div>


	</div>
<br>

<!-- modal para eliminar -->
<div class="modal fade modal-eliminar" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class=" md-content col-md-12">
                <br>
                <label class="text-center col-md-12" for=""><strong>Eliminar</strong></label>
          
            <div style="height:100%;margin-bottom:60px;" class="col-md-12 offset-md-0 bordered">
                <div class="">
                <div class="card-body">
                    <form id="form-eliminar" action="<?php echo URL_SITIO ?>scripts/destinatarios.php" method="post" enctype="multipart/form-data">
                      
                        <label for="">¿Seguro que desea eliminar este destinatario?</label>

                        <br>
                        <br>
                        <br>
                        <br>

                        <input type="hidden" id="PK_Destinatario" name="PK_Destinatario">
                        <input type="hidden" value="eliminar" name="accion">

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

<?php include 'footer.php' ?>
</body>
</html>

<script>
     $('#lbl-carrito').hide();
     $('.alert-success').hide();
     $('.alert-danger').hide();

     <?php if(isset($_REQUEST['msj'])){ ?>
        <?php if($_REQUEST['msj'] == 'agregado'){ ?>
            $('.alert-success').html('Destinatario registrado.');
            $('.alert-success').show('slow');
        <?php }else if($_REQUEST['msj'] == 'eliminado'){  ?>
            $('.alert-success').html('Destinatario eliminado.');
            $('.alert-success').show('slow');
        <?php }else if($_REQUEST['msj'] == 'error_noagregado'){  ?>
            $('.alert-danger').html('No se agregó el destinatario.');
            $('.alert-danger').show('slow');
        <?php } else if($_REQUEST['msj'] == 'error_noeliminado'){  ?>
            $('.alert-danger').html('No se eliminó el destinatario.');
            $('.alert-danger').show('slow');
        <?php } ?>
     <?php } ?>
	$(document).ready(function(){
		$('#inputPais').change(function(){
		    recargarListaCiudad();
		});
	})
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

    function eliminar(pk_destinatario){
         $('#PK_Destinatario').val(pk_destinatario);
     }

    $('#btnEliminar').click(function(e){
        e.preventDefault();
        
        $('#form-eliminar').submit();
    })
</script>