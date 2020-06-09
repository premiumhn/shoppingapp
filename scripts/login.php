<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include ("../global/config.php");
include ("../global/conexion.php");



if($_SERVER['REQUEST_METHOD'] == 'POST'){

  
     // comprobar que el usuario no existe
     $buscar_usuario = $pdo->prepare("SELECT * FROM Usuarios WHERE NombreUsuario = :nombreUsuario AND (FK_TipoUsuario = 1 OR FK_TipoUsuario = 3)");
     $buscar_usuario->bindParam(':nombreUsuario', $_POST['input_username']);
    //  $buscar_usuario->bindParam(':Contrasena', openssl_encrypt($_POST['input_password'], COD, KEY));
     $buscar_usuario->execute();
     $usuario = $buscar_usuario->fetchAll(PDO::FETCH_ASSOC);
     $cuenta_usuario = $buscar_usuario->rowCount();

     if(openssl_decrypt($usuario[0]['Contrasena'], COD, KEY) ==  $_POST['input_password']){
        
        session_start();
        $_SESSION['login_user'] = $usuario[0]['PK_Usuario']; 
        
        //echo "<script>alert('".$_SESSION['login_user']."')</script>";
        header('location: ../index.php');
    }else{
       
        
        header('location: ../Login');
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