<!--Name : Hithesh Krishnamurthy ID: 1001096009 -->
<html>
<head><title>Message Board</title>
<script src="http://code.jquery.com/jquery-1.9.0.min.js"></script>
</head>
<style type="text/css">
	  body { font: 14px/1.4 Georgia, Serif; background: background-size: cover; color: #cccccc; }
.loginForm {
	color: #996699;
	text-decoration: none;
}
.page-wrapper {
    background: transparent url() repeat-y top left;
	margin: 0px auto;
	padding: 0px;
	width: 752px;
	text-align: left;
}

.loginForm{
	display:table;
	margin: 20px auto;
	padding: 6px 24px 26px;
	position:relative;
	font-weight: 400;
	background: #fff;
	box-shadow: 0 1px 3px rgba(0,0,0,.13);
}
.loginForm .overlay{
	position: absolute;
	top: 0px;
	left: 0px;
	right: 0px;
	bottom: 0px;
	padding: 50px 30px;
	display: none;
	background: rgba(204, 204, 204, 0.7);
	text-align: center;
}
.loginForm .overlay .message{
	display: none;
}
.loginForm label{
	display: block;
	margin-top: 20px;
}
.loginForm label span{
	color: #777;
	display: block;
	font-size: 14px;
}
.loginForm input[type=text], .loginForm input[type=password]{
	margin-top: 5px;
	background: #fbfbfb;
	padding: 3px;
	border: 1px solid #ddd;
	box-shadow: inset 0 1px 2px rgba(0,0,0,.07);
	font-size: 24px;
}
.loginForm .submit{
	display: inline-block;
	padding: 7px 15px;
	margin-top: 15px;
	background: #23A3F8;
	color: white;
	cursor: pointer;
	box-shadow: 0 1px 3px rgba(0,0,0,.13);
}
.loginForm .submit:hover{
	box-shadow: inset 0 1px 2px rgba(0,0,0,.07);
}
.loginForm .submit:active{
	box-shadow: rgba(0, 0, 0, 0.498039) 0px 2px 10px -3px inset;
}
</style>
<body>
  		<div id="content">
			<form name="form1" method="post" action=""/>
				<div class="loginForm">
				<label>Logged in as: <?php session_start();  print  '<Strong>'.$_SESSION["user"]. PHP_EOL.'</strong>'. PHP_EOL;     if(isset($_GET['reply'])){print "<li>"; print "".$_GET['reply']; print "</li>";}  ?></label>
				<label>
					<span>Enter Message</span>
					<input name="mymessage" type="text" id="mymessage">
				</label>
				<div class="submit">
				<input type="button" name="Post" value="Go back" onClick="parent.location='menu.php'">
				<div class="submit">
				<input type="submit" name="Post" value="Post Message">
				</form>
				</div>
				<div class="overlay">
					<div class="message processing">
						<h2>Posting Message</h2>
					</div>
				</div>
			</div>
        </div>
<script>
    
<!--This verifies if the entered details are proper, else displays appropriate message-->

    function verifyfields(){
    if (!document.getElementById('myusername').value.trim()){
        alert('Username fields cant be left blank!');}
		else (!document.getElementById('mypassword').value.trim()){
	    alert('Password fields cant be left blank!');}
    }
</script>



<?php
$id = uniqid();
$user = $_SESSION["user"];
$follows = '';
error_reporting(E_ALL);
ini_set('display_errors','On');

try {
  $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=test","root","hith1242",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  
   if (isset($_SESSION["login"]))
{
if (isset($_POST["mymessage"]))
{
   $message = $_POST["mymessage"];
} 
}
else 
{
	echo "<script type='text/javascript'>alert('Please login to post messages here! ');</script>";
	header("refresh: 0.1; url=board.php");
}

if(isset($_GET['reply'])){
$replyto = $_GET['reply'];
$replyto = substr($replyto,17);
}

if (isset($_POST["mymessage"]))
  {
  if ($message != NULL)
  {  
   if(isset($_GET['reply'])){
    $dbh->beginTransaction();
  
     $dbh->exec("insert into posts values('$id','$user','$replyto',NOW(),'$message')")
        or die(print_r($dbh->errorInfo(), true));
     $dbh->commit();
     header( "refresh:0.2; url=menu.php" ); 
   }
   else {
	   
      $dbh->beginTransaction();

      $dbh->exec("insert into posts values('$id','$user','',NOW(),'$message')")
        or die(print_r($dbh->errorInfo(), true));
      $dbh->commit();
      header( "refresh:0.2; url=menu.php" ); 
   }
  } 
  else 
  { echo "<script type='text/javascript'>alert('Please enter some message! ');</script>";
  }
  }
  
}
catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
}

 

?>
 	</body>
</html>


