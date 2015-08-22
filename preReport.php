<html>
<head>
  <Title>IT Security and Confidentiality Training</Title>
  <link rel="stylesheet" type="text/css" href="report.css" />
  <script language="javascript" type="text/javascript">
   function showTests(str) {
        if (str==""){
            document.getElementById("div1").innerHTML="Select a supervisor for more details!";
            return;
        }
        var xhr = false;
        if (window.XMLHttpRequest) {
            // IE7+, Firefox, Chrome, Opera, Safari
            xhr = new XMLHttpRequest();
        } 
 
        if (xhr) {
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById("div1").innerHTML = xhr.responseText;
                }
            }
            xhr.open("GET", "Reportmini.php?q="+str, true);
            xhr.send(null);
        }
    }
  
  </script>
</head>
<body>

<?php   
require 'utils.php';
require 'DBConnect.php'; //Database Connection
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
<div id="preReport">
<div id="spacer">&nbsp;</div>
<div id="spacer">&nbsp;</div>

<select name="supervisors" onchange="showTests(this.value)">
<?php
$query="SELECT [supervisor] FROM [APACS Sandbox].[dbo].[00securitytestTEMP]  group by [supervisor] order by [supervisor]";
$qresult=sqlsrv_query($db, $query, array(), array("Scrollable"=>"buffered"));
if ($qresult===False) {echo "fail";}
if(!sqlsrv_has_rows($qresult)) { return false;}
while ($row = sqlsrv_fetch_array($qresult)) {
echo '<option value="'. $row['supervisor'] .'">' .$row['supervisor'] . '</option>';
}

?>

</select>
<div id="div1">Select a supervisor for latest test scores of their employees</div>
Latest test scores updated on <?php echo $LastUpDate; ?>.
</div>
	
<?php	
function get_string_between($string, $start, $end){
    $string = " ".$string;
    $ini = strpos($string,$start);
    if ($ini == 0) return "";
    $ini += strlen($start);
    $len = strpos($string,$end,$ini) - $ini;
    return substr($string,$ini,$len);
}

  ?>