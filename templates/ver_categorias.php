<?php
require ('../scripts/comprobaciones.php'); 

$pagina = false;
$items_por_pagina = 2;

if (isset($_GET["pagina"])) {
    $pagina = $_GET["pagina"];
}

if (!$pagina) {
    $inicio = 0;
    $pagina = 1;
} else {
    $inicio = ($pagina - 1) * $items_por_pagina;
}


//Consulta seleccionar categorías
$select_categorias_total = $pdo->prepare("SELECT * FROM Categorias");
$select_categorias_total->execute();
$listaCategorias_total = $select_categorias_total->fetchAll(PDO::FETCH_ASSOC);

//Consulta seleccionar categorías
$select_categorias = $pdo->prepare("SELECT * FROM Categorias ORDER BY PK_Categoria DESC LIMIT ". $inicio .", " . $items_por_pagina);
$select_categorias->execute();
$listaCategorias = $select_categorias->fetchAll(PDO::FETCH_ASSOC);

//calculo el total de paginas
$total_pages = ceil(count($listaCategorias_total) / $items_por_pagina);

?>

<div  role="alert" data-delay="5000" aria-live="assertive" aria-atomic="true" id="toast_mensaje" class="toast" data-autohide="true">
        <div class="toast-body">
        </div>
</div> 
<div class="col-md-2 ">
            <div class="card card-left">
                <ul class="list-group list-group-flush">
                <li class="list-group-item">
                            <a href="Registro-Datos?menu=ver_categorias" type="submit" class="col-md-12 btn btn-primary">Ver todas</a>
                    </li>
                    <li class="list-group-item">
                            <a href="Registro-Datos?menu=registro_categoria"  type="submit" class="col-md-12 btn btn-primary">Nueva</a>
                    </li>
                </ul>
            </div>
        </div>
<div style="height:100%;margin-bottom:60px;" class="col-md-10 ">
<div id="mensaje-success" class="alert alert-success" role="alert"></div>
<div id="mensaje-error" class="alert alert-danger" role="alert"></div>
    <div class="card">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Imagen</th>
                    <th scope="col">Categoría</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Estado</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($listaCategorias as $categoria){ ?>
                <tr WIDTH="100%">
                    <td  WIDTH="30%" ><div class="cont_imagen"><img id="imagen_<?php echo $categoria['PK_Categoria']?>" class="col-md-12 imagen" src="<?php echo URL_SITIO ?>uploads/img/categorias/<?php echo $categoria['Imagen'] ?>" alt=""></div ></td>
                    <td id="nombreCategoria_<?php echo $categoria['PK_Categoria']?>" WIDTH="20%"><?php echo $categoria['NombreCategoria'] ?></td>
                    <td id="descripcion_<?php echo $categoria['PK_Categoria']?>"  WIDTH="40%"><?php echo $categoria['Descripcion'] ?></td>
                    <td id="estado_<?php echo $categoria['PK_Categoria']?>" WIDTH="10%"><?php echo ($categoria['Estado']==1)?'<label class="switch">
                                                                                                                                        <input onClick="cambiarEstadoCategoria('. $categoria["PK_Categoria"] .')" class="check" type="checkbox" checked>
                                                                                                                                        <span class="slider round"></span>
                                                                                                                                    </label>':
                                                                                                                                    '<label class="switch">
                                                                                                                                        <input onClick="cambiarEstadoCategoria('. $categoria["PK_Categoria"] .')" class="check" type="checkbox">
                                                                                                                                        <span class="slider round"></span>
                                                                                                                                    </label>'; ?></td>
                    <td><button onClick="editar(<?php echo $categoria['PK_Categoria'] ?>)" type="button" class="btn btn-edit" data-toggle="modal" data-target=".modal-editar"><i class="fas fa-edit mr-2"></i></button></td>
                    <td><button onClick="eliminar(<?php echo $categoria['PK_Categoria'] ?>)" type="button" class="btn btn-eliminar" data-toggle="modal" data-target=".modal-eliminar"><i class="fas fa-trash-alt mr-2"></i></button></td>
                </tr>
                <?php }?>
            </tbody>
        </table>

    </div>
    </div>
    <br>
    <?php 

echo '<nav class="col-md-12">';
echo '<ul class="pagination" >';

