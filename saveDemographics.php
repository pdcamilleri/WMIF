<?php

require_once("constants.php");
require_once("database.php");

function post($key) {
  if (isset($_POST[$key])) {
    return $_POST[$key];
  }
  return false;
}

$connection = getDatabaseConnection();

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
$gender = mysql_real_escape_string(post('Gender'));
$age = mysql_real_escape_string(post('Age'));
$education = mysql_real_escape_string(post('Education'));
$employment = mysql_real_escape_string(post('Employment'));
$marital = mysql_real_escape_string(post('Marital'));
$income = mysql_real_escape_string(post('Income'));
$mid = mysql_real_escape_string(post('mid'));

// lets setup our insert query
$sql = sprintf("INSERT INTO %s VALUES (NULL, '%s', '%s', '%s', '%s', '%s', '%s', '%s');",
    'demographics',
    $mid, $gender, $age, $education, $employment, $marital, $income
);

// lets run our query
$result = mysqli_query($connection, $sql);

// setup our response "object"
$resp = new stdClass();
$resp->success = false;
if($result) {
  $resp->success = true;
}

mysqli_close($connection);

print json_encode($resp);
?>
