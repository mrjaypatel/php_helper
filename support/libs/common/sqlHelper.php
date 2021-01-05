<?php
@session_start();
$PATH_PREFIX = $_SESSION['lib_prefix'];
require_once __DIR__.$PATH_PREFIX."callme.php";
/**
* For getting key and value for updateing data in Database->Table
*/
function getKeyValue($KEY = array(), $VALUE = array()){
	$TOTAL_COL = count($KEY);
	$TOTAL_ROW = count($VALUE);
	$STR = "";
	$i = 0;
	if($TOTAL_COL == $TOTAL_ROW){
		while ( $i < $TOTAL_COL) {
			$STR .= "`".$KEY[$i]."`";
			$STR .= "=\"".$VALUE[$i]."\" ";
			if ($i < $TOTAL_COL - 1) {
			 	$STR .= ",";
			 } 
			 $i++;
		}
	}else{
		$STR = "Cols: ".$TOTAL_COL." | Rows: ".$TOTAL_ROW;
	}
	return $STR;
}

/**
* For converting to string from array List, like key_structure or like value_structure
*/
function stringFromArray($DATA = array(), $TYPE = false){
	if($TYPE){
		$str ="";
		$END = count($DATA);
		for ($i=0; $i < $END; $i++) { 
			if($DATA[$i] != "id"){
				if($i != ($END-1)){
					$str .= "'";
					$str .= $DATA[$i];
					$str .= "',";
				}else{
					$str .= "'";
					$str .= $DATA[$i];
					$str .= "'";			
				}
			}
			
		}
	}else{
		$str ="";
		$END = count($DATA);
		for ($i=0; $i < $END; $i++) { 
			if($DATA[$i] != "id"){
				if($i != ($END-1)){
					$str .= "`";
					$str .= $DATA[$i];
					$str .= "`,";
				}else{
					$str .= "`";
					$str .= $DATA[$i];
					$str .= "`";			
				}
			}
		}	
	}

	return $str;
}



// For print colums and values for command string from Array
function printColStyle($COL, $TABLE, $CONDITION, $STYLE="txt"){
	global $con;	
	$DATA = array();
	$id = getColData('id', $TABLE, $CONDITION);
	$DATA = getColData($COL, $TABLE, $CONDITION);
	switch ($STYLE) {
		case 'option':
			$i = 0;
			while ( $i< count($DATA)) {
				echo "<option value=\"".$id[$i]."\" >".$DATA[$i]."</option>";
				$i++;				
			}
		break;
		case 'list':
			$i = 0;
			while ( $i< count($DATA)) {
				echo "<li>".$DATA[$i]."</li>";
				$i++;
			}
		break;	
		case 'value':
			$i = 0;
			while ( $i< count($DATA)) {
				echo "value=\"".$DATA[$i]."\"";
				$i++;
			}
			break;
		default:
			$i = 0;
			while ( $i< count($DATA)) {
				echo $DATA[$i];
				$i++;
			}
			break;
	}

}



// For counting rows from table present for same condition
function countRows($TABLE, $CONDITION=""){
	global $con;
	$cmdStr = "SELECT * FROM `".$TABLE."` ".$CONDITION;
	$run = mysqli_query($con, $cmdStr);
	$count = mysqli_num_rows($run);
	return $count;	
}



/*
*	Getting column information from any tables from currant database.
*/
function getColData($COL, $TABLE, $CONDITION = "", $PRINT_TEXT = false){
	global $con;
	$cmdStr = "SELECT `".$COL."` FROM ".$TABLE." ".$CONDITION;	
	$run = mysqli_query($con, $cmdStr);
	$DATA = array();
	while ($row = mysqli_fetch_array($run)) {
		array_push($DATA, $row[$COL]);
	}
	if($PRINT_TEXT){
		return $DATA[0];
	}else{
		return $DATA;
	}
	
}

/*
*	Getting column information from any tables from currant database.
*/
function getAllData($TABLE, $CONDITION = "", $PRINT_TEXT = false){
	global $con;
	$cmdStr = "SELECT * FROM ".$TABLE." ".$CONDITION;	
	$run = mysqli_query($con, $cmdStr);
	$DATA = array();
	// mysqli_fetch_assoc($run);
	// return;
	while ($row = mysqli_fetch_assoc($run)) {
		array_push($DATA, $row);
	}
	if($PRINT_TEXT){
		return $DATA[0];
	}else{
		return $DATA;
	}
	
}