if ($total_pages > 1) {
    if ($pagina != 1) {
        echo '<li class="page-item"><a class="page-link" href="Registro-Datos?menu=ver_categorias&pagina='.($pagina-1).'"><span aria-hidden="true">&laquo;</span></a></li>';
    }

    for ($i=1;$i<=$total_pages;$i++) {
        if ($pagina == $i) {
            echo '<li class="page-item active"><a class="page-link" href="#">'.$pagina.'</a></li>';
        } else {
            echo '<li class="page-item"><a class="page-link" href="Registro-Datos?menu=ver_categorias&pagina='.$i.'">'.$i.'</a></li>';
        }
    }

    if ($pagina != $total_pages) {
        echo '<li class="page-item"><a class="page-link" href="Registro-Datos?menu=ver_categorias&pagina='.($pagina+1).'"><span aria-hidden="true">&raquo;</span></a></li>';
    }
}
echo '</ul>';
echo '</nav>';

?>
</div>

<!-- modal para editar -->
<div class="modal fade modal-editar" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class=" md-content col-md-12">
                <br>
                <label class="text-center col-md-12" for=""><strong>Editar categoría</strong></label>
          
            <div style="height:100%;margin-bottom:60px;" class="col-md-12 offset-md-0 bordered">
                <div class="card">
                <div class="card-body">
                    <form id="form-edit" action="<?php echo URL_SITIO ?>scripts/registro_datos.php" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="inputAddress">Nombre de la categoría</label>
                            <input type="text" class="form-control" name="input_nombreCategoria" id="inputCategoryName" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="inputAddress2">Descripción</label>
                            <input type="text" class="form-control" name="input_descripcion" id="inputDescripcion" placeholder="">
                        </div>
                        <br>
                        <label for="inputAddress2">Imagen</label>
                        <div class="col-md-6 offset-md-3">
                            <div class="col-md-12" id="cont_imagen">
                                <img id="showImagen" style="width:100%" src="" alt="">
                            </div>
                        </div>
                        <br>
                        <div class="custom-file">
                            <input type="file" accept="image/*" class="custom-file-input" id="inputImagen" name="input_imagen">
                            <label class="custom-file-label" for="customFile">Cambiar imagen</label>
                        </div>
                        <br>
                        <br>
                        <fieldset class="form-group">
                        <label for="inputAddress2">Estado</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="input_estado" id="inputRadioActivo" value="1">
                            <label class="form-check-label" for="inputRadioActivo">
                                Activa
                            </label>
                            </div>
                            <div class="form-check">
                            <input class="form-check-input" type="radio" name="input_estado" id="inputRadioInactivo" value="0">
                            <label class="form-check-label" for="inputRadioInactivo">
                                Inactiva
                            </label>
                            </div>
                        </fieldset>
                        <br>
                        <input type="hidden" id="PK_Categoria" name="pk_categoria">
                        <input type="hidden" value="editar_categoria" name="action">
                        <input type="hidden" value="editar_categoria" name="menu">

                        <div class="text-center col-md-12">
                        <button id="btnEditar" type="submit" data-dismiss="modal" class=" btn-modal btn btn-primary col-md-5">Editar</button>&nbsp&nbsp&nbsp
                        <button id="btnCancelar" type="" data-dismiss="modal" class=" btn-modal btn btn-secondary col-md-5">Cancelar</button>
                        </div>
                        
                    </form>

                </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

<!-- modal para eliminar -->
<div class="modal fade modal-eliminar" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class=" md-content col-md-12">
                <br>
                <label class="text-center col-md-12" for=""><strong>Eliminar categoría</strong></label>
          
            <div style="height:100%;margin-bottom:60px;" class="col-md-12 offset-md-0 bordered">
                <div class="card">
                <div class="card-body">
                    <form id="form-eliminar" action="<?php echo URL_SITIO?>scripts/registro_datos.php" method="post" enctype="multipart/form-data">
                      
                        <label for="">¿Seguro que desea eliminar la categoria "<span id="nombreCategoriaEl"></span>"?</label>

                        <br>
                        <br>
                        <br>
                        <br>

                        <input type="hidden" id="PK_CategoriaEl" name="pk_categoria">
                        <input type="hidden" value="eliminar_categoria" name="action">
                        <input type="hidden" value="eliminar_categoria" name="menu">

                        <div class="text-center col-md-12">
                        <button id="btnEliminar" type="submit" data-dismiss="modal" class=" btn-modal btn btn-danger col-md-5">Eliminar</button>&nbsp&nbsp&nbsp
                        <button id="btnCancelar" type="" data-dismiss="modal" class=" btn-modal btn btn-secondary col-md-5">Cancelar</button>
                        </div>
                        
                    </form>

                </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>


