<?php
namespace Glitches;
class Glitch extends \Database\Base {
	public static function TableName() { return "glitches"; }
	public static function getGlitch($name) {
		try {
			$glitch = self::LoadBy(["name"=>$name])[0];
			return $glitch;
		} catch  (\Exceptions\ItemNotFound $e) {
			return false;
		}
	}
	
	public function getStatBoostType() {
		
		$type = json_decode($this->json_data);
		return $type->stats->distributionType;
	}

    public function getRivals($returnString = false) {
		$data = json_decode($this->json_data);
        $rivals = $data->unlockConditions->rivalTrainerTypes;
		if ($returnString) {
			$string = \Rivals\Rival::getRival($rivals[0]);
			unset($rivals[0]);
			foreach($rivals as $rival) {
				$string .= ", ".\Rivals\Rival::getRival($rival);
			}
			return $string;
        } else {
			return $rivals;
		}
	}
	
	public function getStatBoostEn() {
		
		switch($this->getStatBoostType()) {
			case 'twoPriority':
			  return "Two Priority (1st: 40%; 2nd: 40%; 3rd: 20%)";
			  break;
			case 'even':
			  return "Even (All: 33%)";
			  break;
			case 'scaling':
			  return "Scaling (1st: 45%; 2nd: 35%; 3rd: 20%)";
			  break;
			case 'topPriority':
			  return "Top Priority (1st: 40%; 2nd: 30%; 3rd: 30%)";
			  break;
			default:
			  return "Unknown...";
			  break;
		}
	}
	
	public function getAbilityOne() {
		$data = json_decode($this->json_data);
		$ability = $data->abilities[0];
		return \Pokemon\Pokemon::getAbility($ability,1);

//		return $return;//["name"=>$ability->getName(),"desc"=>$ability->getDescription()];
	}

	public function getAbilityTwo() {
		$data = json_decode($this->json_data);
		$ability = $data->abilities[1];
		return \Pokemon\Pokemon::getAbility($ability,1);
	}
	
	public function getRating() {
		return \Ratings\Rating::getGlitchRating($this->id);
	}
		

	public function getAbilityHA() {
		$data = json_decode($this->json_data);
		$ability = $data->abilities[2];
		return \Pokemon\Pokemon::getAbility($ability,1);
	}
	
	public function adjustStats($stats,$boost) {
		$data = json_decode($this->json_data);
		$boosted = $stats;
		switch($this->getStatBoostType()) {
			case 'twoPriority':
			  $statOne = $data->stats->statsToBoost[0];
			  $boosted[$statOne]['value'] = round(($boost*0.4))+ $boosted[$statOne]['value'];
			  $boosted[$statOne]['percent'] = floor(($boosted[$statOne]['value'] / 255)*100);			  
			  $statTwo = $data->stats->statsToBoost[1];
			  $boosted[$statTwo]['value'] = round(($boost*0.4))+ $boosted[$statTwo]['value'];
			  $boosted[$statTwo]['percent'] = floor(($boosted[$statTwo]['value'] / 255)*100);
			  $statThree = $data->stats->statsToBoost[2];
			  $boosted[$statThree]['value'] = round(($boost*0.2))+ $boosted[$statThree]['value'];
			  $boosted[$statThree]['percent'] = floor(($boosted[$statThree]['value'] / 255)*100);
			  break;
			case 'even':
			  $statOne = $data->stats->statsToBoost[0];
			  $boosted[$statOne]['value'] = round(($boost*0.33))+ $boosted[$statOne]['value'];
			  $boosted[$statOne]['percent'] = floor(($boosted[$statOne]['value'] / 255)*100);			  
			  $statTwo = $data->stats->statsToBoost[1];
			  $boosted[$statTwo]['value'] = round(($boost*0.33))+ $boosted[$statTwo]['value'];
			  $boosted[$statTwo]['percent'] = floor(($boosted[$statTwo]['value'] / 255)*100);
			  $statThree = $data->stats->statsToBoost[2];
			  $boosted[$statThree]['value'] = round(($boost*0.33))+ $boosted[$statThree]['value'];
			  $boosted[$statThree]['percent'] = floor(($boosted[$statThree]['value'] / 255)*100);
			  break;
			case 'scaling':
			  $statOne = $data->stats->statsToBoost[0];
			  $boosted[$statOne]['value'] = round(($boost*0.45))+ $boosted[$statOne]['value'];
			  $boosted[$statOne]['percent'] = floor(($boosted[$statOne]['value'] / 255)*100);			  
			  $statTwo = $data->stats->statsToBoost[1];
			  $boosted[$statTwo]['value'] = round(($boost*0.35))+ $boosted[$statTwo]['value'];
			  $boosted[$statTwo]['percent'] = floor(($boosted[$statTwo]['value'] / 255)*100);
			  $statThree = $data->stats->statsToBoost[2];
			  $boosted[$statThree]['value'] = round(($boost*0.20))+ $boosted[$statThree]['value'];
			  $boosted[$statThree]['percent'] = floor(($boosted[$statThree]['value'] / 255)*100);
			  break;
			case 'topPriority':
			  $statOne = $data->stats->statsToBoost[0];
			  $boosted[$statOne]['value'] = round( ($boost*0.4))+ $boosted[$statOne]['value'];
			  $boosted[$statOne]['percent'] = floor(($boosted[$statOne]['value'] / 255)*100);			  
			  $statTwo = $data->stats->statsToBoost[1];
			  $boosted[$statTwo]['value'] = round(($boost*0.3))+ $boosted[$statTwo]['value'];
			  $boosted[$statTwo]['percent'] = floor(($boosted[$statTwo]['value'] / 255)*100);
			  $statThree = $data->stats->statsToBoost[2];
			  $boosted[$statThree]['value'] = round(($boost*0.3))+ $boosted[$statThree]['value'];
			  $boosted[$statThree]['percent'] = floor(($boosted[$statThree]['value'] / 255)*100);
			  break;
			default:
			  return "Unknown...";
			  break;
		}
		return $boosted;
		
	}
	
