<?php

require_once("constants.php");

$config = parse_ini_file(CONFIG_FILE);

echo json_encode($config);

?>

