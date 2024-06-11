<?php

/*
Plugin Name:  Aopokedex Plugin
Plugin URI:   https://www.vps-fcc51471.vps.ovh.net/aopokedex
Description:  Plugin para la página Aopokedex
Version:      1.0
Author:       m1chaelD
Author URI:   https://www.vps-fcc51471.vps.ovh.net/aopokedex
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  aopokedex
Domain Path:  /languages
*/

// Ruta absoluta
require_once (ABSPATH . 'wp-load.php');

// Cargar funciones de WordPress
require_once (ABSPATH . 'wp-includes/pluggable.php');
require_once (ABSPATH . 'wp-includes/post.php');
require_once (ABSPATH . 'wp-includes/user.php');
require_once (ABSPATH . 'wp-admin/includes/image.php');
require_once (ABSPATH . 'wp-admin/includes/taxonomy.php');
require_once (ABSPATH . 'wp-admin/includes/template.php');


// Activar - Desactivar Plugin
register_activation_hook(__FILE__, 'aopokedex_activate');

register_deactivation_hook(__FILE__, 'aopokedex_deactivate');

function aopokedex_activate()
{
    require_once __DIR__ . '/includes/aopokedex-activator.php';
    Aopokedex_Activator::activate();
}
;

function aopokedex_deactivate()
{
    require_once __DIR__ . '/includes/aopokedex-deactivator.php';
    Aopokedex_Deactivator::deactivate();
}
;

add_action('init', 'crear_post_personalizado_pokemon');

function crear_post_personalizado_pokemon()
{

    $labels = array(
        'name' => __('Pokemon', 'aopokedex'),
        'singular_name' => __('pokemon', 'aopokedex'),
        'add_new' => __('Add New', 'aopokedex'),
        'add_new_item' => __('Add New Pokemon', 'aopokedex'),
        'view_item' => __('View Pokemon', 'aopokedex'),
        'search_items' => __('Search Pokemon', 'aopokedex'),
    );

    $args = array(
        'labels' => $labels,
        'has_archive' => true,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'menu_icon' => get_site_url() . "/wp-content/uploads/2024/05/pokeball-figma-24x24-1.svg",
        'supports' => array('title', 'editor', 'thumbnail'),
        'can_export' => true
    );

    return register_post_type('pokemon', $args);

}


add_action('init', 'crear_post_personalizado_tipo_pokemon');


function crear_taxonomia_para_pokemon()
{
    register_taxonomy('pokedex_region', ['pokemon'], [
        'hierarchical' => false,
        'rewrite' => ['slug' => 'pokedex_region'],
        'show_admin_column' => true,
        'show_in_rest' => true,
        'labels' => [
            'name' => __('Regional Pokedex', 'aopokedex'),
            'singular_name' => __('Regional Pokedex', 'aopokedex'),
            'all_items' => __('All Regional Pokedexs', 'aopokedex'),
            'edit_item' => __('Edit Regional Pokedex', 'aopokedex'),
            'view_item' => __('View Regional Pokedex', 'aopokedex'),
            'update_item' => __('Update Regional Pokedex', 'aopokedex'),
            'add_new_item' => __('Add New Regional Pokedex', 'aopokedex'),
            'new_item_name' => __('New Regional Pokedex Name', 'aopokedex'),
            'search_items' => __('Search Regional Pokedexs', 'aopokedex'),
            'popular_items' => __('Popular Regional Pokedexs', 'aopokedex'),
            'separate_items_with_commas' => __('Separate Regional Pokedexs with comma', 'aopokedex'),
            'choose_from_most_used' => __('Choose from most used Regional Pokedexs', 'aopokedex'),
            'not_found' => __('No Regional Pokedexs found', 'aopokedex'),
        ]
    ]);
}

add_action('init', 'crear_taxonomia_para_pokemon');

register_taxonomy_for_object_type('pokedex_region', 'pokemon');

function crear_post_personalizado_tipo_pokemon()
{

    $labels = array(
        'name' => __('Pokemon Type', 'aopokedex'),
        'singular_name' => __('pokemon_type', 'aopokedex'),
    );

    $args = array(
        'labels' => $labels,
        'show_ui' => true,
        'show_in_menu' => true,
    );

    register_post_type('pokemon_type', $args);

}

add_action("admin_init", "add_post_meta_boxes");

