<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\REPImport;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\Elector;
use App\Models\CodigoCne;


class REPImportTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'repcne:importar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $index = 0;
        $users = (new FastExcel)->import(public_path('excel/rep.xlsx'), function ($row) {
            
            
            if(Elector::where("cedula", $row["CEDULA"])->where("nacionalidad", $row["NAC"])->count() == 0){

                $elector = new Elector;
                $elector->nacionalidad = $row["NAC"];
                $elector->cedula = $row["CEDULA"];
                $elector->primer_apellido = $row["APELLIDO 1"];
                $elector->segundo_apellido = $row["APELLIDO 2"];
                $elector->primer_nombre = $row["NOMBRE 1"];
                $elector->segundo_nombre = $row["NOMBRE 2"];
                $elector->sexo = $row["SEXO"];
                $elector->fecha_nacimiento = $row["F. NAC"];
                $elector->estado_id = $row["EDO"];
                $elector->municipio_id = $row["MUN"];
                $elector->parroquia_id = $this->findParroquia($row["MUN"], $row["PAQ"]);
                $elector->centro_votacion_id = $this->findCentroVotacion($row["CODIGO"]);
                $elector->save();
                
    
            }
            
    
            

        });
    }

    function findParroquia($municipio, $parroquia){

        $codigoParroquia = CodigoCne::where("cod_mcpo_cne", $municipio)->where("cod_pq_cne", $parroquia)->first();
        return $codigoParroquia->parroquia_id;

    }

    function findCentroVotacion($centroVotacion){

        $codigoParroquia = CodigoCne::where("cod_cv_cne", $centroVotacion)->first();
        return $codigoParroquia->centro_votacion_id;

    }
}
