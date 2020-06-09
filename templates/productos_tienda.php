<?php 
error_reporting(E_ALL);
ini_set('display_errors', '1');
include '../global/config.php';
include '../global/conexion.php';
include '../global/const.php';
session_start();

$txtID=(isset($_POST['PK_Pais']))?$_POST["PK_Pais"]:"";
$txtNombre=(isset($_POST['NombreProducto']))?$_POST["NombreProducto"]:"";
$txtdescripcion=(isset($_POST['Descripcion']))?$_POST["Descripcion"]:"";
$txtCPU=(isset($_POST['CantidadPorUnidad']))?$_POST["CantidadPorUnidad"]:"";
$txtprecioU=(isset($_POST['PrecioUnitario']))?$_POST["PrecioUnitario"]:"";
$txtprecioE=(isset($_POST['PrecioEnvio']))?$_POST["PrecioEnvio"]:"";
$txtDescuento=(isset($_POST['Descuento']))?$_POST["Descuento"]:"";
$txtUnidadesD=(isset($_POST['UnidadesDisponibles']))?$_POST["UnidadesDisponibles"]:"";
$txtEstado=(isset($_POST['EstadoProd']))?$_POST["EstadoProd"]:"0";
$imagen = (isset($_FILES['logo'])) ? $_FILES['logo'] : "";
$txtTienda=(isset($_POST['FK_Tienda']))?$_POST["FK_Tienda"]:"";
$txtCategoria=(isset($_POST['FK_Categoria']))?$_POST["FK_Categoria"]:"";
$txtAdomicilio=(isset($_POST['Adomicilio']))?$_POST["Adomicilio"]:"0";

$txtTalla=(isset($_POST['FK_Talla']))?$_POST["FK_Talla"]:NULL;
$txtColor=(isset($_POST['FK_Color']))?$_POST["FK_Color"]:"";
$txtPeso=(isset($_POST['Peso']))?$_POST["Peso"]:"";



$accion=(isset($_POST['accion']))?$_POST["accion"]:"";

