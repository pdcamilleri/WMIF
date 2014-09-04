<?php>
  session_start();
  //Collect all the form data
  $ip = $_SERVER["REMOTE_ADDR"];
  $mid = $_SESSION['mid'];
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <meta http-equiv="Expires" content="0" />
    <meta http-equiv="Cache-Control" content="no-cache" />
    <meta http-equiv="Pragma" content="no-cache" />

    <script src="js/jquery.min.js"></script>

    <script src="js/admin.js"></script>

    <title>Admin Control Panel</title>
  </head>
  <body>
    <div id="noticeboard"> Noticeboard</div>
    <?php   
      $config = parse_ini_file("config.ini");

      print_r($config);
    
    ?>
    <p>
      <button class="button" onclick="saveSettings()"> Save Setting</button>
    </p>
  </body>
</html> 

