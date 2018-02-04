<?php 

namespace LetGo;


class User {

	private $letgo;
	private $client;
	public $Car;

	public function __construct($letgoInstance,$clientHttp)
	{
		$this->letgo=$letgoInstance;
		$this->client=$clientHttp;
		$this->Car=new Car($letgoInstance,$clientHttp);
	}


	/**
	* Get a specific user specified by user_id
	* 
	* @param string uuid
	* @return mixed user data
	* @author Mohamed@v12
	* @date   2018-02-01
	*/
	public function get($userId)
	{

		$headers=array(
			'Content-Type'	=>'application/json',
			'Accept'		=>'json/letgo.providers.api.beta.v1',
			'Authorization'	=>$this->letgo->getToken()
		);
		
		$params=array(
			'headers'=>$headers,
			'timeout'  => 10.0,
			'verify'	=>false,
		);

		try {
			$response=$this->client->get($this->letgo::API_URL.'/users/'.$userId,$params);
			if($response->getStatusCode()==200 && $response->getReasonPhrase()=='OK'){
				$data=$response->json();
				return $data;
			}
		}catch(\Exception $e) {
			echo __METHOD__.' get Ex:'.$e->getMessage();exit;
		}
	}


	/**
	* Create a User
	* @param array (user Data)
	* @return mixed
	*/
	public function create($fields=array())
	{
		$required=array(
			"userId",
			"name",
			"email",
			"password",
			"countryCode",
		);

		foreach($required as $key){
			if(!array_key_exists($key, $fields)){
				return "the {$key} is required but not found";
			}
		}
		

		$headers=array(
			'Content-Type'	=>'application/x-www-form-urlencoded',
			'Accept'		=>'json/letgo.providers.api.beta.v1',
			'Authorization'	=>$this->letgo->getToken()
		);

		$params=array(
			'headers'	=>$headers,
			'body'		=>$fields,
			'timeout'	=> 10.0,
			'verify'	=>false,
		);

		try {
			$response=$this->client->post($this->letgo::API_URL.'/users',$params);
			if($response->getStatusCode()==201 && $response->getReasonPhrase()=='Created'){
				return true;
			}else{
				return false;
			}
		}catch(\Exception $e) {
			echo __METHOD__.' get Ex:'.$e->getMessage();exit;
		}
	}

	/**
	* Update a user details 
	* @param string userId
	* @param array userData
	* @return mixed (string | boolean)
	*/
	public function update($userId=null,$fields=array())
	{
		$required=array(
			"name",
			"email",
			"countryCode",
		);

		foreach($required as $key){
			if(!array_key_exists($key, $fields)){
				return "the {$key} is required but not found";
			}
		}

		$headers=array(
			'Content-Type'	=>'application/x-www-form-urlencoded',
			//'Content-Type'	=>'application/json',
			'Accept'		=>'json/letgo.providers.api.beta.v1',
			'Authorization'	=>$this->letgo->getToken()
		);
		
		$params=array(
			'headers'	=>$headers,
			'body' 		=>$fields,
			'timeout'	=> 10.0,
			'verify'	=>false,
		);

		try {
			$response=$this->client->put($this->letgo::API_URL.'/users/'.$userId,$params);
			
			if($response->getStatusCode()==202 && $response->getReasonPhrase()=='Accepted'){
				return true;
			}else{
				return false;
			}
		}catch(\Exception $e) {
			echo __METHOD__.' get Ex:'.$e->getMessage();exit;
		}
	}

	/**
	* Get Aull users created by the API
	* 
	*/
	public function all()
	{
		$headers=array(
			'Content-Type'	=>'application/json',
			'Accept'		=>'json/letgo.providers.api.beta.v1',
			'Authorization'	=>$this->letgo->getToken()
		);
		
		$params=array(
			'headers'=>$headers,
			'timeout'  => 10.0,
			'verify'	=>false,
		);

		try {
			$response=$this->client->get($this->letgo::API_URL.'/users',$params);
			if($response->getStatusCode()==200 && $response->getReasonPhrase()=='OK'){
				$data=$response->json();
				return $data;
			}
		}catch(\Exception $e) {
			echo __METHOD__.' get Ex:'.$e->getMessage();exit;
		}
	}

}


?>
