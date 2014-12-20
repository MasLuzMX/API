<?php
require '../MasLuz.php';

$ml = new MasLuz('CLIENT_ID', 'SECRET_KEY');
$ml->getAccessToken();

$result = $meli->get('/customers');

echo '<pre>';
print_r($result);
echo '</pre>';