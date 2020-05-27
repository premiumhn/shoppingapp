<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include ("../global/config.php");
include ("../global/conexion.php");

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

    
        $username = (isset($_POST['input_username'])) ? $_POST['input_username'] : "";
        $email = (isset($_POST['input_email'])) ? $_POST['input_email'] : "";
        $password = (isset($_POST['input_password'])) ? $_POST['input_password'] : "";

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
        $tipoUsuario = 1;
        $idioma = 1;
        $foto = "";
        $codigo_confirmacion = 'C' . DATE('His') .'d'. DATE('Ymd');
        

        switch($action){
            case "register":
            
                $insert_usuario = $pdo->prepare("INSERT INTO Usuarios(NombreUsuario, Contrasena, Correo, Estado, FK_TipoUsuario, FK_Idioma, Foto, CodigoConfirmacion)
                                                        VALUES(:NombreUsuario, :Contrasena, :Correo, :Estado, :FK_TipoUsuario, :FK_Idioma, :Foto, :CodigoConfirmacion)");
    
                $insert_usuario->bindParam(':NombreUsuario', $username);
                $insert_usuario->bindParam(':Contrasena', openssl_encrypt($password, COD, KEY));
                $insert_usuario->bindParam(':Correo', $email);
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
                $buscar_usuario->bindParam(':nombreUsuario', $username);
                $buscar_usuario->execute();
                $usuario_creado = $buscar_usuario->fetchAll(PDO::FETCH_ASSOC);

               

                
                //Insertar Cliente
                $insert_cliente = $pdo->prepare("INSERT INTO `Clientes` ( `FK_Usuario`, `PrimerNombre`, `SegundoNombre`, `PrimerApellido`, `SegundoApellido`, `Direccion1`, `Telefono`, `FK_Ciudad`) 
                                                                 VALUES ( :FK_Usuario, :PrimerNombre, :SegundoNombre, :PrimerApellido, :SegundoApellido, :Direccion1, :Telefono, NULL);");
                $insert_cliente->bindParam(':FK_Usuario', $usuario_creado[0]['PK_Usuario']);
                $insert_cliente->bindParam(':PrimerNombre', $firstName);
                $insert_cliente->bindParam(':SegundoNombre', $middleName);
                $insert_cliente->bindParam(':PrimerApellido', $firstSurname);
                $insert_cliente->bindParam(':SegundoApellido', $secondSurname);
                $insert_cliente->bindParam(':Direccion1', $address);
                $insert_cliente->bindParam(':Telefono', $telephone);
                try{
                    $insert_cliente->execute();
                    //header('Location: ../Inicio');
                    header('location: ../scripts/email.php?c='.$codigo_confirmacion.'&m='.$email);
                }catch(PDOException $e){
                    echo "Error ". $e->getMessage();
                }
    
            break;
        }
    
       
    }
?>