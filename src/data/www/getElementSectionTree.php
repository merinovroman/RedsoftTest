<?php
require_once './vendor/autoload.php';
use RomanAM\GetProduct;

$api = new GetProduct(true);

$api->getElementSectionTree($_GET['sectionId']);
?>