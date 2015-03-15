<?php

function log_and_exit($str = "no error string provided") {
    just_log($str);
      exit();
}

function just_log($str = "no error string provided") {
    error_log($str, 3, "log");
}




?>



