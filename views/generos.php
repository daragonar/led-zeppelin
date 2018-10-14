
<div class="container">
<?php
if ($page == "gendel"||$page == "generos") {
    if (isset($_GET['id'])) {
        $controGeneros->deleteGenero($_GET['id']);
    }
   
        $generos = $controGeneros->getGeneros();

        ?>
<h2 class="text-center">Todos los generos</h2>

<table class="table table-hover">
    <thead>
        <tr>
            <th>Generos</th>
            <th>Eliminar</th>
            <th>Modificar</th>
        </tr>
    </thead>
        <tbody>
    <?php foreach ($generos->Generos as $genero) {
        ?>
        <tr>
            <td><a href="?page=gen&id=<?php echo $genero->Id; ?>"><?php echo $genero->Nombre; ?></a></td>
            <td><a href="?page=gendel&id=<?php echo $genero->Id; ?>">Eliminar</a></td>
            <td><a href="?page=genup&id=<?php echo $genero->Id; ?>">Editar</a></td>
        </tr>
        <?php 
    } ?>
    </tbody>
</table>


<a type="button" class="btn btn-large btn-block btn-default" href="?page=gennew">button</a>


<?php
}?>

<?php 
if ($page == "gen") {
    $genero = $controGeneros->getGenero($_GET['id']);
    echo "<h3>$genero->Nombre</h3>";
    ?>
<?php

}
?>


<?php 

if ($page == "genup" || $page == "gennew") {

    if ($page == "genup") {
        if (isset($_POST['gen'])) {
           $t= $controGeneros->updateGenero($_GET['id'], $_POST);
        }
    } elseif ($page == "gennew") {
        if (isset($_POST['gen'])) {
            $controGeneros->nuevoGenero($_POST);
        }
    }

    if (isset($_GET['id'])) {
        $genero = $controGeneros->getGenero($_GET['id']);
    }

    ?>
    
    
    <form action="?page=<?php if (isset($genero)) {
                            echo "genup&id=" . $genero->Id;
                        } else {
                            echo "gennew";
                        }
                        ?>" method="POST" class="form-horizontal" role="form">
            <div class="form-group">
                <legend>Título</legend>
            </div>
            <?php
            if (isset($t)) {
                    if ($t['http_code']==200 ) {
                        echo '<h4 class="bg-success text-center">Actualizado correctamente</h4>';
                    }elseif($t['http_code']!=200){
                        echo '<h4 class="bg-danger text-center">Error al actualizar</h4>';    
                    }
                }
            ?>
          
            <div class="form-group">
                <label for="input" class="col-sm-2 control-label">Pelicula:</label>
                <div class="col-sm-10">
                    <input type="text" name="genero" id="input" class="form-control" value="<?php if (isset($genero)) {
                                                                                                echo $genero->Nombre;
                                                                                            } ?>" >
                </div>
                
            </div>
            
    
            <div class="form-group">
                <div class="col-sm-10 col-sm-offset-2">
                    <button type="submit" name="gen" class="btn btn-primary">Enviar</button>
                </div>
            </div>
    </form>
    
    
<?php

}
?>
</div>
