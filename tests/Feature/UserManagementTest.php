<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('admin.owner_email', 'admin@jvropatipica.mx');
        $this->owner = User::factory()->create([
            'name' => 'Administrador JV',
            'email' => 'admin@jvropatipica.mx',
        ]);
    }

    public function test_only_the_owner_can_see_and_access_user_management(): void
    {
        $editor = User::factory()->create(['email' => 'editor@example.com']);

        $this->actingAs($owner = $this->owner)
            ->get('/admin')
            ->assertOk()
            ->assertSee('Usuarios');
        $this->actingAs($owner)->get('/admin/users')->assertOk()->assertSee('Cuenta principal');
        $this->actingAs($owner)->get('/admin/users/create')->assertOk()->assertSee('Nuevo usuario');

        $this->actingAs($editor)
            ->get('/admin')
            ->assertOk()
            ->assertDontSee('Usuarios');

        $this->actingAs($editor)->get('/admin/users')->assertForbidden();
        $this->actingAs($editor)->get("/admin/users/{$owner->id}/edit")->assertForbidden();
    }

    public function test_owner_can_create_a_user_with_a_password(): void
    {
        $this->actingAs($this->owner)->post('/admin/users', [
            'name' => 'Editora Catálogo',
            'email' => 'editora@jvropatipica.mx',
            'password' => 'ClaveSegura2026!',
            'password_confirmation' => 'ClaveSegura2026!',
        ])->assertRedirect('/admin/users');

        $user = User::where('email', 'editora@jvropatipica.mx')->firstOrFail();
        $this->assertTrue(Hash::check('ClaveSegura2026!', $user->password));
    }

    public function test_owner_can_change_another_users_password(): void
    {
        $editor = User::factory()->create(['email' => 'editor@example.com']);

        $this->actingAs($this->owner)->put("/admin/users/{$editor->id}", [
            'name' => $editor->name,
            'email' => $editor->email,
            'password' => 'NuevaClave2026!',
            'password_confirmation' => 'NuevaClave2026!',
        ])->assertRedirect('/admin/users');

        $this->assertTrue(Hash::check('NuevaClave2026!', $editor->fresh()->password));
    }

    public function test_owner_can_delete_another_user(): void
    {
        $editor = User::factory()->create(['email' => 'editor@example.com']);

        $this->actingAs($this->owner)->delete("/admin/users/{$editor->id}")
            ->assertRedirect('/admin/users');

        $this->assertDatabaseMissing('users', ['id' => $editor->id]);
    }

    public function test_owner_account_cannot_be_deleted_or_change_its_email(): void
    {
        $this->actingAs($this->owner)->delete("/admin/users/{$this->owner->id}")
            ->assertStatus(422);

        $this->actingAs($this->owner)->put("/admin/users/{$this->owner->id}", [
            'name' => 'Administrador Principal',
            'email' => 'otro@example.com',
            'password' => '',
            'password_confirmation' => '',
        ])->assertRedirect('/admin/users');

        $this->assertDatabaseHas('users', [
            'id' => $this->owner->id,
            'name' => 'Administrador Principal',
            'email' => 'admin@jvropatipica.mx',
        ]);
    }
}
