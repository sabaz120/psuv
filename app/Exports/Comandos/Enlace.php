<?php

namespace App\Exports\Comandos;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use App\Models\PersonalEnlaceTerritorial as Model;
class Enlace implements FromView
{
    public function __construct()
    {

    }

    public function view(): View
    {
      $query=Model::query();
      $query->with([
        "personalCaracterizacion",
        "centroVotacion.parroquia.municipio"
      ]);
      $query->orderBy('centro_votacion_id',"ASC");
      $query=$query->get();
      $view="exports.comandos.enlace";
        return view($view, [
            'results' => $query
        ]);
    }
}
