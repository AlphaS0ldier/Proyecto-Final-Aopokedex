<?php

require (__DIR__ . "/../wp-content/plugins/aopokedex/funciones_recogida_datos.php");

use PHPUnit\Framework\TestCase;

class PluginAopokedexTest extends TestCase
{
    public function test_llamar_api()
    {
        $expected = [
            "count" => 1302,
            "next" => "https://pokeapi.co/api/v2/pokemon?offset=1&limit=1",
            "previous" => null,
            "results" => [
                [
                    "name" => "bulbasaur",
                    "url" => "https://pokeapi.co/api/v2/pokemon/1/"
                ]
            ]
        ];

        $this->assertEquals($expected, llamar_api("https://pokeapi.co/api/v2/pokemon?limit=1"));
    }

    public function test_coger_nombre_pokemon_nombre_normal()
    {

        $expected = "Bulbasaur";

        $data = llamar_api("https://pokeapi.co/api/v2/pokemon/1");

        $nombre_resultado = coger_nombre_pokemon($data["name"]);

        $this->assertEquals($expected, $nombre_resultado);
    }

    public function test_coger_nombre_pokemon_nombre_guiones()
    {

        $expected = "Deoxys";

        $data = llamar_api("https://pokeapi.co/api/v2/pokemon/386");

        $nombre_resultado = coger_nombre_pokemon($data["name"]);

        $this->assertEquals($expected, $nombre_resultado);
    }


    public function test_coger_pokedex_region_y_generacion()
    {

        $expected = [
            "kanto" => 25,
            "johto" => 22,
            "hoenn" => 156
        ];

        $data = llamar_api("https://pokeapi.co/api/v2/pokemon/25");

        $pokemon_extra_datos = llamar_api($data["species"]["url"]);

        $resultado = coger_pokedex_region_y_generacion($pokemon_extra_datos["pokedex_numbers"])["regional"];

        $this->assertEquals($expected, $resultado);
    }


    public function test_coger_descripcion()
    {

        $expected = [
            "en" => "It stores electricity in the electric sacs
on its cheeks. When it releases pent-up
energy in a burst, the electric power is
equal to a lightning bolt.",
            "es" => "Cada vez que un Pikachu se encuentra con algo nuevo, le
lanza una descarga eléctrica. Cuando se ve alguna baya
chamuscada, es muy probable que sea obra de un Pikachu,
ya que a veces no controlan la intensidad de la descarga."
        ];

        $data = llamar_api("https://pokeapi.co/api/v2/pokemon/25");

        $pokemon_extra_datos = llamar_api($data["species"]["url"]);

        $resultado_descripcion = coger_descripcion($pokemon_extra_datos['flavor_text_entries']);

        $this->assertEquals($expected, $resultado_descripcion);
    }


    public function test_coger_especie()
    {

        $expected = ["en" => "Mouse Pokémon", "es" => "Pokémon Ratón"];

        $data = llamar_api("https://pokeapi.co/api/v2/pokemon/25");

        $pokemon_extra_datos = llamar_api($data["species"]["url"]);

        $resultado_especie = coger_especie($pokemon_extra_datos['genera']);

        $this->assertEquals($expected, $resultado_especie);
    }

    public function test_coger_habilidades()
    {
        $expected = [
            "en" => [
                "Static" => false,
                "Lightning-rod" => true
            ],
            "es" => [
                "Elec. Estática" => false,
                "Pararrayos" => true
            ]
        ];

        $data = llamar_api("https://pokeapi.co/api/v2/pokemon/25");

        $resultado_habilidades = coger_habilidades($data["abilities"]);

        $this->assertEquals($expected, $resultado_habilidades);
    }


    public function test_coger_estadisticas()
    {
        $expected = [
            "en" => [
                "hp"=>35,
                "attack"=>55,
                "defense"=>40,
                "special-attack"=>50,
                "special-defense"=>50,
                "speed"=>90
            ],
            "es" => [
                "PS"=>35,
                "Ataque"=>55,
                "Defensa"=>40,
                "Ataque Especial"=>50,
                "Defensa Especial"=>50,
                "Velocidad"=>90
            ]
        ];

        $data = llamar_api("https://pokeapi.co/api/v2/pokemon/25");

        $resultado_estadisticas = coger_estadisticas($data["stats"]);

        $this->assertEquals($expected, $resultado_estadisticas);

    }


