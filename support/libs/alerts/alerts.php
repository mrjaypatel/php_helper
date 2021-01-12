<?php
@session_start();
$PATH_PREFIX = $_SESSION['lib_prefix'];
require_once __DIR__.$PATH_PREFIX."callme.php";

/**
[1] msg($message,$ALERT_TYPE, $SCRIPT = true,  $redirect="#",$TYPE = "info");
[2] logMe($message, $script = false);
*/


//Message Alert
function msg($message, $redirect="#", $ALERT_TYPE = "js", $SCRIPT = true  ,$TYPE = "info"){	
	switch ($ALERT_TYPE) {
		case 'js':
			jsMsg($message, $SCRIPT, $redirect);
		break;
		case 'notify':
			notifyMsg($message, $SCRIPT, $redirect, $TYPE);
		break;		
		default:
			jsMsg($message, $SCRIPT, $redirect);
		break;
	}
}

//Child API of msg for Javascript Alert
function jsMsg($message, $SCRIPT, $redirect){
	$MESSAGE_BODY = 'alert("'.$message.'");';
	if ($SCRIPT) {
		printInScript($MESSAGE_BODY);								    
	}else {
		echo $MESSAGE_BODY;				        
	}
	if($redirect != "#"){
		if($SCRIPT){
			printInScript('window.location = "'.$redirect.'";');
		}else{
			echo 'window.location = "'.$redirect.'";';
		}
	}


}

//Child API of msg for proper UI alert
function notifyMsg($message, $SCRIPT, $redirect, $TYPE){
	switch ($TYPE) {
		case 'info':
			$image = "../images/notify/info.jpg";
			$title = "Information!";
			break;
		case 'success':
			$image = "../images/notify/success.png";
			$title = "Success!";
			break;
		case 'warning':
			$image = "../images/notify/warning.png";
			$title = "Warning!";
			break;
		case 'danger':
			$image = "../images/notify/danger.png";
			$title = "Danger!";
			break;
		
		default:
			$image = "../images/notify/info.jpg";
			$title = "Info!";
			break;
		}	

		$MESSAGE_BODY = '		 
				$.notify({
					icon: \''.$image.'\',
					title: \''.$title.'\',
					message: \''.$message.'\'
				},{
					type: \'minimalist\',
					delay: 5000,
					icon_type: \'image\',
					template: \'<div data-notify="container" id="cust-'.$TYPE.'" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">\' +
						\'<img data-notify="icon" class="img-circle pull-left">\' +
						\'<span data-notify="title">{1}</span>\' +
						\'<span data-notify="message">{2}</span>\' +
					\'</div>\'
				});';

		if($SCRIPT){
			printInScript($MESSAGE_BODY);
	        if($redirect != "#"){
	        	printInScript('window.location = "'.$redirect.'";');	     
	        }
		}else{
			echo $MESSAGE_BODY;
	        if($redirect != "#"){
	        	echo 'window.location = "'.$redirect.'";';
	        }			
		}
	
}




//Get Console log with <script> tag or without tag!
function logMe($message, $script = true){
	$MESSAGE_BODY = 'console.log("'.$message.'");';
	if($script){
		printInScript($MESSAGE_BODY);		
	}else{
		echo $MESSAGE_BODY;	
	}	
}

?>