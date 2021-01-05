<?php
@session_start();
$PATH_PREFIX = $_SESSION['lib_prefix'];
require_once __DIR__.$PATH_PREFIX."callme.php";


/***
[1] buildString($TITLE, $TYPE = "-");
[2] splitSpan($TITLE);
[3] nav($URL, $JS=false, $SCRIPT=false);
[4] createDir("Dir Name");
[5] arrayFromString($string, $saperate);
*/




// Build string of diffrent types
// Saperate from space and arrange in diffrent type
function buildString($TITLE, $TYPE = "-"){
	$newStr =  strtolower($TITLE);
	switch ($TYPE) {
		case '_':
			return preg_replace('/\s+/', '_', $newStr);
		break;	
		case ',':
			return preg_replace('/\s+/', ',', $newStr);	
		break;
		default:
			return preg_replace('/\s+/', '-', $newStr);
		break;
	}
	
}


//Split string into two part and print first with <span> tag
function splitSpan($TITLE){
	$TMP_STR = buildString($TITLE, "array");	
	if(count($TMP_STR) > 1){
		return "<span>".$TMP_STR[0]."</span> ".$TMP_STR[1];
	}
	return $TMP_STR;
}

//Navigate to Page
function nav($URL, $JS=false , $SCRIPT=false){
	if($js){
		$JS_BODY = "window.location='".$URL."'";
		if ($SCRIPT) {
			printInScript($JS_BODY);
		}else{
			echo $JS_BODY;
		}
	}else{
		header("location: ".$URL);
	}
	
}


//Get Page Url
function pageUrl(){  
	$link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] 
	                === 'on' ? "https" : "http") . "://" . 
	          $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
	return $link; 
}

//Get Page Name
function pageName($ext = true){
	$page = arrayFromString($_SERVER['PHP_SELF'], '/');
	$count = count($page);
	if($ext){
		return $page[$count-1];
	}else{
		$base = arrayFromString($page[$count-1], ".");
		return $base[0];
	}	
}


//Create New Dir
function createDir($dir){
	if (!is_dir($dir)) mkdir($dir, 0777, true);
}



//Get Today calender date information
function today($FORMAT = "NA"){
	date_default_timezone_set("Asia/Kolkata");  
	if ($FORMAT != "NA") {
		//d-m-Y H:i:s
		return date($FORMAT);
	}else{
		return date('d-m-Y H:i:s');
	}	
}



//Get Array from string
function arrayFromString($string, $saperate){
	return explode($saperate,$string);
}


//For getting json data from file
function dumpFromJson($FILE_PATH, $PARENT, $ELEMENT, $INDEX = 0)
{
	$DATA = file_get_contents($FILE_PATH); 
	$D_DATA = json_decode($DATA);	
	return $D_DATA->$PARENT[$INDEX]->$ELEMENT;		
}

//For getting json data in array
function arrayFromJson($FILE_PATH, $PARENT,  $ELEMENT){
	$ARR_DATA = array();	
	$json = json_decode(file_get_contents($FILE_PATH));
	foreach ($json->$PARENT as $item) {
	    array_push($ARR_DATA, $item->$ELEMENT);
	}				
	return $ARR_DATA;	
}

//For searching data into json file
function searchInArrayFromJson($SEARCH, $FILE_PATH, $PARENT, $ELEMENT, $RESULT_ELEMENT){
	$RESULT = "";
	$json = json_decode(file_get_contents($FILE_PATH));
	foreach ($json->$PARENT as $item) {
	    if ($item->$ELEMENT == $SEARCH) {
	        $RESULT = $item->$RESULT_ELEMENT;
	    }
	}
	return $RESULT;
}


function socialShare($display_text, $title, $url, $share_on){
	if(matchParam("fb",$share_on)){
		echo '<a href="https://www.facebook.com/sharer/sharer.php?u='.$url.'&t='.$title.'" onclick="javascript:window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600");return false;" target="_blank" title="Share on Facebook">'.$display_text.'</a>';
	}else if(matchParam("wa",$share_on)){
		echo '<a href="whatsapp://send?text='.$url.'" data-action="share/whatsapp/share" onClick="javascript:window.open(this.href, "", "menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600");return false;" target="_blank" title="Share on whatsapp">'.$display_text.'</a>';
	}else{
		echo "Check Share On Option!";
	}
}

function matchParam($param1, $param2){
	if(strcasecmp($param1, $param2) === 0){
		return true;
	}else{
		return false;
	}
}



?>