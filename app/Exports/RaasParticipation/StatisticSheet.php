<?php

namespace App\Exports\RaasParticipation;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
class StatisticSheet implements FromView,WithTitle,ShouldAutoSize
{
    public $type;
    public $municipio_id;
    public $parroquia_id;
    public $comunidad_id;
    public $calle_id;
    public function __construct($type,$municipio_id,$parroquia_id,$comunidad_id,$calle_id)
    {
        $this->type = $type;
        $this->municipio_id = $municipio_id;
        $this->parroquia_id = $parroquia_id;
        $this->comunidad_id = $comunidad_id;
        $this->calle_id = $calle_id;
    }

    
    /**
     * @return string
     */
    public function title(): string
    {
        return 'Estadística de Participación';
    }

    public function view(): View
    {
        $condition="1=1";
        if($this->municipio_id)
        $condition="mu.id='".$this->municipio_id."'";
        if($this->parroquia_id)
        $condition.=" AND pa.id='".$this->parroquia_id."'";
        if($this->comunidad_id)
        $condition.=" AND co.id='".$this->comunidad_id."'";
        if($this->calle_id)
        $condition.=" AND ca.id='".$this->calle_id."'";
        $raw=[];
        if ($this->type=="UBCH") {
            $raw=
                DB::select(DB::raw("
                    select mu.nombre municipio, pa.nombre parroquia,codigo codigo_ubch, cv.nombre as nombre_ubch, 
                    count(*) total_participacion
                    from public.centro_votacion cv
                    join public.participacion_ubch_roles pubch on pubch.centro_votacion_id=cv.id
                    join public.personal_caracterizacion pc on pc.id=pubch.personal_caracterizacion_id
                    join public.parroquia pa on pa.id=cv.parroquia_id
                    join public.municipio mu on mu.id=pa.municipio_id
                    where {$condition}
                    group by mu.nombre,pa.nombre,codigo,cv.nombre
                    order by mu.nombre,codigo_ubch;
                    "
                ));
        }else if($this->type=="Comunidad"){
            $raw=
                DB::select(DB::raw("
                select mu.nombre municipio, 
                pa.nombre parroquia,codigo codigo_ubch, cv.nombre as nombre_ubch, co.nombre comunidad, 
                count(*) total_participacion
                from public.comunidad co
                join public.participacion_comunidad_roles pcom on pcom.comunidad_id=co.id
                join public.personal_caracterizacion pc on pcom.personal_caracterizacion_id=pc.id
                join public.centro_votacion cv on cv.id=co.centro_votacion_id
                join public.parroquia pa on pa.id=cv.parroquia_id
                join public.municipio mu on mu.id=pa.municipio_id
                where {$condition}
                group by mu.nombre,pa.nombre,codigo,cv.nombre,co.nombre
                order by mu.nombre,codigo_ubch,co.nombre,co.nombre;
                "
            ));
        }else if($this->type=="Calle"){
            $raw=
            DB::select(DB::raw("
                select mu.nombre municipio, 
                pa.nombre parroquia,
                codigo codigo_ubch, 
                cv.nombre as nombre_ubch, 
                co.nombre comunidad, 
                ca.nombre calle, 
                count(*) total_participacion
                from public.calle ca
                join public.participacion_calle_roles pcal on pcal.calle_id=ca.id
                join public.personal_caracterizacion pc on pcal.personal_caracterizacion_id=pc.id
                join public.comunidad co on co.id=ca.comunidad_id
                join public.centro_votacion cv on cv.id=co.centro_votacion_id
                join public.parroquia pa on pa.id=cv.parroquia_id
                join public.municipio mu on mu.id=pa.municipio_id
                where {$condition}
                group by mu.nombre,pa.nombre,codigo,cv.nombre,co.nombre,ca.nombre
                order by mu.nombre,codigo_ubch,co.nombre,ca.nombre;
             "
            ));
        }
        // dd($raw);
        return view('exports.raas.participation.statisticsSheet', [
            'results' => $raw
        ]);
    }
}
