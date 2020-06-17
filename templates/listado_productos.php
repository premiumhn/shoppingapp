<?php include 'header_admin.php' ?>
<?php
	$sql8=$pdo->prepare("SELECT p.PK_Producto,p.NombreProducto,p.Imagen,p.PrecioUnitario,p.Descuento,p.UnidadesDisponibles,p.UnidadesVendidas,p.Estado,c.PK_Categoria,c.NombreCategoria FROM Productos p INNER JOIN Categorias c
		ON p.FK_Categoria=c.PK_Categoria");
	$sql8->execute();
	$listaPro=$sql8->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="col-md-12">
			<div class="card mb-3 ">
	          	<div class="card-header">
	             	<i class="fas fa-table"></i>
					Listado de Productos
	          	</div>
            	<div class="card-body">
              		<div class="table-responsive">
                		<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  			<thead class="text-center">
			                    <tr>
									<th hidden>ID</th>
									<th>Nombre</th>
									<th>Imagen</th>
									<th>Precio</th>
									<th>Descuento</th>
									<th>Disponibles</th>
									<th>Vendidas</th>
									<th>Estado</th>
									<th hidden>IdCat</th>
									<th>Categoria</th>

									
								</tr>
			                </thead>
			                <img src="" alt="">
                			<tbody> 
                				<?php foreach ($listaPro as $produc ) {?>
									<tr>
										<td hidden> <?php echo $produc["PK_Producto"]; ?> </td>
										<td> <?php echo $produc["NombreProducto"]; ?> </td>
										<td> 
											<?php if($produc["Imagen"]==""){
												echo "Sin Imagen";
											}?>
											<img src="<?php $URL_SITIO ?> <?php echo $produc['Imagen']?>" alt="" width="50px" height="50px">
										</td>
										<td> $ <?php echo $produc["PrecioUnitario"]; ?> </td>
										<td> <?php if($produc["Descuento"]==""){
												echo "Sin Descuento";
												}else{
													echo $produc["Descuento"]."%";	
												}
											?></td>
										<td> <?php echo $produc["UnidadesDisponibles"]; ?> UN</td>
										<td> <?php echo $produc["UnidadesVendidas"]; ?> UN</td>
										<td> <?php echo $produc["Estado"]; ?> </td>
										<td hidden> <?php echo $produc["PK_Categoria"]; ?> </td>
										<td> <?php echo $produc["NombreCategoria"]; ?> </td>

										
									</tr>
								<?php } ?>
							</tbody>
                		</table>
              		</div>
            	</div>
          		<div class="card-footer small text-muted">Productos</div>
    		</div>
		</div>






<?php include 'footer_admin.php' ?>