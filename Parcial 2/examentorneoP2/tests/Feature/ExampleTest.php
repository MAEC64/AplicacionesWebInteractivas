<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_el_listado_de_torneos_responde(): void
    {
        $this->get('/torneos')->assertStatus(200);
    }
}
