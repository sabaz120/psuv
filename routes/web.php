<?php

use App\Http\Controllers\Api\Auth\AuthenticationController;
use App\Http\Controllers\RAAS\UBCHController;
use App\Http\Controllers\CodigoCNEController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Listados\REPController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\RAAS\{
    UBCHController as UBCHAPIController,
    JefeComunidadController as JefeComunidadAPIController
};

use App\Http\Controllers\Api\{
    ElectorController
};

use App\Http\Controllers\Api\Votaciones\CuadernilloController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('login');
})->name("login")->middleware("guest");

 Route::get('/home', function () {
    return view("dashboard");
 })->name("home")->middleware("auth");

 Route::view('/listado/rep', 'Listados.rep.rep')->name("listados.rep");
 Route::post("/listado/amount/rep", [REPController::class,'getMunicipioAmount']);
 Route::get("/listado/importar/rep", [REPController::class,'download']);
 Route::post("/listado/rep/store-export-job", [REPController::class,'storeExportJob']);

 Route::get('/email-verify/{token}', [AuthenticationController::class,'verifyEmailToken']);

Route::post("/login", [AuthController::class, "login"]);
Route::get("/logout", [AuthController::class, "logout"]);

Route::post("raas/ubch/search-by-cedula", [UBCHAPIController::class, "searchByCedula"]);

Route::post("raas/jefe-comunidad/search-by-cedula", [JefeComunidadAPIController::class, "searchByCedula"]);
Route::post("raas/jefe-comunidad/search-by-cedula-field", [JefeComunidadAPIController::class, "searchByCedulaField"]);

Route::get("elector/search-by-cedula", [ElectorController::class, "searchByCedula"])->name('api.elector.search.by.cedula');

Route::view("/metas-ubch", "metasUBCH.metas");

Route::view("/reporte-carga", "reporteCarga.reporte");

Route::view("/listado-jefes", "reports.listados.listado");

//Auth routes
Route::group(['middleware' => ['auth']], function() {

    //Admin modules
    Route::group(['prefix' => 'admin'], function () {

        Route::get('/calles', function () {
            return view('admin.calles.view');
        });

        Route::get('/comunidad', function () {
            return view('comunidad.index');
        });

        Route::get('/usuarios', function () {
            return view('admin.users.view');
        });

        Route::get('/roles', function () {
            return view('admin.roles.view');
        });

        Route::view('/candidatos', 'candidatos.view');

        Route::view('/centros_votacion', 'centrosVotacion.view');


    
    });

    //Raas modules
    Route::group(['prefix' => 'raas'], function () {

        Route::view('ubch', 'RAAS.jefeUbch.ubch')->name("raas.ubch");
    
        Route::view('jefeComunidad', 'RAAS.jefeComunidad.jefeComunidad')->name("raas.jefe-comunidad");
       
        Route::view('jefeCalle', 'RAAS.jefeCalle.view')->name("raas.jefe-calle");
       
        Route::view('jefeFamilia', 'RAAS.jefeFamilia.view')->name("raas.jefe-familia");
    
        Route::view('reportes/estructura', 'reports.raas.structure');
        Route::view('reportes/movilizacion_electores', 'reports.raas.voter_mobilization');
    
    });

    Route::group(['prefix' => 'instituciones'], function () {

        Route::view('trabajadores', 'instituciones.trabajadores.view');
        Route::view('listado', 'instituciones.listado.view');
        
    });

    Route::group(['prefix' => 'movimientos'], function () {

        Route::view('trabajadores', 'movimientos.trabajadores');
        Route::view('listado', 'movimientos.listado.view');
        
    });

    Route::get("cuadernillo", function(){

        return view("votaciones.cuadernillo.index");
    
    })->name("cuadernillo");

    Route::get("cuadernillo/generate-pdf/{centro_votacion_id}", [CuadernilloController::class, "generatePDF"])->name("cuadernillo.pdf");
    Route::get("cuadernillo/generate-pdf-ubch", [CuadernilloController::class, "generateUBCHPDF"])->name("cuadernillo.ubch.pdf");

    Route::get("gestionar-votos", function(){

        return view("votaciones.gestionarVotos.index");
    
    })->name("gestionar-voto");

    Route::view("sala-tecnica/asociar-personal", "salaTecnica.asociarPersonal.index")->name("asociar-personal");

    Route::group(['prefix' => 'comandos'], function () {

        Route::view('regionales', 'comandos.regional.view');
        Route::view('municipales', 'comandos.municipal.view');
        Route::view('parroquiales', 'comandos.parroquial.view');
        Route::view('enlaces', 'comandos.enlace.view');
        
    });

    Route::view("votaciones/centro-votaciones", "votaciones.centroVotacion.index")->name("votaciones.centro-votacion");
    Route::get("votaciones/centro-votaciones/voto/{centro_votacion_id}", function($centro_votacion_id){

        $centro_votacion = App\Models\CentroVotacion::find($centro_votacion_id);
        return view("votaciones.centroVotacion.voto.index", ["centro_votacion" => $centro_votacion]);

    })->name("votaciones.centro-votacion.voto");

    Route::view("votaciones/gestionar-participacion", "votaciones.gestionarParticipacion.index")->name("votaciones.gestionar-participacion");
    Route::get("votaciones/gestionar-participacion/mesa/{centro_votacion_id}", function($centro_votacion_id){

        return view("votaciones.gestionarParticipacion.mesa.index", ["centro_votacion_id" => $centro_votacion_id]);

    })->name("votaciones.gestionar-participacion.mesa");

    Route::view("cierre-mesa/candidatos", "cierreMesa.candidatos.index")->name('cierre-mesa.candidatos');
    Route::view("cierre-mesa/candidatos/cierre", "cierreMesa.candidatos.cierreCandidatos.index")->name('cierre-mesa.candidatos.cierre');

    Route::view("cierre-mesa/partidos", "cierreMesa.partidos.index")->name('cierre-mesa.partidos');
    Route::view("cierre-mesa/partidos/cierre", "cierreMesa.partidos.cierrePartido.index")->name('cierre-mesa.partidos.cierre');

    Route::view("estadistica/cierre-mesa", "reports.cierreCandidato.view");
    Route::view("estadistica/cierre-mesa-partidos", "reports.cierrePartido.view");

});




