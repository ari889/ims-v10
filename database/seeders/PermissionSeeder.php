<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    protected $permission = [
        ['module_id' => 9, 'name' => 'Permission Access', 'slug' => 'permission-access'],
        ['module_id' => 9, 'name' => 'Permission Add', 'slug' => 'permission-add'],
        ['module_id' => 9, 'name' => 'Permission Edit', 'slug' => 'permission-edit'],
        ['module_id' => 9, 'name' => 'Permission Delete', 'slug' => 'permission-delete'],
        ['module_id' => 9, 'name' => 'Permission Bulk Delete', 'slug' => 'permission-bulk-delete'],
        ['module_id' => 9, 'name' => 'Permission Report', 'slug' => 'permission-report'],
        ['module_id' => 7, 'name' => 'Menu Access', 'slug' => 'menu-access'],
        ['module_id' => 7, 'name' => 'Menu Add', 'slug' => 'menu-add'],
        ['module_id' => 7, 'name' => 'Menu Edit', 'slug' => 'menu-edit'],
        ['module_id' => 7, 'name' => 'Menu Delete', 'slug' => 'menu-delete'],
        ['module_id' => 7, 'name' => 'Menu Bulk Delete', 'slug' => 'menu-bulk-delete'],
        ['module_id' => 7, 'name' => 'Menu Report', 'slug' => 'menu-report'],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::insert($this->permission);
    }
}
