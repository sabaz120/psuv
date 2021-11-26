<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class RaasStructure implements FromView
{
    public $nombreMunicipio;
    public $nombreParroquia;
    public function __construct($nombreMunicipio=null,$nombreParroquia=null)
    {
        $this->nombreMunicipio = $nombreMunicipio;
        $this->nombreParroquia = $nombreParroquia;
    }

    public function view(): View
    {
        $condition="1=1";
        if($this->nombreMunicipio)
        $condition="mu.nombre='".$this->nombreMunicipio."'";
        if($this->nombreParroquia)
        $condition.=" AND pa.nombre='".$this->nombreParroquia."'";
        $raw=
        DB::select(DB::raw("
        SELECT cv.codigo as codigo_ubch,cv.nombre as nombre_ubch, pc.cedula, (pc.primer_apellido||' '||primer_nombre) as jefe_ubch,
        telefono_principal telefono1_jefe_ubch,
       co.nombre comunidad, 
       (select cedula from public.personal_caracterizacion where id=jc.personal_caracterizacion_id) cedula_jefe_comunidad,
       (select (primer_apellido||' '||primer_nombre)from public.personal_caracterizacion where id=jc.personal_caracterizacion_id) jefe_comunidad, 
       (select telefono_principal from public.personal_caracterizacion where id=jc.personal_caracterizacion_id) telefono1_jefe_comunidad,
       ca.nombre calle, 
       (select cedula from public.personal_caracterizacion where id=jca.personal_caraterizacion_id) cedula_jefe_calle,
       (select (primer_apellido||' '||primer_nombre)from public.personal_caracterizacion where id=jca.personal_caraterizacion_id) jefe_calle, 
       (select telefono_principal from public.personal_caracterizacion where id=jca.personal_caraterizacion_id) telefono1_jefe_calle,
       pa.nombre parroquia, mu.nombre municipio
         FROM public.centro_votacion cv
         join public.jefe_ubch ju on cv.id=ju.centro_votacion_id
         join public.personal_caracterizacion pc on ju.personal_caracterizacion_id=pc.id
         join public.jefe_comunidad jc on jc.jefe_ubch_id=ju.id
         join public.comunidad co on co.id=jc.comunidad_id
         join public.jefe_calle jca on jca.jefe_comunidad_id=jc.id
         join public.parroquia pa on pa.id=co.parroquia_id
         join public.calle ca on ca.id=jca.calle_id
         join public.municipio mu on mu.id=pa.municipio_id
         WHERE jc.deleted_at::text is null and ju.deleted_at::text is null AND {$condition}
         order by cv.id;
            "
        ));
        // dd($raw);
        return view('exports.raas.structure', [
            'results' => $raw
        ]);
    }
}
