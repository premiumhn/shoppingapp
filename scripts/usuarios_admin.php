<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include ("../global/config.php");
include ("../global/conexion.php");

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

    
        $username = (isset($_POST['input_nombreUsuario'])) ? $_POST['input_nombreUsuario'] : "";
        $email = (isset($_POST['input_correo'])) ? $_POST['input_correo'] : "";
        $password = (isset($_POST['input_contrasena'])) ? $_POST['input_contrasena'] : "";
        $imagen = (isset($_FILES['input_imagen'])) ? $_FILES['input_imagen'] : "";
        $pk_usuario = (isset($_POST['PK_Usuario'])) ? $_POST['PK_Usuario'] : "";

         // datos temporales mientras se completa el peril
        $firstName = "";
        $middleName = "";
        $firstSurname = "";
        $secondSurname =  "";
        $telephone = "";
        $address = "";
        $country = "";
        $city = "";
    
        // boton
        $action = (isset($_POST['action'])) ? $_POST['action'] : "";
       
        // FALTA
        $estado = 1;
        $tipoUsuario = 3;
        $idioma = 1;
        $codigo_confirmacion = 'C' . DATE('His') .'d'. DATE('Ymd');
        

        switch($action){
            case "register":

                
                $tmp_foto = $_FILES['input_imagen']['tmp_name'];
                $nombre_archivo = "";
            
                if($tmp_foto != ""){
                    $nombre_archivo = ($imagen!="")?"user_".$username."_foto_perfil.jpg":"";
                    if(file_exists ('../uploads/img/perfiles/'.$nombre_archivo)){
                        unlink('../uploads/img/perfiles/'.$nombre_archivo);
                    }
                    move_uploaded_file($tmp_foto, '../uploads/img/perfiles/'.$nombre_archivo);
                    
                }
            
                $insert_usuario = $pdo->prepare("INSERT INTO Usuarios(NombreUsuario, Contrasena, Correo, Estado, FK_TipoUsuario, FK_Idioma, Foto, CodigoConfirmacion)
                                                        VALUES(:NombreUsuario, :Contrasena, :Correo, :Estado, :FK_TipoUsuario, :FK_Idioma, :Foto, :CodigoConfirmacion)");
                
                $password_enc = openssl_encrypt($password, COD, KEY);
                $insert_usuario->bindParam(':NombreUsuario', $username);
                $insert_usuario->bindParam(':Contrasena', $password_enc);
                $insert_usuario->bindParam(':Correo', $email);
                $insert_usuario->bindParam(':Estado', $estado);
                $insert_usuario->bindParam(':FK_TipoUsuario', $tipoUsuario);
                $insert_usuario->bindParam(':FK_Idioma', $idioma);
                $insert_usuario->bindParam(':Foto', $nombre_archivo);
                $insert_usuario->bindParam(':CodigoConfirmacion', $codigo_confirmacion);
    
             
                
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                try{
                    $insert_usuario->execute();
                    header('Location: ../Usuarios-Admin');
                }catch(PDOException $e){
                    echo $e->getMessage();
                }
               
               
    
            break;
            case "editar_usuario":

                
                $nombre_archivo = ($imagen!="")?"user_".$username."_foto_perfil.jpg":"";
                $tmp_foto = $_FILES['input_imagen']['tmp_name'];

            
                if($tmp_foto != ""){
                    if(file_exists ('../uploads/img/perfiles/'.$nombre_archivo)){
                        unlink('../uploads/img/perfiles/'.$nombre_archivo);
                    }
                    move_uploaded_file($tmp_foto, '../uploads/img/perfiles/'.$nombre_archivo);
                    
                    $insert_usuario = $pdo->prepare("UPDATE Usuarios SET NombreUsuario = :NombreUsuario, Correo = :Correo, Contrasena = :Contrasena, Foto = :Foto
                                                     WHERE PK_Usuario = :PK_Usuario");

                    $password_enc = openssl_encrypt($password, COD, KEY);
                    $insert_usuario->bindParam(':NombreUsuario', $username);
                    $insert_usuario->bindParam(':Contrasena', $password_enc);
                    $insert_usuario->bindParam(':Correo', $email);
                    $insert_usuario->bindParam(':Foto', $nombre_archivo);
                    $insert_usuario->bindParam(':PK_Usuario', $pk_usuario);

                }else{
                    $insert_usuario = $pdo->prepare("UPDATE Usuarios SET NombreUsuario = :NombreUsuario, Correo = :Correo, Contrasena = :Contrasena
                                                     WHERE PK_Usuario = :PK_Usuario");

                    $password_enc = openssl_encrypt($password, COD, KEY);
                    $insert_usuario->bindParam(':NombreUsuario', $username);
                    $insert_usuario->bindParam(':Contrasena', $password_enc);
                    $insert_usuario->bindParam(':Correo', $email);
                    $insert_usuario->bindParam(':PK_Usuario', $pk_usuario);
                }
            
    
             
                
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                try{
                    $insert_usuario->execute();
                    header('Location: ../Usuarios-Admin?msj=editado');
                }catch(PDOException $e){
                    echo $e->getMessage();
                }
    
            break;
            case "eliminar_usuario":

                $sql_usuarios_admin = $pdo->prepare("SELECT * FROM Usuarios WHERE FK_TipoUsuario = 3");
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql_usuarios_admin->execute();
                $usuarios_admin = $sql_usuarios_admin->fetchAll(PDO::FETCH_ASSOC);

                if(count($usuarios_admin) == 1){
                    header('Location: ../Usuarios-Admin?msj=error_1');
                }else{
                    $select_usuario = $pdo->prepare("SELECT * FROM Usuarios WHERE PK_Usuario = :PK_Usuario");
                    $select_usuario->bindParam(':PK_Usuario', $pk_usuario);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $select_usuario->execute();
                    $usuario = $select_usuario->fetchAll(PDO::FETCH_ASSOC);

                    $eliminar_usuario = $pdo->prepare("DELETE FROM Usuarios WHERE PK_Usuario = :PK_Usuario");
                    $eliminar_usuario->bindParam(':PK_Usuario', $pk_usuario);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    try{

                        if(file_exists ('../uploads/img/perfiles/'.$usuario[0]['Foto'])){
                            unlink('../uploads/img/perfiles/'.$usuario[0]['Foto']);
                        }

                        $eliminar_usuario->execute();
                        header('Location: ../Usuarios-Admin?msj=eliminado');
                    }catch(PDOException $e){
                        echo $e->getMessage();
                    }
                }

                
    
            break;
            case "editar_mi_usuario":

                
                $nombre_archivo = ($imagen!="")?"user_".$username."_foto_perfil.jpg":"";
                $tmp_foto = $_FILES['input_imagen']['tmp_name'];

            
                if($tmp_foto != ""){
                    if(file_exists ('../uploads/img/perfiles/'.$nombre_archivo)){
                        unlink('../uploads/img/perfiles/'.$nombre_archivo);
                    }
                    move_uploaded_file($tmp_foto, '../uploads/img/perfiles/'.$nombre_archivo);
                    
                    $insert_usuario = $pdo->prepare("UPDATE Usuarios SET NombreUsuario = :NombreUsuario, Correo = :Correo, Contrasena = :Contrasena, Foto = :Foto
                                                     WHERE PK_Usuario = :PK_Usuario");

                    $password_enc = openssl_encrypt($password, COD, KEY);
                    $insert_usuario->bindParam(':NombreUsuario', $username);
                    $insert_usuario->bindParam(':Contrasena', $password_enc);
                    $insert_usuario->bindParam(':Correo', $email);
                    $insert_usuario->bindParam(':Foto', $nombre_archivo);
                    $insert_usuario->bindParam(':PK_Usuario', $pk_usuario);

                }else{
                    $insert_usuario = $pdo->prepare("UPDATE Usuarios SET NombreUsuario = :NombreUsuario, Correo = :Correo, Contrasena = :Contrasena
                                                     WHERE PK_Usuario = :PK_Usuario");

                    $password_enc = openssl_encrypt($password, COD, KEY);
                    $insert_usuario->bindParam(':NombreUsuario', $username);
                    $insert_usuario->bindParam(':Contrasena', $password_enc);
                    $insert_usuario->bindParam(':Correo', $email);
                    $insert_usuario->bindParam(':PK_Usuario', $pk_usuario);
                }
            
    
             
                
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                try{
                    $insert_usuario->execute();
                    header('Location: ../Editar-Usuario-Admin?msj=editado&PK_Usuario='.$pk_usuario);
                }catch(PDOException $e){
                    echo $e->getMessage();
                }
    
            break;
        }
    
       
    }
?>