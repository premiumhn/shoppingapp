<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include ("../global/config.php");
include ("../global/conexion.php");
include ("../global/const.php");

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $nombreTienda = (isset($_POST['input_nombreTienda'])) ? $_POST['input_nombreTienda'] : "";
        $nombreContacto = (isset($_POST['input_nombreContacto'])) ? $_POST['input_nombreContacto'] : "";
        $apellidoContacto = (isset($_POST['input_apellidoContacto'])) ? $_POST['input_apellidoContacto'] : "";
        $webSite = (isset($_POST['input_website'])) ? $_POST['input_website'] : "";
        $nombreUsuario = (isset($_POST['input_correo'])) ? $_POST['input_correo'] : "";
        $correo = (isset($_POST['input_correo'])) ? $_POST['input_correo'] : "";
        $contrasena = (isset($_POST['input_contrasena'])) ? $_POST['input_contrasena'] : "";
        $telefono = (isset($_POST['input_telefono'])) ? $_POST['input_telefono'] : "";
        $direccion1 = (isset($_POST['input_direccion1'])) ? $_POST['input_direccion1'] : "";
        $direccion2 = (isset($_POST['input_direccion2'])) ? $_POST['input_direccion2'] : "";
        $pais = (isset($_POST['input_pais'])) ? $_POST['input_pais'] : "";
        $ciudad = (isset($_POST['input_ciudad'])) ? $_POST['input_ciudad'] : "";
        $adomicilio = (isset($_POST['input_adomicilio'])) ? $_POST['input_adomicilio'] : "";
        $logo = (isset($_FILES['input_logo'])) ? $_FILES['input_logo'] : "";
        $portada = (isset($_FILES['input_portada'])) ? $_FILES['input_portada'] : "";
    
       
        // boton
        $action = (isset($_POST['action'])) ? $_POST['action'] : "";
       
        // FALTA
        $foto = "";
        $estado = 1;
        $tipoUsuario = 2;
        $idioma = 1;
        $codigo_confirmacion = 'C' . DATE('His') .'d'. DATE('Ymd');

        switch($action){
            case "register":
            
                $insert_usuario = $pdo->prepare("INSERT INTO Usuarios(NombreUsuario, Contrasena, Correo, Estado, FK_TipoUsuario, FK_Idioma, Foto, CodigoConfirmacion)
                                                        VALUES(:NombreUsuario, :Contrasena, :Correo, :Estado, :FK_TipoUsuario, :FK_Idioma, :Foto, :CodigoConfirmacion)");

                $pass_encript = openssl_encrypt($contrasena, COD, KEY);
                $insert_usuario->bindParam(':NombreUsuario', $nombreUsuario);
                $insert_usuario->bindParam(':Contrasena', $pass_encript);
                $insert_usuario->bindParam(':Correo', $correo);
                $insert_usuario->bindParam(':Estado', $estado);
                $insert_usuario->bindParam(':FK_TipoUsuario', $tipoUsuario);
                $insert_usuario->bindParam(':FK_Idioma', $idioma);
                $insert_usuario->bindParam(':Foto', $foto);
                $insert_usuario->bindParam(':CodigoConfirmacion', $codigo_confirmacion);
                
                
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                try{
                    $insert_usuario->execute();
                }catch(PDOException $e){
                    echo $e->getMessage();
                }
               
                $buscar_usuario = $pdo->prepare("SELECT PK_Usuario FROM Usuarios WHERE NombreUsuario = :nombreUsuario");
                $buscar_usuario->bindParam(':nombreUsuario', $nombreUsuario);
                $buscar_usuario->execute();
                $usuario_creado = $buscar_usuario->fetchAll(PDO::FETCH_ASSOC);

                

                //Insertar Tienda
                $insert_tienda = $pdo->prepare("INSERT INTO `Tiendas` (`PK_Tienda`, `NombreTienda`, `NombreContacto`, `ApellidoContacto`, `Direccion1`, `Direccion2`, `SitioWeb`, `Correo`, `Adomicilio`, `FK_Ciudad`, `FK_Usuario`, `Telefono`) 
                                                                 VALUES (NULL, :NombreTienda, :NombreContacto, :ApellidoContacto, :Direccion1, :Direccion2, :SitioWeb, :Correo, :Adomicilio, :FK_Ciudad, :FK_Usuario, :Telefono)");
                $insert_tienda->bindParam(':NombreTienda', $nombreTienda);
                $insert_tienda->bindParam(':NombreContacto', $nombreContacto);
                $insert_tienda->bindParam(':ApellidoContacto', $apellidoContacto);
                $insert_tienda->bindParam(':Direccion1', $direccion1);
                $insert_tienda->bindParam(':Direccion2', $direccion2);
                $insert_tienda->bindParam(':SitioWeb', $webSite);
                $insert_tienda->bindParam(':Correo', $correo);
                $insert_tienda->bindParam(':Adomicilio', $adomicilio);
                $insert_tienda->bindParam(':FK_Ciudad', $ciudad);
                $insert_tienda->bindParam(':FK_Usuario', $usuario_creado[0]['PK_Usuario']);
                $insert_tienda->bindParam(':Telefono', $telefono);

                if($insert_tienda->execute()){
                    $uploadOk = 1;
                    // actualizar logo
                    $tmp_foto_logo = $_FILES['input_logo']['tmp_name'];

                    if($tmp_foto_logo != ""){
                        // Codigo para subir la imagen
                      
                        $nombre_archivo = ($logo!="")?"tienda_".$usuario_creado[0]['PK_Usuario']."_logo.jpg":"";
                        
    
                
                        if($tmp_foto_logo != ""){
                            if(file_exists ('../uploads/img/logos/'.$nombre_archivo)){
                                unlink('../uploads/img/logos/'.$nombre_archivo);
                            }
                            move_uploaded_file($tmp_foto_logo, '../uploads/img/logos/'.$nombre_archivo); 
                        }
    
                        // actualizar foto de perfil del usuario
                        $actualizar_usuario = $pdo->prepare("UPDATE `Usuarios` SET `Foto` = :Foto 
                                                            WHERE `Usuarios`.`PK_Usuario` = :PK_Usuario;");
                        $actualizar_usuario->bindParam(':Foto', $nombre_archivo); 
                        $actualizar_usuario->bindParam(':PK_Usuario', $usuario_creado[0]['PK_Usuario']); 
    
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                        try{
                            $actualizar_usuario->execute();
                        }catch(PDOException $e){
                            echo $e->getMessage();
                        }
    
                        // actualizar logo de la tienda
                        $actualizar_tienda = $pdo->prepare("UPDATE `Tiendas` SET `Logo` = :Logo 
                                                            WHERE `Tiendas`.`FK_Usuario` = :FK_Usuario;");
                        $actualizar_tienda->bindParam(':Logo', $nombre_archivo); 
                        $actualizar_tienda->bindParam(':FK_Usuario', $usuario_creado[0]['PK_Usuario']); 
    
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                        try{
                            $actualizar_tienda->execute();
                        }catch(PDOException $e){
                            echo $e->getMessage();
                        }
                    }

                    // actualizar portada
                    $tmp_foto_tienda = $_FILES['input_portada']['tmp_name'];
                    if($tmp_foto_tienda != ""){
                    
                        $nombre_archivo = ($portada!="")?"tienda_".$usuario_creado[0]['PK_Usuario']."_portada.jpg":"";
                        

                
                        if($tmp_foto_tienda != ""){
                            if(file_exists ('../uploads/img/portadas/'.$nombre_archivo)){
                                unlink('../uploads/img/portadas/'.$nombre_archivo);
                            }
                            move_uploaded_file($tmp_foto_tienda, '../uploads/img/portadas/'.$nombre_archivo); 
                        }

                        // actualizar logo de la tienda
                        $actualizar_tienda = $pdo->prepare("UPDATE `Tiendas` SET `Portada` = :Portada 
                                                            WHERE `Tiendas`.`FK_Usuario` = :FK_Usuario;");
                        $actualizar_tienda->bindParam(':Portada', $nombre_archivo); 
                        $actualizar_tienda->bindParam(':FK_Usuario', $usuario_creado[0]['PK_Usuario']); 

                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        try{
                            $actualizar_tienda->execute();
                        }catch(PDOException $e){
                            echo $e->getMessage();
                        }                   
                    }
                    header('location: ../Tiendas-Admin?msj=registrada');
                }
    
            break;
            case "eliminar_tienda":

                $pk_tienda = (isset($_POST['PK_Tienda']))?$_POST['PK_Tienda']:"";

                $select_tienda = $pdo->prepare("SELECT * FROM Tiendas 
                                                WHERE PK_Tienda = :PK_Tienda");
                $select_tienda->bindParam(':PK_Tienda', $pk_tienda);
                $select_tienda->execute();
                $tienda = $select_tienda->fetchAll(PDO::FETCH_ASSOC);

                //ELiminar produtos en carrito de la tienda
                $eliminar_carrito = $pdo->prepare("DELETE c FROM Carrito c JOIN Productos p 
                                                  ON c.FK_Producto = p.PK_Producto JOIN Tiendas t
                                                  ON p.FK_Tienda = t.PK_Tienda  
                                                  WHERE t.PK_Tienda = :PK_Tienda");
                $eliminar_carrito->bindParam(':PK_Tienda', $pk_tienda);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $eliminar_carrito->execute();

                //ELiminar detalles de pedidos de la tienda
                $eliminar_pedidos = $pdo->prepare("DELETE dp FROM DetallePedidos dp JOIN Productos p 
                                                  ON dp.FK_Producto = p.PK_Producto JOIN Tiendas t
                                                  ON p.FK_Tienda = t.PK_Tienda  
                                                  WHERE t.PK_Tienda = :PK_Tienda");
                $eliminar_pedidos->bindParam(':PK_Tienda', $pk_tienda);
                $eliminar_pedidos->execute();

                //ELiminar pedidos de la tienda
                $eliminar_pedidos = $pdo->prepare("DELETE FROM Pedidos 
                                                  WHERE FK_Tienda = :PK_Tienda");
                $eliminar_pedidos->bindParam(':PK_Tienda', $pk_tienda);
                $eliminar_pedidos->execute();

                //ELiminar regiones de envío de la tienda
                $eliminar_regiones = $pdo->prepare("DELETE FROM RegionesEnvio 
                                                  WHERE FK_Tienda = :PK_Tienda");
                $eliminar_regiones->bindParam(':PK_Tienda', $pk_tienda);
                $eliminar_regiones->execute();

                //ELiminar pago temp
                $eliminar_productos = $pdo->prepare("DELETE dp FROM Pago_solouno_temp dp JOIN Productos p 
                                                    ON dp.FK_Producto = p.PK_Producto JOIN Tiendas t
                                                    ON p.FK_Tienda = t.PK_Tienda  
                                                    WHERE t.PK_Tienda = :PK_Tienda");
                $eliminar_productos->bindParam(':PK_Tienda', $pk_tienda);
                $eliminar_productos->execute();

                //ELiminar productos
                $eliminar_productos = $pdo->prepare("DELETE FROM Productos 
                                                  WHERE FK_Tienda = :PK_Tienda");
                $eliminar_productos->bindParam(':PK_Tienda', $pk_tienda);
                $eliminar_productos->execute();

                //ELiminar tienda
                $eliminar_tienda = $pdo->prepare("DELETE FROM Tiendas 
                                                  WHERE PK_Tienda = :PK_Tienda");
                $eliminar_tienda->bindParam(':PK_Tienda', $pk_tienda);
                $eliminar_tienda->execute();

                 //ELiminar usuario de tienda
                 $eliminar_usuario = $pdo->prepare("DELETE FROM Usuarios 
                                                 WHERE PK_Usuario = :PK_Usuario");
                $eliminar_usuario->bindParam(':PK_Usuario', $tienda[0]['FK_Usuario']);
                $eliminar_usuario->execute();

                
  

                header('location: ../Tiendas-Admin?msj=eliminada');

            break;
        }
    
       
    }
?>