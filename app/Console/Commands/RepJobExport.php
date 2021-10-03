<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ExportJob;
use App\Models\Elector;
use Illuminate\Support\Facades\Mail;
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

        $pendingJobs = ExportJob::where("status", "not started")->get();
  
        foreach($pendingJobs as $job){
           
            try{
                $pendingJob = ExportJob::find($job->id);
                $pendingJob->status = "processing";
                $pendingJob->update();

                if($pendingJob->entity == "municipios"){
                    $data = Elector::where("municipio_id", $pendingJob->entity_id)->with("municipio", "parroquia","centroVotacion")->get();
                }else{
                    $data = Elector::with("municipio", "parroquia","centroVotacion")->get();
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
}
