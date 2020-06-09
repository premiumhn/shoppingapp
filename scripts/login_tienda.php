<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include ("../global/config.php");
include ("../global/conexion.php");



if($_SERVER['REQUEST_METHOD'] == 'POST'){


     $buscar_usuario = $pdo->prepare("SELECT * FROM Usuarios WHERE NombreUsuario = :nombreUsuario AND (FK_TipoUsuario = 2 OR FK_TipoUsuario = 3)");
     $buscar_usuario->bindParam(':nombreUsuario', $_POST['input_username']);
    //  $buscar_usuario->bindParam(':Contrasena', openssl_encrypt($_POST['input_password'], COD, KEY));
     $buscar_usuario->execute();
     $usuario = $buscar_usuario->fetchAll(PDO::FETCH_ASSOC);
     $cuenta_usuario = $buscar_usuario->rowCount();

    if($cuenta_usuario > 0){
        $buscar_tienda = $pdo->prepare("SELECT * FROM Tiendas WHERE FK_Usuario = :FK_Usuario ");
        $buscar_tienda->bindParam(':FK_Usuario', $usuario[0]['PK_Usuario']);
        $buscar_tienda->execute();
        $tienda = $buscar_tienda->fetchAll(PDO::FETCH_ASSOC);
    }

     if(openssl_decrypt($usuario[0]['Contrasena'], COD, KEY) ==  $_POST['input_password']){
        
        session_start();
        $_SESSION['login_user'] = $usuario[0]['PK_Usuario']; 
        $_SESSION['PK_Tienda'] = $tienda[0]['PK_Tienda']; 
        
        header('location: ../index.php');
    }else{
       
        
        header('location: ../Login-Tienda');
        //echo "<script>alert('No existe el usuario')</script>";
    }
 
    // if($cuenta_usuario > 0){
        
    //     session_start();
    //     $_SESSION['login_user'] = $usuario[0]['PK_Usuario']; 
        
    //     //echo "<script>alert('".$_SESSION['login_user']."')</script>";
    //     header('location: ../index.php');
    // }else{
       
    //     header('location: ../Login');
    //     echo "<script>alert('No existe el usuario')</script>";
    // }
}

    
?>