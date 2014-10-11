<?php

// TODO factor out this code

require_once("constants.php");
require_once("database.php");

function post($key) {
  if (isset($_POST[$key])) {
    return $_POST[$key];
  }
  return false;
}

$connection = getDatabaseConnection();

// setup our response "object"
$resp = new stdClass();
$resp->success = false;

$mid = post('mid');
$products = post('products');
$product1 = $products[0];
$product2 = $products[1];
$choice = post('choice');
$choiceStrength = post('choiceStrength');
$friend = post('friend');

if (! ( $mid && $product1 && $product2 && $choice && $choiceStrength && $friend)) {
  print_r($_POST);
  //printf("not good\n");
  //print_r($_POST['Age']);
  mysqli_close($connection);
  $resp->error = "query failed";
  print json_encode($resp);
  exit();
} else {
  //print_r("all looking good\n"); 
}

$getMIDquery = sprintf("SELECT id FROM demographics WHERE mid = '%s';", $mid);

$result = mysqli_query($connection, $getMIDquery);
if (!$result) { // query failed
  mysqli_close($connection);
  $resp->error = "select query failed: $getMIDquery";
  print json_encode($resp);
  exit();
}

$row = mysqli_fetch_array($result);
if ($row == NULL) {
  mysqli_close($connection);
  $resp->error = "no rows returned from select query: $getMIDquery";
  print json_encode($resp);
  exit();
}

$id = $row['id'];

$insertQuery = sprintf("INSERT INTO %s VALUES ('%s', 0, '%s', '%s', '%s');", 
                    'choices', $id, $choice, $choiceStrength, $friend);

$result = mysqli_query($connection, $insertQuery);

if($result) {
  $resp->success = true;
} else {
  $resp->error = "query failed";
}

mysqli_close($connection);

print json_encode($resp);
?>
