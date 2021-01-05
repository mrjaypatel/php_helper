<?php 
/**
 * 
 */
require_once 'constants.php';
class Rest 
{
	protected $request;
	protected $serviceName;
	protected $params;
	
	public function __construct(){
		if($_SERVER['REQUEST_METHOD'] !== "POST"){
			$this->throwError(REQUEST_METHOD_NOT_VALID,"Request Method is not valid!");
		}
		
		$handler = fopen('php://input','r');
		$this->request = stream_get_contents($handler);
		print_r($this->request);
		$this->validateRequest();


	}

	public function validateRequest(){

		if ($_SERVER['CONTENT_TYPE']!== "application/json") {
			$this->throwError(REQUEST_CONTENTTYPE_NOT_VALID, "Request content-type is not valid.");
			exit;
		}
		//Check API Name
		$data = json_decode($this->request, true);
		if (!isset($data['name']) || $data['name']=="" ) {
			$this->throwError(API_NAME_REQUIRED, "API Name Required!");
			exit;
		}
		$this->serviceName = $data['name'];
		
		//Check Params
		if (!is_array($data['params'])) {
			$this->throwError(API_PARAM_REQUIRED, "API Params Required!");
			exit;
		}
		$this->params = $data['params'];
	}


	public function processApi(){
		$api = new API;
		$rMethod = new reflectionMethod('API', $this->serviceName);
		if (!method_exists($api, $this->serviceName)) {
			$this->throwError(API_DOST_NOT_EXIST, "API not available.");
		}
		$rMethod->invoke($api);
	}


	public function validateParams($fieldName, $value, $dataType, $required=true){
		if ($required == true && empty($value) == true ) {
			$this->throwError(VALID_PARAMS_REQUIRED, "Pass valid parameters![ ".$fieldName." ] is required!");
			exit;
		}
		switch ($dataType) {
			case BOOLEAN:
				if (!is_bool($value)) {
					$this->throwError(VALID_PARAMS_DATATYPE, "Data type is not proper.[ ".$fieldName." ] this should be boolean!");
					exit;
				}
			break;
			case STRING:			
			if (!is_string($value)) {
					$this->throwError(VALID_PARAMS_DATATYPE, "Data type is not proper.[ ".$fieldName." ] this should be String!");
					exit;
				}
			break;
			case INTEGER:
			if (!is_numeric($value)) {
					$this->throwError(VALID_PARAMS_DATATYPE, "Data type is not proper.[ ".$fieldName." ] this should be Numeric!");
					exit;
				}
			break;
			default:
					$this->throwError(VALID_PARAMS_DATATYPE, "Data type is not proper.[ ".$fieldName);
					exit;
			break;
		}
		return $value;

	}

	public function throwError($code, $message){
		header("content-type: application/json");
		echo json_encode(['error'=>['status'=>$code, 'message'=>$message]]);
		exit;
	}

	public function returnResponse($code, $message){
		header("content-type: application/json");
		echo json_encode(['response'=>['status'=>$code, 'message'=>$message]]);

	}

	//Check user have access permission or not.
	public function checkAccess(){
		if($this->verifyToken()){
			$id = $this->validateParams('id', $this->params['id'],STRING);
			 if(findMe("members", "id", $id)){
			 	return true;
			 }else{
			 	$this->throwError(UNAUTHORIZED_ACCESS, "You are not using your token.");
			 	return false;
			 }
		}else{
			$this->throwError(UNAUTHORIZED_ACCESS, "This action is not allowed for you.");
			return false;
		}
	}

	public function verifyToken(){
		$token = $this->getBearerToken();
		try{
			$decoded = JWT::decode($token, SECRETE_KEY, array('HS256'));
			return true;	
		}catch(Exception $ex){
			$this->throwError(ACCESS_TOKEN_ERRORS, "Invalid Token!".$ex->getMessage());
			return false;			
		}
	}
	
	/**
	* Get hearder Authorization
	* */
	public function getAuthorizationHeader(){
	    $headers = null;
	    if (isset($_SERVER['Authorization'])) {
	        $headers = trim($_SERVER["Authorization"]);
	    }
	    else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
	        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
	    } elseif (function_exists('apache_request_headers')) {
	        $requestHeaders = apache_request_headers();
	        // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
	        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
	        if (isset($requestHeaders['Authorization'])) {
	            $headers = trim($requestHeaders['Authorization']);
	        }
	    }
	    return $headers;
	}
	/**
	 * get access token from header
	 * */
	public function getBearerToken() {
	    $headers = $this->getAuthorizationHeader();
	    // HEADER: Get the access token from the header
	    if (!empty($headers)) {
	        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
	            return $matches[1];
	        }
	    }
	    $this->throwError( ATHORIZATION_HEADER_NOT_FOUND, 'Access Token Not found');
	}


}

?>