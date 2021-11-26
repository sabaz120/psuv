<?php

namespace App\Helpers;
use Storage, File, Exception;

class LoadFileHelper {

    public static function storageFile($file, $title = 'file',$folder="images/") {
        
        $ext = $file->getClientOriginalExtension();
        $name_file = $folder."{$title}.{$ext}";
        Storage::disk('publicmedia')->put($name_file, File::get($file));
        return $name_file;
    }

    
    public static function storageFileBase64($file_request, $title = 'file',$folder="images/") {
            
        $ext = Self::getB64Extension($file_request['file']);
        $name_file = $folder."{$title}.{$ext}";
        $file = Self::getB64File($file_request['file']);
        
        Self::validFormat($ext);
        Storage::disk('publicmedia')->put($name_file, $file);
        return $name_file;
    }
    public static function getB64File($base64_file){
        $file_service_str = substr($base64_file, strpos($base64_file, ",")+1);
        $file = base64_decode($file_service_str);
        return $file;
    }

    public static function getB64Extension($base64_file, $full=null){
        
        $extension = explode(';base64' , $base64_file)[0];
        $extension = explode('/',$extension)[1] ?? NULL;
        return $extension;
    }

    public static function storageImg($file, $title = 'img') {

        $ext = $file->getClientOriginalExtension();
        $name_file = "{$title}.{$ext}";
        Storage::disk('public')->put($name_file, File::get($file));
        return $name_file;
    }

    public static function setTitle($id){
        $data = [
            'purchase_policy_file' => "politicas-de-compra",
            'academic_regulation_file' => "reglamento-academico" ,
            'public_log_file' => "registro-publico",
            'bank_statement_file' => 'estado-de-cuenta-bancaria',
            'utility_receipt_file' => "recibo" ,
            'contract_file' => "contrato-firmado",
            'instruction_letter_file' => "carta-instrucciones-bancarias",
        ];
        return $data[$id];
    }

}
