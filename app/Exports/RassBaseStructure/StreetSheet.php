<?php

namespace App\Exports\RassBaseStructure;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
class StreetSheet implements FromView,WithTitle,ShouldAutoSize
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
        return 'Calle';
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
        select mu.nombre municipio, 
        pa.nombre parroquia,
        codigo codigo_ubch, 
        cv.nombre as nombre_ubch, 
        co.nombre comunidad, 
        ca.nombre calle, 
        rol.nombre_rol roles,
        pc.cedula cedula_equipo_comunidad, (pc.primer_apellido||' '||primer_nombre) as equipo_comunidad, sexo genero,
        telefono_principal telefono_equipo_comunidad
          from public.calle ca
          join public.jefe_calle jca on jca.calle_id=ca.id
          join public.comunidad co on co.id=ca.comunidad_id
          join public.centro_votacion cv on cv.id=co.centro_votacion_id
          join public.roles_nivel_territorial rnt on rnt.id=jca.roles_nivel_territorial_id
          join public.roles_equipo_politico rol on rol.id=rnt.roles_equipo_politico_id
          join public.personal_caracterizacion pc on jca.personal_caraterizacion_id=pc.id
          join public.parroquia pa on pa.id=cv.parroquia_id
          join public.municipio mu on mu.id=pa.municipio_id
          where {$condition}
          order by mu.nombre,codigo_ubch,co.nombre, ca.nombre, roles_equipo_politico_id;
            "
        ));
        // dd($raw);
        return view('exports.raas.streetSheet', [
            'results' => $raw
        ]);
    }
}
