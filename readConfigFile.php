<?php

header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

require_once("constants.php");

$config = parse_ini_file(CONFIG_FILE);

echo json_encode($config);

?>

