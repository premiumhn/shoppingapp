<?php include 'header_admin.php' ?> 
<?php 
error_reporting(E_ALL);
ini_set('display_errors', '1');
	//include '../scripts/comprobaciones.php';


require ('../scripts/comprobaciones.php'); 



$select_id = $pdo->prepare('SELECT * FROM Configuracion');
$select_id->execute();
$id = $select_id->fetchAll(PDO::FETCH_ASSOC);



?>

<link href="<?php echo URL_SITIO ?>static/css/configuracion_sitio.css"rel="stylesheet">



<div class="contenedor row col-md-12">
<div class="alert alert-success col-md-12"></div>
    <div class="card col-md-12">
        <form action="<?php echo URL_SITIO.'scripts/configuracion_sitio.php' ?>" type="post">
            <div class="form-group">
                <br>
                <label for="">ID de cliente paypal</label>
                <a href="" class="btn btn-edit btn-primary"> <i class="fa fa-edit"></i> Editar</a>
                <input class="on_view form-control" disabled value="<?php echo $id[0]['IDClientePaypal'] ?>" >
                <input type="text" id="inputIdClientePaypal" value="<?php echo $id[0]['IDClientePaypal'] ?>" name="input_idClientePaypal" class="form-control on_edit">
                <input type="hidden" name="action" value="editar">
                <br>
                <input type="submit" class="btn btn-primary col-md-2 offset-md-10 on_edit" value="Guardar">
            </div>
        </form>
    </div>
</div>




<script type="text/javascript">
$('.alert-success').hide();
$('.btn-toolbar').hide();

    <?php if(isset($_REQUEST['msj'])){ ?>
        <?php if($_REQUEST['msj'] == 'editado'){ ?> 
            $('.alert-success').html('Configuración actualizada');
            $('.alert-success').show('slow');
        <?php } ?>
    <?php } ?>

    $('.h2-name').html('Configuración del sitio');
    $('#titulo_pagina').html('Shoppingapp | Configuración del sitio');

    

   $('.on_edit').hide();

   $('.btn-edit').click(function(e){
       e.preventDefault();
        $('.on_view').hide();
        $('.on_edit').show();
        $('.btn-edit').hide();
   });

   $('.form-control').keypress(function(e){
    $('.alert-success').hide('slow');
   });

</script>


	


<?php include 'footer_admin.php' ?>

