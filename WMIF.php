<?php
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

      <!-- Bootstrap -->
      <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
      <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

      <!-- D3 & Word Cloud stuff -->
      <script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
      <script src="js/d3-cloud/d3.layout.cloud.js"></script>
      <script src="//cdnjs.cloudflare.com/ajax/libs/seedrandom/2.3.6/seedrandom.min.js"> </script>

      <!-- My own stuff -->
      <link rel="stylesheet" type="text/css" href="css/main.css" />
      <script type="text/javascript" src="js/WMIF.js"></script>

      <title>Decision-making Game</title>
   </head>
   <body>
      <div id="noticeboard">
        <p> MID is <?php print_r($mid); ?> </p>
      </div>
      <hr/>

      <div id="introduction" class="container">
        <p>
          Going to show you some information related to the product asd.
          Want you to look at the reviews and make a decision on whether or not you want to buy that product.
        </p>
        <p>
          When you are ready, click the button to start the experiment
        </p>
        <button class="button" onclick="nextPhase()">Start Experiment</button>
      </div>

      <div id="information" class="container" hidden>

        <div id="productInformation">
          If you can see this, then something (probably JS) is not working correctly.
        </div>

        <div id="expertise">
          <p id="expertiseText">
            If you can see this, then something (probably JS) is not working correctly.
          </p>
          <img src="resources/expert.jpg" height="200">
          <img src="resources/novice.jpg" height="200">
          <img src="resources/biased.jpg" height="200">
        </div>

        <div id="average">
          <p>Average</p>
        </div>

        <div id="description">
          <p>Description</p>
        </div>

        <div id="frequency">
          <p>Frequency</p>
        </div>

        <div id="distribution">
          <p>Distribution</p>
          <br/>
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
        </div>

        <div id="wordcloud">
          <p>Word Cloud</p>
        </div>

        <div id="simultaneous">
        </div>

        <div id="experience">
          <button class="button" id="experienceButton" onclick="getNextExperienceValue(problem.values)"> Get next value</button>
          <div id="experienceDisplay"> Click the button to see the first value</div>
        </div>

        <div>
          <button id="nextPhase" onclick="nextPhase()">Move to next phase</button>
        </div>

      </div>

      <div id="selection" hidden>
        <div id="choiceButtons">
          <p>
            After reviewing each product, which do you prefer?
          </p>
          <button id="product1" class="button" value="1" onclick="recordChoice(1)">Product 1</button>
          <button id="product2" class="button" value="2" onclick="recordChoice(2)">Product 2</button>
        </div>

        <div id="preferenceStrength">
          <p>
            How much do you prefer this choice?
          </p>
          <form>
            <fieldset>
              <legend>Preference</legend>
                <label class="textleft">Strongly prefer product 1</label>
                <label class="textcenter" for="size_1">Neutral</label>
                <label class="textright" for="size_1">Strongly prefer product 2</label>
                
                <br/>
                <input type="radio" name="preference" id="strong1" value="1" />
                <input type="radio" name="preference" id="mod1" value="2" />
                <label for="modStrong1"></label>
                <input type="radio" name="preference" id="weak1" value="3" />
                <label for="size_1"></label>
                <input type="radio" name="preference" id="neutral" value="4" />
                <label for="size_1"></label>
                <input type="radio" name="preference" id="weak2" value="5" />
                <label for="size_1"></label>
                <input type="radio" name="preference" id="mod2" value="6" />
                <label for="size_1"></label>
                <input type="radio" name="preference" id="strong2" value="7" />
            </fieldset>
          </form>
        </div>

        <div id="friendRecommendation" hidden>
          <p>
            How likely would you be to recommend this product to a friend?
          </p>
        </div>

        <div>
          <button id="nextPhase" onclick="nextPhase()">Move to next phase</button>
        </div>
      </div>

      <div id="end" class="container" hidden>
        <p>
          Thank you for participating.
          Your code is ...
        </p>
      </div>
        
   </body>
</html>
