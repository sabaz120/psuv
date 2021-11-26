<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Parroquia;
use App\Models\Municipio;

class ParroquiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $parroquias = [
            ["municipio" => Municipio::where("nombre", "CARIRUBANA")->first()->id, "parroquia" => "CARIRUBANA"],
            ["municipio" => Municipio::where("nombre", "CARIRUBANA")->first()->id, "parroquia" => "Santa Ana"],
            ["municipio" => Municipio::where("nombre", "CARIRUBANA")->first()->id, "parroquia" => "Norte"],
            ["municipio" => Municipio::where("nombre", "CARIRUBANA")->first()->id, "parroquia" => "Punta Cardón"],

            ["municipio" => Municipio::where("nombre", "Los Taques")->first()->id, "parroquia" => "Judibana"],
            ["municipio" => Municipio::where("nombre", "Los Taques")->first()->id, "parroquia" => "Los Taques"],

            ["municipio" => Municipio::where("nombre", "Falcón")->first()->id, "parroquia" => "Pueblo Nuevo"],
            ["municipio" => Municipio::where("nombre", "Falcón")->first()->id, "parroquia" => "Adícora"],
            ["municipio" => Municipio::where("nombre", "Falcón")->first()->id, "parroquia" => "Baraived"],
            ["municipio" => Municipio::where("nombre", "Falcón")->first()->id, "parroquia" => "Buena Vista"],
            ["municipio" => Municipio::where("nombre", "Falcón")->first()->id, "parroquia" => "Jadacaquiva"],
            ["municipio" => Municipio::where("nombre", "Falcón")->first()->id, "parroquia" => "Moruy"],
            ["municipio" => Municipio::where("nombre", "Falcón")->first()->id, "parroquia" => "Adaure"],
            ["municipio" => Municipio::where("nombre", "Falcón")->first()->id, "parroquia" => "El Hato"],
            ["municipio" => Municipio::where("nombre", "Falcón")->first()->id, "parroquia" => "El Vínculo"],

            ["municipio" => Municipio::where("nombre", "Miranda")->first()->id, "parroquia" => "Guzmán Guillermo"],
            ["municipio" => Municipio::where("nombre", "Miranda")->first()->id, "parroquia" => "Mitare"],
            ["municipio" => Municipio::where("nombre", "Miranda")->first()->id, "parroquia" => "Río Seco"],
            ["municipio" => Municipio::where("nombre", "Miranda")->first()->id, "parroquia" => "Sabaneta"],
            ["municipio" => Municipio::where("nombre", "Miranda")->first()->id, "parroquia" => "San Antonio"],
            ["municipio" => Municipio::where("nombre", "Miranda")->first()->id, "parroquia" => "San Gabriel"],
            ["municipio" => Municipio::where("nombre", "Miranda")->first()->id, "parroquia" => "Santa Ana"]


        ];

        foreach($parroquias as $parroquia){

            //$this->store($parroquia);

        }

    }

    function store($parroquia){

        if(Parroquia::where("municipio_id", $parroquia["municipio"])->where("nombre", $parroquia["parroquia"])->count() == 0){

            $parroquiaModel = new Parroquia;
            $parroquiaModel->nombre = $parroquia["parroquia"];
            $parroquiaModel->municipio_id = $parroquia["municipio"];
            $parroquiaModel->save();

        }

    }
}
