<div class="jumbotron">
    <div class="container">
        <h1>Hello, world!</h1>
        <p>Contents ...</p>
        <p>
            <a class="btn btn-primary btn-lg">Learn more</a>
        </p>
    </div>
</div>


<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
<h3 class="text-center">Ultimos Generos</h3>
    <?php 

    $ultGen=$controGeneros->getGenerosUltimo(5);
//var_dump($ultGen);
    foreach ($ultGen->Generos as $gene) {
        echo "<p class='text-center'>$gene->Nombre</p>";
    }
    ?>
</div>


<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
<h3 class="text-center">Ultimas Peliculas</h3>
    <?php  ?>
</div>


<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
<h3 class="text-center">Ultimos Actores</h3>
    <?php  ?>
</div>