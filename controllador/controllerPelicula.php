<?php

class controllerPelicula 
{
    function getPeliculas()
    {
        $service="http://localhost/pelis/servicios/servicioPeli.php/pelis";
        $curl =curl_init($service);
        $response=curl_exec($curl);
        curl_close($curl);

        return $_response;
    }
    
}
