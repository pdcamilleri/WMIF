<?php
session_start();
// TODO find out if its MID or Mid as its case sensitive
$_SESSION['mid'] = $_GET['MID']; 
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <meta http-equiv="Expires" content="0" />
    <meta http-equiv="Cache-Control" content="no-cache" />
    <meta http-equiv="Pragma" content="no-cache" />
    <title>Decision-Making Game</title>
    <link rel="stylesheet" type="text/css" href="css/main.css" />
    <script type="text/javascript" src="js/check_js_enabled.js"></script>
    <!--script src="jquery/jquery.js"></script-->
</head>
<body>
<div id="container" hidden>
    <p class="title">Instructions</p>
    <p>
     Welcome to this decision-making game! 
     In this task you will be presented with 5 different "choice problems". 
     Each choice problem will consist of two different options that you must choose between. 
     Each option will pay out a certain number of points. 
     Note that options are probabilistic and will pay out a <i>different</i> number of points on each play.
  </p>
  <p>
     Your job is to earn the most amount of points by choosing between the options presented in each choice problem. 
     Please make your decisions as if the points correspond to real money and that each choice is independent from any others.
  </p>
  <p>
     The task will take approximately 10 minutes to complete. 
     You will also be asked some demographics questions. 
     Please do not write anything down during the task or use a calculator
     At the end of the task you will receive a code that should be copied back into the Mechanical Turk HIT that will trigger payment. 
     If you are happy to proceed and are 18 years or older, then click on the 'Play' button below.
  </p>
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
