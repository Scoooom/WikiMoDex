<?php
namespace Glitches;
class BuiltIn {
  
  public $baseMon, $name, $type1, $type2, $a1, $a2, $ha, $bst, $hp, $atk, $def, $spatk, $spdef, $spd = null;
  
  public static function getSmittyItems($form) {
	  $forms = file_get_contents(\basePath.'/smittyItems.json');
	  $form = strtolower($form);
	  $data = json_decode($forms);
	  if (isset($data->$form)) {
		  $return = $data->$form;
		  foreach ($return as &$item) {
			  $item = str_replace("SMITTY_",'',$item);
		  }
		  return $return;
	  } else return false;
  }
  
  public static function LoadCore($name) {
	//$name;
    $ret = self::Load();
	if (isset($ret->$name)) {
	  $ret = $ret->$name;
	  return $ret;
	} else return false;
  }
  
  public static function LoadSmitty($name) {
	//$name;
    $ret = self::SmittyLoad();
	if (isset($ret->$name)) {
	  $ret = $ret->$name;
	  return $ret;
	} else return false;
  }
  
  public static function LoadSmittyForm($name) {
	//$name;
    $ret = self::SmittyFormLoad();
	if (isset($ret->$name)) {
	  $ret = $ret->$name;
	  return $ret;
	} else return false;
  }
  
    public static function getNumType($id) {
      $id = strtolower($id);
	  switch ($id) {
	    case 'normal':
		  return 0;
			break;
	    case 'fighting':
			return 1;
			break;
		  case 'flying':
			return 2;
			break;
		  case 'poison':
			return 3;
			break;
		  case 'ground':
			return 4;
			break;
		  case 'rock':
			return 5;
			break;
		  case 'bug':
			return 6;
			break;
		  case 'ghost':
			return 7;
			break;
		  case 'steel':
			return 8;
			break;
		  case 'fire':
			return 9;
			break;
		  case 'water':
			return 10;
			break;
		  case 'grass':
			return 11;
			break;
		  case 'electric':
			return 12;
			break;
		  case 'psychic':
			return 13;
			break;
		  case 'ice':
			return 14;
			break;
		  case 'dragon':
			return 15;
			break;
		  case 'dark':
			return 16;
			break;
		  case 'fairy':
			return 17;
			break;
		}
  }
  
    public static function Load() {
		$data = file_get_contents(BUILTIN);
		return json_decode($data);
	}
	
	public static function SmittyLoad() {
		$data = file_get_contents(BUILTINS);
		return json_decode($data);
	}
	public static function SmittyFormLoad() {
		$data = file_get_contents(BUILTINF);
		return json_decode($data);
	}

}