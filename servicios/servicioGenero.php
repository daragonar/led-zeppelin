<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use pelis\GeneroQuery;
use pelis\Genero;

require '../vendor/autoload.php';
require '../generated-conf/config.php';
$q = new GeneroQuery();
//$genero=new Genero();
//$genero->setNombre();
$app = new \Slim\App;

// Configuración de cabeceras
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
$method = $_SERVER['REQUEST_METHOD'];
if($method == "OPTIONS") {
    die();
}

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) use ($app) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");
    return $response;
});
//metodos de generos
//listar generos
$app->get('/generos', function () use ($app, $q) {
    $generos = $q->find()->toJSON();
    return $generos;
});
//recuperar los ultimos n
$app->get('/generos/{total}', function (Request $request, Response $response, array $args) use ($app, $q) {
    $total=$args['total'];
    $generos = $q->limit($total)->orderById('desc')->find()->toJSON();
    return $generos;
});
//ver un genero
$app->get('/genero/{id}', function (Request $request, Response $response, array $args) use ($app, $q) {
    $id = $args['id'];
    $genero = $q->findPK($id)->toJSON();
    return $genero;
});
//borrar un genero
$app->get('/generodel/{id}', function (Request $request, Response $response, array $args) use ($app, $q) {
    $id = $args['id'];
    $genero = $q->findPK($id);
    $genero->delete();
    return $genero->isDeleted();
});

//actualizar genero
$app->post('/generoup/{id}', function (Request $request, Response $response, array $args) use ($app, $q) {
    $id = $args['id'];
    $data=$request->getParsedBody();
    $genero = $q->findP($id);
    $genero->setNombre($data['genero']);
    $genero->save();
    return $genero;
});
//crear genero
$app->post('/generocre', function (Request $request, Response $response, array $args) use ($app, $q) {
    $data=$request->getParsedBody();
    $genero = new Genero();
    $genero->setNombre($data['genero']);
    $genero->save();
    return $genero;
});
$app->run();