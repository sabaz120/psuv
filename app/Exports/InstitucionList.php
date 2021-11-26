<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class InstitucionList implements FromView
{
    public $nombreMunicipio;
    public $nombreParroquia;
    public $nombreCentroVotacion;
    public $nombreInstitucion;
    public $nombreMovilizacion;
    public $voto;
    public $personal;
    public function __construct(
        $nombreMunicipio=null,
        $nombreParroquia=null,
        $nombreCentroVotacion=null,
        $personal=null,
        $voto=null,
        $nombreInstitucion=null,
        $nombreMovilizacion=null
    )
    {
        $this->nombreMunicipio = $nombreMunicipio;
        $this->nombreParroquia = $nombreParroquia;
        $this->nombreCentroVotacion = $nombreCentroVotacion;
        $this->personal = $personal;
        $this->voto = $voto;
        $this->nombreInstitucion = $nombreInstitucion;
        $this->nombreMovilizacion = $nombreMovilizacion;
    }

    public function view(): View
    {
        $condition="1=1";
        if($this->nombreMunicipio)
        $condition="municipio.nombre='".$this->nombreMunicipio."'";
        if($this->nombreParroquia)
        $condition.=" AND parroquia.nombre='".$this->nombreParroquia."'";
        if($this->nombreCentroVotacion)
        $condition.=" AND cv.nombre='".$this->nombreCentroVotacion."'";
        if($this->nombreInstitucion)
        $condition.=" AND ins.nombre='".$this->nombreInstitucion."'";
        if($this->nombreMovilizacion)
        $condition.=" AND movilizacion.nombre='".$this->nombreMovilizacion."'";
        if($this->voto)
        $condition.=" AND ejercio_voto='".$this->voto."'";
        $view="exports.instituciones.listado";
        if($this->personal=="Trabajadores"){
            $raw=
            DB::select(DB::raw("SELECT ins.nombre institucion, pc.cedula cedula_trabajador, (pc.primer_apellido ||' '|| pc.primer_nombre) nombre_trabajador, pc.telefono_principal, cargo.nombre cargo, prin.direccion, 
            municipio.nombre municipio, parroquia.nombre parroquia, cv.codigo codigo_centro_votacion, cv.nombre centro_votacion, ejercio_voto, movilizacion.nombre movilizacion
              FROM public.institucion ins
              join public.participacion_institucion prin on prin.institucion_id=ins.id
              join public.personal_caracterizacion pc on pc.id=prin.personal_caracterizacion_id
              join public.cargo on cargo.id=prin.cargo_id
              join public.municipio on municipio.id=pc.municipio_id
              join public.parroquia on parroquia.id=pc.parroquia_id
              join public.centro_votacion cv on cv.id=pc.centro_votacion_id
              join public.elector on elector.cedula=pc.cedula
              LEFT join public.votacion on elector.id=votacion.elector_id
              join public.movilizacion on movilizacion.id=pc.movilizacion_id
                where {$condition}
              order by pc.cedula;"
            ));
        }else{
        $view="exports.instituciones.listado_familiares";
        //1xfamilia
            $raw=
            DB::select(DB::raw("SELECT ins.nombre institucion, (select cedula from public.personal_caracterizacion where id=prin.personal_caracterizacion_id) cedula_trabajador,
            (select primer_apellido||' '||primer_nombre from public.personal_caracterizacion where id=prin.personal_caracterizacion_id) trabajador,
            (select telefono_principal from public.personal_caracterizacion where id=prin.personal_caracterizacion_id) telefono_trabajador,
            pc.cedula cedula_familiar, (pc.primer_apellido||' '||pc.primer_nombre) familiar, pc.telefono_principal telefono_familiar, municipio.nombre municipio, parroquia.nombre parroquia,
            cv.codigo codigo_centro_votacion, cv.nombre centro_votacion, ejercio_voto, movilizacion.nombre movilizacion
              FROM public.institucion ins
              join public.participacion_institucion prin on prin.institucion_id=ins.id
              join public.familiar_trabajador ft on ft.participacion_institucion_id=prin.id
              join public.personal_caracterizacion pc on pc.id=ft.personal_caracterizacion_id
              join public.municipio on municipio.id=pc.municipio_id
              join public.parroquia on parroquia.id=pc.parroquia_id
              join public.centro_votacion cv on cv.id=pc.centro_votacion_id
              join public.elector on elector.cedula=pc.cedula
              LEFT join public.votacion on elector.id=votacion.elector_id
              join public.movilizacion on movilizacion.id=pc.movilizacion_id
                where {$condition}
              order by cedula_trabajador,pc.cedula;"
            ));
        }//

        return view($view, [
            'results' => $raw
        ]);
    }
}
