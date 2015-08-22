 <?php   
//Database Connection
$server ='ITDEV';
$connectionInfo = array('Database'=> 'APACS Sandbox',  "UID"=>"stuser", "PWD" => "Training22");
//$connectionInfo = array('Database'=> 'APACS Sandbox');
$db =sqlsrv_connect($server, $connectionInfo);
if ($db === false) { exitWithSQLError('Database connection failed');  }
