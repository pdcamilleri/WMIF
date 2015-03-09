<?php

require_once("constants.php");
require_once("database.php");

function post($key) {
  if (isset($_POST[$key])) {
    return $_POST[$key];
  }
  return false;
}

$cxn = getDatabaseConnection();

// check if we can get hold of all of the required form paramaters
if (!
     (
       post('Age')
       && post('Gender')
       && post('Education')
       && post('Employment')
       && post('Marital')
       && post('Income')
       && post('mid')
     )
   ) {
  error("not all demographic fields were received");
}

// let make sure we escape the data
$gender = mysqli_real_escape_string($cxn, post('Gender'));
$age = mysqli_real_escape_string($cxn, post('Age'));
$education = mysqli_real_escape_string($cxn, post('Education'));

$employment = mysqli_real_escape_string($cxn, post('Employment'));

$marital = mysqli_real_escape_string($cxn, post('Marital'));
$income = mysqli_real_escape_string($cxn, post('Income'));
$mid = mysqli_real_escape_string($cxn, post('mid'));

// lets setup our insert query
$sql = sprintf("INSERT INTO %s VALUES (NULL, '%s', '%s', '%s', '%s', '%s', '%s', '%s');",
    'demographics',
    $mid, $gender, $age, $education, $employment, $marital, $income
);

// lets run our query
$result = mysqli_query($cxn, $sql);

// setup our response "object"
$resp = new stdClass();
$resp->success = false;
if($result) {
  $resp->success = true;
}

mysqli_close($cxn);

print json_encode($resp);
?>
