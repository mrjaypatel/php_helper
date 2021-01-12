<?php
@session_start();
$PATH_PREFIX = $_SESSION['lib_prefix'];
require_once __DIR__.$PATH_PREFIX."callme.php";

$CONFIG_FILE = __DIR__.$PATH_PREFIX."config.json";
$_SESSION['CONFIG_FILE'] = $CONFIG_FILE;
$servername = getConInfo("host",     $CONFIG_FILE);
$username   = getConInfo("username", $CONFIG_FILE);
$password   = getConInfo("password", $CONFIG_FILE);
$database   = getConInfo("database", $CONFIG_FILE);

// Create connection
@$con = new mysqli($servername, $username, $password, $database);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

function getConInfo($INFO, $FILE_PATH){
	return searchForDb("db", $FILE_PATH, "DB_CON", "app", $INFO);
}

//For searching data into json file
function searchForDb($SEARCH, $FILE_PATH, $PARENT, $ELEMENT, $RESULT_ELEMENT){
	$RESULT = "";
	$json = json_decode(file_get_contents($FILE_PATH));
	foreach ($json->$PARENT as $item) {
	    if ($item->$ELEMENT == $SEARCH) {
	        $RESULT = $item->$RESULT_ELEMENT;
	    }
	}
	return $RESULT;
}



?> 
