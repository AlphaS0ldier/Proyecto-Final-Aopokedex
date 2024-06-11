<?php

function llamar_api($url_api)
{

    // URL of the PokeAPI endpoint you want to access
    $apiUrl = $url_api;
    
    // Initialize cURL session
    $curl = curl_init();
    
    // Set cURL options
    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiUrl,
        CURLOPT_RETURNTRANSFER => true,  // Return the response as a string
        CURLOPT_SSL_VERIFYPEER => false, // Do not verify SSL certificate
    ));
    
    // Execute the cURL request
    $response = curl_exec($curl);
    
    // Check for errors
    if ($response === false) {
        // Handle error
        echo "cURL Error: " . curl_error($curl);
    } else {
        // Decode JSON response
        $data = json_decode($response, true);
    }
    
    // Close cURL session
    curl_close($curl);
    

    return $data;
}

function coger_nombre_pokemon($datos)
{

    $nombre = "";

    if (!str_contains($datos, "deoxys")) {
        $nombre = ucwords($datos);
    } else {
        $nombre = ucwords(explode("-", $datos)[0]);
    }

    return $nombre;
}

function coger_pokedex_region_y_generacion($datos)
{

    $result = [];

    $pokedex_regional = [];

    $pokedex_generacion = [];


    foreach ($datos as $numero_pokedex) {

        if ($numero_pokedex["pokedex"]["name"] == "hoenn") {

            $pokedex_regional[$numero_pokedex["pokedex"]["name"]] = $numero_pokedex["entry_number"];

        } else if ($numero_pokedex["pokedex"]["name"] == "original-johto") {

            $nombre_generacion = explode('-', $numero_pokedex["pokedex"]["name"])[1];

            $pokedex_regional[$nombre_generacion] = $numero_pokedex["entry_number"];

        } else if ($numero_pokedex["pokedex"]["name"] == "kanto") {

            $pokedex_regional[$numero_pokedex["pokedex"]["name"]] = $numero_pokedex["entry_number"];
        }
    }

    if (empty($pokedex_regional)) {
        $pokedex_regional = "-";
    }

    $result["regional"] = $pokedex_regional;

    $result["generacion"] = $pokedex_generacion;

    return $result;
}


function coger_descripcion($datos)
{
    $pokemon_descripcion = "";

    $pokemon_descripcion_es = "";

    foreach ($datos as $text) {
        if ($text["version"]["name"] == "emerald") {
            $pokemon_descripcion = $text['flavor_text'];
        }
        if ($text["version"]["name"] == "omega-ruby" && $text["language"]["name"] == "es") {
            $pokemon_descripcion_es = $text['flavor_text'];
        }
    }

    return ["en" => $pokemon_descripcion, "es" => $pokemon_descripcion_es];
}


function coger_especie($datos)
{
    $pokemon_especie = "";

    $pokemon_especie_es = "";

    foreach ($datos as $text) {
        if ($text["language"]["name"] == "en") {
            $pokemon_especie = $text['genus'];
        } else if ($text["language"]["name"] == "es") {
            $pokemon_especie_es = $text['genus'];
        }
    }

    return ["en" => $pokemon_especie, "es" => $pokemon_especie_es];
}


function coger_habilidades($datos)
{

    $habilidades = [];

    $habilidades_es = [];

    foreach ($datos as $habilidad) {


        $nombre_habilidades = llamar_api($habilidad["ability"]["url"])["names"];

        $nombre_habilidad = "";

        foreach ($nombre_habilidades as $nom) {
            if ($nom["language"]["name"] == "es") {
                $nombre_habilidad = $nom["name"];
                break;
            }
        }



        $habilidades_es[ucwords($nombre_habilidad)] = $habilidad["is_hidden"] ? true : false;


        $habilidades[ucwords($habilidad["ability"]["name"])] = $habilidad["is_hidden"] ? true : false;

    }

    return ["en" => $habilidades, "es" => $habilidades_es];
}


function coger_estadisticas($datos)
{
    $estadisticas = [];

    $estadisticas_es = [];

    foreach ($datos as $estadistica) {
        $estadisticas[$estadistica["stat"]["name"]] = $estadistica["base_stat"];

        $nombre_estadisticas = llamar_api($estadistica["stat"]["url"])["names"];

        $nombre_estadistica = "";


        foreach ($nombre_estadisticas as $nom) {
            if ($nom["language"]["name"] == "es") {
                $nombre_estadistica = $nom["name"];
                break;
            }
        }

        $estadisticas_es[$nombre_estadistica] = $estadistica["base_stat"];
    }

    return ["en" => $estadisticas, "es" => $estadisticas_es];
}


function coger_movimientos($datos)
{
    $movimientos = [];

    $movimientos_es = [];

    foreach ($datos as $movimiento) {
        foreach ($movimiento["version_group_details"] as $version) {
            if ($version["version_group"]["name"] == "emerald" || $version["version_group"]["name"] == "ruby-sapphire") {
                if ($version["move_learn_method"]["name"] == "level-up") {
                    $mov_desc = llamar_api($movimiento["move"]["url"]);

                    $nombre_movimiento = "";

                    foreach ($mov_desc["names"] as $mov) {
                        if ($mov["language"]["name"] == "es") {
                            $nombre_movimiento = $mov["name"];
                        }
                    }

                    $movimientos[ucwords($movimiento["move"]["name"])] = [
                        "level" => $version["level_learned_at"],
                        "damage_class" => $mov_desc["damage_class"]["name"],
                        "type" => $mov_desc["type"]["name"],
                        "power" => !empty($mov_desc["power"]) ? $mov_desc["power"] : "-",
                    ];


                    $movimientos_es[ucwords($nombre_movimiento)] = [
                        "level" => $version["level_learned_at"],
                        "damage_class" => $mov_desc["damage_class"]["name"],
                        "type" => $mov_desc["type"]["name"],
                        "power" => !empty($mov_desc["power"]) ? $mov_desc["power"] : "-",
                    ];
                }
            }
        }

    }

    return ["en" => $movimientos, "es" => $movimientos_es];
}

function coger_tipos($datos)
{
    $tipos = [];

    foreach ($datos as $tipo) {
        $tipos[] = $tipo["type"]["name"];
    }

    return $tipos;
}