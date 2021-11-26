<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use App\Models\Candidato as Model;
use App\Models\Eleccion;
class Candidato implements FromView
{
    public function __construct()
    {

    }

    public function view(): View
    {
      $query=Model::query();
      $query->with([
        "municipio",
      ]);
      $eleccion=Eleccion::orderBy('id','DESC')->first();
      $query->where('eleccion_id',$eleccion->id);
      $query->orderBy('id',"ASC");
      $query=$query->get();
      $view="exports.candidatos";
        return view($view, [
            'eleccion' => $eleccion,
            'results' => $query
        ]);
    }
}
