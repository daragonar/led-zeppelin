<?php
// setup Autoload
require_once './vendor/autoload.php';

// setup Propel
require_once './generated-conf/config.php';

//include './controllador/controllerPelicula.php';
//include './controllador/controllerGeneros.php';

$controGeneros = new controllerGenero();

if (isset($_GET['page']) && !empty($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = "";
};
//include_once("./views/header.php");

$header = new header();
switch ($page) {
    case 'actores':
        include("./views/actores.php");
        break;
    case 'peliculas':
        include("./views/peliculas.php");
        break;
    case 'productores':
        include("./views/productores.php");
        break;
    case 'generos':
    case 'gen':
    case 'genup':
    case 'gennew':
    case 'gendel':
        include("./views/generos.php");
        break;
    default:
        include("./views/home.php");
        break;
}

//include_once("./views/footer.php");

$footer = new footer();