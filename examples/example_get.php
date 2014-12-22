<?php
require '../MasLuz.php';

$ml = new MasLuz('CLIENT_ID', 'CLIENT_SECRET');
$ml->getAccessToken();

$result = $ml->get('customer');

echo '<pre>';
foreach ($result['body']->_embedded->customer as $cli){
	echo $cli->name."\n";
}
echo '</pre>';