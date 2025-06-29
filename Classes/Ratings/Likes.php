<?php
namespace Ratings;
class Likes extends \Database\Base {
	public static function TableName() { return "glitchLikes"; }
	public static function get($monId) {
		$count = 0;
		try {
			$data = self::LoadBy(['glitchID'=>$monId]);
			return count($data);
		} catch (\Exceptions\ItemNotFound $e) {
			return 0;
		}
	}
}