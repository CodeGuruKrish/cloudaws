<!--Name : Hithesh Krishnamurthy ID: 1001096009 -->
<html>
<head><title>Buy Products</title></head>
<body>
<style>
.table-curved {
    border-collapse:separate;
    border: solid #ccc 3px;
    border-radius: 25px;
    margin: 0 auto 20px auto;

     border-radius: 15px;

    -moz-border-radius: 15px;

    -webkit-border-radius: 15px;
    color: #446bb3;
}

.table-curved tr:last-child td
{
    border-bottom-left-radius: 25px;    
    border-bottom-right-radius: 25px;   
    margin: 0 auto 20px auto;

     border-radius: 15px;

    -moz-border-radius: 15px;

    -webkit-border-radius: 15px;
    color: #446bb3;
}
</style>
<label>Logged in as: <?php session_start(); print  '<Strong>'.$_SESSION["user"]. PHP_EOL.'</strong>'; ?></label>
<div class="submit"><input type="button" name="Logout" value="Logout" onClick="parent.location='buy.php?logout=1'">
<div>
<?php
header('Content-type: text/html; charset=utf-8');
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);


//----------------------------------------To add new item to database--------------------------------

if(isset($_GET["add"]))
{
header("Refresh: 0.1; url=add.php");
}
	
//----------------------------------------To clear the cart completely-------------------------------

if(isset($_GET["clear"]))
{$flag = $_GET["clear"];

if ($flag == 1){
unset($buyitems);
unset($_SESSION['buytable']);
unset($_SESSION['flag']);
header("Refresh: 0.1; url=buy.php");

}}
?>

<table border=1 class="table table-curved">
</table>
<form action="buy.php" method="GET">
<input type="hidden" name="clear" value="1"/>
</form>
<form action="add.php" method="GET">
<input type="submit" value="Add new item" onclick="add.php"/>
</form>
<p/>

<?php
error_reporting(E_ALL);
ini_set('display_errors','On');

require '/opt/lampp/htdocs/cloudaws/autoloader/aws-autoloader.php';
use Aws\DynamoDb\DynamoDbClient;

$client = \Aws\DynamoDb\DynamoDbClient::factory(array(
    'credentials' => array('key' => 'AKIAJWXHMMEA4J6CVJSA',
    'secret' => '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'),
    'region' => 'us-west-2'
));


//------------------------------Main display menu of the site-----------------------------------

 print "<form action='buy.php' method='get'><fieldset><legend>Find Products:</legend><label>Category : <select name='category' id ='category'><option value='1'>All Items</option>";
print "<optgroup label=Menu></optgroup>";
print "<option value= '1'>Drives</option>";
print "<option value= '2'>Video cards</option>";
print "</optgroup>";
  print "</select></label><label>Search Keywords:<input type='text' name='search'><input type='submit' value='search'></label></fieldset></form>";
?>
<p/>

<?php
$searchItem = '';
$idval = '';

if(isset($_GET["search"])){$searchItem = $_GET["search"];}
if(isset($_GET["category"])){$idval = $_GET["category"];}
if(isset($_GET["category"])){$category = $_GET["category"];}

$searchItem = str_replace(' ', '', $searchItem);


//--------------------------------------------To display elements based on the query---------------------------------------------------

if(isset($_GET["search"])){
$params = array (
    'TableName' => 'products',
    'ExpressionAttributeValues' =>  array (
        ':val1' => array('S' => $searchItem)
     
    ) ,
    'FilterExpression' => 'contains (prodname, :val1)',
    'Limit' => 10 
);


$count = 0;
do {
    $response = $client->scan ( $params );
    $items = $response->get ( 'Items' );
    //$items = $response->get ( $params );
   $count = $count + count ( $items );

if ($count == 0)
{
   print  "<p>No items matched the query</p>";
}
else{
     print '<table border="1" align="left" class="table table-curved" bgcolor="#0FFFFF">';
		print '<tr>';
		print '<td>Product Image</td>';
		print '<td>Name</td>';
		print '<td>Price(USD)</td>';
		print '<td>Description</td>';
		print '</tr>';
    // Do something with the $items
    foreach ( $items as $item ) {
        //echo "Scanned item with Title \"{$item['prodname']['S']}\".\n";
        $buyid = $item['custid']['S'];
        $buyurl = 'buy.php?buy='.$buyid;

print '<tr><td><a href="'.$buyurl.'" onclick=""><img src="'.$item['image']['S'].'"alt="Product image" width="173" height="206.5"></a></td>'; 
   print '<td>'.$item['prodname']['S'].'</td>';
   print '<td>'.$item['price']['N'].'$</td>'; 
   print '<td>'.$item['description']['S'].'</td>';
   print '</tr>';
    }}
    
    // Set parameters for next scan
    $params ['ExclusiveStartKey'] = $response ['LastEvaluatedKey'];
} while ( $params ['ExclusiveStartKey'] );

//echo "Table scanned completely. {$count} items found.\n";


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


