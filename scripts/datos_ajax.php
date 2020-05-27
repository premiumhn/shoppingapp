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
        $buscar_usuario = $pdo->prepare("SELECT * FROM Usuarios WHERE NombreUsuario = :nombreUsuario ");
		$buscar_usuario->bindParam(':nombreUsuario', $_POST['NombreUsuario']);
		// $buscar_usuario->bindParam(':Contrasena', $_POST['Contrasena']);
        $buscar_usuario->execute();
		$cuenta_usuario = $buscar_usuario->fetchAll(PDO::FETCH_ASSOC);
		

		if (openssl_decrypt($cuenta_usuario[0]['Contrasena'], COD, KEY) == $_POST['Contrasena']){
            echo 1;
        }else{
			echo 0;
		}

        // if ($cuenta_usuario > 0){
        //     echo 1;
        // }else{
		// 	echo 0;
		// }

		
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



	

?>