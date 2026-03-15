<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'super admin',
                'is_default' => true,
                'all_permissions' => true, // super admin gets ALL permissions
            ],
            [
                'name' => 'user',
                'is_default' => true,
                'all_permissions' => false,
                'permissions' => []
            ]
        ];

        // Create role and assign permission to role
        foreach ($data as $key => $value) {
            try {
                $role = Role::updateOrCreate([
                    'id' => $key + 1
                ], [
                    'id' => $key + 1,
                    'name' => $value["name"],
                    'is_default' => $value["is_default"],
                ]);

                if (!empty($value['all_permissions'])) {
                    // Give all existing permissions to super admin
                    $role->syncPermissions(Permission::all());
                } else {
                    $role->givePermissionTo($value['permissions'] ?? []);
                }
            } catch (\Exception $exception) {
                $this->command->info($exception->getMessage());
            }
        }
    }
}
