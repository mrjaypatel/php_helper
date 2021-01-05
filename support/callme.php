<?php
@session_start();

if(isset($_SESSION['from'])){
	if($_SESSION['from'] == 0){
		$CALLME_PREFIX = "";
		$_SESSION['lib_prefix'] = "/";
	}else if($_SESSION['from'] == 1){
		$CALLME_PREFIX = "/";
		$_SESSION['lib_prefix'] = "/../../";
	}else if($_SESSION['from'] == 2){
		$CALLME_PREFIX = "/";
		$_SESSION['lib_prefix'] = "../../../";
	}else if($_SESSION['from'] == 3){	
		$CALLME_PREFIX = "/";
		$_SESSION['lib_prefix'] = "../../../";
	}else if($_SESSION['from'] == 4){	
		$CALLME_PREFIX = "/";
		$_SESSION['lib_prefix'] = "../../../";
	}else if($_SESSION['from'] == 5){	
		$CALLME_PREFIX = "/";
		$_SESSION['lib_prefix'] = "../../../";
	}else{
		$CALLME_PREFIX = "";
		$_SESSION['lib_prefix'] = "/";
	}
}else{
	$CALLME_PREFIX = "";
	$_SESSION['lib_prefix'] = "/";
}

//Database Configuration
require_once __DIR__.$CALLME_PREFIX.'/libs/db/db.php';

require_once __DIR__.$CALLME_PREFIX.'/libs/alerts/alerts.php';
require_once __DIR__.$CALLME_PREFIX.'/libs/async/async.php';

require_once __DIR__.$CALLME_PREFIX.'/libs/common/non-generics.php';
require_once __DIR__.$CALLME_PREFIX.'/libs/common/sironline.php';
require_once __DIR__.$CALLME_PREFIX.'/libs/common/utils.php';
require_once __DIR__.$CALLME_PREFIX.'/libs/common/sqlHelper.php';

require_once __DIR__.$CALLME_PREFIX.'/libs/crud/create.php';
require_once __DIR__.$CALLME_PREFIX.'/libs/crud/read.php';
require_once __DIR__.$CALLME_PREFIX.'/libs/crud/update.php';
require_once __DIR__.$CALLME_PREFIX.'/libs/crud/delete.php';




?>