function findMe($TABLE, $COL, $VAL, $CONDITION = "NA"){
	global $con;
	if($CONDITION == "NA"){
		$CONDITION = "WHERE `".$COL."` = \"".$VAL."\"";
	}
	
	$data = getColData($COL, $TABLE, $CONDITION);
	if(count($data) > 0){
		return true;
	}else{
		return false;
	}
	
}


//Find before delete
/**
*This API can check row exsist or not in Table
*/
function findBeforeDelete($TABLE,$ID){
	global $con;
	$cmdStr = "SELECT * FROM `$TABLE` WHERE `id`='".$ID."' ";		
	$run = mysqli_query($con, $cmdStr);
	return mysqli_num_rows($run);
}


//Add Data Into Table
function addData($TABLE, $VALS = array(), $DEBUG=true){
	global $con;
	$totalCols = count(getCols($TABLE));
	$totalVals = count($VALS);
	if ($totalVals == $totalCols) {
		$cmdStr = "INSERT INTO `".$TABLE."`(".stringFromArray(getCols($TABLE)).") VALUES (".stringFromArray($VALS, true).")";
		$run = mysqli_query($con, $cmdStr);		
		if($run){
			if($DEBUG){
				print_r(crudResponse("success","Data added","create",$TABLE, 200));
				logMe("Success: Async Task Completed!");	
			}
			
			return true;
		}else{
			if($DEBUG){
				print_r(crudResponse("fail","Not added in table. Run Fail. Check DB Connection --> config.json | ".$cmdStr,"create",$TABLE, 401));
				logMe("Error: Async Task Failed!");
			}			
			return false;
		}
	}else{
		if($DEBUG){
			print_r(crudResponse("fail","Cols and Val not matching.","create",$TABLE, 401));
			logMe("Warning: Colums And Row Are Diffrent!");
		}		
		return false;
	}

}

//Get Table list Array from database
function getTableList($DATABASE){
	//##1 Disp All Table from database
	//show tables
	//##2 Now List All Cols from Table
	//SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'about' ORDER BY ORDINAL_POSITION
	//##3 Return array for col names 
	global $con;
	$TABLES = array();
	$run = mysqli_query($con, "show tables");
	foreach($run as $key=>$row) {
		$DB_NAME = "Tables_in_".$DATABASE;
		array_push($TABLES, $row[$DB_NAME]);    	
 	}
 	return $TABLES;
}

//Get Column list from Table
function getCols($TABLE,$ARRAY = true){
	global $con;
	$cols = array();
	$cmdStr = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = \"$TABLE\" ORDER BY ORDINAL_POSITION";
	$run = mysqli_query($con, $cmdStr);
	foreach ($run as $value) {
		if($value["COLUMN_NAME"] != "id"){
			array_push($cols, $value["COLUMN_NAME"]);
		}		
	}
	if($ARRAY){
		return $cols;
	}else{
		return stringFromArray($cols);
	}
	
}

//Delete row from table
function deleteMe($ID = "NA", $TABLE, $CONDITION = ""){
	global $con;
	if($ID != "NA"){
		$cmdStr = "DELETE FROM $TABLE WHERE `id` =\"$ID\" ";
	}else{
		$cmdStr = "DELETE FROM $TABLE WHERE ".$CONDITION;
	}
	$run = mysqli_query($con, $cmdStr);
	if($run){
		return true;
	}else{
		return false;
	}
}

function crudResponse($status, $msg, $action, $table, $code){
	
	$response = array("status"=>$status, 
					  "message"=>$msg,
					  "action"=>$action,
					  "table"=>$table);
  	
 	http_response_code($code);
 	return json_encode($response);
}



function printInScript($DATA){
	echo '<script type="text/javascript">';
	echo $DATA;
	echo '</script>';
}

//For updating data to config db
function updateData($TABLE, $COLS = array(), $VALS = array(), $CONDITION){
	global $con;
	$totalCols = count($COLS);
	$totalVals = count($VALS);
	if ($totalVals == $totalCols) {
		$cmdStr  = "UPDATE `".$TABLE."` SET ";
		$cmdStr .= getKeyValue($COLS, $VALS);
		$cmdStr .= " WHERE ".$CONDITION;
		 $run = mysqli_query($con, $cmdStr);
		 if($run){
		 	return true;
		 }else{
		 	return false;
		 }
	}else{		
		return false;
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