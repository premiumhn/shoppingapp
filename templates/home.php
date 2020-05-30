<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include '../global/config.php';
include '../global/conexion.php';
include '../global/const.php';

session_start();

require ('../scripts/comprobaciones.php');


$busqueda = (isset($_POST['busqueda'])) ? $_POST['busqueda'] : "";
$pk_categoria = (isset($_GET['pk_categoria'])) ? $_GET['pk_categoria'] : "";
if($pk_categoria != ""){
    $_SESSION['categoria'] = $pk_categoria;
 }else{
    unset($_SESSION['categoria']);
 }


$consulta_tipo_usuario = $pdo->prepare("SELECT * FROM Usuarios
										WHERE PK_Usuario = :PK_Usuario;");
$consulta_tipo_usuario->bindParam(':PK_Usuario', $_SESSION['login_user']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$consulta_tipo_usuario->execute();
$usuario = $consulta_tipo_usuario->fetchAll(PDO::FETCH_ASSOC);

if($usuario[0]['FK_TipoUsuario'] == 2){
	header('Location: Home-Tienda');
}else if($usuario[0]['FK_TipoUsuario'] == 3){
    header('Location: Admin');
}


 $id = (isset($_GET['Tienda'])) ? $_GET['Tienda'] : "";
 if($id != ""){
    $_SESSION['tienda'] = $id;
 }


 if(!isset($_SESSION['tienda'])){
    if(!isset($_SESSION['pais'])){
        header('Location: Inicio?msj=nopais');
    }else{
        header('Location: Tiendas?msj=notienda');
    }
}

 $str_busqueda = '';
if($busqueda!=''){
    $str_busqueda = " AND NombreProducto LIKE '%" . $busqueda . "%'";
}

// consultar productos
$select_productos = $pdo->prepare("SELECT * 
                                   FROM Productos 
                                   WHERE FK_Tienda=:FK_Tienda AND FK_Categoria = :FK_Categoria" . $str_busqueda); 


$select_productos->bindParam(':FK_Tienda', $_SESSION['tienda']);  
$select_productos->bindParam(':FK_Categoria', $_SESSION['categoria']);                         
if(isset($_SESSION['categoria'])){
    $select_productos->execute();
    $productos = $select_productos->fetchAll(PDO::FETCH_ASSOC);
}



// consultar categorias de la tienda
$select_categorias = $pdo->prepare("SELECT c.NombreCategoria, c.Imagen, c.PK_Categoria
                                   FROM Productos p INNER JOIN Categorias c 
                                   ON c.PK_Categoria = p.FK_Categoria 
                                   WHERE p.FK_Tienda = :FK_Tienda 
                                   GROUP BY c.NombreCategoria, c.Imagen, c.PK_Categoria"); 
$select_categorias->bindParam(':FK_Tienda', $_SESSION['tienda']);                        
$select_categorias->execute();
$categorias = $select_categorias->fetchAll(PDO::FETCH_ASSOC);

// consultar nombre tienda
$tiendas = $pdo->prepare("SELECT t.NombreTienda, p.PK_Pais, t.Portada, t.Logo, t.Telefono, t.Sitioweb
                            FROM Tiendas t INNER JOIN Ciudades c
                            ON c.PK_Ciudad = t.FK_Ciudad INNER JOIN Paises p
                            ON c.FK_Pais = p.PK_Pais
                            WHERE t.PK_Tienda=:FK_Tienda");
$tiendas->bindParam(':FK_Tienda', $_SESSION['tienda']);  
$tiendas->execute();
$tienda = $tiendas->fetchAll(PDO::FETCH_ASSOC);

// Ranking tienda
$ranking_tienda = $pdo->prepare("SELECT AVG(Ranking) as 'AVG' FROM Productos WHERE FK_Tienda = :FK_Tienda");
$ranking_tienda->bindParam(':FK_Tienda', $_SESSION['tienda']);  
$ranking_tienda->execute();
$avg_ranking_tienda = $ranking_tienda->fetchAll(PDO::FETCH_ASSOC);

// Ranking tienda
$unidades_vendidas_productos = $pdo->prepare("SELECT SUM(UnidadesVendidas) as 'UnidadesVendidas' FROM Productos WHERE FK_Tienda = :FK_Tienda");
$unidades_vendidas_productos->bindParam(':FK_Tienda', $_SESSION['tienda']);  
$unidades_vendidas_productos->execute();
$unidades_vendidas_tienda = $unidades_vendidas_productos->fetchAll(PDO::FETCH_ASSOC);

// categoria seleccionada
if(isset($_SESSION['categoria'])){
    $select_categoria = $pdo->prepare("SELECT *
                                   FROM Categorias 
                                   WHERE PK_Categoria = :PK_Categoria "); 
    $select_categoria->bindParam(':PK_Categoria', $_SESSION['categoria']);                        
    $select_categoria->execute();
    $categoria_seleccionada = $select_categoria->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" id="hl-viewport" content="width=device-width, initial-scale=1, user-scalable=yes, minimum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Shoppingapp</title>

    <!-- Imports -->
   
    <link href="<?php echo URL_SITIO ?>static/css/home.css" rel="stylesheet" type="text/css" media="all" />
    <link href="<?php echo URL_SITIO ?>static/css/styles.css" rel="stylesheet" type="text/css" media="all" />

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="https://kit.fontawesome.com/b2dbb6a24d.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="<?php echo URL_SITIO ?>static/js/jquery-3.5.0.min.js" ></script>
	<?php include 'iconos.php' ?>
 
</head>
<body>

<?php include '../templates/header.php'; ?>

<div class="col-md-12 cont_portada text-center">
    
    <img style="z-index:0;" class="portada" width="100%" src="<?php echo URL_SITIO.'uploads/img/portadas/'.$tienda[0]['Portada'] ?>" alt="">
    <div class="capa_oscura portada">
    </div>
    <div style="z-index:1;position:absolute;" class="text-center col-md-12">
        <div class="cont_logo">
            <img class="logo" src="<?php echo URL_SITIO.'uploads/img/logos/'.$tienda[0]['Logo'] ?>" alt="">
        </div>
        <h2 class="titulo_tienda" ><?php echo $tienda[0]['NombreTienda'] ?></h2>
        <div class="col-md-12 text-center">
            <div id="form" class="">
                        <?php if(count($avg_ranking_tienda)>0){ ?>
                            <p style="margin-left:-20px;" class="valoracion text-center">
                                <?php 
                                $cont = 1;
                                $ranking = $avg_ranking_tienda[0]['AVG'];
                                //echo $avg_ranking_tienda[0]['AVG'];

                                
                                for($i = 1; $i <= 5; $i++){ 
                                    if($cont <= $ranking){
                                    ?>
                                        <label for="radio1" class="orange">★</label>
                                    <?php 
                                    $cont+=1;
                                    }else{?>
                                        <label for="radio1" class="">★</label>
                                    <?php }
                                } ?>
                        </p>
                         <?php } ?>
                </div>
            </div>
            <div class=" col-md-12 info_tienda">
                <span><i class="  fa fa-receipt"></i> <?php echo $unidades_vendidas_tienda[0]['UnidadesVendidas'] ?> Artículos vendidos</span>
                <span><i class="  fa fa-envelope"></i> Contacto</span>
                <a href="<?php echo (isset($tienda[0]['Sitioweb'])?'http://'.$tienda[0]['Sitioweb']:"#") ?>"><span><i class="  fa fa-globe"></i> Sitio web</span></a> 
                <span><i class="  fa fa-truck"></i> Servicio a domicilio</span>
                <span><i class="  fa fa-phone-alt"></i> <?php echo $tienda[0]['Telefono'] ?></span>
            </div>

            <div class="row col-md-12 info_tienda_movil">
                <span><i class="row fa fa-receipt"></i> <?php echo $unidades_vendidas_tienda[0]['UnidadesVendidas'] ?> Artículos vendidos</span>
                <span><i class="row fa fa-envelope"></i> Contacto</span>
                <span><i class="row fa fa-globe"></i> Sitio web</span>
                <span><i class="row fa fa-truck"></i> Servicio a domicilio</span>
                <span><i class="row fa fa-phone-alt"></i> <?php echo $tienda[0]['Telefono'] ?></span>
            </div>

    </div>
   
</div>

<br>
<div class="col-md-12 text-center titulo">
    
<?php if(isset($_SESSION['categoria'])){ ?>
    <h2>Artículos de <?php echo $categoria_seleccionada[0]['NombreCategoria'] ?></h2>
<?php }else{?>
    <h2><?php echo $tcategorias ?></h2>
<?php } ?> 

</div>	
<!-- Si no hay productos en la tienda -->
<?php  if(count($categorias)==0 and $busqueda == ''){?>
        <div class="text-center col-md-10 offset-md-1 card-msj">
            <img class="col-md-8 " width="100%" src="<?php echo URL_SITIO?>static/img/no_productos.png" alt="">
            <br>
            <br>
            <br>
            <p>Lo sentimos, pero la tienda que seleccionaste aún no ha registrado productos, regresa al menú anterior y sigue explorando otras tiendas.</p>
            <br>
            <a class="btn-flat" href="<?php echo URL_SITIO?>Tiendas?idPais=<?php echo $tienda[0]['PK_Pais']  ?>">Otras tiendas</a>        
        </div>
<?php }?>

<!-- si la busqueda no dió resultados -->
<?php if(isset($_SESSION['categoria'])){ ?>
    <?php if(count($productos)==0 and (isset($_POST['busqueda'])) ? $_POST['busqueda'] : "") {?>
            <div class="text-center col-md-10 offset-md-1 card-msj">
                <img class="col-md-8 " width="100%" src="<?php echo URL_SITIO?>static/img/no_productos.png" alt="">
                <br>
                <br>
                <br>
                <p>Lo sentimos, pero no encontramos productos de "<strong><?php echo $_POST['busqueda'] ?></strong>".</p>
                <br>
                <a class="btn-flat" href="<?php echo URL_SITIO?>Home">Seguir explorando</a>        
            </div>
    <?php }?>
<?php }?>

<?php if(isset($_SESSION['categoria'])){ ?>
    <div class="col-md-12">
        <form action="<?php echo URL_SITIO ?>Home" method="get">
            <input type="hidden" value="" name="pk_categoria">
            <button class="btn btn-flat"><i class="fa fa-arrow-left"></i> Categorías</button>
        </form>
    </div>
<?php } ?>


<div style="padding:50px 20px 50px 20px;" class="text-center ">


<div class="cont_categorias container row col-md-12 ">
<?php if(isset($_SESSION['categoria'])){ ?>
    <?php foreach($productos as $producto){ ?>
        <div class="col-md-3">
            <form id="product_<?php echo $producto['PK_Producto'] ?>" method="get" action="Detalle-Producto">
            <input type="hidden" value="<?php echo $producto['PK_Producto'] ?>" name="producto">
            <figure onclick="verDetalle(<?php echo $producto['PK_Producto'] ?>)" class="text-left card card-product">
                <div class="img-wrap">
                    <img class="img_producto" src="<?php echo URL_SITIO.$producto['Imagen'] ?>">
                </div>
                <figcaption class="info-wrap">
                        <h4 class="title"><?php echo $producto['NombreProducto'] ?></h4>
                        
                        <div id="form" class="">
                                <p class="valoracion">
                                    <?php 
                                    $cont = 1;
                                    $ranking = $producto['Ranking'];

                                    
                                    for($i = 1; $i <= 5; $i++){ 
                                        if($cont <= $ranking){
                                        ?>
                                            <label for="radio1" class="orange">★</label>
                                        <?php 
                                        $cont+=1;
                                        }else{?>
                                            <label for="radio1" class="">★</label>
                                        <?php }
                                        
                                    } ?>


                                </p>
                            </div>
                        <div class="rating-wrap">
                            <div style="font-size:13px;" class="label-rating"><?php echo $producto['UnidadesDisponibles'] ?> Unidades disponibles</div>
                            <div style="font-size:13px;" class="label-rating"><?php echo $producto['UnidadesVendidas'] ?>  Unidades compradas </div>
                        </div> <!-- rating-wrap.// -->
                </figcaption>
                <div class="bottom-wrap">
                    <!-- <a href="detalle_producto.php?producto=<?php echo $producto['PK_Producto'] ?>" class="btn btn-sm btn-primary float-right">Ordenar</a>	 -->
                    <div class="price-wrap h5">
                        <span class="price-new">$ <?php echo $producto['PrecioUnitario'] ?></span> <del class="price-old">$19</del>
                    </div> <!-- price-wrap.// -->
                </div> <!-- bottom-wrap.// -->
            </figure>
            </form>

        </div> <!-- col // -->
    <?php } ?>
<?php } ?>

<?php if(!isset($_SESSION['categoria'])){ ?>
    <?php foreach($categorias as $categoria){ ?>
        <div class="col-md-3">
            <form class="form_categoria" id="categoria_<?php echo $categoria['PK_Categoria'] ?>" method="get" action="<?php echo URL_SITIO ?>Home">
            <input type="hidden" value="<?php echo $categoria['PK_Categoria'] ?>" name="pk_categoria">
            <figure class="card card-product">
                
                    <button style="background-image: url(<?php echo URL_SITIO.'uploads/img/categorias/'.$categoria['Imagen'] ?>)" class=" btn-categoria ">
                        <?php echo $categoria['NombreCategoria'] ?>
                    </button>

            </figure>
            </form>

        </div> <!-- col // -->
    <?php } ?>
<?php } ?>





</div> <!-- row.// -->
</div>
<!-- FIN DIV Temporal -->


<?php include '../templates/footer.php'; ?>

</body>
</html>

<script type="text/javascript">
	function verDetalle(pk_producto){
		console.log('#product_'+pk_producto);
		$('#product_'+pk_producto).submit();
	}

    $('#btn-buscar-producto').click(function(e){
        e.preventDefault()
        $('#search-form').attr("action", "<?php URL_SITIO ?>Home");
        $('#search-form').submit();
    })

    $('.img_categoria').hover(function(e){
        $(this).addClass('transition');
    }, function() {
        $(this).removeClass('transition');
    });

   
   

   


</script>