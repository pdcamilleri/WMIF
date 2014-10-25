<?php
  session_start();
  $_SESSION['mid'] = $_GET['MID']; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <meta http-equiv="Expires" content="0" />
    <meta http-equiv="Cache-Control" content="no-cache" />
    <meta http-equiv="Pragma" content="no-cache" />

    <title>Decision-Making Game</title>

    <link rel="stylesheet" type="text/css" href="css/main.css" />

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/start.js"></script>

  </head>
  <body>
  <div id="container" hidden>
    <p  class="title">Instructions</p>
    <div id="generalInstructions">
    </div>
    <form action="demographics.php" method="GET">
      <div>
        <button class="button" type="submit">Play</button>
      </div>
    </form>
  </div>
  <p id="jsDisabled">
     NOTE: We have checked your browser and found that javascript is not enabled. 
     You will not be able to complete this task unless you enable javascript from your internet browser options screen.
     For instructions on how to do this for your particular web browser, please <a href="http://www.enable-javascript.com/">visit this website</a>.
  </p>
  </body>
</html>
