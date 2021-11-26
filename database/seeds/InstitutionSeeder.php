<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Institucion;
use App\Models\UserInstitucion;

class InstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $users = [
            ["institucion_id" => 12,	"name" => 'BANDA DEL ESTADO JUAN CRISOSTOMO FALCON'	    , 'last_name' => 'ABC' ,'email' =>'bandajuancfalcon@psuv.com'	        ,'password' => bcrypt('juancf1613')	    , "municipio_id" => 10],
            ["institucion_id" => 34,	"name" => 'CIRCUITO RADIAL SOMOS FALCON'	            , 'last_name' => 'ABC' ,'email' =>'cradialsomosfalcon@psuv.com'	        ,'password' => bcrypt('falcon6112')	    , "municipio_id" => 10],
            ["institucion_id" => 33,	"name" => 'CONCEJO LEGISLATIVO DEL ESTADO FALCON'	    , 'last_name' => 'ABC' ,'email' =>'concejolegislativofalcon@psuv.com'	,'password' => bcrypt('clfalcon32')	    , "municipio_id" => 10],
            ["institucion_id" => 25,	"name" => 'CONFALVI'    	                            , 'last_name' => 'ABC' ,'email' =>'confalvi@psuv.com'	                ,'password' => bcrypt('confal1156')	    , "municipio_id" => 10],
            ["institucion_id" => 5,	    "name" => 'CONTRALORIA GENERAL DEL ESTADO FALCON'	    , 'last_name' => 'ABC' ,'email' =>'contraloriafalcon@psuv.com'	        ,'password' => bcrypt('control311')	    , "municipio_id" => 10],
            ["institucion_id" => 11,	"name" => 'CORFALTUR'	                                , 'last_name' => 'ABC' ,'email' =>'corfaltur@psuv.com'	                ,'password' => bcrypt('faltur6515')	    , "municipio_id" => 10],
            ["institucion_id" => 27,	"name" => 'CORPOFALCON'	                                , 'last_name' => 'ABC' ,'email' =>'corpofalcon@psuv.com'	            ,'password' => bcrypt('corfalcon98')	, "municipio_id" => 10],
            ["institucion_id" => 15,	"name" => 'EDUCACION MIRANDA'	                        , 'last_name' => 'ABC' ,'email' =>'educacionmiranda@psuv.com'	        ,'password' => bcrypt('miranda913')	    , "municipio_id" => 10],
            ["institucion_id" => 7,	    "name" => 'EMPRESA JOSEFA CAMEJO'	                    , 'last_name' => 'ABC' ,'email' =>'empresajosefacamejo@psuv.com'	    ,'password' => bcrypt('camejo4175')	    , "municipio_id" => 10],
            ["institucion_id" => 21,	"name" => 'FUNDACION 171 FALCON'	                    , 'last_name' => 'ABC' ,'email' =>'fundacion171falcon@psuv.com'	        ,'password' => bcrypt('falcon1716')	    , "municipio_id" => 10],
            ["institucion_id" => 24,	"name" => 'FUNDACION JUNTOS POR FALCON'	                , 'last_name' => 'ABC' ,'email' =>'juntosfalcon@psuv.com'	            ,'password' => bcrypt('juntos6130')	    , "municipio_id" => 10],
            ["institucion_id" => 23,	"name" => 'FUNDACION PROPARAGUANA'	                    , 'last_name' => 'ABC' ,'email' =>'proparaguana@psuv.com'	            ,'password' => bcrypt('paraguana23')	, "municipio_id" => 10],
            ["institucion_id" => 8,	    "name" => 'FUNDACION REGIONAL EL NIÑO SIMON'	        , 'last_name' => 'ABC' ,'email' =>'fregionalninosimon@psuv.com'	        ,'password' => bcrypt('funsimon88')	    , "municipio_id" => 10],
            ["institucion_id" => 16,	"name" => 'FUNDACION TAIMA TAIMA'	                    , 'last_name' => 'ABC' ,'email' =>'taimataima@psuv.com'	                ,'password' => bcrypt('taima16taima')	, "municipio_id" => 10],
            ["institucion_id" => 20,	"name" => 'FUNDACOAMBI'	                                , 'last_name' => 'ABC' ,'email' =>'fundacoambi@psuv.com'	            ,'password' => bcrypt('coambi2021')	    , "municipio_id" => 10],
            ["institucion_id" => 9,	    "name" => 'FUNDACONSEJOS'	                            , 'last_name' => 'ABC' ,'email' =>'fundaconsejos@psuv.com'	            ,'password' => bcrypt('consejo109')	    , "municipio_id" => 10],
            ["institucion_id" => 10,	"name" => 'FUNDAMUTUAL'	                                , 'last_name' => 'ABC' ,'email' =>'fundamutual@psuv.com'	            ,'password' => bcrypt('fmutual1010')	, "municipio_id" => 10],
            ["institucion_id" => 26,	"name" => 'FUNDAREGION '	                            , 'last_name' => 'ABC' ,'email' =>'fundaregion@psuv.com'	            ,'password' => bcrypt('fundareg26')	    , "municipio_id" => 10],
            ["institucion_id" => 28,	"name" => 'FUNDAREGION PRODUCCION'	                    , 'last_name' => 'ABC' ,'email' =>'fundaregionproduccion@psuv.com'	    ,'password' => bcrypt('regprod280')	    , "municipio_id" => 10],
            ["institucion_id" => 30,	"name" => 'FUNDAREMIS'	                                , 'last_name' => 'ABC' ,'email' =>'fundaremis@psuv.com'	                ,'password' => bcrypt('funremis30')	    , "municipio_id" => 10],
            ["institucion_id" => 29,	"name" => 'FUNDATEATROS '	                            , 'last_name' => 'ABC' ,'email' =>'fundateatros@psuv.com'	            ,'password' => bcrypt('teatros290')	    , "municipio_id" => 10],
            ["institucion_id" => 18,	"name" => 'GOBERNACION DEL ESTADO FALCON'	            , 'last_name' => 'ABC' ,'email' =>'gobernacionestadofalcon@psuv.com'	,'password' => bcrypt('falcon1801')	    , "municipio_id" => 10],
            ["institucion_id" => 17,	"name" => 'HOTEL ESCUELA TODARIQUIBA'	                , 'last_name' => 'ABC' ,'email' =>'todariquiba@psuv.com'	            ,'password' => bcrypt('todariquiba17')	, "municipio_id" => 10],
            ["institucion_id" => 31,	"name" => 'INCUDEF' 	                                , 'last_name' => 'ABC' ,'email' =>'incudef@psuv.com'	                ,'password' => bcrypt('incudef315')	    , "municipio_id" => 10],
            ["institucion_id" => 22,	"name" => 'IREMUJER'	                                , 'last_name' => 'ABC' ,'email' =>'iremujer@psuv.com'	                ,'password' => bcrypt('iremujer22')	    , "municipio_id" => 10],
            ["institucion_id" => 6,	    "name" => 'ORQUESTA SINFONICA DE FALCON'	            , 'last_name' => 'ABC' ,'email' =>'sinfonicafalcon@psuv.com'	        ,'password' => bcrypt('sinfonica60')	, "municipio_id" => 10],
            ["institucion_id" => 4,	    "name" => 'PROCURADURIA'	                            , 'last_name' => 'ABC' ,'email' =>'procuraduria@psuv.com'	            ,'password' => bcrypt('insti0450')	    , "municipio_id" => 10],
            ["institucion_id" => 13,	"name" => 'PROTECCION CIVIL'	                        , 'last_name' => 'ABC' ,'email' =>'proteccioncivil@psuv.com'	        ,'password' => bcrypt('pr0t3c13')	    , "municipio_id" => 10],
            ["institucion_id" => 32,	"name" => 'ROPERO NEGRA MATEA'	                        , 'last_name' => 'ABC' ,'email' =>'roperonegramatea@psuv.com'	        ,'password' => bcrypt('negramatea39')	, "municipio_id" => 10],
            ["institucion_id" => 14,	"name" => 'SALUD MIRANDA'	                            , 'last_name' => 'ABC' ,'email' =>'saludmiranda@psuv.com'	            ,'password' => bcrypt('saludmir14')	    , "municipio_id" => 10],
            ["institucion_id" => 35,	"name" => 'TELEVISORA REGIONAL UNIFALCON'	            , 'last_name' => 'ABC' ,'email' =>'televisoraunifalcon@psuv.com'	    ,'password' => bcrypt('unifalcon35')	, "municipio_id" => 10],
            ["institucion_id" => 19,	"name" => 'VIAS DE FALCON'	                            , 'last_name' => 'ABC' ,'email' =>'viasdefalcon@psuv.com'	            ,'password' => bcrypt('viasfalcon19')	, "municipio_id" => 10],
            ["institucion_id" => 2,	    "name" => 'VTELCA'	                                    , 'last_name' => 'ABC' ,'email' =>'vtelca@psuv.com'	                    ,'password' => bcrypt('vtelca1342')	    , "municipio_id" => 4]
,
        ];



           foreach($users as $user){

                /*$userModel = User::updateOrCreate(
                    ["email" => $user["email"]],
                    [
                        "password" => $user["password"],
                        "name" => $user["name"],
                        "last_name" => $user["last_name"],
                        "municipio_id" => $user["municipio_id"] 
                    ]
                );
                //
                
                
                  
                if(UserInstitucion::where("user_id", $userModel->id)->where("institucion_id", $user["institucion_id"])->count() == 0){

                    $userInstitutionModel = new UserInstitucion;
                    $userInstitutionModel->user_id = $userModel->id;
                    $userInstitutionModel->institucion_id = $user["institucion_id"];
                    $userInstitutionModel->save();

                }*/
                

                    
                



           }

        


    }
}
