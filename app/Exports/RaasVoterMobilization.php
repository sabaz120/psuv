<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class RaasVoterMobilization implements FromView
{
    public $nombreMunicipio;
    public $nombreParroquia;
    public $nombreCentroVotacion;
    public function __construct($nombreMunicipio=null,$nombreParroquia=null,$nombreCentroVotacion=null)
    {
        $this->nombreMunicipio = $nombreMunicipio;
        $this->nombreParroquia = $nombreParroquia;
        $this->nombreCentroVotacion = $nombreCentroVotacion;
    }

    public function view(): View
    {
        $condition="1=1";
        if($this->nombreMunicipio)
        $condition="mu.nombre='".$this->nombreMunicipio."'";
        if($this->nombreParroquia)
        $condition.=" AND pa.nombre='".$this->nombreParroquia."'";
        if($this->nombreCentroVotacion)
        $condition.=" AND cv.nombre='".$this->nombreCentroVotacion."'";
        $raw=
        DB::select(DB::raw("
        SELECT mu.nombre municipio, pa.nombre, co.nombre comunidad, ca.nombre calle, (select cedula from public.personal_caracterizacion where id=jca.personal_caraterizacion_id) cedula_jefe_calle,
        (select primer_apellido||' '||primer_nombre from public.personal_caracterizacion where id=jca.personal_caraterizacion_id) nombre_jefe_calle,
        (select cedula from public.personal_caracterizacion where id=jf.personal_caraterizacion_id) cedula_jefe_familia,
        (select primer_apellido||' '||primer_nombre from public.personal_caracterizacion where id=jf.personal_caraterizacion_id) nombre_jefe_familia,
        cedula cedula_familiar, primer_apellido||' '||primer_nombre nombre_familiar, telefono_principal, cv.nombre centro_votacion
        FROM public.personal_caracterizacion as pc
        JOIN public.jefe_familia jf ON pc.jefe_familia_id=jf.id
        join public.jefe_calle jca on jca.id=jf.jefe_calle_id
        join public.calle ca on ca.id=jca.calle_id
        join public.comunidad co on co.id=ca.comunidad_id
        join public.centro_votacion cv on pc.centro_votacion_id=cv.id
        join public.parroquia pa on pa.id=co.parroquia_id
        join public.municipio mu on mu.id=pa.municipio_id
         WHERE {$condition}
         order by mu.nombre,co.nombre, ca.nombre, cedula_jefe_familia;
            "
        ));
        // dd($raw);
        return view('exports.raas.voter_mobilization', [
            'results' => $raw
        ]);
    }
}
