<?php
namespace Ratings;
class UserLikes extends \Database\Base {
	public static function TableName() { return "userLikes"; }
	public static function get($creatorID) {
		$count = 0;
		try {
			$data = self::LoadBy(['creatorID'=>$creatorID]);
			return count($data);
		} catch (\Exceptions\ItemNotFound $e) {
			return 0;
		}
	}
}