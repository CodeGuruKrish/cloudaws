
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>Add new item</title>
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
<form action="" method='post' enctype="multipart/form-data">
<div id="content" style='margin:10px'>
                              <div class="loginForm">
                                 <h4 align="center">Enter product details</h4><br/>
                                 <label><span>Category:</span></label><select name='category' id ='category'><option value='1'>All Items</option>
                                 <optgroup label=Menu></optgroup>
                                 <option value= '2'>Drives</option>
                                 <option value= '3'>Video cards</option>
                                 <option value= '4'>Sound cards</option>
                                 <option value= '5'>Laptops</option>
                                 </optgroup>
                                 </select>
                                 <label>
					<span>Product name*</span>
					<input name="productname" type="text" id="productname">
				</label>
				<label>
					<span>Price*</span>
					<strong>$</strong><input name="productprice" type="text" id="productprice">
				</label>
				<label>
					<span>Description*</span>
					<input name="productdesc" type="text" id="productdesc">
				</label>
                                <p></p>
                                <div class="submit">
                                <input type='file' name='file'/> <input type='submit' value='Add item'/>
                                </div>
                                <div class="loginForm" ><a href="./buy.php">Back</a></div>	                         
</div>
</div>
</form>

<div class="loginForm">
				<label>
					<h4 align="center"><u>User uploaded items</u></h4>
					<p align="center"><?php get_items() ?></p>
				</label>
		  </div>

<?php 
//session_start();
header('Content-type: text/html; charset=utf-8');
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require '/opt/lampp/htdocs/cloudaws/autoloader/aws-autoloader.php';
use Aws\DynamoDb\DynamoDbClient;


error_reporting(E_ALL);
ini_set('display_errors','On');

include('image_check.php');
$msg='';
if($_SERVER['REQUEST_METHOD'] == "POST")
{

$name = $_FILES['file']['name'];
$size = $_FILES['file']['size'];
$tmp = $_FILES['file']['tmp_name'];
$ext = getExtension($name);

if(strlen($name) > 0)
{

if(in_array($ext,$valid_formats))
{
 
if($size<(1024*1024))
{
include('s3_config.php');
//Rename image name. 
//$actual_image_name = time().".".$ext;
$actual_image_name = $name;
if($s3->putObjectFile($tmp, $bucket , $actual_image_name, S3::ACL_PUBLIC_READ) )
{
$msg = "S3 Upload Successful.";	
$s3file='http://'.$bucket.'.s3.amazonaws.com/'.$actual_image_name;
echo "<script type='text/javascript'>alert('New Item added');</script>";

}
else
$msg = "S3 Upload Fail.";


}
else
$msg = "Image size Max 1 MB";

}
else
$msg = "Invalid file, please upload image file.";

}
else
$msg = "Please select image file.";

}

try {

$id = uniqid();

$client = \Aws\DynamoDb\DynamoDbClient::factory(array(
    'credentials' => array('key' => 'AKIAJWXHMMEA4J6CVJSA',
    'secret' => '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'),
    'region' => 'us-west-2'
));
	
  
  if (isset( $_POST["productname"]))
{    
        $category = $_POST["category"];
        $productname = $_POST["productname"];
	$productprice = $_POST["productprice"];
	$productdesc = $_POST["productdesc"];
        $custid = $_SESSION["userid"];
        $prodid = uniqid();

  if ($category != NULL)
  {
  if ($productname != NULL)
  {
  if ($productprice != NULL)
  {    
  if ($productdesc != NULL)
  { 
  if ($s3file != NULL)
  {
  $response = $client->putItem(array(
                               "TableName" => 'products',
"Item" => (array(
                                     "custid" => array('S' => $custid), 
                                     "prodname" => array('S' => $productname),
                                     "prodid" => array('S' => $prodid),
                                     "description" => array('S' => $productdesc),
                                     "price" => array('N' => $productprice),
                                     "image" => array('S' => $s3file),
                                     "category" => array('S' => $category),
                                             )
)
)
);

$response = $client->putItem(array(
                               "TableName" => 'inventory',
"Item" => (array(
                                     "available" => array('S' => '1'), 
                                     "prodid" => array('S' => $prodid), 
                                             )
)
)
);
  echo "<script type='text/javascript'>alert('New Item added');</script>";
  print "<script>window.location.href = 'add.php';</script>";
  }
  else {echo "<script type='text/javascript'>alert('Please select an image!');</script>";}
  }
  else {echo "<script type='text/javascript'>alert('Please enter a product description!');</script>";}
  }
  else {echo "<script type='text/javascript'>alert('Please enter a price!');</script>";}
  }
  else {echo "<script type='text/javascript'>alert('Please enter a productname!');</script>";} 
  }
  else {echo "<script type='text/javascript'>alert('Please Select a category!');</script>";}
}
}
 catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
}


