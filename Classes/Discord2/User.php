<?php
namespace Discord2;
class User {
	private $access_token, $username, $jsondata, $discrim, $user_id, $user_avatar = null;
	
	function __construct($access_token = null) {
		$user = new \Discord2\Discord();
		$url = $user->url();
		if (!isset($_COOKIE["ScoomVoid"])) {
			return false;
		}
		
		$results = Discord::get_user(self::getToken());
		code(print_r($results,1));
		$this->access_token = self::getToken();
		$this->username = $results['data']['username'];
		$this->jsondata = $results['raw'];
		$this->discrim = $results['data']['discriminator'];
		$this->user_id = $results['data']['id'];
		$this->user_avatar = $results['data']['avatar'];
		if (empty($this->user_avatar)) $this->user_avatar = "default";
	}
	
	public static function isLoggedIn() {

		$token = self::getToken();
		$dis = \Discord2\Discord::get_user($token);
		// echo "DATA: <br /><pre>".print_r($_COOKIE,1)."</pre>";
		// echo "DATA: <br /><pre>".print_r($dis,1)."</pre>";
		$data = $dis['data'];
		if (@$data['message'] == "401: Unauthorized") {
                        self::destroyCookie();
			return false;
		} else {
			if (self::getUsername() != $data['username']) {
			  self::destroyCookie();
			  return false;
			} else {
  			 return true;
			}
		}
	}
	
	public function __get($get) {
		if (isset($this->$get)) {
			return $this->$get;
		} else {
			return false;
		}
	}
	
	public static function getUser() {
		if (self::isLoggedIn()) {
			return new User(self::getToken());
		} else {
			return false;
		}
	}
	
	public function getAvatarURL() {
		if ($this->user_avatar	 == "default") {
			return 'https://dn721700.ca.archive.org/0/items/discordprofilepictures/discordblue.png';
		} else {
			return "https://cdn.discordapp.com/avatars/".$this->user_id."/".$this->avatar_id.".png";
		}
	}		
	
	public static function getToken() {
		$token = explode("::",explode('$$$',@$_COOKIE['ScoomVoid'])[0])[1];
		return $token;
	}
	
	public static function getUsername() {
		$token = explode("::",explode('$$$',@$_COOKIE['ScoomVoid'])[1])[1];
		return $token;
	}
	
	public static function destroyCookie() {
		unset($_COOKIE['ScoomVoid']);
		\setcookie('ScoomVoid', '', time() - 3600, '/'); // empty value and old timestamp
	}

}
