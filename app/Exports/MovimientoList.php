<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class MovimientoList implements FromView
{
    public $nombreMunicipio;
    public $nombreParroquia;
    public $nombreCentroVotacion;
    public $nombreMovimiento;
    public $nombreMovilizacion;
    public $voto;
    public $personal;
    public function __construct(
        $nombreMunicipio=null,
        $nombreParroquia=null,
        $nombreCentroVotacion=null,
        $personal=null,
        $voto=null,
        $nombreMovimiento=null,
        $nombreMovilizacion=null
    )
    {
        $this->nombreMunicipio = $nombreMunicipio;
        $this->nombreParroquia = $nombreParroquia;
        $this->nombreCentroVotacion = $nombreCentroVotacion;
        $this->personal = $personal;
        $this->voto = $voto;
        $this->nombreMovimiento = $nombreMovimiento;
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
        if($this->nombreMovimiento)
        $condition.=" AND movimiento.nombre='".$this->nombreMovimiento."'";
        if($this->nombreMovilizacion)
        $condition.=" AND movilizacion.nombre='".$this->nombreMovilizacion."'";
        if($this->voto)
        $condition.=" AND ejercio_voto='".$this->voto."'";
        $view="exports.movimientos.listado";
        if($this->personal=="Trabajadores"){
            $raw=
            DB::select(DB::raw("SELECT movimiento.nombre movimiento, municipio.nombre municipio, parroquia.nombre parroquia, cv.codigo codigo_centro_votacion, cv.nombre centro_votacion,
            pc.cedula, (pc.primer_apellido||' '||pc.primer_nombre) personal, cargo.nombre cargo, area_atencion, ne.nombre_nivel nivel_estructura, pmov.direccion,
            (select ejercio_voto from votacion where elector.id=elector_id), movilizacion.nombre movilizacion
            FROM public.participacion_movimiento pmov
            join public.movimiento on pmov.movimiento_id=movimiento.id
            join public.personal_caracterizacion pc on pc.id=pmov.personal_caracterizacion_id
            join public.cargo on cargo.id=pmov.cargo_id
            join public.nivel_estructura ne on ne.id=nivel_estructura_id
            join public.municipio on municipio.id=pc.municipio_id
            join public.parroquia on parroquia.id=pc.parroquia_id
            join public.centro_votacion cv on cv.id=pc.centro_votacion_id
            join public.elector on elector.cedula=pc.cedula
            join public.movilizacion on movilizacion.id=pc.movilizacion_id
                where {$condition}
            order by municipio.nombre, parroquia.nombre, pc.cedula;"
            ));
        }else{
        $view="exports.movimientos.listado_familiares";
        //1xfamilia
            $raw=
            DB::select(DB::raw("SELECT movimiento.nombre movimiento, (select cedula from public.personal_caracterizacion where id=pmov.personal_caracterizacion_id) cedula_personal,
            (select primer_apellido||' '||primer_nombre from public.personal_caracterizacion where id=pmov.personal_caracterizacion_id) personal,
            (select telefono_principal from public.personal_caracterizacion where id=pmov.personal_caracterizacion_id) telefono_personal,
            pc.cedula, (pc.primer_apellido||' '||pc.primer_nombre) familiar, pc.telefono_principal,
            municipio.nombre municipio, parroquia.nombre parroquia, cv.codigo codigo_centro_votacion, cv.nombre centro_votacion,
            (select ejercio_voto from votacion where elector.id=elector_id), movilizacion.nombre
            FROM public.participacion_movimiento pmov
            join public.movimiento on pmov.movimiento_id=movimiento.id
            join public.familiar_movimiento fmov on fmov.participacion_movimiento_id=pmov.id
            join public.personal_caracterizacion pc on pc.id=fmov.personal_caracterizacion_id
            join public.municipio on municipio.id=pc.municipio_id
            join public.parroquia on parroquia.id=pc.parroquia_id
            join public.centro_votacion cv on cv.id=pc.centro_votacion_id
            join public.elector on elector.cedula=pc.cedula
            join public.movilizacion on movilizacion.id=pc.movilizacion_id
                where {$condition}
            order by municipio.nombre, parroquia.nombre, pc.cedula;"
            ));
        }//

        return view($view, [
            'results' => $raw
        ]);
    }
}
