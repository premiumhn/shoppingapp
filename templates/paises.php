<?php include 'header_admin.php' ?> 
<?php 
error_reporting(E_ALL);
ini_set('display_errors', '1');
	//include '../scripts/comprobaciones.php';

	$txtID=(isset($_POST['PK_Pais']))?$_POST["PK_Pais"]:"";
	$txtNombre=(isset($_POST['NombrePais']))?$_POST["NombrePais"]:"";
	$txtLogo=(isset($_POST['logo']))?$_POST["logo"]:null;
	$accion=(isset($_POST['accion']))?$_POST["accion"]:"";
	$imagen = (isset($_FILES['logo'])) ? $_FILES['logo'] : "";	
	switch ($accion) {
		case 'agregar':
					$sql=$pdo->prepare("SELECT COUNT(PK_Pais) AS existe
									FROM Paises WHERE NombrePais LIKE ('%".$txtNombre."%')");
					$sql->execute();
					$existe = $sql->fetchAll(PDO::FETCH_ASSOC);

					if(count($existe)!=0)
					{
						#echo "<script> alert('Ya exite un registro con ese nombre de Pais'); </script>";
						//console.log("ya hay un pais");
					}else{
							
							if($imagen !=""){
							$nombre_archivo = ($imagen!="")?"bandera".$txtNombre.".jpg":"";
							$tmp_foto = $_FILES['logo']['tmp_name'];
							if ($_FILES["logo"]["size"] > 1000000) {
	                        	echo "Sorry, your file is too large.";
	                        	$uploadOk = 0;
	                    	}elseif($tmp_foto !=""){
	                    		if(file_exists('../uploads/img/paises/'.$nombre_archivo)){
	                    			unlink('../uploads/img/paises/'.$nombre_archivo);
	                    			}
                    		 		move_uploaded_file($tmp_foto, '../uploads/img/paises/'.$nombre_archivo);

                    			}
							}else{
								$nombre_archivo="";
							}

						$sentencia=$pdo->prepare("INSERT INTO Paises(NombrePais,Logo) VALUES(:NombrePais,:logo)");
						$sentencia->bindParam(':NombrePais',$txtNombre);
						$sentencia->bindParam(':logo',$nombre_archivo);
						$sentencia->execute();
						#echo "<script> alert('País agregado con éxito'); </script>";
						
						}
					
				break;
		case 'editar':
					if($imagen !=""){
						$nom_logo = ($imagen!="")?"bandera".$txtNombre.".jpg":"";
						$nombre_temp = $_FILES['logo']['tmp_name'];
						if ($_FILES["logo"]["size"] > 1000000) {
                        	echo "Sorry, your file is too large.";
                        	$uploadOk = 0;
                    	}elseif($nombre_temp !=""){
                    		if(file_exists('../uploads/img/paises/'.$nom_logo)){
                    			unlink('../uploads/img/paises/'.$nom_logo);
                    		}
                    		 move_uploaded_file($nombre_temp, '../uploads/img/paises/'.$nom_logo);

                    	}
                    	$sql2=$pdo->prepare("UPDATE Paises
						SET NombrePais=:NombrePais,
							Logo=:Logo
						WHERE PK_Pais=:PK_Pais");
						$sql2->bindParam(':NombrePais',$txtNombre);
						$sql2->bindParam(':Logo',$nom_logo);
						$sql2->bindParam(':PK_Pais',$txtID);
						$sql2->execute();
						#echo "<script> alert('País editado con éxito con imagen'); </script>";
						
					}else{
						$sql3=$pdo->prepare("UPDATE Paises
						SET NombrePais=:NombrePais
						WHERE PK_Pais=:PK_Pais");
						$sql3->bindParam(':NombrePais',$txtNombre);
						$sql3->bindParam(':PK_Pais',$txtID);
						$sql3->execute();
						#echo "<script> alert('País editado con éxito sin imagen'); </script>";


					}
					
			break;
		case 'eliminar':
						$sql=$pdo->prepare("SELECT COUNT(p.PK_Pais) AS existe
											FROM Paises p inner JOIN Ciudades c
											ON p.PK_Pais=c.FK_Pais INNER JOIN Tiendas t
											ON c.PK_Ciudad=t.FK_Ciudad
											WHERE p.PK_Pais=".$txtID."");
						$sql->execute();
						$existe = $sql->fetchAll(PDO::FETCH_ASSOC);

						if($existe=!0)
						{
							echo "<script> alert('El País ya está asociado a una tienda, no lo pueda eliminar'); </script>";
						}else{
							$sql3=$pdo->prepare("DELETE from Paises
							WHERE PK_Pais=:PK_Pais");
							$sql3->bindParam(':PK_Pais',$txtID);
							$sql3->execute();
							echo "<script> alert('País eliminado con éxito'); </script>";
						}
					
			break;
		case 'cancelar':
		
			break;
		
		default:
			# code...
			break;
	}

	$sentencia2=$pdo->prepare("SELECT * from Paises");
	$sentencia2->execute();
	$listaPaises=$sentencia2->fetchAll(PDO::FETCH_ASSOC);

?>


	<div class="row ">
		<div class="col-md-4">
			<div class="card">
			  <div class="card-header">
			    <?php echo $rpaises ?>
			  </div>
			 	<form class="form-line" id="frmRegistro" method="post" enctype="multipart/form-data">
			  <div class="card-body">
			    
					
					<input class="form-control"  hidden  type="text" name="PK_Pais" value="<?php echo $txtID ?>"  placeholder="Primary Key" id="inputPK_Pais" readonly >
					
					<label class="" for=""><?php echo $npais ?>:</label>
					<input class="form-control" type="text" maxlength="50" name="NombrePais" id="inputNombrePais" required value="<?php echo $txtNombre ?>">
					<br>

					<div class="custom-file">
                        <input type="file" accept="image/*" class="custom-file-input" required id="inputImagen" name="logo">
                        <label class="custom-file-label" for="logo"><?php echo $nbandera ?></label>
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
	
		<div class="col-md-8">
			<div class="card mb-3 ">
	          	<div class="card-header">
	             	<i class="fas fa-table"></i>
	            	<?php echo $lpais ?>
	          	</div>
            	<div class="card-body">
              		<div class="table-responsive">
                		<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  			<thead class="text-center">
			                    <tr>
									<th hidden>ID</th>
									<th><?php echo $npais ?></th>
									<th><?php echo $ipais ?></th>
									<th><?php echo $naccion ?></th>
								</tr>
			                </thead>
                			<tbody> 
                				<?php foreach ($listaPaises as $pais ) {?>
									<tr>
										<td hidden> <?php echo $pais["PK_Pais"]; ?> </td>
										<td> <?php echo $pais["NombrePais"]; ?> </td>
										<td> 
											<?php if($pais["Logo"]==""){
												echo "Sin Imagen";
												}else{
													echo $pais["Logo"];	
												}

											?>
											
										</td>
										<td> 
											<form method="post">
												<input hidden type="text" name="PK_Pais" value="<?php echo $pais["PK_Pais"]; ?>">
												<input hidden type="text" name="NombrePais" value="<?php echo $pais["NombrePais"]; ?>">
												<input hidden type="text" name="logo" value="<?php echo $pais["logo"]; ?>">
												<button type="submit" class="btn btn-primary" data-toggle="tooltip" title="<?php echo $btnSeleccionar ?>"><i class="fas fa-check"></i></button>
											</form>
										</td>
									</tr>
								<?php } ?>
							</tbody>
                		</table>
              		</div>
            	</div>
          		<div class="card-footer small text-muted"><?php echo $fpaises ?></div>
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