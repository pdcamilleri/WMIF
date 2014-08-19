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

      <!-- JQuery -->
      <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
      <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/themes/smoothness/jquery-ui.css" />
      <!--script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/jquery-ui.min.js"></script-->

      <!-- My own stuff -->
      <link rel="stylesheet" type="text/css" href="css/main.css" />
      <script type="text/javascript" src="js/WMIF.js"></script>

      <!-- Bootstrap -->
      <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
      <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

      <!-- D3 & Word Cloud stuff -->
      <script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
      <script src="js/d3-cloud/d3.layout.cloud.js"></script>
      <script src="//cdnjs.cloudflare.com/ajax/libs/seedrandom/2.3.6/seedrandom.min.js"> </script>

      <title>Decision-making Game</title>
   </head>
   <body>
      <div id="container">
        <div id="noticeboard">
          <p> MID is
            <?php
              print_r($mid);
            ?>
          </p>
        </div>

        <!-- Just have all 3 columns present, and change things using JS -->
        <div id="content-left" class="column col-md-4">
          <p> left</p>

          <button class="button" onclick="createDescription(data)"> Create Description</button>

        </div>

        <div id="content-center" class="column col-md-4">
          <p> center</p>
          <button class="button" onclick="createDistribution(data)"> Create Distribution</button>
        </div>

        <div id="content-right" class="column col-md-4">
          <p> right</p>
          <button class="button" onclick="createWordCloud(data)"> Create Word Cloud</button>
        </div>

        <p>
          Some text afterwards
        </p>
        <svg class="chart">
          <defs>
            <linearGradient id="greygradient" x1="0" x2="0" y1="0" y2="1">
              <stop class="stop1" offset="0%"/>
              <stop class="stop2" offset="100%"/>
            </linearGradient>

            <linearGradient id="colorgradient" x1="0" x2="0" y1="0" y2="1">
              <stop offset="0%" stop-color="#F7DFA5"/>
              <stop offset="100%" stop-color="#F0C14B"/>
            </linearGradient>
          </defs>
              
        </svg>
        <svg class="labels"> </svg>

        <div id="simultaneous">
          <button class="button" onclick="createSimultaneous(data)"> Create Simultanous</button>
        </div>
      </div>
   </body>
</html>
