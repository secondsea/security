<?php 
$filename = "SecurityTraining_data_" . date('Ymd') . ".csv";
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Type: text/csv");
$username = $_SERVER['AUTH_USER'];
$shortusername = substr(strrchr($username,'\\'), 1);   
//debug will have to use hard code
//$shortusername='chiggins';
//$shortusername='jmcgrath';
$line =0;
$latestTests=array();
require 'utils.php';
require 'DBConnect.php'; //Database Connection
$level = 0;
 //echo $shortusername;
 $query = "SELECT  * FROM [APACS Sandbox].[dbo].[00securitytestTEMP] where suplogon = '" . $shortusername . "'";
//	echo $query;
$qresult =sqlsrv_query($db, $query,array(),array("Scrollable"=>"buffered"));  
if ($qresult ===False) { 	$db->exitWithError('query fail'); 	}
if(!sqlsrv_has_rows($qresult)) {  
	//echo "supervises no one  incementer = " . $x;
}
//$testList = array();
list($level, $latestTests, $line) = listTestDates ($shortusername, $db, $level,  $latestTests, $line  );
// now dump to excell
$out = fopen("php://output", 'w');
$flag = false;
foreach($latestTests as $testRow) {
//  echo "test test";
    if(!$flag) {
    // display field/column names as first row
		fputcsv($out, array_keys($testRow), ',', '"');
		$flag = true;
	}
	array_walk($testRow, 'cleanData');
    fputcsv($out, array_values($testRow), ',', '"');
}
fclose($out);
exit;
function listTestDates ($logon, $db , $level, $latestTests, $line ) {
	$isManager=0;
	$empsSupervised = 0;
    $x=0;
	$leftMargin=$level * 25;
	$row_count=0;
	$query = "SELECT  * FROM [APACS Sandbox].[dbo].[00securitytestTEMP] where suplogon = '" . $logon . "'";
		// echo $query;
	$qresult =sqlsrv_query($db, $query,array(),array("Scrollable"=>"buffered"));  
	if ($qresult ===False) { 	$db->exitWithError('query fail'); 	}
	if(!sqlsrv_has_rows($qresult)) { // no rows not a manager   maybe manger livel -1 ????
	//echo "supervises no one  incementer = " . $x;
	//$isManager=0;
	}
	 else {    
	//$isManager=1;
     // supervisor add a level 
		$level++;
		$row_count = sqlsrv_num_rows( $qresult );
	}
	while ($row = sqlsrv_fetch_array($qresult)) {
		$date=$row['testdate']; 
		$formattedDate = $date->format('m-d-Y');
		$x++;  
		if ($x ==1) {
		//echo "Supervises ".  	$row_count . " people";
		}
		// Fill the Array for excel export
		$latestTests[$line]['Employee']=$row['name'];
		$latestTests[$line]['Email']=$row['email'];
		$latestTests[$line]['Date']=$formattedDate;
		$latestTests[$line]['Attempts']=$row['attempts'];
		$latestTests[$line]['supervisor']=$row['supervisor'];
		$latestTests[$line]['supervisorEmail']=$row['supervisor email'];
		$line++;
		//Check if this employee is a supervior
		$mquery = "SELECT  * FROM [APACS Sandbox].[dbo].[00securitytestTEMP] where suplogon = '" . $row['logon'] . "'";
		// echo $query;
		$mqresult =sqlsrv_query($db, $query,array(),array("Scrollable"=>"buffered"));  
		if ($mqresult ===False) { 	$db->exitWithError('query fail'); 	}
		if(!sqlsrv_has_rows($mqresult)) { // no rows not a manager   maybe manger livel -1 ????
		}  else {
		// supervisor add a level 
		//$level++;
		//$row_count = sqlsrv_num_rows( $mqresult );
		list($level, $latestTests, $line) = listTestDates($row['logon'], $db, $level,  $latestTests, $line);  
		}
		//  echo " employee listed increnter =" . $x ; 
		//  echo "rowcount = " .$row_count;
		//echo "Employee level is " .$level;
		if ($row_count ==$x) {  //last of level down
			$level--;
		}
		//	echo  $row['name'] . "is Manager" . $isManager;
	}
	return array ($level, $latestTests, $line);
}	

function cleanData(&$str)
{
    if($str == 't') $str = 'TRUE';
    if($str == 'f') $str = 'FALSE';
    if(preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str)) { $str = "'$str";   }
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
 }
?>