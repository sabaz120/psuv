<?php

use Illuminate\Database\Seeder;
use Database\Seeders\UsersSeeder;
use Database\Seeders\EstadoSeeder;
use Database\Seeders\MunicipioSeeder;
use Database\Seeders\ParroquiaSeeder;
use Database\Seeders\CentroVotacionSeeder;
use Database\Seeders\PartidoPoliticoSeeder;
use Database\Seeders\TipoMovilizacionSeeder;
use Database\Seeders\ElectorSeeder;
use Database\Seeders\ComunidadesSeeder;
use Database\Seeders\CalleSeeder;
use Database\Seeders\InstitutionSeeder;
use Database\Seeders\PermissionSeeder;
//use Database\Seeders\RoleSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $seedersArray = [
            // RoleSeeder::class,
            // UsersSeeder::class,
            // EstadoSeeder::class,
            //MunicipioSeeder::class,
            //ParroquiaSeeder::class,
            //CentroVotacionSeeder::class,
            // PartidoPoliticoSeeder::class,
            // TipoMovilizacionSeeder::class,
            //ElectorSeeder::class,
            //ComunidadesSeeder::class,
            //CalleSeeder::class,

            // InstitutionSeeder::class,
            PermissionSeeder::class,

        ];
        //call seeders
        foreach ($seedersArray as $seeder) $this->call($seeder);
    }
}
