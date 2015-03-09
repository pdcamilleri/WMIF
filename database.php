<?php

require_once("constants.php");

function getDatabaseConnection() {
  // Setup variables to connect to database
  $dbConfig = parse_ini_file(DB_CONFIG_FILE);
  $host = $dbConfig[HOST];
  $dbUser = $dbConfig[DB_USER];
  $dbUserPassword = $dbConfig[DB_USER_PASSWORD] . "!";
  $dbName = $dbConfig[DB_NAME];

/*
echo $host;
echo "\n";
echo $dbUser;
echo "\n";
echo $dbUserPassword;
echo "\n";
echo $dbName;
echo "\n";
*/

  $connection = mysqli_connect($host, $dbUser, $dbUserPassword, $dbName, 3306);

  if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
  }

  return $connection;
}

function error($connection, $errorStr = "no error string provided") {
  //echo "$connection\n";
  mysqli_close($connection);

  $resp = new stdClass();
  $resp->success = false;
  $resp->error = $errorStr;
  print json_encode($resp);

  exit();
}

$connection = getDatabaseConnection();

?>
