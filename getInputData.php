<?php

$filename = "inputData/0.csv";
$data = array();

if (($file = fopen($filename, "r")) !== FALSE) {
  while (($line = fgetcsv($file, 0, ",")) !== FALSE) {
    array_push($data, array_map('intval', $line));
  }
}

echo json_encode($data);

?>
