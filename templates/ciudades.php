<?php include 'header_admin.php' ?>
<?php 
	$txtID=(isset($_POST['PK_Ciudad']))?$_POST["PK_Ciudad"]:"";
	$txtNombre=(isset($_POST['NombreCiudad']))?$_POST["NombreCiudad"]:"";
	$txtPais=(isset($_POST['FK_Pais']))?$_POST["FK_Pais"]:"";
	$accion=(isset($_POST['accion']))?$_POST["accion"]:"";

	switch ($accion) {
		case 'agregar':

						
					
					$sentencia=$pdo->prepare("INSERT into Ciudades(NombreCiudad,FK_Pais) VALUES(:NombreCiudad,:FK_Pais)");
					$sentencia->bindParam(':NombreCiudad',$txtNombre);
					$sentencia->bindParam(':FK_Pais',$txtPais);
					$sentencia->execute();
				break;
		case 'editar':
					$sql2=$pdo->prepare("UPDATE Paises
					SET NombreCiudad=:NombreCiudad
					WHERE PK_Pais=:PK_Pais");
					$sql2->bindParam(':NombreCiudad',$txtNombre);
					$sql2->bindParam(':PK_Pais',$txtID);
					$sql2->execute();
			break;
		case 'eliminar':
					$sql3=$pdo->prepare("DELETE from Paises
					WHERE PK_Pais=:PK_Pais");
					$sql3->bindParam(':PK_Pais',$txtID);
					$sql3->execute();
			break;
		case 'cancelar':
		
			break;
		
		default:
			# code...
			break;
	}

	$sentencia2=$pdo->prepare("SELECT c.PK_Ciudad,c.NombreCiudad,p.PK_Pais,p.NombrePais
							FROM Ciudades c INNER JOIN Paises p
							ON p.PK_Pais=c.FK_Pais");
	$sentencia2->execute();
	$listaPaises=$sentencia2->fetchAll(PDO::FETCH_ASSOC);

	$sql4=$pdo->prepare("SELECT * from Paises");
	$sql4->execute();
	$listPais=$sql4->fetchAll(PDO::FETCH_ASSOC);

?>

	<div class="row">
		<div class="col-md-4">
			<div class="card">
			  <div class="card-header">
			    Registro de Ciudad
			  </div>
			 	<form class="form-line" id="frmRegistro" method="post">
			  <div class="card-body">
			    
					
					<input class="form-control" hidden type="text" name="PK_Ciudad" value="<?php echo $txtID ?>"  placeholder="Primary Key" id="inputPK_Ciudad" readonly >
					<br>
					<label class="" for="">Ciudad:</label>
					<input class="form-control" type="text" name="NombreCiudad" placeholder="Ingrese el nombre de la ciudad" id="inputNombreCiudad" required value="<?php echo $txtNombre ?>">
					<br>

					<div class="">
                        <select name="FK_Pais" id="" class="form-control" required>
                        	<option value="">--Seleccione--</option>
                        	<?php foreach ($listPais as $paisL ) {?>

                        		<option value="<?php echo $paisL['PK_Pais'] ?>"><?php  echo $paisL['NombrePais'] ?></option>
                        	<?php } ?>
                        </select>
                    </div>
			  	</div>
				<div class="card-footer text-muted text-center">
				  	<button class="btn btn-primary" value="agregar" type="submit" name="accion" data-toggle="tooltip" title="Guardar">
				  		<i class="fas fa-save fas-faw"></i>
				  	</button>
					<button class="btn btn-success" value="editar" type="submit" name="accion" data-toggle="tooltip" title="Editar">
						<i class="fas fa-edit fas-faw"></i>
					</button>
					<button class="btn btn-danger" value="eliminar" type="submit" name="accion" data-toggle="tooltip" title="Eliminar">
						<i class="fas fa-trash-alt fas-faw"></i>
					</button>
					<button class="btn btn-warning" value="cancelar" type="submit" name="accion" data-toggle="tooltip" title="Cancelar">
						<i class="fas fa-ban fas-faw"></i>
					</button>
				</div>
			  </form>
			</div>
		</div>
	<div class="col-md-8">
			<div class="card mb-3 ">
	          	<div class="card-header">
	             	<i class="fas fa-table"></i>
	            	Listado de Ciudades
	          	</div>
            	<div class="card-body">
              		<div class="table-responsive">
                		<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  			<thead class="text-center">
			                    <tr>
									<th hidden>IDCIUDAD</th>
									<th>Ciudad</th>
									<th hidden>IDPAIS</th>
									<th >Pais</th>
									<th>ACCION</th>
								</tr>
			                </thead>
                			<tbody> 
                				<?php foreach ($listaPaises as $pais ) {?>
									<tr>
										<td hidden> <?php echo $pais["PK_Ciudad"]; ?> </td>
										<td> <?php echo $pais["NombreCiudad"]; ?> </td>
										<td hidden> <?php echo $pais["PK_Pais"]; ?></td>
										<td> <?php echo $pais["NombrePais"]; ?></td>
										<td> 
											<form method="post">
												<input hidden type="text" name="PK_Ciudad" value="<?php echo $pais["PK_Ciudad"]; ?>">
												<input hidden type="text" name="NombreCiudad" value="<?php echo $pais["NombreCiudad"]; ?>">
												<input hidden type="text" name="FK_Pais" value="<?php echo $pais["FK_Pais"]; ?>">
												<button type="submit" class="btn btn-primary">Seleccionar</button>
												
											</form>
										</td>
									</tr>
								<?php } ?>
							</tbody>
                		</table>
              		</div>
            	</div>
          		<div class="card-footer small text-muted">Ciudades</div>
    		</div>
	</div>
	
</div>



<script>
	$(".custom-file-input").on("change", function() {
	    var fileName = $(this).val().split("\\").pop();
	    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
	});
</script>

<?php include 'footer_admin.php' ?>