<?php 

namespace LetGo;

use GuzzleHttp\Client;

Class LetGo {

	private $token='';

	private $client;

	public $User;

	const API_URL	='https://providers.stg.letgo.com';
	const API_KEY	='your_api_key';
	const API_SECRET='your_api_secret';

	private $debug=true;
	private $file='./curl_debug.txt';
	private $session_var_name='letgo_token';

	public function __construct(){
		$this->client=new Client();
		$this->User=new User($this,$this->client);
	}

	/**
	* Get stored token for the LetGo web API
	* if not found or it had been for more then 20 minutes
	* it will try to reconnect to get a new one
	* @return string (token)
	* @author Mohamed@v12
	* @date   2018-01-31
	*/
	public function getToken()
	{
		$now=new \DateTime();
		
		if(!isset($_SESSION['time'])) {
			$_SESSION[$this->session_var_name]='';
			$tokenTime=new \DateTime();
		}else{
			$tokenTime=\DateTime::createFromFormat('Y-m-d H:i:s',$_SESSION['time']);
			if(!$tokenTime){
				$tokenTime=new \DateTime();
			}
		}
		$tokenInterval=$tokenTime->diff($now);
		$duration=$tokenInterval->format('%i')+$tokenInterval->format('%h')*60;

		if(!isset($_SESSION[$this->session_var_name]) || empty($_SESSION[$this->session_var_name]) || $duration>=20 || $tokenInterval->invert==1){
			
			$token=$this->authenticate();

			if(isset($token['token']['authorization'])){
				$this->token=$token['token']['authorization'];
				$_SESSION[$this->session_var_name]=$this->token;
				$_SESSION['time']=date('Y-m-d H:i:s');
				return $this->token;
			}else{
				die('error getting authorization token');
			}
		}else{
			return $_SESSION[$this->session_var_name];
		}
	}

	/**
	* Try to authenticate against letgo using the API Key & SECRECT to get a token
	* @return mixed array (token)
	* @author Mohamed@v12
	* @date   2018-01-31
	*/
	public function authenticate()
	{
		$headers=array(
			'Content-Type'=>'application/x-www-form-urlencoded',
		);

		$fields=array(
			"key"	=>self::API_KEY,
			"secret"=>self::API_SECRET
		);

		$params=array(
			'headers'=>$headers,
			'body'	=>$fields,
			'timeout'  => 2.0,
			'verify'	=>false,
		);
		
		try {
			$response = $this->client->post(self::API_URL.'/auth',$params);
			if($response->getStatusCode()==200 && $response->getReasonPhrase()=='OK'){
				$token=$response->json();
				var_dump($token);
				return $token;
			}
		}catch (\Exception $e){
			echo __METHOD__.' get Ex:'.$e->getMessage();exit;
		}

	}


	public function getCarsAttributes()
	{

		$headers=array(
			'Content-Type'	=>'application/json',
			'Accept'		=>'json/letgo.providers.api.beta.v1',
			'Authorization'	=>$this->getToken()
		);
		$fields=array(
			'countryCode'=>'US',
		);

		$params=array(
			'headers'=>$headers,
			'query'	=>$fields,
			//'body'	=>$fields,
			'timeout'  => 10.0,
			'verify'	=>false,
		);

		try {
			$response=$this->client->get(self::API_URL.'/cars/attributes',$params);
			if($response->getStatusCode()==200 && $response->getReasonPhrase()=='OK'){
				$data=$response->json();
				return $data;
			}
		}catch(\Exception $e) {
			echo __METHOD__.' get Ex:'.$e->getMessage();exit;
		}
	}

	/**
	* Generate a GUID | UUID version 4
	* @return string uuid
	*
	*/
	public function getGuid()
	{
		$data=openssl_random_pseudo_bytes(16);
		assert(strlen($data) == 16);

		$data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}
}

?>