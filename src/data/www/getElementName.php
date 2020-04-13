<?php
require_once './vendor/autoload.php';
use RomanAM\GetProduct;

$api = new GetProduct(true);

$api->getElementBySubName($_GET['search']);
?>