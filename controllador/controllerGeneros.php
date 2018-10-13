<?php

class controllerGenero
{
    private $cadcon = "http://localhost/pelis/servicios/servicioGenero.php";
    function getGeneros()
    { 
        
        //optimizada   return json_decode(file_get_contents('http://api.geonames.org/citiesJSON?north=44.1&south=-9.9&east=-22.4&west=55.2&lang=de&username=demo'),true);;
        $service = $this->cadcon . "/generos";
        $json = file_get_contents($service);
        $response = json_decode($json);
        return $response;
    }

    function getGenero($id)
    { 
        //optimizada   return json_decode(file_get_contents('http://api.geonames.org/citiesJSON?north=44.1&south=-9.9&east=-22.4&west=55.2&lang=de&username=demo'),true);;
        $service = $this->cadcon . '/genero/' . $id;
        $json = file_get_contents($service);
        $response = json_decode($json);
        return $response;
    }

    function updateGenero($id, $post)
    { 
        //optimizada   return json_decode(file_get_contents('http://api.geonames.org/citiesJSON?north=44.1&south=-9.9&east=-22.4&west=55.2&lang=de&username=demo'),true);;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->cadcon . "/generoup/" . $id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $output = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        echo $output;
    }

    function nuevoGenero( $post)
    { 
        //optimizada   return json_decode(file_get_contents('http://api.geonames.org/citiesJSON?north=44.1&south=-9.9&east=-22.4&west=55.2&lang=de&username=demo'),true);;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->cadcon . "/generocre");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $output = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        echo $output;
    }
}
