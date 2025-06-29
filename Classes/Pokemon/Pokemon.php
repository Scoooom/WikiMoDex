<?php
namespace Pokemon;
class Pokemon {
    public static $cacheFolder = '/var/www/void.scooom.xyz/cache/';
    
	public static function getAbility($ability,$array = false) {
		$cacheFile = self::$cacheFolder."/abilties/".$ability.".cache";
		if (file_exists($cacheFile)) {
			return json_decode(file_get_contents($cacheFile),$array);
		} else {
			$data = file_get_contents('https://pokeapi.co/api/v2/ability/'.$ability);
			$data = json_decode($data);
			

			$return = ["name"=>'Unknown','desc'=>'Unknown'];
			foreach($data->effect_entries as $lang) {
				if ($lang->language->name == "en") {
					$return["desc"] = $lang->short_effect;
					break;
				}
			}
			$return['name'] = ucwords(str_replace("-"," ",$data->name));
			$fp = fopen($cacheFile, 'w');
			fwrite($fp, json_encode($return));
			fclose($fp);
			return $return;
		}
	}
	
	/*
$ogMon = new \PokemonAPI\Pokemon($mon2->speciesId);
*/

	public static function getMon($mon,$array = false) {
		$cacheFile = self::$cacheFolder."/pokemon/".$mon.".cache";
		if (file_exists($cacheFile)) {
			return json_decode(file_get_contents($cacheFile),$array);
		} else {
			$data = file_get_contents('https://pokeapi.co/api/v2/pokemon/'.$mon);
			
			$fp = fopen($cacheFile, 'w');
			fwrite($fp, $data);
			fclose($fp);
			return json_decode($data);
		}
	}
}