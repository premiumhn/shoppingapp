<?php 
error_reporting(E_ALL);
ini_set('display_errors', '1');
include "../global/config.php";
include "../global/conexion.php";

$request = $_POST['request']; 

switch($request){
	case "selectCiudades":
		selectCiudades(); 
	break;
	case "verificarUsuario":
		verificarUsuario();
	break;
	case "verificarCorreo":
		verificarCorreo();
	break;
	case "verificarLogin":
		verificarLogin();
	break;
	case "verificarLoginTienda":
		verificarLoginTienda();
	break;
	case "obtenerNombreDestinatario":
		obtenerNombreDestinatario();
	break;
	case "verificarCategoria":
		verificarCategoria();
	break;
	case "verificarTelefono":
		verificarTelefono();
	break;
	case "valorarArticulo":
		valorarArticulo();
	break;
	case "verificarRegionEnvio":
		verificarRegionEnvio();
	break;
	case "probarURL":
		probarURL();
	break;
	case "unidadesDisponibles":
		unidadesDisponibles();
	break;
	case "cambiarEstadoUsuario":
		cambiarEstadoUsuario();
	break;
	case "cambiarEstadoCategoria":
		cambiarEstadoCategoria();
	break;
	case "cambiarEstadoTienda":
		cambiarEstadoTienda();
	break;
	case "obtenerPrecioEnvio":
		obtenerPrecioEnvio();
	break;
	case "cambiarEstadoCobrosEnvio":
		cambiarEstadoCobrosEnvio();
	break;
	case "activarAdomicilio":
		activarAdomicilio();
	break;
	case "obtenerDetallePedido":
		obtenerDetallePedido();
	break;
	case "obtenerConfiguracion":
		obtenerConfiguracion();
	break;
}
	function selectCiudades(){
		global $pdo;

		$fk_pais=$_POST['FK_Pais'];

		$select_ciudades = $pdo->prepare("SELECT * FROM Ciudades WHERE FK_Pais = :fk_pais");
		$select_ciudades->bindparam(":fk_pais", $fk_pais);
		
		$select_ciudades->execute();
		$listaCiudades = $select_ciudades->fetchAll(PDO::FETCH_ASSOC);
	
		$cadena="<select id='inputCiudad' name='input_ciudad' class='form-control'>
				<option selected>- Seleccione -</option>";
	
		foreach ($listaCiudades as $ciudad) {
			$cadena=$cadena.'<option value='.$ciudad['PK_Ciudad'].'>'.utf8_encode($ciudad['NombreCiudad']).'</option>';
		}
	
		echo  $cadena."</select>";
	}

	function verificarUsuario(){
		global $pdo;
		// comprobar que el usuario no existe
        $buscar_usuario = $pdo->prepare("SELECT * FROM Usuarios WHERE NombreUsuario = :nombreUsuario");
        $buscar_usuario->bindParam(':nombreUsuario', $_POST['NombreUsuario']);
        $buscar_usuario->execute();
        $cuenta_usuario = $buscar_usuario->rowCount();


        if ($cuenta_usuario > 0){
            echo 1;
        }else{
			echo 0;
		}
	}

	function verificarLogin(){
		global $pdo;
		// comprobar que el usuario no existe
        $buscar_usuario = $pdo->prepare("SELECT * FROM Usuarios WHERE NombreUsuario = :nombreUsuario AND (FK_TipoUsuario = 1 OR FK_TipoUsuario = 3)");
		$buscar_usuario->bindParam(':nombreUsuario', $_POST['NombreUsuario']);
		$buscar_usuario->execute();
		$cuenta_usuario = $buscar_usuario->fetchAll(PDO::FETCH_ASSOC);
		
		if(count($cuenta_usuario) > 0){
			if (openssl_decrypt($cuenta_usuario[0]['Contrasena'], COD, KEY) == $_POST['Contrasena']){
				echo 1;
			}else{
				echo 0;
			}
		}else{
			$buscar_usuario_t = $pdo->prepare("SELECT * FROM Usuarios WHERE NombreUsuario = :nombreUsuario AND FK_TipoUsuario = 2");
			$buscar_usuario_t->bindParam(':nombreUsuario', $_POST['NombreUsuario']);
			$buscar_usuario_t->execute();
			$cuenta_usuario_t = $buscar_usuario_t->fetchAll(PDO::FETCH_ASSOC);
			if(count($cuenta_usuario_t) > 0){
				echo 3;
			}else{
				echo 0;
			}
		}	
	}

	function verificarLoginTienda(){
		global $pdo;
		// comprobar que el usuario no existe
        $buscar_usuario = $pdo->prepare("SELECT * FROM Usuarios WHERE NombreUsuario = :nombreUsuario AND (FK_TipoUsuario = 2 OR FK_TipoUsuario =3)");
		$buscar_usuario->bindParam(':nombreUsuario', $_POST['NombreUsuario']);
		$buscar_usuario->execute();
		$cuenta_usuario = $buscar_usuario->fetchAll(PDO::FETCH_ASSOC);
		
		if(count($cuenta_usuario) > 0){
			if (openssl_decrypt($cuenta_usuario[0]['Contrasena'], COD, KEY) == $_POST['Contrasena']){
				echo 1;
			}else{
				echo 0;
			}
		}else{
			$buscar_usuario_t = $pdo->prepare("SELECT * FROM Usuarios WHERE NombreUsuario = :nombreUsuario AND FK_TipoUsuario = 1");
			$buscar_usuario_t->bindParam(':nombreUsuario', $_POST['NombreUsuario']);
			$buscar_usuario_t->execute();
			$cuenta_usuario_t = $buscar_usuario_t->fetchAll(PDO::FETCH_ASSOC);
			if(count($cuenta_usuario_t) > 0){
				echo 3;
			}else{
				echo 0;
			}
		}	
	}

	
	function verificarCorreo(){
		global $pdo;

		// comprobar que el correo no existe
		$buscar_correo = $pdo->prepare("SELECT * FROM Usuarios WHERE Correo = :correo");
		$buscar_correo->bindParam(':correo', $_POST['Correo']);
		$buscar_correo->execute();
		$cuenta_correo = $buscar_correo->rowCount();

		if ($cuenta_correo > 0){
            echo 1;
        }else{
			echo 0;
		}
	}

	function verificarTelefono(){
		global $pdo;

		// comprobar que el telefono no existe
		$buscar_telefono = $pdo->prepare("SELECT * FROM Usuarios WHERE Telefono = :Telefono");
		$buscar_telefono->bindParam(':Telefono', $_POST['Telefono']);
		$buscar_telefono->execute();
		$cuenta_telefono = $buscar_telefono->rowCount();

		if ($cuenta_telefono > 0){
            echo 1;
        }else{
			echo 0;
		}
	}

	function obtenerNombreDestinatario(){
		global $pdo;

		// comprobar que el correo no existe
		$buscar_destinatario = $pdo->prepare("SELECT * 
										FROM Destinatarios 
										WHERE PK_Destinatario = :PK_Destinatario");
		$buscar_destinatario->bindParam(':PK_Destinatario', $_POST['PK_Destinatario']);
		$buscar_destinatario->execute();
		$destinatario = $buscar_destinatario->fetchAll(PDO::FETCH_ASSOC);

		echo $destinatario[0]['NombresDestinatario']."  ".$destinatario[0]['ApellidosDestinatario'];
	}
            
	function verificarCategoria(){
		global $pdo;

		// comprobar que la categoría no existe
		$buscar_catageria = $pdo->prepare("SELECT * FROM Categorias WHERE NombreCategoria = :NombreCategoria");
		$buscar_catageria->bindParam(':NombreCategoria', $_POST['nombreCategoria']);
		$buscar_catageria->execute();
		$cuenta_categoria = $buscar_catageria->rowCount();

		if ($cuenta_categoria > 0){
            echo 1;
        }else{
			echo 0;
		}
	}

	function verificarRegionEnvio(){
		global $pdo;

		// comprobar que la categoría no existe
		$buscar_region = $pdo->prepare("SELECT * FROM RegionesEnvio 
										   WHERE FK_Ciudad = :FK_Ciudad
										   AND FK_Tienda = :FK_Tienda");
		$buscar_region->bindParam(':FK_Ciudad', $_POST['PK_Ciudad']);
		$buscar_region->bindParam(':FK_Tienda', $_POST['PK_Tienda']);
		$buscar_region->execute();
		$cuenta_region = $buscar_region->rowCount();

		if ($cuenta_region > 0){
            echo 1;
        }else{
			echo 0;
		}
	}

	function valorarArticulo(){
		global $pdo;

		// actualizando valoración
		$valoracion = $_POST['valor'];
		$pk_detalle_pedido = $_POST['pk_detalle_pedido'];
		$valorar_articulo = $pdo->prepare("UPDATE DetallePedidos
										  SET Valoracion = :Valoracion	
										  WHERE PK_DetallePedido = :PK_DetallePedido");

		$valorar_articulo->bindParam(':Valoracion', $valoracion);
		$valorar_articulo->bindParam(':PK_DetallePedido', $pk_detalle_pedido);
		$valorar_articulo->execute();

		// buscar producto
		$buscar_producto = $pdo->prepare("SELECT FK_Producto
										  FROM DetallePedidos
										  WHERE PK_DetallePedido = :PK_DetallePedido");

		$buscar_producto->bindParam(':PK_DetallePedido', $pk_detalle_pedido);
		$buscar_producto->execute();
		$producto = $buscar_producto->fetchAll(PDO::FETCH_ASSOC);

		// obtener valoracion del producto entre todos los pedidos
		$valoracion_pedidos = $pdo->prepare("SELECT FORMAT(AVG(Valoracion), 0) as 'avg'
											FROM DetallePedidos
											WHERE FK_Producto = :FK_Producto AND Valoracion !=0");

		$valoracion_pedidos->bindParam(':FK_Producto', $producto[0]['FK_Producto']);
		$valoracion_pedidos->execute();
		$pedidos_avg = $valoracion_pedidos->fetchAll(PDO::FETCH_ASSOC);

		// Actualizar producto
		$actualizar_producto = $pdo->prepare("UPDATE Productos
										SET Ranking = :Ranking	
										WHERE PK_Producto = :PK_Producto");

		$actualizar_producto->bindParam(':Ranking', $pedidos_avg[0]['avg']);
		$actualizar_producto->bindParam(':PK_Producto', $producto[0]['FK_Producto']);
		$actualizar_producto->execute();
		
		

		if ( $actualizar_producto->execute() == 1){
            echo 1;
        }else{
			echo 0;
		}
	}

	function probarURL(){

		$client_id = (isset($_POST['client_id']))?$_POST['client_id']:"";

		if($client_id != ''){
			$url = 'https://www.paypal.com/sdk/js?client-id='. $client_id .'&currency=EUR&intent=order&commit=false&vault=true';
			$ch = curl_init($url);
			$respuesta = curl_exec ($ch);
			$error = curl_error($ch);
		}else{
			echo 1;
		}
	}

	function unidadesDisponibles(){
		global $pdo;

		$pk_producto = (isset($_POST['PK_Producto']))?$_POST['PK_Producto']:"";

		$buscar_producto = $pdo->prepare("SELECT * FROM Productos WHERE PK_Producto = :PK_Producto");
		$buscar_producto->bindParam(':PK_Producto', $pk_producto);
		$buscar_producto->execute();
		$producto = $buscar_producto->fetchAll(PDO::FETCH_ASSOC);

		echo $producto[0]['UnidadesDisponibles'];
	}

	function cambiarEstadoUsuario(){
		global $pdo;

		$pk_usuario = (isset($_POST['PK_Usuario']))?$_POST['PK_Usuario']:"";

		$sql_usuario = $pdo->prepare("SELECT * FROM Usuarios WHERE PK_Usuario = :PK_Usuario");
		$sql_usuario->bindParam(':PK_Usuario', $pk_usuario);
		$sql_usuario->execute();
		$usuario = $sql_usuario->fetchAll(PDO::FETCH_ASSOC); 

		if($usuario[0]['Estado'] == 0){
			$actualizar_usuario = $pdo->prepare("UPDATE Usuarios 
											SET Estado = 1
											WHERE PK_Usuario = :PK_Usuario");
		}else{
			$actualizar_usuario = $pdo->prepare("UPDATE Usuarios 
											SET Estado = 0
											WHERE PK_Usuario = :PK_Usuario");
		}	
		
		$actualizar_usuario->bindParam(':PK_Usuario', $pk_usuario);
		echo $actualizar_usuario->execute();
	}

	function cambiarEstadoCategoria(){
		global $pdo;

		$pk_categoria = (isset($_POST['PK_Categoria']))?$_POST['PK_Categoria']:"";

		$sql_categoria = $pdo->prepare("SELECT * FROM Categorias WHERE PK_Categoria = :PK_Categoria");
		$sql_categoria->bindParam(':PK_Categoria', $pk_categoria);
		$sql_categoria->execute();
		$categoria = $sql_categoria->fetchAll(PDO::FETCH_ASSOC); 

		if($categoria[0]['Estado'] == 0){
			$actualizar_categoria = $pdo->prepare("UPDATE Categorias 
											SET Estado = 1
											WHERE PK_Categoria = :PK_Categoria");
		}else{
			$actualizar_categoria = $pdo->prepare("UPDATE Categorias 
												SET Estado = 0
												WHERE PK_Categoria = :PK_Categoria");
		}	
		
		$actualizar_categoria->bindParam(':PK_Categoria', $pk_categoria);
		echo $actualizar_categoria->execute();
	}

	function cambiarEstadoTienda(){
		global $pdo;

		$pk_tienda = (isset($_POST['PK_Tienda']))?$_POST['PK_Tienda']:"";

		$sql_tienda = $pdo->prepare("SELECT * FROM Tiendas WHERE PK_Tienda = :PK_Tienda");
		$sql_tienda->bindParam(':PK_Tienda', $pk_tienda);
		$sql_tienda->execute();
		$tienda = $sql_tienda->fetchAll(PDO::FETCH_ASSOC); 

		if($tienda[0]['Estado'] == 0){
			$actualizar_tienda = $pdo->prepare("UPDATE Tiendas 
											SET Estado = 1
											WHERE PK_Tienda = :PK_Tienda");
		}else{
			$actualizar_tienda = $pdo->prepare("UPDATE Tiendas 
												SET Estado = 0
												WHERE PK_Tienda = :PK_Tienda");
		}	
		
		$actualizar_tienda->bindParam(':PK_Tienda', $pk_tienda);
		echo $actualizar_tienda->execute();
	}

	function obtenerPrecioEnvio(){
		global $pdo;

		$pk_tienda = (isset($_POST['PK_Tienda']))?$_POST['PK_Tienda']:"";
		$pk_destinatario = (isset($_POST['PK_Destinatario']))?$_POST['PK_Destinatario']:"";

		$sql_destinatario = $pdo->prepare("SELECT * FROM Destinatarios WHERE PK_Destinatario = :PK_Destinatario");
		$sql_destinatario->bindParam(':PK_Destinatario', $pk_destinatario);
		$sql_destinatario->execute();
		$destinatario = $sql_destinatario->fetchAll(PDO::FETCH_ASSOC); 

		$sql_precio = $pdo->prepare("SELECT * FROM RegionesEnvio WHERE FK_Tienda = :FK_Tienda and FK_Ciudad = :FK_Ciudad");
		$sql_precio->bindParam(':FK_Tienda', $pk_tienda);
		$sql_precio->bindParam(':FK_Ciudad', $destinatario[0]['FK_Ciudad']);
		$sql_precio->execute();
		$precio = $sql_precio->fetchAll(PDO::FETCH_ASSOC); 

		echo $precio[0]['PrecioEnvio'];
	}

	function cambiarEstadoCobrosEnvio(){
		global $pdo;

		$config = $pdo->prepare("SELECT * FROM Configuracion");
		$config->execute();
		$datos = $config->fetchAll(PDO::FETCH_ASSOC); 

		if($datos[0]['CobrosPorEnvio'] == 0){
			$actualizar_config = $pdo->prepare("UPDATE Configuracion 
											SET CobrosPorEnvio = 1");
		}else{
			$actualizar_config = $pdo->prepare("UPDATE Configuracion 
												SET CobrosPorEnvio = 0");
		}	
		
		echo $actualizar_config->execute();
	}

	function activarAdomicilio(){
		global $pdo;

		$pk_tienda = (isset($_POST['PK_Tienda']))?$_POST['PK_Tienda']:"";

		$sql_tienda = $pdo->prepare("SELECT * FROM Usuarios WHERE PK_Usuario = :PK_Usuario");
		$sql_tienda->bindParam(':PK_Usuario', $pk_usuario);
		$sql_tienda->execute();
		$tienda = $sql_tienda->fetchAll(PDO::FETCH_ASSOC); 

		if($tienda[0]['Estado'] == 0){
			$actualizar_tienda = $pdo->prepare("UPDATE Tiendas 
											SET Adomicilio = 1
											WHERE PK_Tienda = :PK_Tienda");
		}else{
			$actualizar_tienda = $pdo->prepare("UPDATE Tiendas 
											SET Adomicilio = 0
											WHERE PK_Tienda = :PK_Tienda");
		}	
		
		$actualizar_tienda->bindParam(':PK_Tienda', $pk_tienda);
		echo $actualizar_tienda->execute();
	}

	function obtenerDetallePedido(){
		global $pdo;

		$pk_detalle_pedido = (isset($_POST['PK_DetallePedido']))?$_POST['PK_DetallePedido']:"";

		$sql_detalle_pedido = $pdo->prepare("SELECT p.NombreProducto, 
											c.Cantidad, 
											p.PrecioUnitario, 
											p.Imagen, 

											(SELECT Color From Colores WHERE PK_Color = c.FK_Color) as 'Color',
											(SELECT Talla From Tallas WHERE PK_Talla = c.FK_Talla) as 'Talla',

											p.Descuento, 
											(p.PrecioUnitario * c.Cantidad) as 'Subtotal', 
											(CAST(p.Descuento as DECIMAL(20,0)) ) as DescuentoDecimal, 
											ti.NombreTienda,
											p.PrecioEnvio,
											c.FK_TipoPedido,
											c.PK_DetallePedido,
											c.FK_Pedido,
											c.Estado,
											cli.PrimerNombre,
											cli.PrimerApellido,
											pe.NumeroPedido,
											c.CodigoDetallePedido,
											c.Precio,
											c.Descuento,
											CONCAT(cli.PrimerNombre, ' ', cli.SegundoNombre, ' ', cli.PrimerApellido, ' ', cli.SegundoApellido) as 'NombreCliente',
											(SELECT NombresDestinatario FROM Destinatarios WHERE PK_Destinatario = c.FK_Destinatario) as 'NombresDestinatario',
											(SELECT ApellidosDestinatario FROM Destinatarios WHERE PK_Destinatario = c.FK_Destinatario) as 'ApellidosDestinatario',
											(SELECT Departamento FROM Destinatarios WHERE PK_Destinatario = c.FK_Destinatario) as 'DepartamentoDestinatario',
											(SELECT Telefono FROM Destinatarios WHERE PK_Destinatario = c.FK_Destinatario) as 'TelefonoDestinatario',
											(SELECT Direccion1 FROM Destinatarios WHERE PK_Destinatario = c.FK_Destinatario) as 'Direccion1Destinatario',
											(SELECT Direccion2 FROM Destinatarios WHERE PK_Destinatario = c.FK_Destinatario) as 'Direccion2Destinatario',
											(SELECT CodigoPostal FROM Destinatarios WHERE PK_Destinatario = c.FK_Destinatario) as 'CodigoPostalDestinatario',
												(SELECT cius.NombreCiudad FROM DetallePedidos dps INNER JOIN Destinatarios ds
												ON ds.PK_Destinatario = dps.FK_Destinatario INNER JOIN Ciudades cius
												ON cius.PK_Ciudad = ds.FK_Ciudad
												WHERE dps.PK_DetallePedido = c.PK_DetallePedido) as 'CiudadDestinatario',
												(SELECT pais.NombrePais FROM DetallePedidos dps INNER JOIN Destinatarios ds
												ON ds.PK_Destinatario = dps.FK_Destinatario INNER JOIN Ciudades cius
												ON cius.PK_Ciudad = ds.FK_Ciudad INNER JOIN Paises pais
												ON pais.PK_Pais = cius.FK_Pais
												WHERE dps.PK_DetallePedido = c.PK_DetallePedido) as 'PaisDestinatario',
											DATE_FORMAT(pe.FechaHoraCompra, '%d %m %Y ') as 'FechaCompra',
											DATE_FORMAT(pe.FechaHoraCompra, '%H:%i ') as 'HoraCompra',
											ti.PK_Tienda,
											(SELECT FK_Ciudad FROM Destinatarios WHERE PK_Destinatario = c.FK_Destinatario) as 'FK_Ciudad'


											FROM DetallePedidos c INNER JOIN Productos p
											ON c.FK_Producto = p.PK_Producto INNER JOIN Clientes cli
											ON c.FK_Cliente = cli.PK_Cliente INNER JOIN Usuarios u
											ON cli.FK_Usuario = u.PK_Usuario INNER JOIN Tiendas ti
											ON p.FK_Tienda = ti.PK_Tienda INNER JOIN Pedidos pe
											ON pe.PK_Pedido = c.FK_Pedido 
											WHERE c.PK_DetallePedido = :PK_DetallePedido");
		$sql_detalle_pedido->bindParam(':PK_DetallePedido', $pk_detalle_pedido);									
		$sql_detalle_pedido->execute();
		$detalle_pedido = $sql_detalle_pedido->fetchAll(PDO::FETCH_ASSOC); 

		echo json_encode($detalle_pedido);	
	}

	function obtenerConfiguracion(){
		global $pdo;

		$config = $pdo->prepare("SELECT * FROM Configuracion");
		$config->execute();
		$datos = $config->fetchAll(PDO::FETCH_ASSOC); 

		echo json_encode($datos);	
	}


	

?>