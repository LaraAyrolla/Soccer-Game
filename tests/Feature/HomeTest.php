<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeTest extends TestCase
{
    public function testHomeView()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('game.index');
        $response->assertSee('Crie novas partidas. Jogadores podem ser cadastrados durante a confirmação de presença.');
    }
}
