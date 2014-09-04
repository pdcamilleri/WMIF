<?php

define("CONFIG_FILE", "config.ini");

// $config = parse_ini_file(CONFIG_FILE);

$fp = fopen(CONFIG_FILE, "r") or die("Can't open file: " . CONFIG_FILE);

// store updated file contents and do a single write back to the file at the end
$updatedFileContents = "";

// read file line by line. change value if a matching key exists in POST parameters
while (($line = fgets($fp)) !== false) {

  $pattern = '/([^;].*) = (.*)/'; // match a key and value, if first char is not a ';'

  if (preg_match($pattern, $line, $matches)) {
    $key = $matches[1];

    if (isset($_POST[$key])) {
      $property = $_POST[$key];

      // update the value associated with this key
      $updatePattern = '/(.*) = (.*)/';
      $line = preg_replace($updatePattern, "$1 = $property", $line);
    }
  }

  $updatedFileContents .= $line;
}

fclose($fp);

// write all of our changes back to the file
file_put_contents(CONFIG_FILE, $updatedFileContents);

return json_encode($updatedFileContents);

?>
