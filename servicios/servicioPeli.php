<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use pelis\PeliculaQuery;
use pelis\Pelicula;

require '../vendor/autoload.php';
require '../generated-conf/config.php';

$app = new \Slim\App;
$app->get('/hello/{name}', function ()use ($app) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");
    return $response;
});
//metodos de peliculas

$app->get('/pelis', function() use ($app){
$q=new PeliculaQuery();
$pelis= $q->find();

return $pelis;
});

$app->get('/peli/{id}', function() use ($app){
    $q=new PeliculaQuery();
    $pelis= $q->find($args['id']);
    
    return $pelis;
    });
    
$app->post('/peli', function() use($app){
    $peli=new Pelicula();
    print_r($args);
    //$peli->setTitulo()
});

$app->run();