function get_items() 
{
session_start();
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require '/opt/lampp/htdocs/cloudaws/autoloader/aws-autoloader.php';
//use Aws\DynamoDb\DynamoDbClient;


error_reporting(E_ALL);
ini_set('display_errors','On');

$client = \Aws\DynamoDb\DynamoDbClient::factory(array(
    'credentials' => array('key' => 'AKIAJWXHMMEA4J6CVJSA',
    'secret' => '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'),
    'region' => 'us-west-2'
));

$custid = $_SESSION["userid"];

$params = array (
    'TableName' => 'products',
    'ExpressionAttributeValues' =>  array (
        ':val1' => array('S' => $custid)
     
    ) ,
    'FilterExpression' => 'contains (custid, :val1)',
    'Limit' => 30 
);


$count = 0;
do {
    $response = $client->scan ( $params );
    $items = $response->get ( 'Items' );
    //$items = $response->get ( $params );
   $count = $count + count ( $items );

if ($count == 0)
{
   print  "<p>No user uploaded items</p>";
}
else{
    foreach ( $items as $item ) {
        //echo "Scanned item with Title \"{$item['prodname']['S']}\".\n";
        $buyid = $item['custid']['S'];
        $buyurl = 'buy.php?buy='.$buyid;
        $value = "Delete Item :".$item['prodname']['S'];
   print '<p><img src="'.$item['image']['S'].'"alt="Product image" width="173" height="206.5"></p>'; 
   print '<li>Product name : '.$item['prodname']['S'].'</li>';
   print '<li>Price : $ '.$item['price']['N'].'</li>'; 
   print '<li>Description : '.$item['description']['S'].'</li>';
   print '</li>';
   print '<form action="add.php">
	   <input  type="submit" class="button" name="delete" value = "'.$value.'"/>
    </form>';
    }}    
    // Set parameters for next scan
    $params ['ExclusiveStartKey'] = $response ['LastEvaluatedKey'];
} while ( $params ['ExclusiveStartKey'] );

//echo "Table scanned completely. {$count} items found.\n";


}

//---------------------------------------------Delete user uploaded items-----------------------------------
if(isset($_GET["delete"]))
{

$client = \Aws\DynamoDb\DynamoDbClient::factory(array(
    'credentials' => array('key' => 'AKIAJWXHMMEA4J6CVJSA',
    'secret' => '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'),
    'region' => 'us-west-2'
));


$proddelid =  $_GET["delete"];
$proddelid = substr($proddelid, 13);
$custdelid = $_SESSION["userid"];

print $proddelid;

/*$response = $client->deleteItem(array(
    'TableName' => 'inventory',
        'Key' => array(
            'available' => array('S' => '1'),
            'prodid' => array('S' => '55110705d4dcb'))
));*/

//------------------------------------------------To get all details of items to be deleted------------------------------

$result = $client->getItem(array(
    'ConsistentRead' => true,
    'TableName' => 'products',
    'Key'       => array(
        'custid'   => array('S' => $custdelid),
        'prodname' => array('S' => $proddelid)
    )
));

print_r($items);


//------------------------------------------------To delete the selected item----------------------------------------------

$response = $client->deleteItem(array(
    'TableName' => 'products',
        'Key' => array(
            'custid' => array('S' => $result['Item']['custid']['S']),
            'prodname' => array('S' => $result['Item']['prodname']['S'])), 
              array('description' => array('S' => $result['Item']['description']['S'])),
              array('prodid' => array('S' => $result['Item']['prodid']['S'])),
              array('category' => array('S' => $result['Item']['category']['S'])),
              array('image' => array('S' => $result['Item']['image']['S'])),
              array('price' => array('N' => $result['Item']['price']['S']))  
));



//print_r($response);

echo "<script type='text/javascript'>alert('Item deleted');</script>";

print "<script>window.location.href = 'add.php';</script>";
    
}

?>
		

</body>
</html>
