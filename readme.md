## LetGo-PHP-Client

This is a package to be used with Guzzele v5.3.0 (because of php 5.4) to consume LetGo REST API. so please use it on your own.

* LetGo API documentation [here](https://hackmd.io/MwEwbAnBYOwCwFoCGYBMSFwBwgKbIDMsBGBAYywAZgAjGYy1XJEIA===)

* Guzzele documentation [here](http://docs.guzzlephp.org/en/5.3/overview.html)
* 
#### Install


```
composer require med-ezzairi/letgo "1.0.*"
```

#### Config

```
$config=array(
    //this is for dev
	'API_URL'	=>'https://providers.stg.letgo.com',
    //API key provided by letgo.com
	'API_KEY'	=>'your_api_key',
    //api secret is also provided by them
	'API_SECRET'=>'your_api_secret',
    //to avoid ssl host verificatio
	'verify'	=>false,
    //connection timeout
	'timeout'	=>10.0
);
```

#### Instance

```
//We are using psr-4 autoloading
use LetGo\LetGo;
$letgo=new LetGo($config);
```

#### Authentication

```
/**
* letgo API uses a JWT Bearer which expires in 20 minutes
* so, the $letgo object will check every call, 
* if the token have been expired it will renew it auto
* 
*/
$token=$letgo->getToken();
```

#### Generate a GUID v4

```
/**
* letgo API requires guid v4 for each ressource your creating
* so, I added a function to let you get a random guid v4
* it uses open_ssl, please check the code if you need deep details
* 
*/
$guid=$letgo->getGuid();
```

#### Get all cars attributes

```
/**
* I work with v12software.com, which is a Dealership Management System 
* So, posting cars on letgo requires to specify makeId & modelId
* for each car, and those are guids, you can get them easily
* 
*/

$carsAttribs=$letgo->getCarsAttributes();

var_dump($carsAttribs);
```



### User

#### Create

```
$genGuid=$letgo->getGuid();

$userData=array(
	"userId"	=> $genGuid,
	"name"		=> "User Name",
	"email"		=> "user@email.com",
	"password"	=> "VeryScurePassword",
	"countryCode"=> "US",
	"address"	=> "Here is my address",
	"city"		=> "City",
	"zip"		=> "96058",
);
$data=$letgo->User->create($userData);

var_dump($genGuid,$data);
```

#### Update
```
$userData=array(
	"name"		=> "User Name",
	"email"		=> "user@email.com",
	"countryCode"=> "US",
	"address"	=> "Here is my address",
	"city"		=> "City",
	"zip"		=> "74065",
);
$data=$letgo->User->update($userID,$userData);

var_dump($data);
```

#### Get
```
//to retreive user's information
$data=$letgo->User->get($userID);

var_dump($data);
```

#### List all users
```
//to retreive all users created by your application
$data=$letgo->User->all();

var_dump($data);
```

### Car

for now I'm running on an issue on car creation, please hold on, will the problem is solved I will post the rest of the package doc.

