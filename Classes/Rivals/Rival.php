<?php
namespace Rivals;
class Rival {
  public static $rivals = '{"303":"Lance","350":"Blue","351":"Red","356":"Alder","357":"Iris","207":"Giovanni","81":"Cyrus","83":"Ghetsis","79":"Archie","77":"Maxie","85":"Lysandre","89":"Guzma","91":"Rose","200":"Brock","201":"Misty","202":"Lt Surge","206":"Blaine","205":"Sabrina","238":"Roxie","258":"Allister","220":"Norman","270":"Larry","354":"Wallace","87":"Lusamine","362":"Nemona","359":"Hau","353":"Steven"}';

  public static function getRival($id) {
    $return = json_decode(self::$rivals,1)[$id];
	if ($return == "") return "Unknown";
	return $return;
  }
}
