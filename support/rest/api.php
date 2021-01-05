<?php 
/**
 * 
 */
require_once 'restCall.php';

class Api extends Rest{
	public $con;
	public function __construct()
	{
		parent::__construct();
	}
	//Generate New token For every User For actions.
	public function generateToken(){
		$username = $this->validateParams('email', $this->params['email'],STRING);
		if(findMe("members", "username", $username)){
			$userId = getColData("id", "members", "WHERE `username`=\"".$username."\"", true);
			$payload = [
				"userId"=>$userId,
				"iat"=>time(),
				"iss" => $_SERVER['SERVER_ADDR'],
				"exp"=>time()+(3600)];
			$token = JWT::encode($payload, SECRETE_KEY);		
			$this->returnResponse(SUCCESS_RESPONSE, $token);
		}else{
			$this->throwError(USER_NOT_FOUND, "User not found IN System.");
		}

	}



	public function crud(){
		if($this->checkAccess() == false){exit;}
		$action = $this->validateParams('action', $this->params['action'],STRING);
		switch ($action) {
			case 'create':
				$this->createUser();
			break;			
			default:
				$this->throwError(CRUD_ERROR, "Invalid Action!");
			break;
		}
	}

	//Use this api for creation of user in > members table
	private function createUser(){
		$username = $this->validateParams('email', $this->params['email'],STRING);
		$password = $this->validateParams('password', $this->params['password'],STRING);
		$checkActive = "WHERE `username` =\"".$username."\" AND `active`=";
		if(findMe("members", "username", $username) && findMe("members", "active", 0, $checkActive."1")){
			$this->returnResponse(USER_ALREADY_EXIST, "User Already Exist! AND ACTIVE!");
			exit;
		}
		if(findMe("members", "username", $username) && findMe("members", "active", 0, $checkActive."0")){	
			//If Valid to Update In Data Base
			if(!updateData("members",array('active', 'password'),array(1, $password), "`username` =\"".$username."\"")){
				$this->returnResponse(CREATE_FAIL, "User Creation Failed. Check With Server!");
				exit;
			}else{			
				$this->returnResponse(SUCCESS_RESPONSE, "User[".$username."]: activated  Successfully.");
				exit;
			}		
			
		}
		if(!addData("members", array($username, $password, 1, today("Y-m-d")), false)){
			$this->returnResponse(CREATE_FAIL, "User Creation Failed. Check With Server!");
			exit;
		}else{
			//If Valid To Create In Database			
			$this->returnResponse(SUCCESS_RESPONSE, "User[".$username."]: created Successfully.");
			exit;
		}		
	}


}

?>