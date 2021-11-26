<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Parroquia;
use App\Models\Comunidad;

class ComunidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $comunidades = [
            ["parroquia_id" => Parroquia::where("nombre", "Carirubana")->first()->id, "nombre" => "Comunidad 1"],
            ["parroquia_id" => Parroquia::where("nombre", "Carirubana")->first()->id, "nombre" => "Comunidad 2"],
        ];

        foreach($comunidades as $comunidad){

            //$this->store($comunidad);

        }

    }

    function store($comunidad){

        if(Comunidad::where("parroquia_id", $comunidad["parroquia_id"])->where("nombre", $comunidad["nombre"])->count() == 0){

            Comunidad::create($comunidad);

        }

    }
}
