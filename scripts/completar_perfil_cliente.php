<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include ("../global/config.php");
include ("../global/conexion.php");
session_start();

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $primerNombre = (isset($_POST['input_primerNombre'])) ? $_POST['input_primerNombre'] : "";
        $segundoNombre = (isset($_POST['input_segundoNombre'])) ? $_POST['input_segundoNombre'] : "";
        $primerApellido = (isset($_POST['input_primerApellido'])) ? $_POST['input_primerApellido'] : "";
        $segundoApellido = (isset($_POST['input_segundoApellido'])) ? $_POST['input_segundoApellido'] : "";
        $telefono = (isset($_POST['input_telefomo'])) ? $_POST['input_telefomo'] : "";
        $direccion1 = (isset($_POST['input_direccion1'])) ? $_POST['input_direccion1'] : "";
        $direccion2 = (isset($_POST['input_direccion2'])) ? $_POST['input_direccion2'] : "";
        $pais = (isset($_POST['input_pais'])) ? $_POST['input_pais'] : "";
        $ciudad = (isset($_POST['input_ciudad'])) ? $_POST['input_ciudad'] : "";
        $imagen = (isset($_FILES['input_imagen'])) ? $_FILES['input_imagen'] : "";
        
        
        // boton
        $action = (isset($_POST['action'])) ? $_POST['action'] : "";
       
        switch($action){
            case "completar":

                $buscar_cliente = $pdo->prepare("SELECT * FROM Clientes WHERE FK_Usuario = :FK_Usuario");
                $buscar_cliente->bindParam(':FK_Usuario', $_SESSION['login_user']);
                $buscar_cliente->execute();
                $cliente = $buscar_cliente->fetchAll(PDO::FETCH_ASSOC);
               
                // echo "<script>alert('".$cliente."')</script>";
                

                // actualizar foto de usuario
                if($imagen != ""){
                    // echo "<script>alert('".$imagen."')</script>";
                    // Codigo para subir la imagen
                  
                    $nombre_archivo = ($imagen!="")?"user_".$_SESSION['login_user']."_foto_perfil.jpg":"";
                    $tmp_foto = $_FILES['input_imagen']['tmp_name'];

            
                    if($tmp_foto != ""){
                        if(file_exists ('../uploads/img/perfiles/'.$nombre_archivo)){
                            unlink('../uploads/img/perfiles/'.$nombre_archivo);
                        }
                        move_uploaded_file($tmp_foto, '../uploads/img/perfiles/'.$nombre_archivo);
                      
                    }

                    $actualizar_usuario = $pdo->prepare("UPDATE `Usuarios` SET `Foto` = :Foto 
                                                        WHERE `Usuarios`.`PK_Usuario` = :PK_Usuario;");

                    $actualizar_usuario->bindParam(':Foto', $nombre_archivo); 
                    $actualizar_usuario->bindParam(':PK_Usuario', $_SESSION['login_user']); 

                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $actualizar_usuario->execute();
                   
                }
               

                // actualizar cliente
                $actualizar_cliente = $pdo->prepare("UPDATE `Clientes` SET `PrimerNombre` = :PrimerNombre, `SegundoNombre` = :SegundoNombre, `PrimerApellido` = :PrimerApellido, `SegundoApellido` = :SegundoApellido, `Direccion1` = :Direccion1, `Direccion2` = :Direccion2, `Telefono` = :Telefono, `FK_Ciudad` = :FK_Ciudad 
                                                 WHERE `Clientes`.`PK_Cliente` = :PK_Cliente AND `Clientes`.`FK_Usuario` = :FK_Usuario;");
    
                $actualizar_cliente->bindParam(':PrimerNombre', $primerNombre);
                $actualizar_cliente->bindParam(':SegundoNombre', $segundoNombre);
                $actualizar_cliente->bindParam(':PrimerApellido', $primerApellido);
                $actualizar_cliente->bindParam(':SegundoApellido', $segundoApellido);
                $actualizar_cliente->bindParam(':Direccion1', $direccion1);
                $actualizar_cliente->bindParam(':Direccion2', $direccion2);
                $actualizar_cliente->bindParam(':Telefono', $telefono);
                $actualizar_cliente->bindParam(':FK_Ciudad', $ciudad);
                $actualizar_cliente->bindParam(':PK_Cliente', $cliente[0]['PK_Cliente']);
                $actualizar_cliente->bindParam(':FK_Usuario', $cliente[0]['FK_Usuario']);

                

                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $actualizar_cliente->execute();
                header('Location: ../Inicio');
                
    
            break;
        }
    
       
    }
?>