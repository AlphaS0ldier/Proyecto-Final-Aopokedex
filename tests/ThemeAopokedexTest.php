<?php


require(__DIR__."/../wp-content/themes/aopokedex/calculos.php");

use PHPUnit\Framework\TestCase;

class ThemeAopokedexTest extends TestCase
{
    public function test_calcular_ev_no_es_hp()
    {
        $expected=14.3;

        $nombre="attack";
        $base=40;
        $nivel=10;
        $ev=52;

        $this->assertEquals($expected,calcular_ev($nombre,$base,$ev,$nivel));
    }

    public function test_calcular_ev_es_hp()
    {
        $expected=14.3;

        $nombre="hp";
        $base=40;
        $nivel=10;
        $ev=52;

        $this->assertNotEquals($expected,calcular_ev($nombre,$base,$ev,$nivel));
    }

    public function test_calcular_dano()
    {
        $expected=122.8;

        $ataque=22;

        $defensa=28;

        $nivel=100;

        $debilidad=2;

        $poder=90;

        $stab=1;

        $this->assertEquals($expected,calcular_dano($ataque,$defensa,$nivel,$debilidad,$poder,$stab));
    }
}