    public function test_coger_movimientos()
    {
        $expected = [
            "en" => [
                "Slam"=>[
                    "level"=>20,
                    "damage_class"=>"physical",
                    "type"=>"normal",
                    "power"=>80
                ],
                "Tail-whip"=>[
                    "level"=>6,
                    "damage_class"=>"status",
                    "type"=>"normal",
                    "power"=>"-"
                ],
                "Growl"=>[
                    "level"=>1,
                    "damage_class"=>"status",
                    "type"=>"normal",
                    "power"=>"-"
                ],
                "Thunder-shock"=>[
                    "level"=>1,
                    "damage_class"=>"special",
                    "type"=>"electric",
                    "power"=>40
                ],
                "Thunderbolt"=>[
                    "level"=>26,
                    "damage_class"=>"special",
                    "type"=>"electric",
                    "power"=>90
                ],
                "Thunder-wave"=>[
                    "level"=>8,
                    "damage_class"=>"status",
                    "type"=>"electric",
                    "power"=>"-"
                ],
                "Thunder"=>[
                    "level"=>41,
                    "damage_class"=>"special",
                    "type"=>"electric",
                    "power"=>110
                ],
                "Agility"=>[
                    "level"=>33,
                    "damage_class"=>"status",
                    "type"=>"psychic",
                    "power"=>"-"
                ],
                "Quick-attack"=>[
                    "level"=>11,
                    "damage_class"=>"physical",
                    "type"=>"normal",
                    "power"=>40
                ],
                "Double-team"=>[
                    "level"=>15,
                    "damage_class"=>"status",
                    "type"=>"normal",
                    "power"=>"-"
                ],
                "Light-screen"=>[
                    "level"=>50,
                    "damage_class"=>"status",
                    "type"=>"psychic",
                    "power"=>"-"
                ],
            ],
            "es" => [
                "Atizar"=>[
                    "level"=>20,
                    "damage_class"=>"physical",
                    "type"=>"normal",
                    "power"=>80
                ],
                "Látigo"=>[
                    "level"=>6,
                    "damage_class"=>"status",
                    "type"=>"normal",
                    "power"=>"-"
                ],
                "Gruñido"=>[
                    "level"=>1,
                    "damage_class"=>"status",
                    "type"=>"normal",
                    "power"=>"-"
                ],
                "Impactrueno"=>[
                    "level"=>1,
                    "damage_class"=>"special",
                    "type"=>"electric",
                    "power"=>40
                ],
                "Rayo"=>[
                    "level"=>26,
                    "damage_class"=>"special",
                    "type"=>"electric",
                    "power"=>90
                ],
                "Onda Trueno"=>[
                    "level"=>8,
                    "damage_class"=>"status",
                    "type"=>"electric",
                    "power"=>"-"
                ],
                "Trueno"=>[
                    "level"=>41,
                    "damage_class"=>"special",
                    "type"=>"electric",
                    "power"=>110
                ],
                "Agilidad"=>[
                    "level"=>33,
                    "damage_class"=>"status",
                    "type"=>"psychic",
                    "power"=>"-"
                ],
                "Ataque Rápido"=>[
                    "level"=>11,
                    "damage_class"=>"physical",
                    "type"=>"normal",
                    "power"=>40
                ],
                "Doble Equipo"=>[
                    "level"=>15,
                    "damage_class"=>"status",
                    "type"=>"normal",
                    "power"=>"-"
                ],
                "Pantalla De Luz"=>[
                    "level"=>50,
                    "damage_class"=>"status",
                    "type"=>"psychic",
                    "power"=>"-"
                ],
            ]
        ];

        $data = llamar_api("https://pokeapi.co/api/v2/pokemon/25");

        $resultado_movimientos = coger_movimientos($data["moves"]);

        $this->assertEquals($expected, $resultado_movimientos);

    }


    public function test_coger_tipos(){

        $expected=["electric"];

        $data = llamar_api("https://pokeapi.co/api/v2/pokemon/25");

        $resultado_tipos = coger_tipos($data["types"]);

        $this->assertEquals($expected,$resultado_tipos);
    }
}
