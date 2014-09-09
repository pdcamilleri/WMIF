<?php
/*
   This file just changes the values in a config file if the associated
   key is present in the POST parameters.

*/

define("CONFIG_FILE", "config.ini");
// $config = parse_ini_file(CONFIG_FILE);

$fp = fopen(CONFIG_FILE, "r") or die("Can't open file: " . CONFIG_FILE);

// the approach is going to be to store the updated file contents as we go
// and do a single write back to the file at the end
$updatedFileContents = "";

// read file line by line. change value if a matching key exists in POST parameters
while (($line = fgets($fp)) !== false) {

  $pattern = '/([^;].*) = (.*)/'; 
  // match a key and value, if first char is not a ';'/comment
  // might be a source of BUGS! when dealing with things, eg strings?

  if (preg_match($pattern, $line, $matches)) {
    $key = $matches[1];

    if (isset($_POST[$key])) {
      $value = $_POST[$key];

      // update the value associated with this key
      $updatePattern = '/(.*) = (.*)/';
      $line = preg_replace($updatePattern, "$1 = $value", $line);
    }
  }

  $updatedFileContents .= $line;
}

fclose($fp);

// write all of our changes back to the file
file_put_contents(CONFIG_FILE, $updatedFileContents) or die ("Unable to write to file: " . CONFIG_FILE);

return json_encode($updatedFileContents);

?>
