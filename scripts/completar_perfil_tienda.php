<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include ("../global/config.php");
include ("../global/conexion.php");
session_start();

    echo json_encode($_POST);
    echo json_encode($_FILES);

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $id_cliente_paypal = (isset($_POST['input_idClientePaypal'])) ? $_POST['input_idClientePaypal'] : "";
        $adomicilio = (isset($_POST['input_adomicilio'])) ? $_POST['input_adomicilio'] : "";
        $logo = (isset($_FILES['input_logo'])) ? $_FILES['input_logo'] : "";
        $portada = (isset($_FILES['input_portada'])) ? $_FILES['input_portada'] : "";
        
        
        
        // boton
        $action = (isset($_POST['action'])) ? $_POST['action'] : "";
        echo json_encode($_POST);
        switch($action){
            case "completar":

           

                $buscar_tienda = $pdo->prepare("SELECT * FROM Tiendas WHERE FK_Usuario = :FK_Usuario");
                $buscar_tienda->bindParam(':FK_Usuario', $_SESSION['login_user']);
                $buscar_tienda->execute();
                $tienda = $buscar_tienda->fetchAll(PDO::FETCH_ASSOC);
               
               
                $uploadOk = 1;
                // actualizar logo
                if($logo != ""){
                    // Codigo para subir la imagen
                  
                    $nombre_archivo = ($logo!="")?"tienda_".$_SESSION['login_user']."_logo.jpg":"";
                    $tmp_foto = $_FILES['input_logo']['tmp_name'];

            
                    if($tmp_foto != ""){
                        if(file_exists ('../uploads/img/logos/'.$nombre_archivo)){
                            unlink('../uploads/img/logos/'.$nombre_archivo);
                        }
                        move_uploaded_file($tmp_foto, '../uploads/img/logos/'.$nombre_archivo); 
                    }

                    // actualizar foto de perfil del usuario
                    $actualizar_usuario = $pdo->prepare("UPDATE `Usuarios` SET `Foto` = :Foto 
                                                        WHERE `Usuarios`.`PK_Usuario` = :PK_Usuario;");
                    $actualizar_usuario->bindParam(':Foto', $nombre_archivo); 
                    $actualizar_usuario->bindParam(':PK_Usuario', $_SESSION['login_user']); 

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
                    $actualizar_tienda->bindParam(':FK_Usuario', $_SESSION['login_user']); 

                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    try{
                        $actualizar_tienda->execute();
                    }catch(PDOException $e){
                        echo $e->getMessage();
                    }
                }

                // actualizar portada
                if($portada != ""){
                  
                    $nombre_archivo = ($portada!="")?"tienda_".$_SESSION['login_user']."_portada.jpg":"";
                    $tmp_foto = $_FILES['input_portada']['tmp_name'];

            
                    if($tmp_foto != ""){
                        if(file_exists ('../uploads/img/portadas/'.$nombre_archivo)){
                            unlink('../uploads/img/portadas/'.$nombre_archivo);
                        }
                        move_uploaded_file($tmp_foto, '../uploads/img/portadas/'.$nombre_archivo); 
                    }

                     // actualizar logo de la tienda
                     $actualizar_tienda = $pdo->prepare("UPDATE `Tiendas` SET `Portada` = :Portada 
                                                          WHERE `Tiendas`.`FK_Usuario` = :FK_Usuario;");
                    $actualizar_tienda->bindParam(':Portada', $nombre_archivo); 
                    $actualizar_tienda->bindParam(':FK_Usuario', $_SESSION['login_user']); 

                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    try{
                        $actualizar_tienda->execute();
                    }catch(PDOException $e){
                        echo $e->getMessage();
                    }                   
                }
               

                // actualizar cliente
                $actualizar_tienda = $pdo->prepare("UPDATE `Tiendas` SET `IDClientePaypal` = :IDClientePaypal, `Adomicilio` = :Adomicilio
                                                   WHERE `Tiendas`.`PK_Tienda` = :PK_Tienda AND `Tiendas`.`FK_Usuario` = :FK_Usuario;");
    
                $actualizar_tienda->bindParam(':IDClientePaypal', $id_cliente_paypal);
                $actualizar_tienda->bindParam(':Adomicilio', $adomicilio);
                $actualizar_tienda->bindParam(':PK_Tienda', $tienda[0]['PK_Tienda']);
                $actualizar_tienda->bindParam(':FK_Usuario', $tienda[0]['FK_Usuario']);

                

                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                try{
                    $actualizar_tienda->execute();
                    header('location: ../Home-Tienda?msj=perfilTiendaCompleto');
                }catch(PDOException $e){
                    echo $e->getMessage();
                }
               
    
            break;
        }
    
       
    }
?>