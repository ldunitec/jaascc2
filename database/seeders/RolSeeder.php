<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

         $admin = Role::firstOrCreate(['name' => 'ADMIN']);
        $usuario = Role::firstOrCreate(['name' => 'USUARIO']);

      $permisos = [
        'admin.index',
      ];
      foreach ($permisos as $permiso) {
        $permi = Permission::firstOrCreate(['name' => $permiso]);
        $permi->syncRoles([$admin]);
        # code...
      }
    }
}
