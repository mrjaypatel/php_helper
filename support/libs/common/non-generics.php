<?php
@session_start();
$PATH_PREFIX = $_SESSION['lib_prefix'];
require_once __DIR__.$PATH_PREFIX."callme.php";



function setLog($LOG_MESSAGE, $LOG_PATH="../logs/NAV_ACTION_LOG.txt"){
	$data  = "\n-----------------------------------------------------------";
	$data .= "\nTime: ".today();
	$data .= "\nPage: ".getPageUrl();
	$data .= "\nLog Msg: ".$LOG_MESSAGE;
	$data .= "\n-----------------------------------------------------------";

	$LOG_FILE = fopen($LOG_PATH, "a") or die("Unable to open file!");	
	fwrite($LOG_FILE, $data);
	fclose($LOG_FILE); 
}


//For getting head information from config file
//It will automaticly detact your page and provide info from cnfig file! Just provide it on config file
function getPageHead($FILE_PATH="page_config.json"){
	echo '<title>';
	echo getPageInfo("title",$FILE_PATH);
	echo '</title>	
		  <meta charset="UTF-8">		  
		  <meta name="description" content="';
	echo getPageInfo("description",$FILE_PATH);
	echo '">
		  <meta name="keywords" content="';
	echo getPageInfo("keywords",$FILE_PATH);
	echo '">
		  <meta name="author" content="';
	echo getPageInfo("author",$FILE_PATH);
	echo '">
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">';
		pageConfig("css");
		
}

//For getting page info from config_json file
function getPageInfo($INFO, $FILE_PATH){
	$page = basename($_SERVER['PHP_SELF']);
	// if($page == "index.php"){
	// 	$URL  =  $_SERVER['REQUEST_URI'];
	// 	$no   = count(explode("/",$URL));
	// 	$data = array();
	// 	$data = explode("/",$URL);   
	//     $end  = $no-1;
	// 	$page = $data[$end];
	// }
	$page.'|'.$FILE_PATH.'|'.$INFO;
	return searchInArrayFromJson($page, $FILE_PATH, "PAGE_CONFIG", "page", $INFO);	
}



//Logout user from the system
function logout($ACTION){
	if($ACTION == "HTML"){
		echo "../auth/logout.php";
	}else if("PHP"){
		logMe("User Logd out!");
		session_start();
		session_destroy();
		nav("../auth");
	}
}


function checkAuth(){
	if(!isset($_SESSION['token']) && !isset($_SESSION['menu'])){
		msg("Authentication Problem!","../auth");
	}
}



//Login to System
function login($USERNAME, $PASSWORD,$TABLE="users"){
	global $con;
	$cmdStr = "SELECT * FROM `$TABLE` WHERE `username` = \"$USERNAME\" AND `password` = \"$PASSWORD\" ";
	$run = mysqli_query($con, $cmdStr);
	$count = mysqli_num_rows($run);
	if($count == 1){
		while($row = mysqli_fetch_array($run)){
				//Session			
				//logMe("Login Success! from: read");
				$_SESSION['app_user'] = $row['username'];
				$_SESSION['role'] = $row['role'];
				$_SESSION['app_name'] = $row['nickname'];
				$_SESSION['token'] = $row['username']."_".rand(0,10000);
				setLog("Login! Success");
				//User priviliges	
				switch ($row['role']) {
					case 'TP':
						$_SESSION['menu'] = array(1,2,3,7,10,12,15);
						break;
					case 'TL':
						$_SESSION['menu'] = array(1,2,3,4);
						break;
					case 'ADMIN':
						$_SESSION['menu'] = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16);
						break;
					default:
						$_SESSION['menu'] = array(1,2,3);
						break;

				}

			}
		return true; 
	}else{
		setLog("Login! Failed");
		//logMe("Login Fail! from: read");
		return false;
	}	

}




?>