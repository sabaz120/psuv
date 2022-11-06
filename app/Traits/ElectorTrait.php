<?php 
namespace App\Traits;

use App\Models\PersonalCaracterizacion;
use App\Models\{
    Elector,
    CentroVotacion,
    Estado,
    Municipio,
    Parroquia,
};
use Auth;
use Illuminate\Support\Facades\Http;

trait ElectorTrait
{

    public function verifyOrCreateEstado($nombre)
    {
        $estado = Estado::where('nombre', $nombre)->first();
        if ($estado) {
            return $estado;
        }

        $estado = new Estado();
        $estado->nombre = $nombre;
        $estado->save();

        return $estado;
    }

    public function verifyOrCreateMunicipio($nombre, $estado_id)
    {
        $municipio = Municipio::where('nombre', $nombre)->first();
        if ($municipio) {
            return $municipio;
        }

        $municipio = new Municipio();
        $municipio->nombre = $nombre;
        $municipio->estado_id = $estado_id;
        $municipio->save();

        return $municipio;
    }

    public function verifyOrCreateParroquia($nombre, $municipio_id)
    {
        $parroquia = Parroquia::where('nombre', $nombre)->first();
        if ($parroquia) {
            return $parroquia;
        }

        $parroquia = new Parroquia();
        $parroquia->nombre = $nombre;
        $parroquia->municipio_id = $municipio_id;
        $parroquia->save();

        return $parroquia;
    }

    public function verifyOrCreateCentro($nombre, $parroquia_id)
    {
        $centro = CentroVotacion::where('nombre', $nombre)->first();
        if ($centro) {
            return $centro;
        }

        $centro = new CentroVotacion();
        $centro->nombre = $nombre;
        $centro->parroquia_id = $parroquia_id;
        $centro->codigo = \Illuminate\Support\Str::random(13);
        $centro->direccion = "Alguna dirección";
        $centro->save();

        return $centro;
    }

    public function searchInCNE($cedula, $nacionalidad = 'V')
    {
        try {
     
            $response = Http::withHeaders([
                'Content-Type' => 'text/html; charset=UTF-8;',
                'Accept' => "*",
                'User-Agent' => "PostmanRuntime/7.29.0",
                'Host' => 'www.cne.gob.ve'
            ])->get('http://www.cne.gob.ve/web/registro_electoral/ce.php?nacionalidad='.$nacionalidad.'&cedula='.$cedula);
            $response = $response->body();
            $body = explode('<td', $response);
            //\Log::info($response);
            if(!isset($body)){
                return null;
            }

            $nameSanitize = $body['14'];
            $estadoSanitize = $body['16'];
            $municipioSanitize = $body['18'];
            $parroquiaSanitize = $body['20'];
            $centroSanitize = $body['22'];

            if (strpos($nameSanitize, 'ESTATUS')) {
                return null;
            }

            if (strpos($nameSanitize, 'planilla')) {
                return null;
            }

            $name = substr($nameSanitize, 17, strpos($nameSanitize, '</b>') - 17);

            if (strlen($name) > 0) {
                $estado = str_replace('EDO.', '', substr($estadoSanitize, 14, strpos($estadoSanitize, '</td>') - 14));
                $municipio = str_replace('MP.', '', substr($municipioSanitize, 18, strpos($municipioSanitize, '</td>') - 18));
                $parroquia = str_replace('PQ.', '', substr($parroquiaSanitize, 14, strpos($parroquiaSanitize, '</td>') - 14));
                $centro = substr($centroSanitize, 36, strpos($centroSanitize, '</font>') - 36);
                $estado = $this->verifyOrCreateEstado(trim($estado));
                $municipio = $this->verifyOrCreateMunicipio(trim($municipio), $estado->id);
                $parroquia = $this->verifyOrCreateParroquia(trim($parroquia), $municipio->id);
                $centro = $this->verifyOrCreateCentro(trim($centro), $parroquia->id);

                return [
                    'nombre_apellido' => $name,
                    'full_name' => $name,
                    'municipio_id' => $municipio->id,
                    'parroquia_id' => $parroquia->id,
                    'centro_votacion_id' => $centro->id,
                    'estado_id' => $estado->id,
                ];
            }

            return null;
        } catch (\Exception $e) {
            \Log::error($e);

            return false;
        }
    }

    function searchPersonalCaracterizacionOrElector($cedula, $municipio_id,$nacionalidad="V"){

        if($municipio_id == null){

            $elector = $this->searchPersonalCaracterizacionByCedula($cedula);
            if($elector){
                return ["success" => true, "elector" => $elector];
            }

            $elector = $this->searchElectorByCedula($cedula);
            if($elector){
                return ["success" => true, "elector" => $elector];
            }

            $elector = $this->searchInCNE($cedula, $nacionalidad);
            if ($elector) {
                return ['success' => true, 'elector' => $elector];
            }

            return ["success" => false, "msg" => "Elector no encontrado"];

        }else{

            $elector = $this->searchPersonalCaracterizacionByCedula($cedula);
            if($elector){
                
                if($elector->municipio_id != $municipio_id){
                    return ["success" => false, "msg" => "Éste Elector no pertenece a este municipio"];
                }
        
                return ["success" => true, "elector" => $elector];
            }

            $elector = $this->searchElectorByCedula($cedula);
    
            if($elector){

                if($elector->municipio_id != $municipio_id){
                    return ["success" => false, "msg" => "Éste Elector no pertenece a este municipio"];
                }
             
                return ["success" => true, "elector" => $elector];
            }

            return ["success" => false, "msg" => "Elector no encontrado"];

        }

    }

    function searchPersonalCaracterizacionByCedula($cedula){

        $elector = PersonalCaracterizacion::where("cedula", $cedula)->first();
        return $elector;

    }

    function searchElectorByCedula($cedula){

        $elector = Elector::where("cedula", $cedula)->first();
        return $elector;

    }

}