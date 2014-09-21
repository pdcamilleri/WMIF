<?php
/*
   This file just changes the values in a config file if the associated
   key is present in the POST parameters.

*/

require_once("constants.php");

$fp = fopen(CONFIG_FILE, "r") or die("Can't open file: " . CONFIG_FILE);

// the approach is going to be to store the updated file contents as we go
// and do a single write back to the file at the end
$updatedFileContents = "";

// read file line by line. change value if a matching key exists in POST parameters
while (($line = fgets($fp)) !== false) {

  $quotePattern = '/([^;].*) = (".*")/'; 
  $boolPattern = '/([^;].*) = (.*)/'; 
  // match a key and value, if first char is not a ';'/comment char

  // TODO factorise this somehow?
  if (preg_match($quotePattern, $line, $matches)) {
    $key = $matches[1];

    if (isset($_POST[$key])) {
      $value = $_POST[$key];

      // update the value associated with this key
      $updatePattern = '/(.*) = (.*)/';
      $line = preg_replace($updatePattern, "$1 = \"$value\"", $line);
      if ($line == NULL) {
        echo "error with key: $key";
      }
    }
  } elseif (preg_match($boolPattern, $line, $matches)) {
    $key = $matches[1];

    if (isset($_POST[$key])) {
      $value = $_POST[$key];

      // update the value associated with this key
      $updatePattern = '/(.*) = (.*)/';
      $line = preg_replace($updatePattern, "$1 = $value", $line);
      if ($line == NULL) {
        echo "error with key: $key";
      }
    }
  }

  $updatedFileContents .= $line;
}

fclose($fp);

// write all of our changes back to the file
file_put_contents(CONFIG_FILE, $updatedFileContents) or die ("Unable to write to file: " . CONFIG_FILE);

echo json_encode($updatedFileContents);

?>
