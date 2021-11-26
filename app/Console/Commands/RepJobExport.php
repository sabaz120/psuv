<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ExportJob;
use App\Models\CuadernilloExportJob;
use App\Models\Elector;
use App\Models\Eleccion;
use App\Models\Votacion;
use App\Models\JefeUbch;
use App\Models\CentroVotacion;
use App\Models\PersonalCaracterizacion;
use App\Models\DescargaCuadernillo;
use Illuminate\Support\Facades\Mail;
use PDF;
use Storage;

use Rap2hpoutre\FastExcel\FastExcel;

class RepJobExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rep:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export large REP';

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
        
        ini_set("memory_limit", -1);
        ini_set('max_execution_time', 0);

        $this->exportREP();
        $this->cuadernilloExport();
        

    }

    function exportREP(){
        $pendingJobs = ExportJob::where("status", "not started")->get();
  
        foreach($pendingJobs as $job){
           
            try{
                $pendingJob = ExportJob::find($job->id);
                $pendingJob->status = "processing";
                $pendingJob->update();

                if($pendingJob->entity == "municipios"){
                    $data = Elector::where("municipio_id", $pendingJob->entity_id)->with("municipio", "parroquia","centroVotacion")->get();
                }else{

                    $dataAmount = ceil(Elector::with("municipio", "parroquia","centroVotacion")->count() / 50000);
                    
                    $this->wholeDataBatchFile($dataAmount, $pendingJob->pid); 
                    $this->packFiles($pendingJob->pid);

                    $url = url($pendingJob->pid.".zip");
                    $this->sendEmail($url, $pendingJob->email);

                    $pendingJob->status = "finished";
                    $pendingJob->update();

                    return 0;
                   
                }
                $dataParts = 0;
                $dataAmount = $data->count();
               
                if($dataAmount > 50000){

                    $dataParts = ceil($data->count() / 50000);
                    $data = $data->chunk($dataAmount / $dataParts);

                }

                $this->batchFiles($data, $dataParts, $pendingJob->pid);
                $this->packFiles($pendingJob->pid);
                
                $pendingJob->status = "finished";
                $pendingJob->update();

                $url = url($pendingJob->pid.".zip");
                $this->sendEmail($url, $pendingJob->email);

            }catch(\Exception $e){

                $pendingJob = ExportJob::find($job->id);
                $pendingJob->status = "not started";
                $pendingJob->update();

                dd($e->getMessage());
                

            }

        }
    }

    function batchFiles($data, $parts, $id){

        if($parts > 0)
        {   
            $index = 0;
            foreach($data as $dat){

                $this->exportData($dat, $id, $index);
                sleep(10);
                $index++;
            }
        }

        else{
            $this->exportData($data);
        }

        

    }

    function exportData($dat, $id, $part){

        (new FastExcel($dat))->export(public_path()."/excel/".$id."REP".$part.".xlsx", function ($user) {
            return [
                'NACIONALIDAD' => $user->nacionalidad,
                'CEDULA' => $user->cedula,
                'PRIMER APELLIDO' => $user->primer_apellido,
                'SEGUNDO APELLIDO' => $user->segundo_apellido,
                'PRIMER NOMBRE' => $user->primer_nombre,
                'SEGUNDO NOMBRE' => $user->segundo_nombre,
                'SEXO' => $user->fn,
                'ESTADO' => "FALCÓN",
                'MUNICIPIO' => $user->municipio->nombre,
                'PARROQUIA' => $user->parroquia->nombre,
                'CENTRO VOTACION' => $user->centroVotacion->nombre,
            ];
        });

    }

    function packFiles($id){

        $files = Storage::disk('publicmedia')->allFiles("excel");
        foreach($files as $file){

            if(strpos($file, $id) > -1){
                //dump(public_path()."/".$file);
                
                exec("cp ".public_path()."/".$file." /".str_replace("excel/", "", $file));
                exec("zip -r /var/www/psuv/public/".$id.".zip /".str_replace("excel/", "", $file));
                exec("rm /".str_replace("excel/", "", $file));
            }

        }


    }

    function sendEmail($url, $email){

        $data = ["url" => $url];
        $to_email = $email;
        
        Mail::send("emails.sendREP", $data, function($message) use ($to_email) {
            
            $message->to($to_email, "Usuario")->subject("¡Tu archivo está listo!");
            $message->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));

        });

    }


    function wholeDataBatchFile($rounds, $pid){

        for($i = 0; $i < $rounds; $i++){

            $skip = $i * 50000;
            $take = 50000;

            $data = Elector::with("municipio", "parroquia","centroVotacion")->skip($skip)->take($take)->get();

            (new FastExcel($data))->export(public_path()."/excel/".$pid."REP".$i.".xlsx", function ($user) {
                return [
                    'NACIONALIDAD' => $user->nacionalidad,
                    'CEDULA' => $user->cedula,
                    'PRIMER APELLIDO' => $user->primer_apellido,
                    'SEGUNDO APELLIDO' => $user->segundo_apellido,
                    'PRIMER NOMBRE' => $user->primer_nombre,
                    'SEGUNDO NOMBRE' => $user->segundo_nombre,
                    'SEXO' => $user->fn,
                    'ESTADO' => "FALCÓN",
                    'MUNICIPIO' => $user->municipio->nombre,
                    'PARROQUIA' => $user->parroquia->nombre,
                    'CENTRO VOTACION' => $user->centroVotacion->nombre,
                ];
            });
            

            sleep(40);


        }

    }

    function cuadernilloExport(){

        $pendingJobs = CuadernilloExportJob::where("status", "not started")->get();
      
        foreach($pendingJobs as $job){

            try{

                $jobModel = CuadernilloExportJob::find($job->id);
                $jobModel->status = "started";
                $jobModel->update();

                $this->cargarElectoresEnVotacion($job->centro_votacion_id);

                $electores = Votacion::where("centro_votacion_id", $job->centro_votacion_id)->with("elector")->orderBy("codigo_cuadernillo")->get();
                $votaciones = $this->organizar($electores);
                $jefeUbch = JefeUbch::where("centro_votacion_id", $job->centro_votacion_id)->with("personalCaracterizacion")->first();
                $centroVotacion = CentroVotacion::with("parroquia", "parroquia.municipio")->find($job->centro_votacion_id);

                $splittedArray = array_chunk($votaciones, 1135);

                $part = 1;
                foreach($splittedArray as $splittedData){

                    if($part == 1){
                        $pdf = PDF::loadView('pdf.cuadernillo.cuadernillo', ["votaciones" => $splittedData, "jefeUbch" => $jefeUbch, "centroVotacion" => $centroVotacion])->save(public_path('cuadernillos/') . $job->pid.'.pdf');
                    }else{

                        $pdf = PDF::loadView('pdf.cuadernillo.continuacion', ["votaciones" => $splittedData, "jefeUbch" => $jefeUbch, "centroVotacion" => $centroVotacion])->save(public_path('cuadernillos/') . $job->pid.'parte-'.$part.'.pdf');

                    }

                    $part++;
                    sleep(5);
                }

                $files = Storage::disk('publicmedia')->allFiles("cuadernillos");
                foreach($files as $file){

                    if(strpos($file, $job->pid) > -1){
                       
                        exec("cp ".public_path()."/".$file." /".str_replace("cuadernillos/", "", $file));
                        exec("zip -r /var/www/psuv/public/cuadernillos/".$job->pid.".zip /".str_replace("cuadernillos/", "", $file));
                        exec("rm /".str_replace("cuadernillos/", "", $file));
                    }

                }

                $descargaCuadernillo = new DescargaCuadernillo;
                $descargaCuadernillo->eleccion_id = $eleccion = Eleccion::orderBy("id", "desc")->first()->id;
                $descargaCuadernillo->centro_votacion_id = $job->centro_votacion_id;
                $descargaCuadernillo->file = url('/').'/cuadernillos/'.$job->pid.".zip";
                $descargaCuadernillo->descargado = true;
                $descargaCuadernillo->save();

                $this->sendEmail(url('cuadernillos/'. $job->pid.'.zip'), $job->email);

                $jobModel = CuadernilloExportJob::find($job->id);
                $jobModel->status = "finished";
                $jobModel->update();

            }catch(\Exception $e){

                $jobModel = CuadernilloExportJob::find($job->id);
                $jobModel->status = "not started";
                $jobModel->update();

                dD($e->getMessage(), $e->getLine());

            }

        }

        

    }

    function cargarElectoresEnVotacion($centroVotacionId){

        if(Votacion::where("centro_votacion_id", $centroVotacionId)->count() > 0){
            return;
        }

        $eleccion = Eleccion::orderBy("id", "desc")->first();
        $electores = Elector::where("centro_votacion_id", $centroVotacionId)->orderBy("cedula", "asc")->get();

        $index = 1;
        foreach($electores as $elector){

            if(Votacion::where("elector_id", $elector->id)->count() == 0){

                $votacion = new Votacion;
                $votacion->codigo_cuadernillo = $index;
                $votacion->eleccion_id = $eleccion->id;
                $votacion->elector_id = $elector->id;
                $votacion->centro_votacion_id = $elector->centro_votacion_id;
                $votacion->save();

                $index++;
            }

        }

    }

    function organizar($electores){

        $votaciones = [];

        foreach($electores as $elector){

            $votaciones[] = [

                "codigo_cuadernillo" => $elector->codigo_cuadernillo,
                "cedula" => $elector->elector->cedula,
                "nombre_completo" => $elector->elector->primer_nombre." ".$elector->elector->primer_apellido,
                "caracterizacion" => PersonalCaracterizacion::where("nacionalidad", $elector->elector->nacionalidad)->where("cedula", $elector->elector->cedula)->count()

            ];

        }

        return $votaciones;

    }

}
