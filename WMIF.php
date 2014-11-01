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
      <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/jquery-ui.min.js"></script>

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

      <script type="text/javascript">
        var mid = <?php echo json_encode($_SESSION['mid']); ?>;
      </script>

      <title>Decision-making Game</title>
   </head>
   <body>
      <div id="noticeboard">
        <p> MID is <?php print_r($mid); ?> </p>
      </div>
      <hr/>

      <div id="introduction" class="container">
        <h2 class="page-header"> Problem Instructions </h2>
        <p>
          shouldnt see this
        </p>
        <button class="button" onclick="nextPhase()">Start Experiment</button>
      </div>

      <div id="information" class="container" hidden>

        <div id="productInformation">
          <h3 class="page-header">  </h3>
          <p class="lead"> </p>
        </div>

        <div id="expertise">
          <p id="expertiseText">
            If you can see this, then something (probably JS) is not working correctly.
          </p>
          <!--img src="resources/expert.jpg" height="200">
          <img src="resources/novice.jpg" height="200">
          <img src="resources/biased.jpg" height="200"-->
        </div>

        <div id="average" class="info-format">
          <p>Average</p>
        </div>

        <div id="description" class="info-format">
          <p>Description</p>
        </div>

        <div id="frequency" class="info-format">
          <p>Frequency</p>
        </div>

        <div id="distribution" class="info-format">
          <p>Distribution</p>
          <br/>
          <svg class="labels"> </svg>
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
        </div>

        <div id="wordcloud" class="info-format">
          <p>The size of the score represents how frequently that score has occurred</p>
          <br/>
          <div id="cloud">
          </div>
        </div>

        <div id="simultaneous" class="info-format">
          <p> 
            A list of the scores:
          </p>
          <div id="simultaneous-table">
          </div>
        </div>

        <div id="experience" class="info-format">
          <p>
            Click the button below to see the previous scores. There are <span id="totalScores"></span> previous scores.
          </p>
          <button class="button" id="experienceButton" onclick="getNextExperienceValue()"> Get next value</button>
          <div id="experienceDisplay"> ? </div>
        </div>

        <div>
          <br/>
          <button id="nextPhase" class="button" onclick="nextPhase()">Move to next phase</button>
        </div>

      </div>

      <div id="selection" hidden>
        <div id="choiceButtons" class="container">
          <p>
            Please indicate which of the following do you prefer?
          </p>
          <button id="product1" class="button" value="1" onclick="recordChoice(1)">Product 1</button>
          <button id="product2" class="button" value="2" onclick="recordChoice(2)">Product 2</button>
        </div>

        <p id="choiceDisplay"> </p>


        <form id="choiceForm" class="container" onsubmit="checkChoices(); return false;" hidden>

          <div id="strengthChoice" hidden>
            <p>
              How much do you prefer this option?
            </p>

            <div class="labels"> 
              <label class="alignleft">Strongly prefer product 1</label>
              <label class="aligncenter">Neutral</label>
              <label class="alignright">Strongly prefer product 2</label>
            </div>

            <div class="radioButtons"> 

              <input class="product1" type="radio" name="strength" id="strengthstrong1" disabled="disabled" value="1" />
              <input class="product1" type="radio" name="strength" id="strengthmod1" disabled="disabled" value="2" />
              <input class="product1" type="radio" name="strength" id="strengthweak1" disabled="disabled" value="3" />
              <input class="product1 product2" type="radio" name="strength" id="strengthneutral" disabled="disabled" value="4" />
              <input class="product2" type="radio" name="strength" id="strengthweak2" disabled="disabled" value="5" />
              <input class="product2" type="radio" name="strength" id="strengthmod2" disabled="disabled" value="6" />
              <input class="product2" type="radio" name="strength" id="strengthstrong2" disabled="disabled" value="7" />
            </div>

          </div>

          <br/>

          <div id="choiceWhy" hidden>
            <label for="why">
              Please explain why you choose this option?
            </label>
            <br/>
            <textarea id="why" name="why" rows="5" cols="100">
            </textarea>
          </div>

          <br/>

          <div id="recommendChoice" hidden>
            <p>
              How likely would you be to recommend your choice to someone else?
            </p>

            <div class="labels"> 
              <label class="alignleft">Very likely</label>
              <label class="aligncenter">Neutral</label>
              <label class="alignright">Very unlikely</label>
            </div>

            <br/>

            <div class="radioButtons">
              <input type="radio" name="friend" id="friendstrong1" value="1" />
              <input type="radio" name="friend" id="friendmod1" value="2" />
              <input type="radio" name="friend" id="friendweak1" value="3" />
              <input type="radio" name="friend" id="friendneutral" value="4" />
              <input type="radio" name="friend" id="friendweak2" value="5" />
              <input type="radio" name="friend" id="friendmod2" value="6" />
              <input type="radio" name="friend" id="friendstrong2" value="7" />
            </div>

          </div>

          <div id="sliders" hidden>

          <!-- TODO convert to php loop -->
            <div>

              <br/>
              <p>
                Estimate the likelihood of each outcome occuring
              </p>

              <ul class="sliders" id="sliders_1" index="1">
                <li>
                  <div class="ui-widget slider-box">
                    <span class="outcomeValues">-</span>
                    <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"> 
                    <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 0%;">0</a></div>
                  </div>
                </li>
                <li>
                  <div class="ui-widget slider-box">
                    <span class="outcomeValues">-</span>
                    <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"> 
                    <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 0%;">0</a></div>
                  </div>
                </li>
                <li>
                  <div class="ui-widget slider-box">
                    <span class="outcomeValues">-</span>
                    <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"> 
                    <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 0%;">0</a></div>
                  </div>
                </li>
                <li>
                  <div class="ui-widget slider-box">
                    <span class="outcomeValues">-</span>
                    <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"> 
                    <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 0%;">0</a></div>
                  </div>
                </li>
                <li>
                  <div class="ui-widget slider-box">
                    <span class="outcomeValues">-</span>
                    <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"> 
                    <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 0%;">0</a></div>
                  </div>
                </li>
                <li>
                  <div class="ui-widget slider-box">
                    <span class="outcomeValues">-</span>
                    <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"> 
                    <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 0%;">0</a></div>
                  </div>
                </li>
                <li>
                  <div class="ui-widget slider-box">
                    <span class="outcomeValues">-</span>
                    <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"> 
                    <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 0%;">0</a></div>
                  </div>
                </li>
                <li>
                  <div class="ui-widget slider-box">
                    <span class="outcomeValues">-</span>
                    <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"> 
                    <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 0%;">0</a></div>
                  </div>
                </li>
                <li>
                  <div class="ui-widget slider-box">
                    <span class="outcomeValues">-</span>
                    <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"> 
                    <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 0%;">0</a></div>
                  </div>
                </li>
                <li>
                  <div class="ui-widget slider-box">
                    <span class="outcomeValues">-</span>
                    <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"> 
                    <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 0%;">0</a></div>
                  </div>
                </li>
              </ul>
              <p>
                <span class="sliderScore" id="sliderScore_1" style="color:red">0%</span>
              </p>
            </div>

            <br/>

            <div>
              <ul class="sliders" id="sliders_2" index="1">
                <li>
                  <div class="ui-widget slider-box">
                    <span class="outcomeValues">-</span>
                    <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"> 
                    <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 0%;">0</a></div>
                  </div>
                </li>
                <li>
                  <div class="ui-widget slider-box">
                    <span class="outcomeValues">-</span>
                    <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"> 
                    <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 0%;">0</a></div>
                  </div>
                </li>
                <li>
                  <div class="ui-widget slider-box">
                    <span class="outcomeValues">-</span>
                    <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"> 
                    <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 0%;">0</a></div>
                  </div>
                </li>
                <li>
                  <div class="ui-widget slider-box">
                    <span class="outcomeValues">-</span>
                    <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"> 
                    <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 0%;">0</a></div>
                  </div>
                </li>
                <li>
                  <div class="ui-widget slider-box">
                    <span class="outcomeValues">-</span>
                    <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"> 
                    <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 0%;">0</a></div>
                  </div>
                </li>
                <li>
                  <div class="ui-widget slider-box">
                    <span class="outcomeValues">-</span>
                    <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"> 
                    <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 0%;">0</a></div>
                  </div>
                </li>
                <li>
                  <div class="ui-widget slider-box">
                    <span class="outcomeValues">-</span>
                    <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"> 
                    <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 0%;">0</a></div>
                  </div>
                </li>
                <li>
                  <div class="ui-widget slider-box">
                    <span class="outcomeValues">-</span>
                    <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"> 
                    <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 0%;">0</a></div>
                  </div>
                </li>
                <li>
                  <div class="ui-widget slider-box">
                    <span class="outcomeValues">-</span>
                    <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"> 
                    <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 0%;">0</a></div>
                  </div>
                </li>
                <li>
                  <div class="ui-widget slider-box">
                    <span class="outcomeValues">-</span>
                    <div class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"> 
                    <a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 0%;">0</a></div>
                  </div>
                </li>
              </ul>
              <p>
                <span class="sliderScore" id="sliderScore_2" style="color:red">0%</span>
              </p>
            </div>

          </div>

          <button id="submitChoices">Submit answers</button>
        </form>
      </div>

      <div id="attentionCheck" hidden>
        <form>
          <fieldset>
            <legend>Which of the following did not appear in the experiment</legend>
            <p>
              <input type="radio" name="missing" id="attnmissing" value="0" />
              <label for="missing">A line graph showing review ratings over time</label>
            </p>

            <p>
              <input type="radio" id="attndescription" name="missing" value="1">
              <label for="description">Description</label>
            </p>

            <p>
              <input type="radio" id="attnfrequency" name="missing" value="1">
              <label for="frequency">Frequency</label>
            </p>

            <p>
              <input type="radio" id="attnaverage" name="missing" value="1">
              <label for="average">Average</label>
            </p>

            <p>
              <input type="radio" id="attndistribution" name="missing" value="1">
              <label for="distribution">Distribution</label>
            </p>

            <p>
              <input type="radio" id="attnwordcloud" name="missing" value="1">
              <label for="wordcloud">Wordcloud</label>
            </p>

            <p>
              <input type="radio" id="attnsimultaneous" name="missing" value="1">
              <label for="simultaneous">Simultaneous</label>
            </p>

            <p>
              <input type="radio" id="attnexperience" name="missing" value="1">
              <label for="experience">Experience</label>
            </p>

          </fieldset>
        </form>

        <div>
          <button id="nextPhase" onclick="nextPhase()">Move to next phase</button>
        </div>
      </div>

      <div id="end" class="container" hidden>
        <p>
          Thank you for completing this task. 
          Note, the next review score for the product that you chose was 
          <span id="randomValueFromChoice"></span>.
        </p>
        <p>
          In order to complete this HIT you must submit a completition code back into the AMT website. 
          Your completion code is <strong>WIMF1_X</strong>
        </p>
      </div>
        
   </body>
</html>
