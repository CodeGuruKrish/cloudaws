<?php

require '/opt/lampp/htdocs/cloudaws/autoloader/aws-autoloader.php';
use Aws\DynamoDb\DynamoDbClient;

$tablename = 'users';

echo "Creating table $tablename..." . PHP_EOL;

$client = \Aws\DynamoDb\DynamoDbClient::factory(array(
    'credentials' => array('key' => 'AKIAJWXHMMEA4J6CVJSA',
    'secret' => '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'),
    'region' => 'us-west-2'
));

//echo $client;

print "here";

 $response = $client->putItem(array(
                               "TableName" => 'users',
"Item" => (array(
                                     "username" => array('S' => "hithesh"),
                                     "password" => array('S' => md5("blah")), 
                                             )
)
)
);

?>


