<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_home_page_is_available(): void
    {
        $this->seed();
        $this->get('/')->assertOk()->assertSee('Tradición,')->assertSee('Prendas seleccionadas');
    }

    public function test_catalog_and_product_pages_are_available(): void
    {
        $this->seed();
        $this->get('/colecciones')->assertOk()->assertSee('Colecciones');
        $this->get('/colecciones/guayaberas')->assertOk()->assertSee('Guayaberas');
        $this->get('/productos/guayabera-blanca-clasica')->assertOk()->assertSee('Consultar disponibilidad por WhatsApp');
        $this->get('/guia-de-tallas')->assertOk()->assertSee('Encuentra tu');
        $this->get('/nosotros')->assertOk()->assertSee('Nuestra historia también se viste');
        $this->get('/contacto')->assertOk()->assertSee('Estamos para');
    }
}
