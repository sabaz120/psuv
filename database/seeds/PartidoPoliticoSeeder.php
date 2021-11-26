<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PartidoPolitico;

class PartidoPoliticoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $partidos = [
            "PSUV",
            "NO APLICA"
            //"Acción Democrática",
            //"Primero Justicia"
        ];

        foreach($partidos as $partido){
            $this->store($partido);
        }

    }

    function store($partido){

        if(PartidoPolitico::where("nombre", $partido)->count() == 0){

            $partidoPoliticoModel = new PartidoPolitico;
            $partidoPoliticoModel->nombre = $partido;
            $partidoPoliticoModel->save();

        }

    }
}
