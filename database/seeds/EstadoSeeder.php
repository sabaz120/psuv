<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Estado;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /*if(Estado::where("nombre", "Falcón")->count() == 0){
            $estado = new Estado;
            $estado->nombre = "Falcón";
            $estado->save();
        }*

    }
}
