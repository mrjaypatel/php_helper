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
 

function pageConfig($TYPE){
	switch($TYPE){
		case "css":
			configStyle();
		break;
		case "js":
			configJs();
		break;
		case "head":
			getPageHead();
		break;
		default:
		break;

	}
}
//Get css page links from config page
function configStyle(){
	foreach (getFromJson("bootstrap", "app", "style", "css") as $value )  {
		echo "<link rel=\"stylesheet\" href=\"".$value."\">";
	  }
}

//Get js page links from config page
function configJs(){
	foreach (getFromJson("bootstrap", "app", "style", "js") as $value )  {
		echo "<script src=\"".$value."\"></script>";
	  }
}


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



//For searching data into json file
function getFromJson($PARENT_ARRAY_NAME, $UNIQUE_KEY, $UNIQUE_VALUE, $RESULT_ELEMENT){
	$RESULT = "";
	$json = json_decode(file_get_contents($_SESSION['CONFIG_FILE']));
	foreach ($json->$PARENT_ARRAY_NAME as $item) {
	    if ($item->$UNIQUE_KEY == $UNIQUE_VALUE) {
	        $RESULT = $item->$RESULT_ELEMENT;
	    }
	}
	return $RESULT;
}


function TS(){
	return today("YmdHis");
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
	$url = "http://sironline.in/".$url;
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


function encryptMe($string, $key){
	return  rtrim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_ECB)));
}

function decryptMe($string, $key){
	return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($string), MCRYPT_MODE_ECB));
}

function hashword($string, $salt){
	return crypt($string, '$1$'.$salt.'$1$');
}

//Cookie set
function setMyCookie($key, $value, $time = 1){
	$time = $time * (time() + (86400 * 30)); // 86400 = 1 day
	@setcookie($key, $value, $time, "/"); 
}

function getMyCookie($cookie_name){
	if(!isset($_COOKIE[$cookie_name])) {
		return 0;
	  } else {	
		return $_COOKIE[$cookie_name];
	  }
}



function pageIndicator($id){
	if(getMyCookie("page", ",") == 0){
	  setMyCookie("page", $id);
	  return array($id);
	}
	$elements = arrayFromString(getMyCookie("page"), ",");
	if(!in_array($id,$elements)){
	  array_push($elements,$id);
	  setMyCookie("page", arrayToString($elements));
	} 
	return $elements;
  }

	function clearIndicator(){
		setMyCookie("page", "");
	}

  function removeFromIndex($id){
	$elements = pageIndicator($id);
	$newPageSetting = array();
	for($index = 0; $index <= array_search($id, $elements); $index++ ){
		array_push($newPageSetting, $elements[$index]);
	}
	setMyCookie("page", arrayToString($newPageSetting));
	return $newPageSetting;

  }

  function printIndicator($id){
	echo "<div class='col-md-12 navigator-link' >";	 
	$elements = removeFromIndex($id);
	echo '<a href="index.php" style="font-size: 14px; font-family: Arial, sans-serif; ">Home > </a>';
	for($index = 0; $index < count($elements); $index++){
		
		if($index == 0){
			echo '<a href="sub-cat.php?catId='.$elements[0].'&&remove='.$elements[0].'"  style="font-size: 14px; font-family: Arial, sans-serif; ">';
			echo printColStyle("title", "blog_cat", "where `id`=".$elements[0]);
			echo "</a>";
		}else{
			
			echo "<a href='sub-cat.php?catId=".$elements[0]."&&subCatId=".$elements[$index]."&&remove=".$elements[$index]."' style='font-size: 14px; font-family: Arial, sans-serif; '>";
			echo printColStyle("title", "blog_cat", "where `id`=".$elements[$index]);
			echo "</a>"
			;
		}
	  
	  if($index != count($elements)-1){
		echo  "</a> > ";
	  }    
	}
	echo "</div>";
  }

?>