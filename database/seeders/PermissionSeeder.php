<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'users-index', 'users-show', 'users-create', 'users-update', 'users-delete',
            'roles-index', 'roles-show', 'roles-create', 'roles-update', 'roles-delete',
            'category-index', 'category-show', 'category-create', 'category-update', 'category-delete',
            'sub_catgory-index', 'sub_catgory-show', 'sub_catgory-create', 'sub_catgory-update', 'sub_catgory-delete',
            'size-index', 'size-show', 'size-create', 'size-update', 'size-delete',
            'terms_of_conditions-edit',
            'setting-edit',
            'help-index', 'help-show', 'help-create', 'help-update', 'help-delete',
            'complaint-index', 'complaint-show', 'complaint-delete',
            'vendors-index', 'vendors-show', 'vendors-create', 'vendors-update', 'vendors-delete',
            'deliveries-index', 'deliveries-show', 'deliveries-create', 'deliveries-update', 'deliveries-delete',


        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
