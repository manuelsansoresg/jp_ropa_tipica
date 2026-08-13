<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    private string $uploadDirectory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uploadDirectory = storage_path('framework/testing/product-images-'.Str::random(12));
        config()->set('uploads.product_images_path', $this->uploadDirectory);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->uploadDirectory);

        parent::tearDown();
    }

    public function test_admin_routes_require_authentication(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/products')->assertRedirect('/admin/login');
    }

    public function test_admin_can_create_a_category_that_appears_in_the_public_menu(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/admin/categories', [
            'name' => 'Camisas tradicionales',
            'description' => 'Prendas tradicionales de Yucatán.',
            'active' => '1',
            'sort_order' => '4',
        ])->assertRedirect('/admin/categories');

        $this->assertDatabaseHas('categories', ['slug' => 'camisas-tradicionales', 'active' => true]);
        $this->get('/')->assertOk()->assertSee('Camisas tradicionales');
    }

    public function test_public_menu_keeps_categories_inline_when_there_are_five_or_fewer(): void
    {
        foreach (range(1, 5) as $position) {
            Category::create([
                'name' => 'Categoría '.$position,
                'slug' => 'categoria-'.$position,
                'active' => true,
                'sort_order' => $position,
            ]);
        }

        $this->get('/')
            ->assertOk()
            ->assertSee('data-menu-mode="inline"', false)
            ->assertSee('Catálogo')
            ->assertSee('Categoría 5');
    }

    public function test_public_menu_groups_categories_when_there_are_more_than_five(): void
    {
        foreach (range(1, 6) as $position) {
            Category::create([
                'name' => 'Categoría '.$position,
                'slug' => 'categoria-'.$position,
                'active' => true,
                'sort_order' => $position,
            ]);
        }

        $this->get('/')
            ->assertOk()
            ->assertSee('data-menu-mode="grouped"', false)
            ->assertSee('6 categorías')
            ->assertSee('Ver catálogo completo')
            ->assertSee('Categoría 6');
    }

    public function test_admin_can_create_a_product_without_a_category_with_images_and_sizes(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/admin/products', [
            'category_id' => '',
            'name' => 'Prenda Especial JV',
            'short_description' => 'Edición especial.',
            'description' => 'Una prenda de edición especial sin categoría.',
            'price' => '1250',
            'material' => 'Lino',
            'colors_text' => 'Blanco, Marfil',
            'availability' => 'Disponible bajo pedido',
            'active' => '1',
            'featured' => '1',
            'images' => [UploadedFile::fake()->image('prenda.jpg', 1200, 1600)],
            'sizes' => [
                ['name' => 'M'],
                ['name' => 'G'],
            ],
        ])->assertRedirect();

        $product = Product::where('slug', 'prenda-especial-jv')->firstOrFail();
        $this->assertNull($product->category_id);
        $this->assertCount(1, $product->images);
        $this->assertCount(2, $product->sizes);
        $this->assertStringStartsWith('/images/products/', $product->images->first()->image);
        $this->assertFileExists($this->uploadDirectory.'/'.basename($product->images->first()->image));
        $this->get('/productos/prenda-especial-jv')->assertOk()->assertSee('Colección JV')->assertSee('Disponible bajo pedido');
    }

    public function test_admin_can_preview_store_and_remove_a_category_cover(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/admin/categories', [
            'name' => 'Colección con portada',
            'image' => UploadedFile::fake()->image('portada.webp', 1200, 1500),
            'active' => '1',
        ])->assertRedirect('/admin/categories');

        $category = Category::where('slug', 'coleccion-con-portada')->firstOrFail();
        $storedImage = $category->image;

        $this->assertStringStartsWith('/images/products/', $storedImage);
        $this->assertFileExists($this->uploadDirectory.'/'.basename($storedImage));

        $this->actingAs($user)->put("/admin/categories/{$category->id}", [
            'name' => $category->name,
            'remove_image' => '1',
            'active' => '1',
        ])->assertRedirect('/admin/categories');

        $this->assertNull($category->fresh()->image);
        $this->assertFileDoesNotExist($this->uploadDirectory.'/'.basename($storedImage));
    }

    public function test_deleting_a_category_keeps_its_products_uncategorized(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Temporal', 'slug' => 'temporal', 'active' => true]);
        $product = Product::create(['category_id' => $category->id, 'name' => 'Producto permanente', 'slug' => 'producto-permanente', 'active' => true]);

        $this->actingAs($user)->delete("/admin/categories/{$category->id}")->assertRedirect('/admin/categories');

        $this->assertDatabaseHas('products', ['id' => $product->id, 'category_id' => null]);
    }

    public function test_admin_can_delete_multiple_selected_products(): void
    {
        $user = User::factory()->create();
        $products = collect([
            Product::create(['name' => 'Producto uno', 'slug' => 'producto-uno', 'active' => true]),
            Product::create(['name' => 'Producto dos', 'slug' => 'producto-dos', 'active' => true]),
            Product::create(['name' => 'Producto conservado', 'slug' => 'producto-conservado', 'active' => true]),
        ]);

        $this->actingAs($user)->delete('/admin/products/bulk', [
            'product_ids' => $products->take(2)->pluck('id')->all(),
        ])->assertRedirect('/admin/products');

        $this->assertDatabaseMissing('products', ['id' => $products[0]->id]);
        $this->assertDatabaseMissing('products', ['id' => $products[1]->id]);
        $this->assertDatabaseHas('products', ['id' => $products[2]->id]);
    }

    public function test_admin_can_delete_the_entire_product_catalog(): void
    {
        $user = User::factory()->create();
        Product::create(['name' => 'Producto uno', 'slug' => 'producto-uno', 'active' => true]);
        Product::create(['name' => 'Producto dos', 'slug' => 'producto-dos', 'active' => true]);

        $this->actingAs($user)->delete('/admin/products/bulk', ['select_all' => '1'])
            ->assertRedirect('/admin/products');

        $this->assertDatabaseCount('products', 0);
    }
}
