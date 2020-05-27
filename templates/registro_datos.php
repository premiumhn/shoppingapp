<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include '../global/config.php';
include '../global/conexion.php';
include '../global/const.php';

session_start();
require 'language/requirelanguage.php';

require ('../scripts/comprobaciones.php');
// tipo de registro
$form = (isset($_REQUEST['menu']))? $_REQUEST['menu'] : "";
$buscar_usuario = $pdo->prepare("SELECT * FROM Usuarios
                                WHERE PK_Usuario = :PK_Usuario");
$buscar_usuario->bindParam('PK_Usuario', $_SESSION['login_user']);
$buscar_usuario->execute();
$usuario = $buscar_usuario->fetchAll(PDO::FETCH_ASSOC);  
?> 
<?php if($usuario[0]['FK_TipoUsuario'] == 3){ 
        include ("../templates/header_admin.php"); 
    }else{
        include ("../templates/header.php");
}?>  


<div class="row" style="width:100%;margin:0px;">
    
    
    <?php 
        switch($form){
            case 'registro_categoria':
                require ('./registro_categoria.php');
            break;
            case 'ver_categorias':
                require ('./ver_categorias.php');
            break;
            case 'perfil_usuario':
                require ('./perfil_usuario.php');
            break;
            case 'perfil_tienda':
                require ('./perfil_tienda.php');
            break;
            case 'registro_regionesEnvio':
                require ('./regiones_envio.php');
            break;
            case 'ver_regionesEnvio':
                require ('./ver_regionesEnvio.php');
            break;
        }
    ?>
   
    
</div>
    
    
    <?php if($usuario[0]['FK_TipoUsuario'] == 3){ 
        include ("../templates/footer_admin.php"); 
    }else{
        include ("../templates/footer.php");
    }?>
</body>
</html>

<script type="text/javascript">
 $('#search-form').hide();
</script>

