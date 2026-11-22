<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Checking for roles:
        // $role = app(Role::class)->findByName(RoleEnum::Administrator);
        // $role = app(Role::class)->findOrCreate(RoleEnum::Administrator);

        // Blade directives:
        // @can(enum_value(RoleEnum::ROLE_NAME))
        // @if(auth()->user()->can('edit articles') && $some_other_condition)
        // You can use @can, @cannot, @canany, and @guest

        // reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Utworzenie ról
        app(Role::class)->findOrCreate(RoleEnum::Administrator->value, 'web');
        app(Role::class)->findOrCreate(RoleEnum::Organizator->value, 'web');
        app(Role::class)->findOrCreate(RoleEnum::Sedzia->value, 'web');
        app(Role::class)->findOrCreate(RoleEnum::Zawodnik->value, 'web');

        // this can be done as separate statements
        // $role = Role::create(['name' => 'writer']);
        // $role->givePermissionTo('edit articles');
    }
}
