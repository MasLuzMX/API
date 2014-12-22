<?php
require '../MasLuz.php';

$ml = new MasLuz('CLIENT_ID', 'CLIENT_SECRET');
$ml->getAccessToken();

$result = $ml->get('customer');

echo '<pre>';
print_r($result);
echo '</pre>';