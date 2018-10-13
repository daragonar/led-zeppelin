<?php

use pelis\GeneroQuery;
require './vendor/autoload.php';
require './generated-conf/config.php';

$q=new GeneroQuery();

$generos = $q->find()->toJSON();

print_r($generos);