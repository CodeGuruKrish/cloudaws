<?php
// Bucket Name
$bucket="hithesh";
if (!class_exists('S3'))require_once('S3.php');
			
//AWS access info
if (!defined('awsAccessKey')) define('awsAccessKey', 'AKIAJWXHMMEA4J6CVJSA');
if (!defined('awsSecretKey')) define('awsSecretKey', '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');
			
//instantiate the class
$s3 = new S3(awsAccessKey, awsSecretKey);

$s3->putBucket($bucket, S3::ACL_PUBLIC_READ);

?>
