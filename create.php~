<?php

require '/opt/lampp/htdocs/cloudaws/autoloader/aws-autoloader.php';
use Aws\DynamoDb\DynamoDbClient;

$tablename = 'users';

echo "Creating table $tablename..." . PHP_EOL;

$client = \Aws\DynamoDb\DynamoDbClient::factory(array(
    'credentials' => array('key' => 'AKIAJFCDA66T76NWLV6Q',
    'secret' => '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'),
    'region' => 'us-west-2'
));

//echo $client;

print "here";

 $response = $client->putItem(array(
                               "TableName" => 'test',
"Item" => (array(
                                     "username" => array('S' => "jon"),
                                     "password" => array('S' => "test"), 
                                             )
)
)
);

?>


