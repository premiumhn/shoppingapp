<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include ("../global/config.php");
include ("../global/conexion.php");

session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    
  $action = (isset($_POST['action']))?$_POST['action'] : "";

  switch($action){
      case 'agregar_carrito':
        // echo json_encode($_POST);

        $cantidad = (isset($_POST['input_cantidad'])) ? $_POST['input_cantidad'] : "";
        $pk_producto = (isset($_POST['PK_Producto'])) ? $_POST['PK_Producto'] : "";
        $talla = (isset($_POST['input_talla'])) ? $_POST['input_talla'] : "";
        $color = (isset($_POST['input_color'])) ? $_POST['input_color'] : "";
        $fecha = new Datetime();
        $fecha->format('Y-m-d\TH:i:s.u');
        $fecha_hora_agregado = date('Y-m-d H:i:s');
        $adomicilio = (isset($_POST['input_adomicilio'])) ? $_POST['input_adomicilio'] : "";
        $destinatario = (isset($_POST['input_destinatario'])) ? $_POST['input_destinatario'] : "";

        // cliente
        $buscar_cliente = $pdo->prepare("SELECT * FROM Clientes
                                         WHERE FK_Usuario = :FK_Usuario");
        $buscar_cliente->bindParam(':FK_Usuario', $_SESSION['login_user']);

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $buscar_cliente->execute(); 
        $cliente = $buscar_cliente->fetchAll(PDO::FETCH_ASSOC);


        $insert_carrito = $pdo->prepare("INSERT INTO `Carrito` (`PK_Carrito`, `Cantidad`, `FK_Producto`, `FK_Talla`, `FK_Color`, `FechaHoraAgregado`, `FK_Cliente`, `FK_TipoPedido`, `FK_Destinatario`) 
                                        VALUES (NULL, :Cantidad, :FK_Producto, :FK_Talla, :FK_Color, :FechaHoraAgregado, :FK_Cliente, :FK_TipoPedido, :FK_Destinatario);");

        $insert_carrito->bindParam(':Cantidad', $cantidad);
        $insert_carrito->bindParam(':FK_Producto', $pk_producto);

        $talla_null = NULL;
        (isset($_POST['input_talla'])) ? $insert_carrito->bindParam(':FK_Talla', $talla) : $insert_carrito->bindParam(':FK_Talla', $talla_null);
       

        $color_null = NULL;
        (isset($_POST['input_color'])) ? $insert_carrito->bindParam(':FK_Color', $color) : $insert_carrito->bindParam(':FK_Color', $color_null);
  


        $insert_carrito->bindParam(':FechaHoraAgregado', $fecha_hora_agregado);
        $insert_carrito->bindParam(':FK_Cliente', $cliente[0]['PK_Cliente']);
        $insert_carrito->bindParam(':FK_TipoPedido', $adomicilio);

        $destinatario_null = NULL;
        (isset($_POST['input_destinatario'])) ? $insert_carrito->bindParam(':FK_Destinatario', $destinatario) : $insert_carrito->bindParam(':FK_Destinatario', $destinatario_null);
        

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $insert_carrito->execute(); 

        header('location: ../Carrito');
      break;

      case 'comprar':

      

      $cantidad = (isset($_POST['input_cantidad'])) ? $_POST['input_cantidad'] : "";
      $pk_producto = (isset($_POST['PK_Producto'])) ? $_POST['PK_Producto'] : "";
      $talla = (isset($_POST['input_talla'])) ? $_POST['input_talla'] : "";
      $color = (isset($_POST['input_color'])) ? $_POST['input_color'] : "";
      $fecha = new Datetime();
      $fecha->format('Y-m-d\TH:i:s.u');
      $fecha_hora_agregado = date('Y-m-d H:i:s');
      $adomicilio = (isset($_POST['input_adomicilio'])) ? $_POST['input_adomicilio'] : "";
      $destinatario = (isset($_POST['input_destinatario'])) ? $_POST['input_destinatario'] : "";

      // cliente
      $buscar_cliente = $pdo->prepare("SELECT * FROM Clientes
                                       WHERE FK_Usuario = :FK_Usuario");
      $buscar_cliente->bindParam(':FK_Usuario', $_SESSION['login_user']);

      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $buscar_cliente->execute(); 
      $cliente = $buscar_cliente->fetchAll(PDO::FETCH_ASSOC);


      $pk_pago = $cliente[0]['PK_Cliente'] . "_" . date('Y-m-d_H:i:s') . "_" .$pk_producto;

      $insert_pago_solouno = $pdo->prepare("INSERT INTO `Pago_solouno_temp` (`PK_Pago`, `Cantidad`, `FK_Producto`, `FK_Talla`, `FK_Color`, `FechaHoraAgregado`, `FK_Cliente`, `FK_TipoPedido`, `FK_Destinatario`) 
                                      VALUES (:PK_Pago, :Cantidad, :FK_Producto, :FK_Talla, :FK_Color, :FechaHoraAgregado, :FK_Cliente, :FK_TipoPedido, :FK_Destinatario);");

      $insert_pago_solouno->bindParam(':PK_Pago', $pk_pago);
      $insert_pago_solouno->bindParam(':Cantidad', $cantidad);
      $insert_pago_solouno->bindParam(':FK_Producto', $pk_producto);

      $talla_null = NULL;
      (isset($_POST['input_talla'])) ? $insert_pago_solouno->bindParam(':FK_Talla', $talla) : $insert_pago_solouno->bindParam(':FK_Talla', $talla_null);
     

      $color_null = NULL;
      (isset($_POST['input_color'])) ? $insert_pago_solouno->bindParam(':FK_Color', $color) : $insert_pago_solouno->bindParam(':FK_Color', $color_null);



      $insert_pago_solouno->bindParam(':FechaHoraAgregado', $fecha_hora_agregado);
      $insert_pago_solouno->bindParam(':FK_Cliente', $cliente[0]['PK_Cliente']);
      $insert_pago_solouno->bindParam(':FK_TipoPedido', $adomicilio);

      $destinatario_null = NULL;
      (isset($_POST['input_destinatario'])) ? $insert_pago_solouno->bindParam(':FK_Destinatario', $destinatario) : $insert_pago_solouno->bindParam(':FK_Destinatario', $destinatario_null);
      

      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $insert_pago_solouno->execute(); 

      header('location: ../Pago-SoloUno?p=' . $pk_pago);
        
      break;
  }
     
}

    
?>