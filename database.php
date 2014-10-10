<?php

require_once("constants.php");

function getDatabaseConnection() {
  // Setup variables to connect to database
  $dbConfig = parse_ini_file(DB_CONFIG_FILE);
  $host = $dbConfig[HOST];
  $dbUser = $dbConfig[DB_USER];
  $dbUserPassword = $dbConfig[DB_USER_PASSWORD];
  $dbName = $dbConfig[DB_NAME];

  $connection = mysqli_connect($host, $dbUser, $dbUserPassword, $dbName);

  if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
  }

  return $connection;
}

?>
