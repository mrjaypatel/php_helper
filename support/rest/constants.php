<?php
/*SECURITY*/
define('SECRETE_KEY', 'HUMBOINGO_12352');

/*DATA TYPE*/
define('BOOLEAN', '1');
define('INTEGER', '2');
define('STRING' , '3');


/*Error Codes*/
define('REQUEST_METHOD_NOT_VALID',      100);
define('REQUEST_CONTENTTYPE_NOT_VALID', 101);
define('REQIEST_NOT_VALID',             102);
define('VALID_PARAMS_REQUIRED',  	    103);
define('VALID_PARAMS_DATATYPE',   		104);
define('API_NAME_REQUIRED',             105);
define('API_PARAM_REQUIRED',            106);
define('API_DOST_NOT_EXIST',            107);
define('INVALID_USER_PASS', 			108);
define('USER_ALREADY_EXIST', 			109);
define('USER_NOT_FOUND', 			    110);
define('CRUD_ERROR', 			        111);


define('SUCCESS_RESPONSE', 				200);


/*Server Errors*/
define('ATHORIZATION_HEADER_NOT_FOUND', 300);
define('ACCESS_TOKEN_ERRORS',           301);
define('CREATE_FAIL', 				 	302);
define('JWT_PROCESSING_ERROR',			303);
define('UNAUTHORIZED_ACCESS', 			304);



/*Support File Path*/
define('INSIDE_SUPPORT', '../callme.php');













?>