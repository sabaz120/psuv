<?php

namespace App\Exports\Comandos;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use App\Models\PersonalComandoMunicipal as Model;
class Municipal implements FromView
{
    public function __construct()
    {

    }

    public function view(): View
    {
      $query=Model::query();
      $query->with([
        "personalCaracterizacion",
        "comisionTrabajo",
        "responsabilidadComando",
        "municipio"
      ]);
      $query->orderBy('comision_trabajo_id',"ASC");
      $query=$query->get();
      $view="exports.comandos.municipal";
        return view($view, [
            'results' => $query
        ]);
    }
}
