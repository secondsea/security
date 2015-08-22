<html>
<head>
  <Title>IT Security and Confidentiality Training</Title>
  <link rel="stylesheet" type="text/css" href="report.css" />
</head>
<body>

<?php   
require 'utils.php';
//Database Connection
$server ='ITDEV';
$connectionInfo = array('Database'=> 'APACS Sandbox',  "UID"=>"stuser", "PWD" => "Training22");
//$connectionInfo = array('Database'=> 'APACS Sandbox');
$db =sqlsrv_connect($server, $connectionInfo);
if ($db === false) { exitWithSQLError('Database connection failed');  }
//LDAP
$LDAPHost = "communityaction.us";
$dn = "OU=USERS, OU=COMMUNITYACTION,DC=communityaction,DC=us";    
$LDAPUserDomain = "@communityaction.us";
$LDAPFieldsToFind = array("cn", "givenname", "samaccountname", "homedirectory", "telephonenumber", "manager", "name", "mail" );
//    print_r ($LDAPFieldsToFind);
$ds=ldap_connect($LDAPHost);  // must be a valid LDAP server!
$LDAPUser = "ITSCCTrain"; 
$LDAPUserPassword = "AccessInfo1";
if ($ds) { 
	 ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3); 
 	 ldap_set_option($ds, LDAP_OPT_REFERRALS, 1);  
     $r=ldap_bind($ds,$LDAPUser.$LDAPUserDomain,$LDAPUserPassword);
				// Debug code to see if the ldap is working
				//   	$filter="(samaccountname=mcusack)";      replace mcusack with your logon if I am not here
				//	echo $filter;
				//$sr=ldap_search($ds, $dn, $filter, $LDAPFieldsToFind);
	  			//$info = ldap_get_entries($ds, $sr);
				//echo $info["count"];
		}

//  


$delquery="Delete FROM [APACS Sandbox].[dbo].[00securitytestTEMP]";

$delresult =sqlsrv_query($db, $delquery);  

if (!$delresult) { 	echo "fail"; 	}
else { echo "sucess";}










$query ="WITH testscores AS (SELECT  [logon]  ,[name]  ,[email]    ,[testdate]    ,[attempts]   ,[supervisor]    ,[supervisor email],   DENSE_RANK() OVER (PARTITION BY logon           ORDER BY testdate DESC) RN     FROM    [APACS Sandbox].[dbo].[00securitytest2]) SELECT    [logon]   ,[name]    ,[email]    ,[testdate]   ,[attempts] ,[supervisor]    ,[supervisor email]  FROM    testscores  WHERE   rn = 1";





//$query = "SELECT  * FROM [APACS Sandbox].[dbo].[00securitytest2]  ";
//	echo $query;
$qresult =sqlsrv_query($db, $query,array(),array("Scrollable"=>"buffered"));  
if ($qresult ===False) { 	$db->exitWithError('query fail'); 	}
if(!sqlsrv_has_rows($qresult)) { 	return false; 	}

	
?>

<p style="text-align: center;"><button onclick="self.location.href = '/ReportExcel.php';">click here to trigger CSV download</button>
</p>
<div id="headings">
	<span id="HeadManager" >Manager</span>
	
</div>
<div id="headings">
	<span id="HeadEmp" >Employee</span>
	<span id="HeadEmail">Email</span>
	<span id="HeadDate">Date Taken</span>
	<span id="HeadAttempts">Attempts</span>
	<span id="HeadOld">At Time of Test</span>
	<span id="HeadNew">Current</span>
	<span id="HeadManEmail">Manager Email</span>
</div>
  <div id="results">
	
<?php	

while ($row = sqlsrv_fetch_array($qresult)) {
	$shortusername=$row['logon'];
	// Get user info from LDAP
  if ($ds) { 
	$filter="(samaccountname=$shortusername)";
	$sr=ldap_search($ds, $dn, $filter, $LDAPFieldsToFind);
	$info = ldap_get_entries($ds, $sr);
  	//Echo $info["count"];
  for ($x=0; $x<$info["count"]; $x++) {

  //    $LDAPFieldsToFind = array("cn", "name", "manager" );
     	$sam=$info[$x]['samaccountname'][0];
    $thename=$info[$x] ['name'] [0];
	$firstname=$info[$x] ['givenname'] [0];
    $fullstring=$info[$x] ['manager'] [0];
	$manager = get_string_between($fullstring, "CN=", ",OU");
          
     if(isset($info[$x] ['mail'] [0])){ $email= $info[$x] ['mail'] [0]; } else {$email ="email not set";}
//    print "SAMAccountName is: $sam <br />";
//	print "the name is  $thename <br />";
// print "The manager is $manager <br />";

 // ok now get manager's email
 
   $filter="(name=$manager)";
   $sr2=ldap_search($ds, $dn, $filter, $LDAPFieldsToFind);
   $info2 = ldap_get_entries($ds, $sr2);
    for ($Y=0; $Y<$info2["count"]; $Y++) {
  	$Mansam=$info2[$Y]['samaccountname'][0];
	if(isset($info2[$Y] ['mail'] [0])){ $Manemail= $info2[$Y] ['mail'] [0]; } else {$Manemail ="email not set";}
//	print "the manager  email is $Manemail <br/>";
}   
 
 

 
 
  }
	
 
} else {
  echo "<h4>Unable to connect to LDAP server</h4>";
}


//$date=new DateTime(); //this returns the current date time
$date=$row['testdate']; 
$formattedDate = $date->format('m-d-Y');
//echo $result;
//echo "<br>";
//$krr = explode('-',$result);
//echo "<br>";
//$result = implode("",$krr);
//echo $result;

?>
<div id=dataline">
<div id="Emp"><?php echo $row['name'] ?><br></div>
<div id="Email"><?php echo $email ?></div>
<div id="Date"><?php echo $formattedDate ?></div>
<div id="Attempts"><?php echo $row['attempts'] ?></div>
<div id="OldMan"><?php echo $row['supervisor'] ?></div>
<div id="NewMan"><?php echo $manager ?></div>
<div id="ManEmail"><?php echo $Manemail ?></div>
</div>
<div id="spacer">&nbsp;</div>


<?php


//	echo "data: " .." " . . " ". . " ". . " ". . "  <BR>";
	
//echo "<BR><br>";

	
	
	
	//}
     //$testHistory[]=array($row['name'], $	
	
	//index	logon		email	testdate	attempts	supervisor	supervisor email

	
	
	
	?>
	
	
	
	

	
	<?php
	
	}
	
	
//		$row_count=sqlsrv_num_rows($qresult);
//			$query = "INSERT INTO [APACS Sandbox].[dbo].[00securitytest2]  ([logon],[name],[email],[testdate],[attempts], [supervisor], [supervisor email])   VALUES   ('$shortusername',  '$thename', '$email',     GETDATE(),$attempts, '$manager', '$Manemail')";

			//$query = "UPDATE [APACS Sandbox].[dbo].[00securitytest] SET [testdate] = GETDATE(), [attempts]=$attempts WHERE [name]= '$shortusername'";

	function get_string_between($string, $start, $end){
    $string = " ".$string;
    $ini = strpos($string,$start);
    if ($ini == 0) return "";
    $ini += strlen($start);
    $len = strpos($string,$end,$ini) - $ini;
    return substr($string,$ini,$len);
	}

  ?>