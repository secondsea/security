<?
$page_title = "The Simple Quiz Script";
// If the form is submitted run the script
if(isset($_POST['submit'])){
$quest1 = $_POST['quest1']; 
$quest2 = $_POST['quest2'];  
$quest3 = $_POST['quest3'];  
$quest4 = $_POST['quest4'];  
$quest5 = $_POST['quest5'];
// Lets make sure that everything has been submitted
if($quest1 == NULL OR $quest2 == NULL OR $quest3 == NULL OR $quest4 == NULL OR $quest5 == NULL){
$test_complete .='Please complete the quiz! <a href="javascript:history.go(-1)">Go Back</a>';
}else{
// change the quest1 to the right answer
if($quest1 == "3") { 
$test_complete .="Question one is <span class='green'>correct</span>, well done!<br/>";  
}else{ 
$test_complete .="Question one is <span class='red'>incorrect</span>!<br/>"; 
} 
// change the quest2 to the right answer
if($quest2 == "2") { 
$test_complete .="Question two is <span class='green'>correct</span>, well done!<br/>"; 
}else{ 
$test_complete .="Question two is <span class='red'>incorrect</span>!<br/>"; 
} 
// change the quest3 to the right answer
if($quest3 == "1") { 
$test_complete .="Question three is <span class='green'>correct</span>, well done!<br/>"; 
}else{ 
$test_complete .="Question three is <span class='red'>incorrect</span>!<br/>"; 
}
// change the quest4 to the right answer
if($quest4 == "3") { 
$test_complete .="Question four is <span class='green'>correct</span>, well done!<br/>"; 
}else{ 
$test_complete .="Question four is <span class='red'>incorrect</span>!<br/>"; 
}
// change the quest5 to the right answer
if($quest5 == "2") { 
$test_complete .="Question five is <span class='green'>correct</span>, well done!<br/>"; 
}else{ 
$test_complete .="Question five is <span class='red'>incorrect</span>!<br/>"; 
}
// Now lets see if all the questions are correct, this must match the above quest settings
if($quest1 == "3" & $quest2 == "2" & $quest3 == "1" & $quest4 == "3" & $quest5 == "2"){
$test_complete .="<p>Congratulations, you got all the questions correct!</p>"; 
}else{
// If any of the questions are not correct lets tell them
$test_complete .='<p>Your not there just yet! <a href="javascript:history.go(-1)">Try again</a></p>'; 
}}}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>The Simple PHP Quiz Script - www.funkyvision.co.uk</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
<!--
body,td,th {
	color: #000000;
}
.green {color:#009933;}
.red {color:#CC0000;}
-->
</style></head>
<body>
	<? if(!isset($_POST['submit'])){ ?>
	<h2>Quiz Script</h2>
	<p>Lets have some fun and see if you can get all the questions correct .. Good luck!</p>
    <form method="post">
  <p>1. Is this a good script?<br>
    <input type="radio" name="quest1" value="3">
    Yes<br>
    <input type="radio" name="quest1" value="2">
    Maybe<br>
    <input type="radio" name="quest1" value="1">
    No</p>
  <p>2. Do you like free scripts?<br>
    <input type="radio" name="quest2" value="1">
    Some times<br>
    <input type="radio" name="quest2" value="3">
    Never<br>
    <input type="radio" name="quest2" value="2">
    Always</p>
  <p>3. Are Funky Vision's scripts easy to use?<br>
    <input type="radio" name="quest3" value="2">
    No<br>
    <input type="radio" name="quest3" value="1">
    Yes<br>
    <input type="radio" name="quest3" value="3">
    Not sure</p>
  <p>4. Can you turn this script into a fun game? <br>
    <input type="radio" name="quest4" value="3">
    Yes
    <br>
    <input type="radio" name="quest4" value="2">
    Never
    <br>
    <input type="radio" name="quest4" value="1">
    Maybe
  </p>
	  <p>5. Can these quiz scripts add fun to your website? <br>
    <input type="radio" name="quest5" value="3">
    Nope
    <br>
    <input type="radio" name="quest5" value="2">
    Always<br>
    <input type="radio" name="quest5" value="1">
    Never
	  </p>
      <p>
    <input type="submit" name="submit" value="Submit Quiz">
  </p>
</form>
<? }else{ 
echo "<h2>Quiz Results</h2>
<p>".$test_complete."</p>";
}?>
</body>
</html>
