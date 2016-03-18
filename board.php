<!--Name : Hithesh Krishnamurthy ID: 1001096009 -->
<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
<meta content="utf-8" http-equiv="encoding">
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
                                <label><span> Cloud Project - 3 - AWS Web Hosting </span><label>
				<label>
					<span>Username</span>
					<input name="myusername" type="text" id="myusername">
				</label>
				<label>
					<span>Password</span>
					<input name="mypassword" type="password" id="mypassword">
				</label>
				<div class="submit">
				<input type="submit" name="Submit" value="Login">
				</form>
				</div>
				<div class="overlay">
					<div class="message processing">
						<h2>Logging In</h2>
					</div>
					<div class="message success">
						<h2>Logged In</h2>
						<p>You have been logged in</p>
					</div>
				</div>
			</div>
		<div class="loginForm" >Not registered yet??
 <a href="./board.php?register=1">Click here!</a></div>
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
 	</body>
</html>
<?php
header('Content-type: text/html; charset=utf-8');
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require '/opt/lampp/htdocs/cloudaws/autoloader/aws-autoloader.php';
use Aws\DynamoDb\DynamoDbClient;

session_start();
$chkusr = '';
$chkkey = '';

unset($user);
unset($key);

error_reporting(E_ALL);
ini_set('display_errors','On');

try {
  //$dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=test","root","hith1242",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));\

$client = \Aws\DynamoDb\DynamoDbClient::factory(array(
    'credentials' => array('key' => 'AKIAJWXHMMEA4J6CVJSA',
    'secret' => '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'),
    'region' => 'us-west-2'
));

if (isset( $_POST["myusername"]))
{
  $user = $_POST["myusername"] ;
  $key = $_POST["mypassword"];
} 

  //To prevent SQL Injection
  if (isset( $_POST["myusername"]))
{
$user = stripslashes($user);
$key = md5(stripslashes($key));

// Check for the username and password fields
  if ($user != NULL && $key !=NULL)
  {
 $response = $client->getItem(array(
                               "TableName" => 'users',
                               "ConsistentRead" => true,
                               "Key" => array(
                                     "username" => array('S' => $user),
                                     "password" => array('S' => $key),
                                             )
)
);

 $chkusr = $response["Item"]["username"]["S"];
 $chkkey = $response["Item"]["password"]["S"];
  
   if (($chkusr==$user) && ($chkkey==$key))
   {	
		   $_SESSION["user"] = $user;
                   $_SESSION["userid"] = $response["Item"]["id"]["S"];
		   $_SESSION["login"] = 1 ; 
           header( "refresh:0.2; url=buy.php" ); 
	
   } 
   else {echo "<script type='text/javascript'>alert('Invalid credentials! Try again!');</script>";
}
  }
} 

}
 catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
} 

// For redirecting to register page 
if(isset($_GET["register"]))
{$flag = $_GET["register"];

if ($flag == 1){
header("Location: register.php");
}}

?>


