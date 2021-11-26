<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Elector;
use Rap2hpoutre\FastExcel\FastExcel;
use DB;

class EliminarElectoresDuplicados extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sanitize:elector';

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
        
        $this->eliminarDuplicados();

    }


    function eliminarDuplicados(){

        $duplicates = DB::table('elector')
                ->select('cedula', 'nacionalidad', DB::raw('COUNT(*) as "count"'))
                ->groupBy('cedula', 'nacionalidad')
                ->havingRaw('COUNT(*) > 1')
                ->get();

        $deletedAmount = 0;
        foreach($duplicates as $elector){


            $elector = Elector::where("cedula", $elector->cedula)->where("nacionalidad", $elector->nacionalidad)->orderBy('id', "desc")->first()->delete();
            $deletedAmount++;
        }


    }

    function encontrarCedulaFaltante(){

        
        $users = (new FastExcel)->import(public_path('excel/excel_comparar.xlsx'), function ($row) {
   

            if(Elector::where("cedula", $row["CEDULA"])->count() == 0){

            
                dump($row["CEDULA"]);
     
    
            }
            

        });

    }
}