function add_post_meta_boxes()
{
    add_meta_box("pokemon_metabox_id", __('Pokemon Data', 'aopokedex'), "pokemon_metabox", "pokemon", "normal", "low");
    add_meta_box("pokemon_type_metabox_id", __('Pokemon Type', 'aopokedex'), "pokemon_type_metabox", "pokemon_type", "normal", "low");
}
function pokemon_metabox()
{
    global $post;
    $custom = get_post_custom($post->ID);

    $nombre = $custom["_name"][0];
    $sprite = $custom["_sprite"][0];
    $pokedex_nacional = $custom["_pokedex_national"][0];
    $pokedex_regional = get_post_meta($post->ID, "_pokedex_regional")[0];
    $descripcion = $custom["_description"][0];
    $especie = $custom["_genus"][0];
    $habilidades = get_post_meta($post->ID, "_abilities")[0];
    $estadisticas = get_post_meta($post->ID, "_stats")[0];
    $movimientos = get_post_meta($post->ID, "_moves")[0];
    $tipos = get_post_meta($post->ID, "_types")[0];
    ?>
    <div>
        <div>
            <label>Name:</label><br />
            <input type="text" name="_name" value="<?php echo $nombre ?>">
        </div>

        <div>
            <img src="<?php echo $sprite ?>">
        </div>

        <div>
            <label>National Pokedex Number:</label><br />
            <input type="text" name="_pokedex_national" value="<?php echo $pokedex_nacional ?>">
        </div>


        <?php
        if (is_array($pokedex_regional))
            foreach ($pokedex_regional as $nombre_region => $num_region) {
                echo '<div><label>' . ucwords($nombre_region) . ' Regional Pokedex Number</label><br />
                    <input type="text" name="_pokedex_regional" value="' . $num_region . '"></div>';
            }
        ?>

        <div>
            <label>Pokemon Description:</label><br />
            <textarea rows="4" cols="50" name="_description"><?php echo $descripcion ?></textarea>
        </div>

        <div>
            <label>Pokemon Genus:</label><br />
            <input type="text" name="_genus" value="<?php echo $especie ?>">
        </div>



        <div>
            <?php
            foreach ($tipos as $index => $tipo) {

                echo '<div><label>Type ' . ($index + 1) . '</label>';
                echo '<input type="text" name="' . $tipo . '" value="' . $tipo . '"></div>';
            }
            ?>
        </div>


        <?php
        $i = 1;
        foreach ($habilidades as $key => $hidden) {

            $hidden_text = $hidden == "true" ? "checked" : "";

            echo "<div><label>Ability " . $i . "</label><br/>";
            echo '<input type="text" name="_ability" value="' . $key . '" >';
            echo "<label>Hidden " . $i . "</label>";
            echo '<input type="checkbox" ' . $hidden_text . ' name="_ability" value="' . $key . '" >';
            echo '</div>';

            $i++;
        }
        ?>
        <div>
            <?php

            foreach ($estadisticas as $nombre => $valor) {
                echo '<div><label>' . $nombre . '</label>';
                echo '<input type="text" name="' . $nombre . '" value="' . $valor . '"></div>';
            }

            ?>
        </div>



        <div>
            <?php
            foreach ($movimientos as $movimiento => $valor) {
                echo '<div><label>' . $movimiento . '</label>';
                echo '<div>';

                echo '<div><label>' . $valor["nombre_esp"] . '</label>';
                echo '<div>';

                echo '<label>Level</label>';
                echo '<input type="text" name="' . $movimiento . '-level' . '" value="' . $valor['level'] . '">';

                echo '<label>Power</label>';
                echo '<input type="text" name="' . $movimiento . '-damage' . '" value="' . $valor['power'] . '">';

                echo '<label>Damage Class</label>';
                echo '<input type="text" name="' . $movimiento . '-damage_class' . '" value="' . $valor['damage_class'] . '">';

                echo '<label>Move Type</label>';
                echo '<input type="text" name="' . $movimiento . '-type' . '" value="' . $valor['type'] . '"></div></div>';
            }
            ?>
        </div>


    </div>
    <?php
}

function pokemon_type_metabox()
{
    global $post;
    print_r(get_post_meta($post->ID, "_type_weakness")[0]);
}

//Función para crear un nuevo menú

function crear_configuracion_api()
{
    add_settings_section(
        'conf_plugin_menu',
        __('', 'conf_plugin'),
        '',
        'conf_plugin'
    );
}


add_action('admin_init', 'crear_configuracion_api');

function conf_plugin_options_page()
{
    add_menu_page(
        'Configuración',
        'Configuración Plugin',
        'manage_options',
        'conf_plugin',
        'sincronizar_html'
    );
}


add_action('admin_menu', 'conf_plugin_options_page');


function borrar_posts()
{
    $allposts = get_posts(array('post_type' => "pokemon", 'numberposts' => -1));
    foreach ($allposts as $eachpost) {
        wp_delete_post($eachpost->ID, true);
    }
}


