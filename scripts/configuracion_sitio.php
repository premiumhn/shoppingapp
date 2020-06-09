<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include ("../global/config.php");
include ("../global/conexion.php");
include ("../global/const.php");


    
        $id_cliente_paypal = (isset($_REQUEST['input_idClientePaypal'])) ? $_REQUEST['input_idClientePaypal'] : "";
        $fondo_tienda = (isset($_FILES['input_fondoTienda'])) ? $_FILES['input_fondoTienda'] : "";
        $fondo_cliente = (isset($_FILES['input_fondoCliente'])) ? $_FILES['input_fondoCliente'] : "";
        $comision = (isset($_REQUEST['input_comision'])) ? $_REQUEST['input_comision'] : "";
        $por_cada = (isset($_REQUEST['input_porcada'])) ? $_REQUEST['input_porcada'] : "";

        // boton
        $action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : "";

        switch($action){
            case "editar":

                $editar_configuracion = $pdo->prepare("UPDATE Configuracion
                                                       SET IDClientePaypal = :IDClientePaypal,
                                                       Comision = :Comision,
                                                       PorCada = :PorCada");
                $editar_configuracion->bindParam(':IDClientePaypal', $id_cliente_paypal);
                $editar_configuracion->bindParam(':Comision', $comision);
                $editar_configuracion->bindParam(':PorCada', $por_cada);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $editar_configuracion->execute();

                //subir imagen de fondo del login de tienda
                
                $tmp_foto = $_FILES['input_fondoTienda']['tmp_name'];
                if($tmp_foto != ""){
                    $nombre_archivo = ($fondo_tienda!="")?"fondo-login-tienda.jpg":"";
                    if(file_exists ('../uploads/img/configuracion/'.$nombre_archivo)){
                        unlink('../uploads/img/configuracion/'.$nombre_archivo);
                    }
                    move_uploaded_file($tmp_foto, '../uploads/img/configuracion/'.$nombre_archivo);
                    $editar_fondo_tienda = $pdo->prepare("UPDATE Configuracion
                                                       SET FondoLoginTienda = :FondoLoginTienda");
                    $editar_fondo_tienda->bindParam(':FondoLoginTienda', $nombre_archivo);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $editar_fondo_tienda->execute();
                }

                //subir imagen de fondo del login de cliente
                $tmp_fotoc = $_FILES['input_fondoCliente']['tmp_name'];
                if($tmp_fotoc != ""){
                    $nombre_archivoc = ($fondo_cliente!="")?"fondo-login-cliente.jpg":"";
                    if(file_exists ('../uploads/img/configuracion/'.$nombre_archivoc)){
                        unlink('../uploads/img/configuracion/'.$nombre_archivoc);
                    }
                    move_uploaded_file($tmp_fotoc, '../uploads/img/configuracion/'.$nombre_archivoc);
                    $editar_fondo_cliente = $pdo->prepare("UPDATE Configuracion
                                                       SET FondoLoginCliente = :FondoLoginCliente");
                    $editar_fondo_cliente->bindParam(':FondoLoginCliente', $nombre_archivoc);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $editar_fondo_cliente->execute();
                }



               
                header('location: ../Configuracion-Sitio?msj=editado');



            break;
        }
    
       
 
?>