	public function getTypeOneEn() {
		$type = json_decode($this->json_data);
		return $this->getTypeEn($type->primaryType);
	}

	public function getTypeTwoEn() {
		$type = json_decode($this->json_data);
		return $this->getTypeEn($type->secondaryType);
	}
	
	public function getOGMon() {
		$mon2 = json_decode($this->json_data);
		return \Pokemon\Pokemon::getMon($mon2->speciesId);
	}

	
	public static function getTypeEn($id) {
		switch ($id) {
			case 0:
			  return "Normal";
			  break;
			case 1:
			  return "Fighting";
			  break;
			case 2:
			  return "Flying";
			  break;
			case 3:
			  return "Poison";
			  break;
			case 4:
			  return "Ground";
			  break;
			case 5:
			  return "Rock";
			  break;
			case 6:
			  return "Bug";
			  break;
			case 7:
			  return "Ghost";
			  break;
			case 8:
			  return "Steel";
			  break;
			case 9:
			  return "Fire";
			  break;
			case 10:
			  return "Water";
			  break;
			case 11:
			  return "Grass";
			  break;
			case 12:
			  return "Electric";
			  break;
			case 13:
			  return "Psychic";
			  break;
			case 14:
			  return "Ice";
			  break;
			case 15:
			  return "Dragon";
			  break;
			case 16:
			  return "Dark";
			  break;
			case 17:
			  return "Fairy";
			  break;
		}
	}
	
	public function getOGStats() {
		
		$ogStats = array();
		$og = $this->getOGMon();
		
		foreach($og->stats as $stat) {
			$indice = 0;
			$statName = $stat->stat->name;
			switch($statName) {
				case 'hp':
				  $indice = 0;
				  break;
				case 'attack':
				  $indice = 1;
				  break;
				case 'defense':
				  $indice = 2;
				  break;
				case 'special-attack':
				  $indice = 3;
				  break;
				case 'special-defense':
				  $indice = 4;
				  break;
				case 'speed':
				  $indice = 5;
				  break;
			}
			$ogStats[$indice] = ["value"=>$stat->base_stat,"percent"=>floor(($stat->base_stat / 255)*100)];
		}
		return $ogStats;
	}

	public function calculateTotalIncrease($bst) {
		$newTotal = $bst;
		$increase = 0;
		do {
			$currentIncrease = ($newTotal * 0.2);
			$newTotal += $currentIncrease;
			$increase += $currentIncrease;
		} while($newTotal < 500);
		return $increase;     
	}



}
