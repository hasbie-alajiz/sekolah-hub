<?php

declare(strict_types=1);

namespace App\Modules\System\database\seeders;

use App\Models\User;
use App\Modules\System\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SystemSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Define Permissions
        $permissions = [
            'system.manage',
            'settings.manage',
            'users.manage',
            'audit_logs.view',
            'media.upload',
            'media.manage',
            'cms.manage',
            'gallery.manage',
            'contact.manage',
            'ppdb.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 3. Define Roles & Assign Permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        // Super Admin gets all permissions
        $superAdminRole->syncPermissions(Permission::all());

        $adminSekolahRole = Role::firstOrCreate(['name' => 'Admin Sekolah']);
        $adminSekolahRole->syncPermissions([
            'settings.manage',
            'users.manage',
            'media.upload',
            'media.manage',
            'cms.manage',
            'gallery.manage',
            'contact.manage',
            'ppdb.manage',
        ]);

        $editorRole = Role::firstOrCreate(['name' => 'Editor']);
        $editorRole->syncPermissions([
            'media.upload',
            'cms.manage',
            'gallery.manage',
        ]);

        // 4. Create Default Super Admin User
        $adminEmail = 'hasbialaziz67@gmail.com';
        $user = User::where('email', $adminEmail)->first();

        if (!$user) {
            $user = User::create([
                'name' => 'Super Admin',
                'email' => $adminEmail,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        }

        $user->assignRole($superAdminRole);

        // 5. Seed Initial Settings
        $settings = [
            'theme.active' => [
                'value' => 'school-classic',
                'description' => 'The currently active public frontend website theme.',
            ],
            'cloudflare.turnstile.site_key' => [
                'value' => '0x4AAAAAADnUDmc-9DJ4l5fi',
                'description' => 'Cloudflare Turnstile site key for SPAM protection.',
            ],
            'cloudflare.turnstile.secret_key' => [
                'value' => '',
                'description' => 'Cloudflare Turnstile secret key.',
            ],
            'school.name' => [
                'value' => 'Sekolah Hub',
                'description' => 'The name of the school.',
            ],
            'school.email' => [
                'value' => 'info@sekolah.sch.id',
                'description' => 'Official email address of the school.',
            ],
        ];

        foreach ($settings as $key => $data) {
            Setting::firstOrCreate(
                ['key' => $key],
                [
                    'value' => $data['value'],
                    'description' => $data['description'],
                ]
            );
        }
    }
}
