<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Municipio;
use App\Models\Estado;

class MunicipioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $municipios = [
            "CARIRUBANA",
            "LOS TAQUES",
            "FALCON",
            "MIRANDA"
        ];

        foreach($municipios as $municipio){

            //$this->store($municipio);

        }

    }

    function store($municipio){

        if(Municipio::where("nombre", $municipio)->count() == 0){
            $municipioModel = new Municipio;
            $municipioModel->nombre = $municipio;
            $municipioModel->estado_id = Estado::first()->id;
            $municipioModel->save();
        }

    }
}
