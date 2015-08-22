<?php 
//$username = $_SERVER['AUTH_USER'];
//$shortusername = substr(strrchr($username,'\\'), 1);   


//debug will have to use hard code

//$shortusername='chiggins';

$shortusername='jmcgrath';

?>




<html>
<head>
  <Title>IT Security and Confidentiality Training</Title>
  <link rel="stylesheet" type="text/css" href="report.css" />


</head>
<body>
	 <!--script type="text/javascript">    SomeFunction();  </script-->
<?php   
require 'utils.php';
require 'DBConnect.php'; //Database Connection
$ManagerId=0;
// get last update date
$infoQuery="SELECT ([Last Update])  FROM [APACS Sandbox].[dbo].[00securityTestLog] where [index] =1";
$infoResult =sqlsrv_query($db, $infoQuery,array(),array("Scrollable"=>"buffered"));  
if ($infoResult ===False) { 	$db->exitWithError('query fail'); 	}
if(!sqlsrv_has_rows($infoResult)) { 	return false; 	}
 while ($infoRow = sqlsrv_fetch_array($infoResult)) {
$date=$infoRow['Last Update']; 
//$LastUpDate = $date->format('m-d-Y H:i:s');
 $LastUpDate = $date->format('m-d-Y h:i A');
 }
?>

<!--select name="supervisors" onchange="showTests(this.value)"-->
<?php
//$query="SELECT [supervisor] FROM [APACS Sandbox].[dbo].[00securitytestTEMP]  group by [supervisor] order by [supervisor]";
//$qresult=sqlsrv_query($db, $query, array(), array("Scrollable"=>"buffered"));
//if ($qresult===False) {echo "fail";}
//if(!sqlsrv_has_rows($qresult)) { return false;}
//while ($row = sqlsrv_fetch_array($qresult)) {
//echo '<option value="'. $row['supervisor'] .'">' .$row['supervisor'] . '</option>';


//}

?>

<!--/select>
<div id="div1">  </div>
<!--Select a supervisor for latest test scores of their employees</div>
Latest test scores updated on <?php// echo $LastUpDate; ?>.
</div>
<!--p style="text-align: center;"><button onclick="self.location.href = '/ReportExcel.php';">click here to trigger CSV download</button>
</p>
<div id="headings">
	<span id="HeadManager" >Manager</span>
	
</div-->








<div id ="listPod">
<?php	
$level = 0;

 
 $iquery = "SELECT [supervisor] FROM [APACS Sandbox].[dbo].[00securitytestTEMP] where suplogon = '" . $shortusername . "'";
		// echo $query;
	$iqresult =sqlsrv_query($db, $iquery,array(),array("Scrollable"=>"buffered"));  
	if ($iqresult ===False) { 	$db->exitWithError('iquery fail'); 	}
	if(!sqlsrv_has_rows($iqresult)) { // no rows not a manager er livel -1 ????
	//echo "supervises no one  incementer = " . $x;

	//$isManager=0;
	echo "You are either not a manager or you don't have employees who have taken the security training ";
	
	}
	 else {    
	$irow = sqlsrv_fetch_array($iqresult);
	$ManagerId++;
?> 
 <ul id="master" class="tree">
  	<li>       
		 <input type="checkbox" checked  id="Manager<?php echo $ManagerId?>" /> 
		 <label for="Manager<?php echo $ManagerId; ?>"><?php echo $irow['supervisor'] ?> Supervises:</label>
			<ul>
  
<?php 
//$testList = array();
list($level, $ManagerId) = listTestDates($shortusername, $db, $level, $ManagerId );  
  //$level =  listTestDates ($shortusername, $db, $level, $ManagerID );
echo "</ol>";
 

 	}

	
	?>
	
	</div>
	
	
<?php 	
//echo "test";
//foreach ($Managers as $key =>$value) { echo $key ." xxx     ".$value   ."<br>" ; }  

//foreach ($classes as $key =>$value) { echo "<option value =\"$key\">$value </option> \n";  }  











function listTestDates ($logon, $db , $level , $ManagerId) {
  $isManager=0;
$empsSupervised = 0;
      $x=0;
	  $leftMargin=$level * 25;
	     $row_count=0;
	
	$query = "SELECT  * FROM [APACS Sandbox].[dbo].[00securitytestTEMP] where suplogon = '" . $logon . "'"; 	//echo $query;
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
	
	
	//echo "debug 2";
	
	while ($row = sqlsrv_fetch_array($qresult)) {
	//echo "debug 3";
		$date=$row['testdate']; 
		$formattedDate = $date->format('m-d-Y');
	  $x++; //echo "incrementer =" . $x;	  
	 
if ($x ==1) {
$Managers[$row['suplogon']] = $row['supervisor'] ;
    	?>
	


	
	<?php
    //echo "Supervises ".  	$row_count . " people";
	//	include ('heading.inc'); 
  
  }
?>


  
  <?php 
  //$x++;
  //echo "debug 1";
  //Check if this employee is a supervior
	$mquery = "SELECT  * FROM [APACS Sandbox].[dbo].[00securitytestTEMP] where suplogon = '" . $row['logon'] . "'";
		// echo $query;
	$mqresult =sqlsrv_query($db, $mquery,array(),array("Scrollable"=>"buffered"));  
	if ($mqresult ===False) { 	$db->exitWithError('query fail'); 	}
if(!sqlsrv_has_rows($mqresult)) { // no rows not a manager   maybe manger livel -1 ????
?>
 	<li class="dataline"><?php echo $row['name'] ?> &nbsp; <?php echo $row['email'] ?> <span style=" position:absolute; left:500px;">&nbsp;Test Date:<?php echo $formattedDate ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row['attempts'] ?>&nbsp;attempts</span>

		
		
			</li>
<?php
			
			
			
			
			}  else {

     $ManagerId++;
	//$isManager=1;
     // supervisor add a level 
	 //$level++;
	//$row_count = sqlsrv_num_rows( $mqresult );
	?>
 	<li class="dataline">
			<input type="checkbox" unchecked  id="Manager<?php echo $ManagerId?>" /> <label for="Manager<?php echo $ManagerId; ?>"><?php echo $row['name'] ?>	
			&nbsp;<?php echo $row['email'] ?> <span style=" position:absolute; left:500px;">&nbsp;Test Date:<?php echo $formattedDate ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row['attempts'] ?>&nbsp;attempts</span></label> 
			<ul>
	
	
	<?php 
	 list($level, $ManagerId) = listTestDates($row['logon'], $db, $level, $ManagerId );  
	//$level = listTestDates($row['logon'], $db, $level, $ManagerID; );  
	
	
	
	
}
	
	
	
	
	
    //echo " employee listed increnter =" . $x ; 
    //echo "rowcount = " .$row_count;
   //echo "Employee level is " .$level;
   if ($row_count ==$x) {  //last of level down
   $level--;
echo "<li> </li>	  </ul></li>";
	
  }
  
  
  

    //$level =listTestDates($row['logon'], $db, $level ); 
  //list($level, $Managers) = listTestDates($row['logon'], $db, $level, $Managers);  
   //	echo  $row['name'] . "is Manager" . $isManager;
  
	}
//return $level;	

	return array ($level, $ManagerId);
	}	
  ?>
  
  
  </div>