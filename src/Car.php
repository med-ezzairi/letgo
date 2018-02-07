<?php 

/**
* Vehicle class to be used with LetGo
* Creating Cars, Getting Car's Data, Updating Car's Data
* 
* 
* @author Mohamed@v12
* @date   2018-02-01
*/

namespace LetGo;

class Car
{
	
	private $letgo;
	private $client;
	private $userId;

	function __construct($letgoInstance,$clientHttp,$userId='')
	{
		$this->letgo 	=$letgoInstance;
		$this->client 	=$clientHttp;
		$this->userId	=$userId;
	}

	/**
	* Check a Car creation status for a User
	* @param string Guid for a User
	* @param string Guid for a Car
	* @return mixed (array | boolean | string)
	* 
	*/
	public function getStatus($userId='',$carId) 
	{
		//'70940a18-e617-444c-b247-bc02d676d509'
		if (empty($this->userId) && empty($userId)) {
			return "the userId is mondatory";
		}

		if (!empty($userId)) {
			$this->userId=$userId;
		}

		$headers=array(
			'Content-Type'	=>'application/json',
			'Accept'		=>'json/letgo.providers.api.beta.v1',
			'Authorization'	=>$this->letgo->getToken()
		);

		$params=array(
			'headers'	=>$headers,
			//'body'		=>$fields,
			'timeout'	=> $this->letgo->timeout,
			'verify'	=>$this->letgo->verify,
		);

		try {
			$response=$this->client->get($this->letgo->API_URL."/users/{$this->userId}/cars/{$carId}/status",$params);
			//var_dump($response);exit;
			if ($response->getStatusCode()==200 && $response->getReasonPhrase()=='OK') {
				$data=$response->json();
				//echo $data;
				return $data;
			} else {
				return false;
			}
		} catch(\Exception $e) {
			echo __METHOD__.' get Ex:'.$e->getMessage();exit;
		}
	}

	/**
	* Create a new car for a User
	* @param string UserId (API should be able to create cars for that user)
	* @param array Car data
	* @return mixed (string | boolean)
	*/
	public function create($userId='',$fields)
	{
		if (empty($this->userId) && empty($userId)) {
			return "the userId is mondatory";
		}

		if (!empty($userId)) {
			$this->userId=$userId;
		}

		$required=array(
			"carId", // string|uuid
			"name",
			"description",
			"countryCode",
			"city",
			"zipCode",
			"latitude",
			"longitude",
			"priceAmount",
			"year",
			"images",
		);

		//var_dump($fields);exit;
		
		foreach($required as $key){
			if(!array_key_exists($key, $fields)){
				return "the {$key} is required but not found";
			}
		}

		if(strlen($fields['name'])>255 || strlen($fields['description'])>1500){
			return "The car name and description length is limited to 255 and 1500 respectively";
		}

		$headers=array(
			'Content-Type'	=>'application/x-www-form-urlencoded',
			//'Content-Type'	=>'multipart/form-data',//for files
			'Accept'		=>'json/letgo.providers.api.beta.v1',
			'Authorization'	=>$this->letgo->getToken()
		);

		/*
		//get files content for images
		foreach ($fields['images'] as $key=>$image) {
			$fields['images'][$key]=fopen($image, 'r');
		}
		*/

		$params=array(
			'headers'	=>$headers,
			'body'		=>$fields,
			'timeout'	=> $this->letgo->timeout,
			'verify'	=>$this->letgo->verify,
			//'debug' 	=> true,
		);

		try {
			$response=$this->client->post($this->letgo->API_URL."/users/{$this->userId}/cars",$params);
			var_dump($response);exit;
			if ($response->getStatusCode()==202 && $response->getReasonPhrase()=='Accepted') {
				return true;
			} else {
				return false;
			}
		} catch(\Exception $e) {
			echo __METHOD__.' get Ex:'.$e->getMessage();exit;
		}
	}

	/**
	* Gat a Car for a User
	* @param string Guid for the User
	* @param string Guid for the Car
	* @return mixed (array | boolean | string)
	*/
	public function get($userId='',$carId)
	{
		if (empty($this->userId) && empty($userId)) {
			return "the userId is mondatory";
		}

		if (!empty($userId)) {
			$this->userId=$userId;
		}

		$headers=array(
			'Content-Type'	=>'application/json',
			'Accept'		=>'json/letgo.providers.api.beta.v1',
			'Authorization'	=>$this->letgo->getToken()
		);

		$params=array(
			'headers'	=>$headers,
			'timeout'	=> $this->letgo->timeout,
			'verify'	=>$this->letgo->verify,
		);

		try {
			$response=$this->client->get(
				$this->letgo->API_URL."/users/{$this->userId}/cars/{$carId}",
				$params
			);
			//var_dump($response);exit;
			if ($response->getStatusCode()==200 && $response->getReasonPhrase()=='Accepted') {
				$data=$response->json();
				//echo $data;
				return data;
			} else {
				return false;
			}
		} catch(\Exception $e) {
			echo __METHOD__.' get Ex:'.$e->getMessage();exit;
		}
	}

	/**
	* Get a Listing for a user (specified by userId)
	* @param string (userId as guid)
	* @return mixed (array | boolean)
	* 
	*/
	public function all($userId='')
	{
		if (empty($this->userId) && empty($userId)) {
			return "the userId is mondatory";
		}

		if (!empty($userId)) {
			$this->userId=$userId;
		}

		$headers=array(
			'Content-Type'	=>'application/json',
			'Accept'		=>'json/letgo.providers.api.beta.v1',
			'Authorization'	=>$this->letgo->getToken()
		);

		$params=array(
			'headers'	=>$headers,
			//'body'		=>$fields,
			'timeout'	=> $this->letgo->timeout,
			'verify'	=>$this->letgo->verify,
		);

		try {
			$response=$this->client->get($this->letgo->API_URL."/users/{$this->userId}/cars",$params);
			//var_dump($response);exit;
			if ($response->getStatusCode()==200 && $response->getReasonPhrase()=='OK') {
				$data=$response->json();
				//echo $data;
				return $data;
			} else {
				return false;
			}
		} catch(\Exception $e) {
			echo __METHOD__.' get Ex:'.$e->getMessage();exit;
		}
	}

}

?>