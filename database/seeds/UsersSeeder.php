<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Municipio;

class UsersSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$array = [
			[
				"name" => "Sabas",
				"last_name" => "Admin",
				"email" => "admin@psuv.com",
				"password" => bcrypt("admin123"),
				"role" => "admin"
			],
			[
				"name" => "ACOSTA",
				"last_name" => "ACOSTA",
				"email" => strtolower("ACOSTA@psuv.com"),
				"password" => bcrypt("acosta6745"),
				"municipio_id" => Municipio::where("nombre", "ACOSTA")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "BOLIVAR",
				"last_name" => "BOLIVAR",
				"email" => strtolower("BOLIVAR@psuv.com"),
				"password" => bcrypt("bolivar2589"),
				"municipio_id" => Municipio::where("nombre", "BOLIVAR")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "BUCHIVACOA",
				"last_name" => "BUCHIVACOA",
				"email" => strtolower("BUCHIVACOA@psuv.com"),
				"password" => bcrypt("buchivacoa2367"),
				"municipio_id" => Municipio::where("nombre", "BUCHIVACOA")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "CARIRUBANA",
				"last_name" => "CARIRUBANA",
				"email" => strtolower("CARIRUBANA@psuv.com"),
				"password" => bcrypt("patria2665"),
				"municipio_id" => Municipio::where("nombre", "CARIRUBANA")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "COLINA",
				"last_name" => "COLINA",
				"email" => strtolower("COLINA@psuv.com"),
				"password" => bcrypt("admin2459"),
				"municipio_id" => Municipio::where("nombre", "COLINA")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "DEMOCRACIA",
				"last_name" => "DEMOCRACIA",
				"email" => strtolower("DEMOCRACIA@psuv.com"),
				"password" => bcrypt("patria9067"),
				"municipio_id" => Municipio::where("nombre", "DEMOCRACIA")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "FALCON",
				"last_name" => "FALCON",
				"email" => strtolower("FALCON@psuv.com"),
				"password" => bcrypt("patria1289"),
				"municipio_id" => Municipio::where("nombre", "FALCON")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "FEDERACION",
				"last_name" => "FEDERACION",
				"email" => strtolower("FEDERACION@psuv.com"),
				"password" => bcrypt("federacion1290"),
				"municipio_id" => Municipio::where("nombre", "FEDERACION")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "MAUROA",
				"last_name" => "MAUROA",
				"email" => strtolower("MAUROA@psuv.com"),
				"password" => bcrypt("mauroa5692"),
				"municipio_id" => Municipio::where("nombre", "MAUROA")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "MIRANDA",
				"last_name" => "MIRANDA",
				"email" => strtolower("MIRANDA@psuv.com"),
				"password" => bcrypt("miranda9012"),
				"municipio_id" => Municipio::where("nombre", "MIRANDA")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "PETIT",
				"last_name" => "PETIT",
				"email" => strtolower("PETIT@psuv.com"),
				"password" => bcrypt("petit8723"),
				"municipio_id" => Municipio::where("nombre", "PETIT")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "SILVA",
				"last_name" => "SILVA",
				"email" => strtolower("SILVA@psuv.com"),
				"password" => bcrypt("silva0911"),
				"municipio_id" => Municipio::where("nombre", "SILVA")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "ZAMORA",
				"last_name" => "ZAMORA",
				"email" => strtolower("ZAMORA@psuv.com"),
				"password" => bcrypt("zamora6001"),
				"municipio_id" => Municipio::where("nombre", "ZAMORA")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "DABAJURO",
				"last_name" => "DABAJURO",
				"email" => strtolower("DABAJURO@psuv.com"),
				"password" => bcrypt("dabajuro2555"),
				"municipio_id" => Municipio::where("nombre", "DABAJURO")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "MONS. ITURRIZA",
				"last_name" => "MONS. ITURRIZA",
				"email" => strtolower("ITURRIZA@psuv.com"),
				"password" => bcrypt("iturriza6053"),
				"municipio_id" => Municipio::where("nombre", "MONS. ITURRIZA")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "LOS TAQUES",
				"last_name" => "LOS TAQUES",
				"email" => strtolower("LOSTAQUES@psuv.com"),
				"password" => bcrypt("admin3478"),
				"municipio_id" => Municipio::where("nombre", "LOS TAQUES")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "PIRITU",
				"last_name" => "PIRITU",
				"email" => strtolower("PIRITU@psuv.com"),
				"password" => bcrypt("piritu1167"),
				"municipio_id" => Municipio::where("nombre", "PIRITU")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "UNION",
				"last_name" => "UNION",
				"email" => strtolower("UNION@psuv.com"),
				"password" => bcrypt("union5493"),
				"municipio_id" => Municipio::where("nombre", "UNION")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "SAN FRANCISCO",
				"last_name" => "SAN FRANCISCO",
				"email" => strtolower("SANFRANCISCO@psuv.com"),
				"password" => bcrypt("francisco1549"),
				"municipio_id" => Municipio::where("nombre", "SAN FRANCISCO")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "JACURA",
				"last_name" => "JACURA",
				"email" => strtolower("JACURA@psuv.com"),
				"password" => bcrypt("jacura9247"),
				"municipio_id" => Municipio::where("nombre", "JACURA")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "CACIQUE MANAURE",
				"last_name" => "CACIQUE MANAURE",
				"email" => strtolower("MANAURE@psuv.com"),
				"password" => bcrypt("manure1290"),
				"municipio_id" => Municipio::where("nombre", "CACIQUE MANAURE")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "PALMASOLA",
				"last_name" => "PALMASOLA",
				"email" => strtolower("PALMASOLA@psuv.com"),
				"password" => bcrypt("palmasola7936"),
				"municipio_id" => Municipio::where("nombre", "PALMASOLA")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "SUCRE",
				"last_name" => "SUCRE",
				"email" => strtolower("SUCRE@psuv.com"),
				"password" => bcrypt("sucre1011"),
				"municipio_id" => Municipio::where("nombre", "SUCRE")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "URUMACO",
				"last_name" => "URUMACO",
				"email" => strtolower("URUMACO@psuv.com"),
				"password" => bcrypt("urumaco5284"),
				"municipio_id" => Municipio::where("nombre", "URUMACO")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "TOCOPERO",
				"last_name" => "TOCOPERO",
				"email" => strtolower("TOCOPERO@psuv.com"),
				"password" => bcrypt("tocopero6795"),
				"municipio_id" => Municipio::where("nombre", "TOCOPERO")->first()->id,
				"role" => "admin"
			],
			



























			[
				"name" => "ACOSTA",
				"last_name" => "ACOSTA",
				"email" => strtolower("ACOSTA_operador@psuv.com"),
				"password" => bcrypt("acosta674534"),
				"municipio_id" => Municipio::where("nombre", "ACOSTA")->first()->id,
				"role" => "operador"
			],
			[
				"name" => "BOLIVAR",
				"last_name" => "BOLIVAR",
				"email" => strtolower("BOLIVAR_operador@psuv.com"),
				"password" => bcrypt("bolivar258967"),
				"municipio_id" => Municipio::where("nombre", "BOLIVAR")->first()->id,
				"role" => "operador"
			],
			[
				"name" => "BUCHIVACOA",
				"last_name" => "BUCHIVACOA",
				"email" => strtolower("BUCHIVACOA_operador@psuv.com"),
				"password" => bcrypt("buchivacoa236742"),
				"municipio_id" => Municipio::where("nombre", "BUCHIVACOA")->first()->id,
				"role" => "operador"
			],
			[
				"name" => "CARIRUBANA",
				"last_name" => "CARIRUBANA",
				"email" => strtolower("CARIRUBANA_operador@psuv.com"),
				"password" => bcrypt("patria266545"),
				"municipio_id" => Municipio::where("nombre", "CARIRUBANA")->first()->id,
				"role" => "operador"
			],
			[
				"name" => "COLINA",
				"last_name" => "COLINA",
				"email" => strtolower("COLINA_operador@psuv.com"),
				"password" => bcrypt("admin245982"),
				"municipio_id" => Municipio::where("nombre", "COLINA")->first()->id,
				"role" => "operador"
			],
			[
				"name" => "DEMOCRACIA",
				"last_name" => "DEMOCRACIA",
				"email" => strtolower("DEMOCRACIA_operador@psuv.com"),
				"password" => bcrypt("patria906790"),
				"municipio_id" => Municipio::where("nombre", "DEMOCRACIA")->first()->id,
				"role" => "operador"
			],
			[
				"name" => "FALCON",
				"last_name" => "FALCON",
				"email" => strtolower("FALCON_operador@psuv.com"),
				"password" => bcrypt("patria128912"),
				"municipio_id" => Municipio::where("nombre", "FALCON")->first()->id,
				"role" => "operador"
			],
			[
				"name" => "FEDERACION",
				"last_name" => "FEDERACION",
				"email" => strtolower("FEDERACION_operador@psuv.com"),
				"password" => bcrypt("federacion129061"),
				"municipio_id" => Municipio::where("nombre", "FEDERACION")->first()->id,
				"role" => "operador"
			],
			[
				"name" => "MAUROA",
				"last_name" => "MAUROA",
				"email" => strtolower("MAUROA_operador@psuv.com"),
				"password" => bcrypt("mauroa569256"),
				"municipio_id" => Municipio::where("nombre", "MAUROA")->first()->id,
				"role" => "operador"
			],
			[
				"name" => "MIRANDA",
				"last_name" => "MIRANDA",
				"email" => strtolower("MIRANDA_operador@psuv.com"),
				"password" => bcrypt("admin784523"),
				"municipio_id" => Municipio::where("nombre", "MIRANDA")->first()->id,
				"role" => "operador"
			],
			[
				"name" => "PETIT",
				"last_name" => "PETIT",
				"email" => strtolower("PETIT_operador@psuv.com"),
				"password" => bcrypt("petit872382"),
				"municipio_id" => Municipio::where("nombre", "PETIT")->first()->id,
				"role" => "operador"
			],
			[
				"name" => "SILVA",
				"last_name" => "SILVA",
				"email" => strtolower("SILVA_operador@psuv.com"),
				"password" => bcrypt("silva091164"),
				"municipio_id" => Municipio::where("nombre", "SILVA")->first()->id,
				"role" => "operador"
			],
			[
				"name" => "ZAMORA",
				"last_name" => "ZAMORA",
				"email" => strtolower("ZAMORA_operador@psuv.com"),
				"password" => bcrypt("zamora600167"),
				"municipio_id" => Municipio::where("nombre", "ZAMORA")->first()->id,
				"role" => "operador"
			],
			[
				"name" => "DABAJURO",
				"last_name" => "DABAJURO",
				"email" => strtolower("DABAJURO_operador@psuv.com"),
				"password" => bcrypt("dabajuro255592"),
				"municipio_id" => Municipio::where("nombre", "DABAJURO")->first()->id,
				"role" => "operador"
			],
			[
				"name" => "MONS. ITURRIZA",
				"last_name" => "MONS. ITURRIZA",
				"email" => strtolower("ITURRIZA_operador@psuv.com"),
				"password" => bcrypt("iturriza605351"),
				"municipio_id" => Municipio::where("nombre", "MONS. ITURRIZA")->first()->id,
				"role" => "operador"
			],
			[
				"name" => "LOS TAQUES",
				"last_name" => "LOS TAQUES",
				"email" => strtolower("LOSTAQUES_operador@psuv.com"),
				"password" => bcrypt("admin347878"),
				"municipio_id" => Municipio::where("nombre", "LOS TAQUES")->first()->id,
				"role" => "operador"
			],
			[
				"name" => "PIRITU",
				"last_name" => "PIRITU",
				"email" => strtolower("PIRITU_operador@psuv.com"),
				"password" => bcrypt("piritu116738"),
				"municipio_id" => Municipio::where("nombre", "PIRITU")->first()->id,
				"role" => "operador"
			],
			[
				"name" => "UNION",
				"last_name" => "UNION",
				"email" => strtolower("UNION_operador@psuv.com"),
				"password" => bcrypt("union549337"),
				"municipio_id" => Municipio::where("nombre", "UNION")->first()->id,
				"role" => "operador"
			],
			[
				"name" => "SAN FRANCISCO",
				"last_name" => "SAN FRANCISCO",
				"email" => strtolower("SANFRANCISCO_operador@psuv.com"),
				"password" => bcrypt("francisco154985"),
				"municipio_id" => Municipio::where("nombre", "SAN FRANCISCO")->first()->id,
				"role" => "operador"
			],
			[
				"name" => "JACURA",
				"last_name" => "JACURA",
				"email" => strtolower("JACURA_operador@psuv.com"),
				"password" => bcrypt("jacura924750"),
				"municipio_id" => Municipio::where("nombre", "JACURA")->first()->id,
				"role" => "operador"
			],
			[
				"name" => "CACIQUE MANAURE",
				"last_name" => "CACIQUE MANAURE",
				"email" => strtolower("MANAURE_operador@psuv.com"),
				"password" => bcrypt("manure129047"),
				"municipio_id" => Municipio::where("nombre", "CACIQUE MANAURE")->first()->id,
				"role" => "operador"
			],
			[
				"name" => "PALMASOLA",
				"last_name" => "PALMASOLA",
				"email" => strtolower("PALMASOLA_operador@psuv.com"),
				"password" => bcrypt("palmasola793617"),
				"municipio_id" => Municipio::where("nombre", "PALMASOLA")->first()->id,
				"role" => "operador"
			],
			[
				"name" => "SUCRE",
				"last_name" => "SUCRE",
				"email" => strtolower("SUCRE_operador@psuv.com"),
				"password" => bcrypt("sucre101114"),
				"municipio_id" => Municipio::where("nombre", "SUCRE")->first()->id,
				"role" => "operador"
			],
			[
				"name" => "URUMACO",
				"last_name" => "URUMACO",
				"email" => strtolower("URUMACO_operador@psuv.com"),
				"password" => bcrypt("urumaco528487"),
				"municipio_id" => Municipio::where("nombre", "URUMACO")->first()->id,
				"role" => "operador"
			],
			[
				"name" => "TOCOPERO",
				"last_name" => "TOCOPERO",
				"email" => strtolower("TOCOPERO_operador@psuv.com"),
				"password" => bcrypt("tocopero679564"),
				"municipio_id" => Municipio::where("nombre", "TOCOPERO")->first()->id,
				"role" => "operador"
			],
		];
		foreach ($array as $arr) {
			$u = User::firstOrCreate([
				"email" => $arr['email']
			], collect($arr)->except('role')->all());
			if (!$u->hasRole($arr['role']))
				$u->assignRole($arr['role']);
		}
	}
}
