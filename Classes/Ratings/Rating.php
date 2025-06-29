<?php
namespace Ratings;
class Rating {
	public static function getGlitchRating($glitch_id) {
		$likes = \Ratings\Likes::get($glitch_id);
		$dislikes = \Ratings\Dislikes::get($glitch_id);
		return ($likes - $dislikes);
	}
}