<?php

// TODO get files randomly 
$filename = "inputData/0.csv";

if (($file = fopen($filename, "r")) !== FALSE) {
   $product1 = array_map('intval', fgetcsv($file, 0, ","));
   $product2 = array_map('intval', fgetcsv($file, 0, ","));
}

$data = array($product1, $product2);
echo json_encode($data);

?>
