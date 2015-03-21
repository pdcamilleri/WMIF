<?php

header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

$filename = "inputData/0.csv";
$data = array();

if (($file = fopen($filename, "r")) !== FALSE) {
  while (($line = fgetcsv($file, 0, ",")) !== FALSE) {
    array_push($data, array_map('intval', $line));
  }
}

echo json_encode($data);

?>