<script type="text/javascript">

    $('#mensaje-success').hide();
    $('#mensaje-error').hide();

    <?php 
        $msj = (isset($_GET['msj']))?$_GET['msj']:"";
        if( $msj == 'editada'){ 
    ?>
        $('#mensaje-success').html('<i class="fa fa-check"></i>Categoría actualizada exitosamente');
        $('#mensaje-success').show();
    <?php } elseif( $msj == 'eliminada'){ ?>
        $('#mensaje-success').html('<i class="fa fa-check"></i>Categoría eliminada.');
        $('#mensaje-success').show();
    <?php } elseif( $msj == 'muypesada'){ ?>
        $('#mensaje-error').html('Imagen demasiado pesada. La imagen debe pesar menos de 3 MB.');
        $('#mensaje-error').show();
    <?php } elseif( $msj == 'error_1'){ ?>
        $('#mensaje-error').html('No se puede eliminar la catagoría porque ya tiene productos asociados.');
        $('#mensaje-error').show();
    <?php } ?>

    $('#btnEditar').click(function(e){
        e.preventDefault();
        
        
        
        // consultar existencia de correo
        var existe_categoria;
            $.ajax({
                    type:"POST",
                    async: false,
                    url:"<?php echo URL_SITIO?>scripts/datos_ajax.php",
                    data: {"request" : "verificarCategoria", 
                            "nombreCategoria" : $('#inputCategoryName').val()},
                    success:function(r){
                        existe_categoria = r;
                    }
        });

       
        if($('#inputCategoryName').val() == "" || $('#inputDescripcion').val() == "" ){
            toast('Faltan uno o más campos');
        }else if(existe_categoria == 1 && $('#inputCategoryName').val() != nombre_actual){
            toast('Ya existe una categoría con ese nombre');
        }else{    
            $('#form-edit').submit();
        }

    });

    $('#inputImagen').bind('change', function() {
            var peso = this.files[0].size/1024/1024;
            if(peso > 5){
                toast('Imagen demasiado pesada, debe pesar menos de 5 MB');
                this.val("");
            };
    });

     
    $('#btnEliminar').click(function(e){
        e.preventDefault();
        
        $('#form-eliminar').submit();
    })

    function toast(msj){
            $('.toast-body').html(msj);
            $('#toast_mensaje').toast('show');
    }

    var nombre_actual;
     function editar(pk_categoria){
         var src_img = $('#imagen_' + pk_categoria).attr('src');
         $('#inputCategoryName').val($('#nombreCategoria_'+pk_categoria)[0].innerText);
         $('#inputDescripcion').val($('#descripcion_'+pk_categoria)[0].innerText);
         $('#showImagen').attr('src', src_img);
         $('#PK_Categoria').val(pk_categoria);

        if($('#estado_'+pk_categoria)[0].innerText == 1){
            $("#inputRadioActivo").attr("checked", "checked");
        }else{
            $("#inputRadioInactivo").attr("checked", "checked");
        }
         nombre_actual = $('#nombreCategoria_'+pk_categoria)[0].innerText;
     }

     function eliminar(pk_categoria){
         $('#nombreCategoriaEl').html($('#nombreCategoria_'+pk_categoria)[0].innerText);
         $('#PK_CategoriaEl').val(pk_categoria);

     }

     function vistaPrevia(input){
        if(input.files && input.files[0]){
            var reader = new FileReader();
            
           
            reader.onload = function(e){
                $('#cont_imagen').html("<img style='width:100%' id='showImagen' src='"+ e.target.result +"' >");
                console.log(e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    function cambiarEstadoCategoria(pk_categoria){
         // activar o desactivar usuario
         var response;
            $.ajax({
                    type:"POST",
                    async: false,
                    url:"<?php echo URL_SITIO?>scripts/datos_ajax.php",
                    data: {"request" : "cambiarEstadoCategoria", 
                           "PK_Categoria" : pk_categoria},
                    success:function(r){
                        console.log(r);
                    }
            });
    };

    $('#inputImagen').change(function(){
        vistaPrevia(this);
    });

</script>
