<?php
namespace Users;
class Users extends \Database\Base {
	public static function TableName() { return "users"; }
    public static function getUser($username = "auto") {
		if ($username == "auto") {
			$username = \Discord2\User::getUsername();
		}
		try {
			return \Users\Users::LoadBy(["username"=>$username])[0];
		} catch  (\Exceptions\ItemNotFound $e) {
			return false;
		}
	}

	public function getLink() {
		return "<a href='/u:".$this->username.".html'>".$this->username."</a>";
	}
	
	public function getUploadCount() {
		try {
			$data = \Glitches\Glitch::LoadBy(["created_by"=>$this->id]);
			return count($data);
		} catch  (\Exceptions\ItemNotFound $e) {
			return "0";
		}
	}
	
	public function likesGlitch($id) {
		$user = \Discord2\User::getUser();
		$likes = false;
		try {
			$a = \Ratings\Likes::loadBy(["glitchID"=>$id,"userID"=>$user->user_id]);
			return true;
		} catch (\Exceptions\ItemNotFound $e) {
			return false;
		}
	}

	public function likesUser($id) {
		$user = \Discord2\User::getUser();
		try {
			$a = \Ratings\UserLikes::loadBy(["creatorID"=>$id,"userID"=>$user->user_id]);
			return true;
		} catch (\Exceptions\ItemNotFound $e) {
			return false;
		}
	}
	
	public function dislikesGlitch($id) {
		$user = \Discord2\User::getUser();
		$likes = false;
		try {
			$a = \Ratings\Dislikes::loadBy(["glitchID"=>$id,"userID"=>$user->user_id]);
			return true;
		} catch (\Exceptions\ItemNotFound $e) {
			return false;
		}
	}

	public static function createUser($username, $user_id, $avatar) {
		$user = new \Users\Users();
		$user->username = $username;
		$user->user_id = $user_id;
		$user->avatar_id = $avatar;
		$user->join_date = time();
		$user->last_login = time();
		$user->Save();
		return $user;
	}

	public function getAvatarURL() {
		if ($this->avatar_id == "default") {
			return 'https://dn721700.ca.archive.org/0/items/discordprofilepictures/discordblue.png';
		} else {
			return "https://cdn.discordapp.com/avatars/".$this->user_id."/".$this->avatar_id.".png";
		}
	}

}
