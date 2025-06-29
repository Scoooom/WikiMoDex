<?php
namespace Ratings;
class Dislikes extends \Database\Base {
	public static function TableName() { return "glitchDislikes"; }
	public static function get($monId) {
		$count = 0;
                return 0; // Disable dislikes for now	
		try {
			$data = self::LoadBy(['glitchID'=>$monId]);
			return count($data);
		} catch (\Exceptions\ItemNotFound $e) {
			return 0;
		}
	}
}
