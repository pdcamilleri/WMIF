<?php>
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
      <title>Decision-making Game</title>
      <link rel="stylesheet" type="text/css" media="screen" href="css/jquery.css" />
      <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
      <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
      <link rel="stylesheet" type="text/css" href="css/main.css" />
      <script type="text/javascript" src="js/WMIF.js"></script>
      <script type="text/javascript">
      	var mid = <?php echo json_encode($mid); ?>;
      </script>
   </head>
   <body>
      <div id="container">
      <?php
        print_r($mid);
      ?>

      <button id="testButton" onclick="testdb()">
        Test Database Button
      </button>

      </div>
   </body>
</html>
