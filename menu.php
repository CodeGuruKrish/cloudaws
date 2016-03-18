<?php
// Hithesh Krishnamurtthy ID - 1001096009
session_start();  ?>
<html>
<head><title>Message Board</title>
<script src="http://code.jquery.com/jquery-1.9.0.min.js"></script>
</head>
<style type="text/css">
	  body { font: 14px/1.4 Georgia, Serif; background: background-size: cover; color: #cccccc; }
	
.button {
     background:none!important;
     border:none; 
     padding:0!important;
     font: inherit;
     /*border is optional*/
     border-bottom:1px solid #444; 
     cursor: pointer;
}
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
				<label>Logged in as: <?php print  '<Strong>'.$_SESSION["user"]. PHP_EOL.'</strong>'; ?></label>
				<label>
					<span>Select an option: </span>
				</label>
				<div class="submit">
				<input type="button" name="Logout" value="Logout" onClick="parent.location='menu.php?logout=1'">
				<div class="submit">
				<input type="button" name="Message" value="Newmessage" onClick="parent.location='chat.php'">
				</form>
				</div>	
				<div class="overlay">
					<div class="message processing">
						<h2>Processing..</h2>
					</div>
				</div>
			</div>
        </div>
		<div class="loginForm">
				<label>
					<h4 align="center"><u>Messages </u></h4>
					<p align="center"><?php get_messages() ?></p>
				</label>
		  </div>

<?php

function get_messages() 
{
	error_reporting(E_ALL);
ini_set('display_errors','On');

try {
	 if (isset($_SESSION["login"]))
{
   $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=test","root","hith1242",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

  $count =  $dbh->prepare("select COUNT(*) from posts");
  $count->execute();

  $stmt = $dbh->prepare("select * from posts ORDER by datetime");
  $stmt->execute();
  
  print "<pre>";
  while ($cnt = $count->fetch()) {
	$cnt = $cnt[0];
  }
   print "</pre>";
  
  print "<pre>";
  while ($row = $stmt->fetch()) {
	 //print_r($row);
	 for ($i=0;$i<=$cnt;$i++)
	 {   $value = "Reply to message ".$row[0];
		 print "<p>";
		 print "<p>";
	    print '<li align="left"><strong>Message ID: </strong>'.$row[0]."</p></li>";
		 print "<p>";
		print '<li align="left"><strong>Username: </strong> '.$row[1]."</p></li>";
		 print "<p>";
		if (empty($row[2]))
		{  }
		else{print '<li align="left"><strong>Reply to: </strong>'.$row[2]."</p></li>";}
		 print "<p>";
		print '<li align="left"><strong>Datetime: </strong>'.$row[3]."</p></li>";
		 print "<p>";
		print '<li align="left"><strong>Message: </strong>'.$row[4]."</p></li>";
		print "</p>";
		print '<form action="chat.php">
	   <input  type="submit" class="button" name="reply" value="'.$value.'" />
    </form>';
	 }
  }

  print "</pre>";
  
}
else 
{
	echo "<script type='text/javascript'>alert('Please login to post messages here! ');</script>";
	header("refresh: 0.1; url=board.php");
}
} 
 catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
} 	
}

  if(isset($_GET["logout"]))
{$flag = $_GET["logout"];

if ($flag == 1){
print "<script>window.location.href = 'board.php';</script>";
unset($test);
unset($_SESSION["login"]);
session_destroy();
session_unset();     
}}




?>

 	</body>
</html>


