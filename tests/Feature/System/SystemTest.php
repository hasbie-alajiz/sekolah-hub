<?php

declare(strict_types=1);

namespace Tests\Feature\System;

use App\Models\User;
use App\Modules\System\Models\Setting;
use App\Modules\System\Models\AuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class SystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $editor;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Run SystemSeeder to seed permissions, roles, settings, and the default Super Admin
        $this->seed(\App\Modules\System\database\seeders\SystemSeeder::class);

        // 2. Retrieve Super Admin user from database (seeded in SystemSeeder)
        $this->superAdmin = User::where('email', 'hasbialaziz67@gmail.com')->first();

        // 3. Create an Editor user manually for negative/authorization testing
        $this->editor = User::factory()->create([
            'name' => 'Editor User',
            'email' => 'editor@example.com',
        ]);
        $this->editor->assignRole('Editor');
    }

    /**
     * Test seeder created default settings, roles and users correctly.
     */
    public function test_system_seeder_initializes_defaults(): void
    {
        $this->assertDatabaseHas('roles', ['name' => 'Super Admin']);
        $this->assertDatabaseHas('roles', ['name' => 'Admin Sekolah']);
        $this->assertDatabaseHas('roles', ['name' => 'Editor']);

        $this->assertDatabaseHas('settings', [
            'key' => 'theme.active',
            'value' => 'school-classic'
        ]);

        $this->assertDatabaseHas('settings', [
            'key' => 'cloudflare.turnstile.site_key',
            'value' => '0x4AAAAAADnUDmc-9DJ4l5fi'
        ]);

        $this->assertNotNull($this->superAdmin);
        $this->assertTrue($this->superAdmin->hasRole('Super Admin'));
    }

    /**
     * Test only users with settings.manage permission can view settings.
     */
    public function test_only_authorized_users_can_view_settings(): void
    {
        // 1. Unauthorized user (Editor doesn't have settings.manage)
        $response = $this->actingAs($this->editor)->get(route('admin.settings.index'));
        $response->assertStatus(403);

        // 2. Authorized user (Super Admin has permission bypass)
        $response = $this->actingAs($this->superAdmin)->get(route('admin.settings.index'));
        $response->assertStatus(200);
        $response->assertViewHas('settings');
    }

    /**
     * Test updating settings, saving changes and recording in audit log.
     */
    public function test_user_can_update_settings_and_logs_audit(): void
    {
        $this->actingAs($this->superAdmin);

        $response = $this->post(route('admin.settings.update'), [
            'settings' => [
                'school.name' => 'Sekolah Hub Baru',
                'school.email' => 'admin_baru@sekolah.sch.id',
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        // Check values updated in DB
        $this->assertDatabaseHas('settings', [
            'key' => 'school.name',
            'value' => 'Sekolah Hub Baru',
        ]);

        // Check audit log recorded the action
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'settings.update',
            'user_id' => $this->superAdmin->id,
        ]);

        $auditLog = AuditLog::where('action', 'settings.update')->first();
        $this->assertNotNull($auditLog);
        $this->assertEquals('Sekolah Hub Baru', $auditLog->new_values['school.name'] ?? null);
    }

    /**
     * Test User Management CRUD actions.
     */
    public function test_admin_can_manage_users_with_roles(): void
    {
        $this->actingAs($this->superAdmin);

        // 1. Create User
        $response = $this->post(route('admin.users.store'), [
            'name' => 'Guru Baru',
            'email' => 'guru@sekolah.sch.id',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'Editor',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'name' => 'Guru Baru',
            'email' => 'guru@sekolah.sch.id',
        ]);

        $createdUser = User::where('email', 'guru@sekolah.sch.id')->first();
        $this->assertTrue($createdUser->hasRole('Editor'));

        // Check audit log for creation
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'user.create',
            'auditable_id' => $createdUser->id,
        ]);

        // 2. Update User
        $response = $this->put(route('admin.users.update', $createdUser->id), [
            'name' => 'Guru Editor',
            'email' => 'guru@sekolah.sch.id',
            'role' => 'Admin Sekolah',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $createdUser->id,
            'name' => 'Guru Editor',
        ]);

        $createdUser->refresh();
        $this->assertTrue($createdUser->hasRole('Admin Sekolah'));
        $this->assertFalse($createdUser->hasRole('Editor'));

        // 3. Delete User
        $response = $this->delete(route('admin.users.destroy', $createdUser->id));
        $response->assertRedirect();
        $this->assertDatabaseMissing('users', ['id' => $createdUser->id]);

        // Check audit log for deletion
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'user.delete',
            'user_id' => $this->superAdmin->id,
        ]);
    }

    /**
     * Test audit logging masks sensitive fields like password.
     */
    public function test_audit_logging_masks_sensitive_data(): void
    {
        $this->actingAs($this->superAdmin);

        // Create a user which triggers audit logging
        $this->post(route('admin.users.store'), [
            'name' => 'Staff Baru',
            'email' => 'staff@sekolah.sch.id',
            'password' => 'supersecretpassword',
            'password_confirmation' => 'supersecretpassword',
            'role' => 'Editor',
        ]);

        $auditLog = AuditLog::where('action', 'user.create')->first();
        $this->assertNotNull($auditLog);

        // Assert no plaintext password exists in logs
        $oldJson = json_encode($auditLog->old_values);
        $newJson = json_encode($auditLog->new_values);

        $this->assertStringNotContainsString('supersecretpassword', (string)$oldJson);
        $this->assertStringNotContainsString('supersecretpassword', (string)$newJson);
    }
}
