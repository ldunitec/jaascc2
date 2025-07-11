<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Pago;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([RolSeeder::class]);
        
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('12345678')
        ])->assignRole('ADMIN');

       DB::table('clientes')->insert([
            'nombre' => 'cliente 1 Apellido 1',
            'dni' => '1023-9876-12534',
            'correo' => 'cliente1@correo.com',
            'telefono' => '99887766',
            'direccion' => 'casa de cliente 1',
            'activo' => '1',
        ]);
        DB::table('clientes')->insert([
            'nombre' => 'cliente 2 Apellido 2',
            'dni' => '1023-9876-15564',
            'correo' => 'cliente2@correo.com',
            'telefono' => '99887756',
            'direccion' => 'casa de cliente 2',
            'activo' => '1',
        ]);
      
    }
}
