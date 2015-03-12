<?php

function log_and_exit($str = "no error string provided") {
  error_log($str, 3, "log");
  exit();
}

?>