switch ($accion) {
	case 'agregar':
					if($imagen !=""){
						$nombre_archivo = ($imagen!="")?"".$txtNombre.".jpg":"";
						$tmp_foto = $_FILES['logo']['tmp_name'];
						if ($_FILES["logo"]["size"] > 1000000) {
							echo "<span class='text-danger'>Sorry, your file is too large.</span>";
							$uploadOk = 0;
						}elseif($tmp_foto !=""){
							if(file_exists('../uploads/img/productos/'.$nombre_archivo)){
								unlink('../uploads/img/productos/'.$nombre_archivo);
								}
								 move_uploaded_file($tmp_foto, '../uploads/img/productos/'.$nombre_archivo);

							}
						}else{
							$nombre_archivo="";
						}
					$insert_producto = $pdo->prepare("INSERT INTO Productos(NombreProducto,Descripcion,CantidadPorUnidad,PrecioUnitario,PrecioEnvio,Descuento,UnidadesDisponibles,Estado,Imagen,FK_Tienda,FK_Categoria,Adomicilio)
					VALUES(:NombreProducto,:Descripcion,:CantidadPorUnidad,:PrecioUnitario,:PrecioEnvio,:Descuento,:UnidadesDisponibles,:Estado,:Imagen,:FK_Tienda,:FK_Categoria,:Adomicilio)");

					$insert_producto->bindParam(':NombreProducto', $txtNombre);
					$insert_producto->bindParam(':Descripcion', $txtdescripcion);
					$insert_producto->bindParam(':CantidadPorUnidad', $txtCPU);
					$insert_producto->bindParam(':PrecioUnitario', $txtprecioU);
					$insert_producto->bindParam(':PrecioEnvio', $txtprecioE);
					$insert_producto->bindParam(':Descuento', $txtDescuento);
					$insert_producto->bindParam(':UnidadesDisponibles', $txtUnidadesD);
					$insert_producto->bindParam(':Estado', $txtEstado);
					$insert_producto->bindParam(':Imagen', $nombre_archivo);
					$insert_producto->bindParam(':FK_Tienda', $txtTienda);
					$insert_producto->bindParam(':FK_Categoria', $txtCategoria);
					$insert_producto->bindParam(':Adomicilio', $txtAdomicilio);
					try {
							$insert_producto->execute();
							$sql7=$pdo->prepare("SELECT PK_Producto FROM productos where NombreProducto='".$txtNombre."' ORDER BY PK_Producto desc");
							$sql7->execute();
							$listaP=$sql7->fetchAll(PDO::FETCH_ASSOC);

							$insert_detalle=$pdo->prepare("INSERT INTO Detalleproducto(FK_Producto,FK_Talla,FK_Color,Peso) VALUES(:FK_Producto,:FK_Talla,:FK_Color,:Peso)");
							$insert_detalle->bindParam(':FK_Producto', $listaP[0]["PK_Producto"]);
							$insert_detalle->bindParam(':FK_Talla', $txtTalla);
							$insert_detalle->bindParam(':FK_Color', $txtColor);
							$insert_detalle->bindParam(':Peso', $txtPeso);					           
							try {
								$insert_detalle->execute();
							} catch (Exception $e) {
								echo "No se ha guardado el detalle del producto!";
							}

					} catch (Exception $e) {
						echo $e;
					}
					
			break;
	case 'editar':
				
		break;
	case 'eliminar':
				
		break;
	case 'cancelar':
	
		break;
	
	default:
		# code...
		break;
}

$sql4=$pdo->prepare("SELECT PK_Categoria,NombreCategoria FROM Categorias where Estado=1");
$sql4->execute();
$listaCat=$sql4->fetchAll(PDO::FETCH_ASSOC);

$sql5=$pdo->prepare("SELECT* FROM Tiendas where Estado=1");
$sql5->execute();
$listaT=$sql5->fetchAll(PDO::FETCH_ASSOC);

$sql6=$pdo->prepare("SELECT* FROM Colores");
$sql6->execute();
$listaC=$sql6->fetchAll(PDO::FETCH_ASSOC);

$sql8=$pdo->prepare("SELECT* FROM Tallas");
$sql8->execute();
$listaTa=$sql8->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Productos</title>

	<link href="<?php echo URL_SITIO ?>static/css/home.css" rel="stylesheet" type="text/css" media="all" />
    <link href="<?php echo URL_SITIO ?>static/css/styles.css" rel="stylesheet" type="text/css" media="all" />

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="https://kit.fontawesome.com/b2dbb6a24d.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="<?php echo URL_SITIO ?>static/js/jquery-3.5.0.min.js" ></script>
	<?php include 'iconos.php' ?>
</head>
<body>
<?php include 'header.php' ?>
<div class="container">
	<br>
		<div class="col-md-12">
			<div class="card">
			  <div class="card-header">
			    Registro de Productos
			  </div>
			 	<form class="form-line" id="frmRegistro" method="post" enctype="multipart/form-data">
			  <div class="card-body">
			  	<div class="form-group">
			  		<div class="form-row">
			  			<div class="col-md-6">
			  				<div class="form-label-group">
			  					<label for="InputNombreProducto">Nombre Producto</label>
			  					<input maxlength="200" type="text" id="InputNombreProducto" required placeholder="Nombre del producto" name="NombreProducto" class="form-control">
			  				</div>
			  			</div>
			  			<div class="col-md-6">
			  				<div class="form-label-group">
			  					<label for="InputCantidadPorUnidad">Unidad de Medida</label>
			  					<input type="number" min="1" max="100" id="InputCantidadPorUnidad" required placeholder="Ejemplo: 1,6,12,24 ..." name="CantidadPorUnidad" class="form-control">
			  				</div>
			  			</div>
			  			
			  		</div>
			  	</div>
			  	<div class="form-group">
			  		<div class="form-row">
			  			
			  			<div class="col-md-6">
			  				<label for="InputPrecioUnitario">Precio Unitario</label>
			  				<input type="number" min="1" id="InputPrecioUnitario" required placeholder="Precio del producto" name="PrecioUnitario" class="form-control">
			  			</div>
			  			<div class="col-md-6">
			  				<div class="form-label-group">
			  					<label for="InputPrecioEnvio">Precio Envio </label>
			  					<input type="number" id="InputPrecioEnvio" required placeholder="Precio de envio" name="PrecioEnvio" class="form-control">
			  				</div>
			  			</div>

			  		</div>
			  	</div>
			  	<div class="form-group">
			  		<div class="form-row">
			  			
			  			<div class="col-md-6">
			  				<label for="InputDescuento">Descuento (%)</label>
			  				<input type="number" min="0" max="99" id="InputDescuento" required placeholder="Ejemplo: 10, 15, 25, 50" name="Descuento" class="form-control">
			  			</div>
			  			<div class="col-md-6">
			  				<div class="form-label-group">
			  					<label for="InputUnidadesDisponibles">Unidades disponibles</label>
			  					<input type="number" min="0" max="999" id="InputUnidadesDisponibles" required placeholder="Unidades Disponibles" name="UnidadesDisponibles" class="form-control">
			  				</div>
			  			</div>
			  		</div>
			  	</div>
			  	<div class="form-group">
			  		<div class="form-row">
			  			
			  			<div class="col-md-12">
			  				<label for="InputDescripcion">Descripcion</label>
			  				<textarea maxlength="600" class="form-control" required placeholder="Ingrese una pequeña descripción del producto" name="Descripcion" id="idDescripcion" cols="30" rows="3"></textarea>
			  				
			  			</div>
			  		</div>
			  	</div>
			  	<div class="form-group">
			  		<div class="form-row">
			  			<div class="col-md-12">
							  <label for="">Imagen</label>
			  				<div class="custom-file">
			  					<label class="custom-file-label" for="logo">Seleccionar archivo</label>
		                        <input type="file" accept="image/*" class="custom-file-input" id="inputImagen" name="logo">
                    		</div>
			  			</div>
			  		</div>
			  	</div>
			  	<div class="form-group">
			  		<div class="form-row">
			  			<div class="col-md-6">
			  				<div class="form-label-group">
			  					<label for="InputUnidadesDisponibles">Tienda</label>
			  					<select name="FK_Tienda" id="idTienda" class="form-control">
			  					<option value="">--Seleccione--</option>
			  					<?php foreach($listaT as $cat) {?>
			  							<option value="<?php echo $cat['PK_Tienda']?>"><?php echo $cat["NombreTienda"] ?></option>
			  					<?php  }?>
			  				</select>
			  				</div>
			  			</div>
			  			<div class="col-md-6">
			  				<label for="idCategoria">Categoria</label>
			  				<select name="FK_Categoria" id="idCategoria" class="form-control">
			  					<option value="">--Seleccione--</option>
			  					<?php foreach($listaCat as $cat) {?>
			  							<option value="<?php echo $cat['PK_Categoria']?>"><?php echo $cat["NombreCategoria"] ?></option>
			  					<?php  }?>
			  				</select>
			  			</div>
			  		</div>
			  	</div>
			  	<div class="form-group">
			  		<div class="form-row">
			  			<div class="col-md-4">
			  				<div class="form-label-group">
			  					<label for="idTalla">Talla</label>
			  					<select name="FK_Talla" id="idTalla" class="form-control">
			  					<option value="">--Seleccione--</option>
			  					<?php foreach($listaTa as $talla) {?>
			  							<option value="<?php echo $talla['PK_Talla']?>"><?php echo $talla["Talla"] ?></option>
			  					<?php  }?>
			  				</select>
			  				</select>
			  				</div>
			  			</div>
			  			<div class="col-md-4">
			  				<div class="form-label-group">
			  					<label for="idPeso">Peso (Kg)</label>
			  					<input type="text" class="form-control" name="Peso" id="idPeso">
			  				</select>
			  				</div>
			  			</div>
			  			<div class="col-md-4">
			  				<label for="idColor">Color</label>
			  				<select name="FK_Color" id="idColor" class="form-control">
			  					<option value="">--Seleccione--</option>
			  					<?php foreach($listaC as $color) {?>
			  							<option value="<?php echo $color['PK_Color']?>"><?php echo $color["Color"] ?></option>
			  					<?php  }?>
			  				</select>
			  			</div>
			  		</div>
			  	</div>
			  	<div class="form-group">
			  		<div class="form row">
			  			<div class="col-md-6">
			  				<label for="">Activo</label>
			  				<label class="switch">
                            	<input id="idEstado" name="EstadoProd" class="check" type="checkbox" checked="checked" onchange="cambia();" value="1"  >
                            	<span class="slider round"></span>
                            </label>
			  			</div>
			  			<div class="col-md-6">
			  				<label for="">A domicilio</label>
			  				<label class="switch">
                            	<input id="idAdomicilio" name="Adomicilio" class="check" type="checkbox" checked="checked" onchange="cambiaA();" value="1"  >
                            	<span class="slider round"></span>
                            </label>
			  			</div>
			  		</div>
			  	</div>
			    	
					
			  	</div>
				<div class="card-footer text-muted text-center">
				  	<button class="btn btn-primary" value="agregar" type="submit" name="accion" data-toggle="tooltip" title="<?php echo $btnGuardar ?>">
				  		<i class="fas fa-save fas-faw"></i>
				  	</button>
					<button class="btn btn-success" value="editar" type="submit" name="accion" data-toggle="tooltip" title="<?php echo $btnEditar ?>">
						<i class="fas fa-edit fas-faw"></i>
					</button>
					<button class="btn btn-danger" value="eliminar" type="submit" name="accion" data-toggle="tooltip" title="<?php echo $btnEliminar ?>">
						<i class="fas fa-trash-alt fas-faw"></i>
					</button>
					<button class="btn btn-warning" value="cancelar" type="reset" name="accion" data-toggle="tooltip" title="<?php echo $btnCancelar ?>">
						<i class="fas fa-ban fas-faw"></i>
					</button>
				</div>
			  </form>
			</div>
		</div>
		<div class="row">

		</div>

	
</div>
<br>
<br>


</body>
</html>


	



<script>
	
	$(".custom-file-input").on("change", function() {
	    var fileName = $(this).val().split("\\").pop();
	    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
	});

	function cambia(){	
		if($("#idEstado").prop('checked')){
			$("#idEstado").val(1);
		}else{
			$("#idEstado").val(2);
		}
	}
	function cambiaA(){	
		if($("#idAdomicilio").prop('checked')){
			$("#idAdomicilio").val(1);
		}else{
			$("#idAdomicilio").val(2);
		}
	}
	
</script>

<?php include 'footer.php' ?>