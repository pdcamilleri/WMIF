<?php

require_once("constants.php");

function getDatabaseConnection() {
  // Setup variables to connect to database
  $dbConfig = parse_ini_file(DB_CONFIG_FILE);
  $host = $dbConfig[HOST];
  $dbUser = $dbConfig[DB_USER];
  $dbUserPassword = $dbConfig[DB_USER_PASSWORD] . "!";
  $dbName = $dbConfig[DB_NAME];

  $connection = mysqli_connect($host, $dbUser, $dbUserPassword, $dbName, 3306);

  if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
  }

  return $connection;
}

function error($connection, $errorStr = "no error string provided") {
  mysqli_close($connection);

  $resp = new stdClass();
  $resp->success = false;
  $resp->error = $errorStr;
  print json_encode($resp);

  exit();
}

function getMIDfromId($cxn, $id) {
  $query = sprintf("SELECT * FROM demographics WHERE id = '%s';", $id);
  $result = mysqli_query($cxn, $query);
  if (!$result && $result['num_rows'] != 1) {
    error($cxn, "select query failed: $query");
  }

  $row = mysqli_fetch_array($result);

  return $row['mid'];
}

?>
