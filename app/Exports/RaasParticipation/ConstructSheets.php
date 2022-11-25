<?php

namespace App\Exports\RaasParticipation;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ConstructSheets implements WithMultipleSheets
{
    use Exportable;
    public $type;
    public $municipio_id;
    public $parroquia_id;
    public $comunidad_id;
    public $calle_id;
    public function __construct($type,$municipio_id=null,$parroquia_id=null,$comunidad_id=null,$calle_id=null)
    {
        $this->type = $type;
        $this->municipio_id = $municipio_id;
        $this->parroquia_id = $parroquia_id;
        $this->comunidad_id = $comunidad_id;
        $this->calle_id = $calle_id;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [
            new ListSheet($this->type,$this->municipio_id,$this->parroquia_id,$this->comunidad_id,$this->calle_id),
            new StatisticSheet($this->type,$this->municipio_id,$this->parroquia_id,$this->comunidad_id,$this->calle_id),
        ];

        return $sheets;
    }
}
