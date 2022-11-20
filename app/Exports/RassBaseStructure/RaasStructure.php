<?php

namespace App\Exports\RassBaseStructure;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RaasStructure implements WithMultipleSheets
{
    use Exportable;
    public $nombreMunicipio;
    public $nombreParroquia;
    public function __construct($nombreMunicipio=null,$nombreParroquia=null)
    {
        $this->nombreMunicipio = $nombreMunicipio;
        $this->nombreParroquia = $nombreParroquia;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [
            new UbchSheet($this->nombreMunicipio,$this->nombreParroquia),
            new CommunitySheet($this->nombreMunicipio,$this->nombreParroquia),
            new StreetSheet($this->nombreMunicipio,$this->nombreParroquia)
        ];

        return $sheets;
    }
}