function sincronizar_html()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_GET['settings-updated'])) {
        add_settings_error('conf_plugin_messages', 'conf_plugin_message', __('Settings Saved', 'conf_plugin'), 'updated');
    }

    settings_errors('conf_plugin_messages');

    ?>
    <div class="wrap">
        <?php
        if (isset($_POST['sync_pokemon'])) {
            sincronizar_api_pokemon();
        } else if (isset($_POST['delete_pokemon'])) {
            borrar_posts();
        }
        ?>
        <h1>
            <?php echo esc_html(get_admin_page_title()); ?>
        </h1>
        <form action="" method="post">
            <?php
            submit_button('Sincronizar Pokemon');
            ?>
            <input type="hidden" name="sync_pokemon" value="1">
        </form>
        <form action="" method="post">
            <?php
            submit_button('Borrar Pokemon');
            ?>
            <input type="hidden" name="delete_pokemon" value="1">
        </form>
    </div>
    <?php
}


function llamar_api($url_api)
{

    $response = wp_remote_get($url_api);
    if (is_wp_error($response)) {
        return 'Error al obtener los datos';
    }

    // Decodificar JSON
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo 'Error al decodificar los datos JSON: ' . json_last_error_msg();
    }

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

            if (!term_exists($numero_pokedex["pokedex"]["name"], 'pokedex_region')) {

                $pokedex = end(llamar_api($numero_pokedex["pokedex"]["url"])["pokemon_entries"]);

                wp_insert_term(
                    $numero_pokedex["pokedex"]["name"],
                    'pokedex_region',
                    ['description' => $pokedex['entry_number']]
                );

            }

            $pokedex_generacion[] = get_term_by('name', $numero_pokedex["pokedex"]["name"], 'pokedex_region')->term_id;

        } else if ($numero_pokedex["pokedex"]["name"] == "original-johto") {

            $nombre_generacion = explode('-', $numero_pokedex["pokedex"]["name"])[1];

            $pokedex_regional[$nombre_generacion] = $numero_pokedex["entry_number"];

            if (!term_exists($nombre_generacion, 'pokedex_region')) {

                $pokedex = end(llamar_api($numero_pokedex["pokedex"]["url"])["pokemon_entries"]);

                wp_insert_term(
                    $nombre_generacion,
                    'pokedex_region',
                    ['description' => $pokedex['entry_number']]
                );
            }

            $pokedex_generacion[] = get_term_by('name', $nombre_generacion, 'pokedex_region')->term_id;

        } else if ($numero_pokedex["pokedex"]["name"] == "kanto") {

            $pokedex_regional[$numero_pokedex["pokedex"]["name"]] = $numero_pokedex["entry_number"];

            if (!term_exists($numero_pokedex["pokedex"]["name"], 'pokedex_region')) {

                $pokedex = end(llamar_api($numero_pokedex["pokedex"]["url"])["pokemon_entries"]);


                wp_insert_term(
                    $numero_pokedex["pokedex"]["name"],
                    'pokedex_region',
                    ['description' => $pokedex['entry_number']]
                );

            }

            $pokedex_generacion[] = get_term_by('name', $numero_pokedex["pokedex"]["name"], 'pokedex_region')->term_id;

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

function coger_tipos($datos){
    $tipos = [];

    foreach ($datos as $tipo) {
        $tipos[] = $tipo["type"]["name"];

        $tipo_existe = get_posts(
            array(
                'post_type' => 'pokemon_type',
                'meta_key' => '_name',
                'meta_value' => $tipo["type"]["name"],
                'post_status' => 'publish',
                'posts_per_page' => 1,
            )
        );

        if (empty($tipo_existe)) {
            $new_post = array(
                'post_title' => $tipo["type"]["name"],
                'post_content' => "",
                'post_status' => 'publish',
                'post_author' => 1,
                'post_type' => 'pokemon_type',
            );

            $post_id = wp_insert_post($new_post);



            $debilidades_api = llamar_api($tipo["type"]["url"])["damage_relations"];

            $debilidades = [];

            foreach ($debilidades_api["double_damage_from"] as $multiplicador) {
                $debilidades["2x"][] = $multiplicador["name"];
            }

            foreach ($debilidades_api["half_damage_from"] as $multiplicador) {
                $debilidades["0.5x"][] = $multiplicador["name"];
            }

            foreach ($debilidades_api["no_damage_from"] as $multiplicador) {
                $debilidades["0x"][] = $multiplicador["name"];
            }

            add_post_meta($post_id, "_name", $tipo["type"]["name"]);

            add_post_meta($post_id, "_type_weakness", $debilidades);

        }
    }

    return $tipos;
}

function sincronizar_api_pokemon()
{

    $data = llamar_api("https://pokeapi.co/api/v2/pokemon?limit=386");

    $results = $data["results"];

    foreach ($results as $result) {


        $post_existe = get_posts(
            array(
                'post_type' => 'pokemon',
                'meta_key' => '_name',
                'meta_value' => $result["name"],
                'post_status' => 'publish',
                'posts_per_page' => -1,
            )
        );

        if (!empty($post_existe)) {
            continue;
        }


        $nombre = coger_nombre_pokemon($result["name"]);


        $pokemon = llamar_api($result["url"]);

        $pokemon_extra_datos = llamar_api($pokemon["species"]["url"]);


        $pokedex_nacional = $pokemon["id"];


        $resultado_pokedex = coger_pokedex_region_y_generacion($pokemon_extra_datos["pokedex_numbers"]);

        $pokedex_regional = $resultado_pokedex["regional"];

        $pokedex_generacion = $resultado_pokedex["generacion"];



        $resultado_descripcion = coger_descripcion($pokemon_extra_datos['flavor_text_entries']);

        $pokemon_descripcion = $resultado_descripcion["en"];

        $pokemon_descripcion_es = $resultado_descripcion["es"];



        $resultado_especie = coger_especie($pokemon_extra_datos['genera']);

        $pokemon_especie = $resultado_especie["en"];

        $pokemon_especie_es = $resultado_especie["es"];



        $resultado_habilidades = coger_habilidades($pokemon["abilities"]);

        $habilidades = $resultado_habilidades["en"];

        $habilidades_es = $resultado_habilidades["es"];



        $resultado_estadisticas = coger_estadisticas($pokemon["stats"]);

        $estadisticas = $resultado_estadisticas["en"];

        $estadisticas_es = $resultado_estadisticas["es"];




        $resultado_movimientos = coger_movimientos($pokemon["moves"]);

        $movimientos = $resultado_movimientos["en"];

        $movimientos_es = $resultado_movimientos["es"];




        $numero = "";

        if (strlen($pokedex_nacional) == 3) {
            $numero = "0" . $pokedex_nacional;
        } else if (strlen($pokedex_nacional) == 2) {
            $numero = "00" . $pokedex_nacional;
        } else if (strlen($pokedex_nacional) == 1) {
            $numero = "000" . $pokedex_nacional;
        }

        $sprite = get_site_url() . "/wp-content/plugins/aopokedex/png/" . $numero . " " . $nombre . ".png";


        $tipos = coger_tipos($pokemon["types"]);

        $pokemon_traducciones = [];

        /*********************  INGLÉS *********************/

        $new_post = array(
            'post_title' => $nombre . "-en",
            'post_content' => "",
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type' => 'pokemon',
        );

        $post_id = wp_insert_post($new_post);

        $pokemon_traducciones["en"] = $post_id;

        pll_set_post_language($post_id, "en");

        add_post_meta($post_id, "_name", $nombre);
        add_post_meta($post_id, "_sprite", $sprite);

        add_post_meta($post_id, "_pokedex_national", $pokedex_nacional);

        add_post_meta($post_id, "_pokedex_regional", $pokedex_regional);

        add_post_meta($post_id, "_description", $pokemon_descripcion);

        add_post_meta($post_id, "_genus", $pokemon_especie);

        add_post_meta($post_id, "_abilities", $habilidades);

        add_post_meta($post_id, "_stats", $estadisticas);

        array_multisort(array_column($movimientos, 'level'), SORT_ASC, SORT_NUMERIC, $movimientos);

        add_post_meta($post_id, "_moves", $movimientos);

        add_post_meta($post_id, "_types", $tipos);

        wp_set_object_terms($post_id, $pokedex_generacion, 'pokedex_region');

        /*********************  ESPAÑOL *********************/

        $new_post_es = array(
            'post_title' => $nombre . "-es",
            'post_content' => "",
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type' => 'pokemon',
        );

        $post_id_es = wp_insert_post($new_post_es);

        $pokemon_traducciones["es"] = $post_id_es;

        pll_set_post_language($post_id_es, "es");

        add_post_meta($post_id_es, "_name", $nombre);
        add_post_meta($post_id_es, "_sprite", $sprite);

        add_post_meta($post_id_es, "_pokedex_national", $pokedex_nacional);

        add_post_meta($post_id_es, "_pokedex_regional", $pokedex_regional);

        add_post_meta($post_id_es, "_description", $pokemon_descripcion_es);

        add_post_meta($post_id_es, "_genus", $pokemon_especie_es);

        add_post_meta($post_id_es, "_abilities", $habilidades_es);

        add_post_meta($post_id_es, "_stats", $estadisticas_es);

        array_multisort(array_column($movimientos_es, 'level'), SORT_ASC, SORT_NUMERIC, $movimientos_es);

        add_post_meta($post_id_es, "_moves", $movimientos_es);

        add_post_meta($post_id_es, "_types", $tipos);

        wp_set_object_terms($post_id_es, $pokedex_generacion, 'pokedex_region');

        pll_save_post_translations($pokemon_traducciones);

    }
}

