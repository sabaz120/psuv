<?php

namespace App\Exports\Comandos;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use App\Models\PersonalComandoRegional as Model;
class Regional implements FromView
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
      ]);
      $query->orderBy('comision_trabajo_id',"ASC");
      $query=$query->get();
      $view="exports.comandos.regional";
        return view($view, [
            'results' => $query
        ]);
    }
}
