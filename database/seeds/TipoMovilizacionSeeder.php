<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movilizacion;

class TipoMovilizacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $movilizaciones = [
            "SI",
            "NO"
        ];

        foreach($movilizaciones as $movilizacion){
            $this->store($movilizacion);
        }

    }

    function store($movilizacion){

        if(Movilizacion::where("nombre", $movilizacion)->count() == 0){
            
            $movilizacionModel = new Movilizacion;
            $movilizacionModel->nombre = $movilizacion;
            $movilizacionModel->save();

        }

    }
}
