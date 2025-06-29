<?php
namespace Discord2;
class Discord {
	public static $base_url =  "https://discord.com";
	public static $bot_token = null;
	private $userData = null;
	public static $client_id = '1379892720147103774';
	public static $secret_id = 'e5JwBcOYIQzvcsaUZ9utF40xNo6naLzQ';
	public static $scope = 'identify';
	public static $baseurl = 'https://discord.com';
	public static $client_secret = 'e5JwBcOYIQzvcsaUZ9utF40xNo6naLzQ';
	public static $redirect_url = 'https://void.scooom.xyz/login.html';
	public $raw = null;
	
	public static function getToken() {
		$token = explode(":",$_COOKIE['ScoomVoid'])[1];
		return $token;
	}
	
	private function gen_state() {
		$_SESSION['state'] = bin2hex(openssl_random_pseudo_bytes(12));
		return $_SESSION['state'];
	}
	
	public function url() {
		$state = $this->gen_state();
		return 'https://discordapp.com/oauth2/authorize?response_type=code&client_id=' . $this::$client_id . '&redirect_uri=' . $this::$redirect_url . '&scope=' . $this::$scope . "&state=" . $state;
	}
	
	public function __construct() {
	}
	public function init() {

		$code = $_GET['code'];
		$state = $_GET['state'];
		# Check if $state == $_SESSION['state'] to verify if the login is legit | CHECK THE FUNCTION get_state($state) FOR MORE INFORMATION.
		$url = $this::$baseurl."/api/oauth2/token";
		$data = array(
			"client_id" => $this::$client_id,
			"client_secret" => $this::$client_secret,
			"grant_type" => "authorization_code",
			"code" => $code,
			"redirect_uri" => $this::$redirect_url
		);
		$headers = array();
		$headers[] = 'Content-Type: application/x-www-form-urlencoded';
		$headers[] = 'Accept: application/json';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curl);
		curl_close($curl);
		$this->raw = $response;
		
	}
	
	 public static function get_user($token) {
		$url = self::$baseurl . "/api/users/@me";
		$headers = array('Content-Type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $token);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		$response = curl_exec($curl);
		curl_close($curl);
		$results = json_decode($response, true);
		return ["data"=>$results,"raw"=>$response,"headers"=>$headers];//$results;
		
	}
	
	public function check_state($state) {
		if ($state == $_SESSION['state']) {
			return true;
		} else {
			# The login is not valid, so you should probably redirect them back to home page
			return false;
		}
	}
	
	public static function setCookie($data) {
		$cookie = 'token::'.$data[0].'$$$username::'.$data[1];
		code($cookie);
        \setcookie("ScoomVoid", $cookie);
    }
}
