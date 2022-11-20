<?php

namespace App\Exports\RassBaseStructure;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UbchSheet implements FromView,WithTitle,ShouldAutoSize
{
    public $nombreMunicipio;
    public $nombreParroquia;
    public function __construct($nombreMunicipio=null,$nombreParroquia=null)
    {
        $this->nombreMunicipio = $nombreMunicipio;
        $this->nombreParroquia = $nombreParroquia;
    }

    
    /**
     * @return string
     */
    public function title(): string
    {
        return 'UBCH';
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
        select mu.nombre municipio, pa.nombre parroquia,codigo codigo_ubch, cv.nombre as nombre_ubch, rol.nombre_rol roles,
        pc.cedula cedula_equipo_ubch, (pc.primer_apellido||' '||primer_nombre) as equipo_ubch, sexo genero, 
        telefono_principal telefono_equipo_ubch
        from public.centro_votacion cv
        join public.jefe_ubch ju on cv.id=ju.centro_votacion_id
        join public.roles_nivel_territorial rnt on rnt.id=ju.roles_nivel_territorial_id
        join public.roles_equipo_politico rol on rol.id=rnt.roles_equipo_politico_id
        join public.personal_caracterizacion pc on ju.personal_caracterizacion_id=pc.id
        join public.parroquia pa on pa.id=CV.parroquia_id
        join public.municipio mu on mu.id=pa.municipio_id
        where ju.deleted_at is null AND {$condition}
        order by mu.nombre, codigo_ubch,roles_equipo_politico_id;
            "
        ));
        // dd($raw);
        return view('exports.raas.ubchSheet', [
            'results' => $raw
        ]);
    }
}
