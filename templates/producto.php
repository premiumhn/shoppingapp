<?php include 'header_admin.php' ?>
<?php 
	$txtID=(isset($_POST['PK_Pais']))?$_POST["PK_Pais"]:"";
	$txtNombre=(isset($_POST['NombrePais']))?$_POST["NombrePais"]:"";
	$txtLogo=(isset($_POST['logo']))?$_POST["logo"]:null;
	$accion=(isset($_POST['accion']))?$_POST["accion"]:"";
	$imagen = (isset($_FILES['logo'])) ? $_FILES['logo'] : "";
	switch ($accion) {
		case 'agregar':
					
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

	$sentencia2=$pdo->prepare("SELECT * from paises");
	$sentencia2->execute();
	$listaPaises=$sentencia2->fetchAll(PDO::FETCH_ASSOC);


?>
	<div class="container">
		<div class="col-md-10">
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
			  					<input type="text" id="InputNombreProducto" required placeholder="Nombre del producto" name="NombreProducto" class="form-control">
			  				</div>
			  			</div>
			  			<div class="col-md-6">
			  				<label for="InputDescripcion">Descripcion</label>
			  				<input type="text" id="InputDescripcion" required placeholder="Descripcion del producto" name="Descripcion" class="form-control">
			  			</div>
			  		</div>
			  	</div>
			  	<div class="form-group">
			  		<div class="form-row">
			  			<div class="col-md-6">
			  				<div class="form-label-group">
			  					<label for="InputCantidadPorUnidad">Unidad de Medida</label>
			  					<input type="text" id="InputCantidadPorUnidad" required placeholder="Unidad de Medida" name="CantidadPorUnidad" class="form-control">
			  				</div>
			  			</div>
			  			<div class="col-md-6">
			  				<label for="InputPrecioUnitario">Precio Unitario</label>
			  				<input type="text" id="InputPrecioUnitario" required placeholder="Precio del producto" name="PrecioUnitario" class="form-control">
			  			</div>
			  		</div>
			  	</div>
			  	<div class="form-group">
			  		<div class="form-row">
			  			<div class="col-md-6">
			  				<div class="form-label-group">
			  					<label for="InputPrecioEnvio">Precio Envio</label>
			  					<input type="text" id="InputPrecioEnvio" required placeholder="Precio de envio" name="PrecioEnvio" class="form-control">
			  				</div>
			  			</div>
			  			<div class="col-md-6">
			  				<label for="InputDescuento">Descuento</label>
			  				<input type="text" id="InputDescuento" required placeholder="Descuento" name="Descuento" class="form-control">
			  			</div>
			  		</div>
			  	</div>
			  	<div class="form-group">
			  		<div class="form-row">
			  			<div class="col-md-6">
			  				<div class="form-label-group">
			  					<label for="InputUnidadesDisponibles">Unidades disponibles</label>
			  					<input type="text" id="InputUnidadesDisponibles" required placeholder="Unidades Disponibles" name="UnidadesDisponibles" class="form-control">
			  				</div>
			  			</div>
			  			<div class="col-md-6">
			  				<label for="InputDescuento">Descuento</label>
			  				<input type="text" id="InputDescuento" required placeholder="Descuento" name="Descuento" class="form-control">
			  			</div>
			  		</div>
			  	</div>
			  	<div class="form-group">
			  		<div class="form-row">
			  			<div class="col-md-6">
			  				<div class="form-label-group">
			  					<label for="InputUnidadesDisponibles">Estado</label>
			  					<input type="text" id="InputUnidadesDisponibles" required placeholder="Unidades Disponibles" name="UnidadesDisponibles" class="form-control">
			  				</div>
			  			</div>
			  			<div class="col-md-6">
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
			  					<input type="text" id="InputUnidadesDisponibles" required placeholder="Unidades Disponibles" name="UnidadesDisponibles" class="form-control">
			  				</div>
			  			</div>
			  			<div class="col-md-6">
			  				<label for="InputDescuento">Categoria</label>
			  				<input type="text" id="InputDescuento" required placeholder="Descuento" name="Descuento" class="form-control">
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



<script>
	
	$(".custom-file-input").on("change", function() {
	    var fileName = $(this).val().split("\\").pop();
	    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
	});
</script>

<?php include 'footer_admin.php' ?>