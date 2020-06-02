<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include ("../global/config.php");
include ("../global/conexion.php");

session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    global $form;
    
    // accion del registro
    $action = (isset($_POST['action'])) ? $_POST['action'] : "";

    switch($action){
        case "registrar_categoria":
            // variables categoria

            // print_r($_POST);

            $nombreCategoria = (isset($_POST['input_nombreCategoria'])) ? $_POST['input_nombreCategoria'] : "";
            $descripcionCategoria = (isset($_POST['input_descripcion'])) ? $_POST['input_descripcion'] : "";
            $imagen = (isset($_FILES['input_imagen']['name'])) ? $_FILES['input_imagen']['name'] : "";
            $estado = (isset($_POST['input_estado'])) ? $_POST['input_estado'] : "";


            // Codigo para subir la imagen
            $fecha = new DateTime();
            $nombre_archivo = ($imagen!="")?$nombreCategoria."_".$fecha->getTimestamp()."_".$_FILES['input_imagen']['name']:"";
            $tmp_foto = $_FILES['input_imagen']['tmp_name'];

    
            if ($_FILES["input_imagen"]["size"] > 50000000) {
                header('location: ../Registro-Datos?menu=ver_categorias&msj=muypesada');
                $uploadOk = 0;
            }elseif($tmp_foto != ""){
                move_uploaded_file($tmp_foto, '../uploads/img/categorias/'.$nombre_archivo);
            }


           
            $insert_categoria = $pdo->prepare("INSERT INTO Categorias(PK_Categoria, NombreCategoria, Descripcion, Imagen, Estado) 
                                                                VALUES (NULL, :NombreCategoria, :Descripcion, :Imagen, :Estado)");

            $insert_categoria->bindParam(':NombreCategoria', $nombreCategoria);
            $insert_categoria->bindParam(':Descripcion', $descripcionCategoria);
            $insert_categoria->bindParam(':Imagen', $nombre_archivo);
            $insert_categoria->bindParam(':Estado', $estado);

            try{
                
                $insert_categoria->execute();
                header('location: ../Registro-Datos?menu=registro_categoria&msj=registrada');
            }catch(PDOException $e){
                echo "Error ". $e->getMessage() . $e->errorInfo();
                header('location: ../Registro-Datos?menu=registro_categoria');
            }

        break;
        case "editar_categoria":

            $nombreCategoria = (isset($_POST['input_nombreCategoria'])) ? $_POST['input_nombreCategoria'] : "";
            $descripcionCategoria = (isset($_POST['input_descripcion'])) ? $_POST['input_descripcion'] : "";
            $imagen = (isset($_FILES['input_imagen']['name'])) ? $_FILES['input_imagen']['name'] : "";
            $estado = (isset($_POST['input_estado'])) ? $_POST['input_estado'] : "";
            $pk_categoria = (isset($_POST['pk_categoria'])) ? $_POST['pk_categoria'] : "";

            $uploadOk = 1;
            if($imagen != ""){
                 // Codigo para subir la imagen
                $fecha = new DateTime();
                $nombre_archivo = ($imagen!="")?$nombreCategoria."_".$fecha->getTimestamp()."_".$_FILES['input_imagen']['name']:"";
                $tmp_foto = $_FILES['input_imagen']['tmp_name'];

        
                if ($_FILES["input_imagen"]["size"] > 3000000) {
                    header('location: ../Registro-Datos?menu=ver_categorias&msj=muypesada');
                    $uploadOk = 0;
                }elseif($tmp_foto != ""){
                    
                    $select_categoria = $pdo->prepare("SELECT * FROM Categorias
                                                    WHERE PK_Categoria = :PK_Categoria");
                    $select_categoria->bindParam(':PK_Categoria', $pk_categoria);
                    $select_categoria->execute();
                    $categoria = $select_categoria->fetchAll(PDO::FETCH_ASSOC);
                    $foto_actual = $categoria[0]['Imagen'];
                    if(file_exists ('../uploads/img/categorias/'.$foto_actual)){
                        unlink('../uploads/img/categorias/'.$foto_actual);
                    }
                    move_uploaded_file($tmp_foto, '../uploads/img/categorias/'.$nombre_archivo);
                }
            }

           if($imagen!="" and $uploadOk != 0){
                $editar_categoria = $pdo->prepare("UPDATE Categorias 
                                                   SET NombreCategoria = :NombreCategoria,
                                                       Descripcion = :Descripcion,
                                                       Imagen = :Imagen, 
                                                       Estado = :Estado 
                                                   WHERE PK_Categoria = :PK_Categoria");

                $editar_categoria->bindParam(':NombreCategoria', $nombreCategoria);
                $editar_categoria->bindParam(':Descripcion', $descripcionCategoria);
                $editar_categoria->bindParam(':Imagen', $nombre_archivo);
                $editar_categoria->bindParam(':Estado', $estado);
                $editar_categoria->bindParam(':PK_Categoria', $pk_categoria);
                $editar_categoria->execute();
                header('location: ../Registro-Datos?menu=ver_categorias&msj=editada');
           }else if($uploadOk != 0){
                $editar_categoria = $pdo->prepare("UPDATE Categorias 
                                                  SET NombreCategoria = :NombreCategoria,
                                                      Descripcion = :Descripcion,
                                                      Estado = :Estado 
                                                  WHERE PK_Categoria = :PK_Categoria");

                $editar_categoria->bindParam(':NombreCategoria', $nombreCategoria);
                $editar_categoria->bindParam(':Descripcion', $descripcionCategoria);
                $editar_categoria->bindParam(':Estado', $estado);
                $editar_categoria->bindParam(':PK_Categoria', $pk_categoria);
                $editar_categoria->execute();
                header('location: ../Registro-Datos?menu=ver_categorias&msj=editada');
           }
            

        break;
        case "eliminar_categoria":
            $pk_categoria = (isset($_POST['pk_categoria'])) ? $_POST['pk_categoria'] : "";


            $select_productos = $pdo->prepare("SELECT * FROM Productos
                                               WHERE FK_Categoria = :PK_Categoria");
            $select_productos->bindParam(':PK_Categoria', $pk_categoria);
            $select_productos->execute();
            $productos = $select_productos->fetchAll(PDO::FETCH_ASSOC);
       
            if(count($productos) == 0){
                $editar_categoria = $pdo->prepare("DELETE FROM Categorias
                                                WHERE PK_Categoria = :PK_Categoria");
                $editar_categoria->bindParam(':PK_Categoria', $pk_categoria);
                $editar_categoria->execute();
                header('location: ../Registro-Datos?menu=ver_categorias&msj=eliminada');
            }else{
                header('location: ../Registro-Datos?menu=ver_categorias&msj=error_1');
            }
            
            

        break;
        case "editar_usuario":

           
            $imagen = (isset($_FILES['input_imagen'])) ? $_FILES['input_imagen'] : ""; 
            $primer_nombre = (isset($_POST['input_primerNombre'])) ? $_POST['input_primerNombre'] : "";
            $segundo_nombre = (isset($_POST['input_segundoNombre'])) ? $_POST['input_segundoNombre'] : "";
            $primer_apellido = (isset($_POST['input_primerApellido'])) ? $_POST['input_primerApellido'] : "";
            $segundo_apellido = (isset($_POST['input_segundoApellido'])) ? $_POST['input_segundoApellido'] : "";
            $nombre_usuario = (isset($_POST['input_nombreUsuario'])) ? $_POST['input_nombreUsuario'] : "";
            $correo = (isset($_POST['input_correo'])) ? $_POST['input_correo'] : "";
            $contrasena = (isset($_POST['input_contrasena'])) ? $_POST['input_contrasena'] : "";
            $idioma = (isset($_POST['input_idioma'])) ? $_POST['input_idioma'] : "";
            $direccion1 = (isset($_POST['input_direccion1'])) ? $_POST['input_direccion1'] : "";
            $direccion2 = (isset($_POST['input_direccion2'])) ? $_POST['input_direccion2'] : "";
            $telefono = (isset($_POST['input_telefono'])) ? $_POST['input_telefono'] : "";
            $pais = (isset($_POST['input_pais'])) ? $_POST['input_pais'] : "";
            $ciudad = (isset($_POST['input_ciudad'])) ? $_POST['input_ciudad'] : "";

     

            $uploadOk = 1;
            $tmp_foto = $_FILES['input_imagen']['tmp_name'];
            if($tmp_foto != ""){
              

                $nombre_archivo = ($imagen!="")?"user_".$_SESSION['login_user'].'_'.date('YmdHis').'.jpg':"";
                    $tmp_foto = $_FILES['input_imagen']['tmp_name'];

            
                    if ($_FILES["input_imagen"]["size"] > 3000000) {
                        header('location: ../Registro-Datos?menu=perfil_usuario&msj=muypesada');
                        $uploadOk = 0;
                    }elseif($tmp_foto != ""){

                        $select_usuario = $pdo->prepare("SELECT * FROM Usuarios
                                                        WHERE PK_Usuario = :PK_Usuario");
                        $select_usuario->bindParam(':PK_Usuario', $_SESSION['login_user']);
                        $select_usuario->execute();
                        $usuario = $select_usuario->fetchAll(PDO::FETCH_ASSOC);
                        $foto_actual = $usuario[0]['Foto'];
                        if(file_exists ('../uploads/img/perfiles/'.$foto_actual)){
                            unlink('../uploads/img/perfiles/'.$foto_actual);
                        }
                    move_uploaded_file($tmp_foto, '../uploads/img/perfiles/'.$nombre_archivo);

                }
            }

           if( $tmp_foto != "" and $uploadOk != 0){
            
                $editar_usuario = $pdo->prepare("UPDATE Usuarios 
                                                 SET NombreUsuario = :NombreUsuario,
                                                     Contrasena = :Contrasena,
                                                     Correo = :Correo, 
                                                     FK_Idioma = :FK_Idioma,
                                                     Foto = :Foto 
                                                 WHERE PK_Usuario = :PK_Usuario");

                $editar_usuario->bindParam(':NombreUsuario', $nombre_usuario);
                $editar_usuario->bindParam(':Contrasena', $contrasena);
                $editar_usuario->bindParam(':Correo', $correo);
                $editar_usuario->bindParam(':FK_Idioma', $idioma);
                $editar_usuario->bindParam(':Foto', $nombre_archivo);
                $editar_usuario->bindParam(':PK_Usuario', $_SESSION['login_user']);

                $editar_cliente = $pdo->prepare("UPDATE Clientes 
                                                 SET PrimerNombre = :PrimerNombre,
                                                     SegundoNombre = :SegundoNombre,
                                                     PrimerApellido = :PrimerApellido, 
                                                     SegundoApellido = :SegundoApellido,
                                                     Direccion1 = :Direccion1,
                                                     Direccion2 = :Direccion2,
                                                     Telefono = :Telefono,
                                                     FK_Ciudad = :FK_Ciudad
                                                 WHERE FK_Usuario = :FK_Usuario");

                $editar_cliente->bindParam(':PrimerNombre', $primer_nombre);
                $editar_cliente->bindParam(':SegundoNombre', $segundo_nombre);
                $editar_cliente->bindParam(':PrimerApellido', $primer_apellido);
                $editar_cliente->bindParam(':SegundoApellido', $segundo_apellido);
                $editar_cliente->bindParam(':Direccion1', $direccion1);
                $editar_cliente->bindParam(':Direccion2', $direccion2);
                $editar_cliente->bindParam(':Telefono', $telefono);
                $editar_cliente->bindParam(':FK_Ciudad', $ciudad);
                $editar_cliente->bindParam(':FK_Usuario', $_SESSION['login_user']);

                $editar_cliente->execute();
                $editar_usuario->execute();
                header('location: ../Registro-Datos?menu=perfil_usuario&msj=editado');
           }else{
                $editar_usuario = $pdo->prepare("UPDATE Usuarios 
                                                 SET NombreUsuario = :NombreUsuario,
                                                Contrasena = :Contrasena,
                                                Correo = :Correo, 
                                                FK_Idioma = :FK_Idioma
                                                WHERE PK_Usuario = :PK_Usuario");

                $editar_usuario->bindParam(':NombreUsuario', $nombre_usuario);
                $editar_usuario->bindParam(':Contrasena', $contrasena);
                $editar_usuario->bindParam(':Correo', $correo);
                $editar_usuario->bindParam(':FK_Idioma', $idioma);
                $editar_usuario->bindParam(':PK_Usuario', $_SESSION['login_user']);

                $editar_cliente = $pdo->prepare("UPDATE Clientes 
                            SET PrimerNombre = :PrimerNombre,
                                SegundoNombre = :SegundoNombre,
                                PrimerApellido = :PrimerApellido, 
                                SegundoApellido = :SegundoApellido,
                                Direccion1 = :Direccion1,
                                Direccion2 = :Direccion2,
                                Telefono = :Telefono,
                                FK_Ciudad = :FK_Ciudad
                            WHERE FK_Usuario = :FK_Usuario");

                $editar_cliente->bindParam(':PrimerNombre', $primer_nombre);
                $editar_cliente->bindParam(':SegundoNombre', $segundo_nombre);
                $editar_cliente->bindParam(':PrimerApellido', $primer_apellido);
                $editar_cliente->bindParam(':SegundoApellido', $segundo_apellido);
                $editar_cliente->bindParam(':Direccion1', $direccion1);
                $editar_cliente->bindParam(':Direccion2', $direccion2);
                $editar_cliente->bindParam(':Telefono', $telefono);
                $editar_cliente->bindParam(':FK_Ciudad', $ciudad);
                $editar_cliente->bindParam(':FK_Usuario', $_SESSION['login_user']);

                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $editar_usuario->execute();
                $editar_cliente->execute();
                header('location: ../Registro-Datos?menu=perfil_usuario&msj=editado');
           }
            

        break;
        case "editar_tienda":
          
            $logo = (isset($_FILES['input_logo'])) ? $_FILES['input_logo'] : ""; 
            $nombre_contacto = (isset($_POST['input_nombreContacto'])) ? $_POST['input_nombreContacto'] : "";
            $apellido_contacto = (isset($_POST['input_apellidoContacto'])) ? $_POST['input_apellidoContacto'] : "";
            $id_cliente_paypal = (isset($_POST['input_idClientePaypal'])) ? $_POST['input_idClientePaypal'] : "";
            $adomicilio = (isset($_POST['input_adomicilio'])) ? $_POST['input_adomicilio'] : "";
            $correo = (isset($_POST['input_correo'])) ? $_POST['input_correo'] : "";
            $contrasena = (isset($_POST['input_contrasena'])) ? $_POST['input_contrasena'] : "";
            $idioma = (isset($_POST['input_idioma'])) ? $_POST['input_idioma'] : "";
            $direccion1 = (isset($_POST['input_direccion1'])) ? $_POST['input_direccion1'] : "";
            $direccion2 = (isset($_POST['input_direccion2'])) ? $_POST['input_direccion2'] : "";
            $telefono = (isset($_POST['input_telefono'])) ? $_POST['input_telefono'] : "";
            $sitioweb = (isset($_POST['input_sitioWeb'])) ? $_POST['input_sitioWeb'] : "";
            $pais = (isset($_POST['input_pais'])) ? $_POST['input_pais'] : "";
            $ciudad = (isset($_POST['input_ciudad'])) ? $_POST['input_ciudad'] : "";
            $portada = (isset($_FILES['input_portada'])) ? $_FILES['input_portada'] : ""; 

    
            $uploadOk = 1;
            $tmp_foto_logo = $_FILES['input_logo']['tmp_name'];
            if($tmp_foto_logo != ""){
                 // Codigo para subir el logo

                 $nombre_archivo_logo = ($logo!="")?"tienda_".$_SESSION['login_user'].'_'.date('YmdHis').'.jpg':"";
                 
                 if ($_FILES["input_logo"]["size"] > 3000000) {
                        header('location: ../Registro-Datos?menu=perfil_tienda&msj=muypesada');
                        $uploadOk = 0;
                    }elseif($tmp_foto_logo != ""){

                        $select_usuario = $pdo->prepare("SELECT * FROM Tiendas
                                                        WHERE FK_Usuario = :PK_Usuario");
                        $select_usuario->bindParam(':PK_Usuario', $_SESSION['login_user']);
                        $select_usuario->execute();
                        $usuario = $select_usuario->fetchAll(PDO::FETCH_ASSOC);
                        $logo_actual = $usuario[0]['Logo'];

                        if(file_exists ('../uploads/img/logos/'.$logo_actual)){
                            unlink('../uploads/img/logos/'.$logo_actual);
                        }
                    move_uploaded_file($tmp_foto_logo, '../uploads/img/logos/'.$nombre_archivo_logo); 
                    }
            }

           
            $tmp_foto_portada = $_FILES['input_portada']['tmp_name'];
            if($tmp_foto_portada != ""){
                // Codigo para subir la portada
              
                $nombre_archivo_portada = ($logo!="")?"tienda_".$_SESSION['login_user'].'_'.date('YmdHis').'.jpg':"";
                


                if ($_FILES["input_portada"]["size"] > 5000000) {
                       header('location: ../Registro-Datos?menu=perfil_tienda&msj=muypesada');
                       $uploadOk = 0;
                       echo 'muy pesada';
                }elseif($tmp_foto_portada != ""){
                        $select_usuario = $pdo->prepare("SELECT * FROM Tiendas
                                                        WHERE FK_Usuario = :PK_Usuario");
                        $select_usuario->bindParam(':PK_Usuario', $_SESSION['login_user']);
                        $select_usuario->execute();
                        $usuario = $select_usuario->fetchAll(PDO::FETCH_ASSOC);
                        $portada_actual = $usuario[0]['Portada'];

                       if(file_exists ('../uploads/img/portadas/'.$portada_actual)){
                           unlink('../uploads/img/portadas/'.$portada_actual);
                       }
                   move_uploaded_file($tmp_foto_portada, '../uploads/img/portadas/'.$nombre_archivo_portada); 
                   }
           }

                if($tmp_foto_logo != ""){
                    $str_logo = ", Foto = :Foto";
                }else{
                    $str_logo = "";
                }

                $editar_usuario = $pdo->prepare("UPDATE Usuarios 
                                                 SET NombreUsuario = :NombreUsuario,
                                                     Contrasena = :Contrasena,
                                                     Correo = :Correo, 
                                                     FK_Idioma = :FK_Idioma".
                                                     $str_logo ."
                                                 WHERE PK_Usuario = :PK_Usuario");

                $editar_usuario->bindParam(':NombreUsuario', $correo);
                $editar_usuario->bindParam(':Contrasena', $contrasena);
                $editar_usuario->bindParam(':Correo', $correo);
                $editar_usuario->bindParam(':FK_Idioma', $idioma);
                $editar_usuario->bindParam(':PK_Usuario', $_SESSION['login_user']);
                if($tmp_foto_logo != ""){
                    $editar_usuario->bindParam(':Foto', $nombre_archivo_logo);
                }



                if($tmp_foto_logo != ""){
                    $str_logo = ", Logo = :Logo";
                }else{
                    $str_logo = "";
                }
                if($tmp_foto_portada != ""){
                    $str_portada = ", Portada = :Portada";
                }else{
                    $str_portada = "";
                }
                
                

                $editar_tienda = $pdo->prepare("UPDATE Tiendas 
                                                 SET NombreContacto = :NombreContacto,
                                                     ApellidoContacto = :ApellidoContacto,
                                                     IDClientePaypal = :IDClientePaypal, 
                                                     SitioWeb = :SitioWeb,
                                                     Adomicilio = :Adomicilio,
                                                     Direccion1 = :Direccion1,
                                                     Direccion2 = :Direccion2,
                                                     Telefono = :Telefono,
                                                     FK_Ciudad = :FK_Ciudad" . 
                                                     $str_logo . 
                                                     $str_portada . 
                                                     " WHERE FK_Usuario = :FK_Usuario");

                                                    

                $editar_tienda->bindParam(':NombreContacto', $nombre_contacto);
                $editar_tienda->bindParam(':ApellidoContacto', $apellido_contacto);
                $editar_tienda->bindParam(':IDClientePaypal', $id_cliente_paypal);
                $editar_tienda->bindParam(':SitioWeb', $sitioweb);
                $editar_tienda->bindParam(':Adomicilio', $Adomicilio);
                $editar_tienda->bindParam(':Direccion1', $direccion1);
                $editar_tienda->bindParam(':Direccion2', $direccion2);
                $editar_tienda->bindParam(':Telefono', $telefono);
                $editar_tienda->bindParam(':FK_Ciudad', $ciudad);
                if($tmp_foto_logo != ""){
                    $editar_tienda->bindParam(':Logo', $nombre_archivo_logo);
                }
                if($tmp_foto_portada != ""){
                    $editar_tienda->bindParam(':Portada', $nombre_archivo_portada);
                }

                // $editar_tienda->debugDumpParams();

                $editar_tienda->bindParam(':FK_Usuario', $_SESSION['login_user']);

                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
               
                $editar_tienda->execute();
                $editar_usuario->execute();
            
            if($uploadOk == 1){
                header('location: ../Registro-Datos?menu=perfil_tienda&msj=editada');
            }     

        break;
        case "registrar_regionEnvio":

            // print_r($_POST);

            $fk_ciudad = (isset($_POST['input_ciudad'])) ? $_POST['input_ciudad'] : "";
            $fk_tienda = (isset($_POST['pk_tienda'])) ? $_POST['pk_tienda'] : "";
            $precio = (isset($_POST['input_PrecioEnvio'])) ? $_POST['input_PrecioEnvio'] : 0;

           
            $insert_region = $pdo->prepare("INSERT INTO RegionesEnvio(FK_Tienda, FK_Ciudad, PrecioEnvio) 
                                                              VALUES (:FK_Tienda, :FK_Ciudad, :PrecioEnvio)");

            $insert_region->bindParam(':FK_Tienda', $fk_tienda);
            $insert_region->bindParam(':FK_Ciudad', $fk_ciudad);
            $insert_region->bindParam(':PrecioEnvio', $precio);

            echo json_encode($_POST);

            
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $insert_region->execute();
                header('location: ../Registro-Datos?menu=registro_regionesEnvio&msj=registrada');
           
        break;
        case "editar_regionEnvio":

            // print_r($_POST);

            $fk_ciudad = (isset($_POST['input_ciudad'])) ? $_POST['input_ciudad'] : "";
            $precio = (isset($_POST['input_PrecioEnvio'])) ? $_POST['input_PrecioEnvio'] : "";
            $pk_region_envio = (isset($_POST['pk_regionEnvio'])) ? $_POST['pk_regionEnvio'] : "";

           
            $actualizar_region = $pdo->prepare("UPDATE RegionesEnvio 
                                                SET  FK_Ciudad = :FK_Ciudad, 
                                                     PrecioEnvio = :PrecioEnvio
                                                WHERE PK_RegionEnvio = :PK_RegionEnvio");

            $actualizar_region->bindParam(':FK_Ciudad', $fk_ciudad);
            $actualizar_region->bindParam(':PrecioEnvio', $precio);
            $actualizar_region->bindParam(':PK_RegionEnvio', $pk_region_envio);

            echo json_encode($_POST);

            
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $actualizar_region->execute();
                header('location: ../Registro-Datos?menu=ver_regionesEnvio&msj=editada');
           
        break;
        case "eliminar_regionEnvio":

            $pk_region_envio = (isset($_POST['pk_regionEnvio'])) ? $_POST['pk_regionEnvio'] : "";

                $borrar_region_envio = $pdo->prepare("DELETE FROM RegionesEnvio
                                                   WHERE PK_RegionEnvio = :PK_RegionEnvio");
                $borrar_region_envio->bindParam(':PK_RegionEnvio', $pk_region_envio);

                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $borrar_region_envio->execute();
                header('location: ../Registro-Datos?menu=ver_regionesEnvio&msj=eliminada');
            

        break;
    }


}
?>