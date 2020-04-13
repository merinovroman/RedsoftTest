<?php
require_once './vendor/autoload.php';
use RomanAM\GetProduct;

$api = new GetProduct(true);

$api->getElementBySection($_GET['sectionId']);
?>