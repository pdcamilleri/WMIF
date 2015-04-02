<?php
  session_start();
  //Collect all the form data
  $ip = $_SERVER["REMOTE_ADDR"];
  $mid = $_SESSION['mid'];
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
   <head>
      <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />

      <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
      <meta http-equiv="Pragma" content="no-cache" />
      <meta http-equiv="Expires" content="0" />

      <!-- JQuery -->
      <!--script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script-->
      <script src="js/jquery.min.js"></script>
      <!--link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/themes/smoothness/jquery-ui.css" /-->
      <!--script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/jquery-ui.min.js"></script-->
      <script src="js/jquery-ui.min.js"></script>
      <link rel="stylesheet" href="css/jquery-ui.css" />

      <!-- Bootstrap -->
      <!--script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script-->
      <script src="js/bootstrap.min.js"></script>
      <!--link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css"-->
      <link rel="stylesheet" href="css/bootstrap.css">

      <!-- D3 & Word Cloud stuff -->
      <!--script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script-->
      <script src="js/d3.v3.min.js" charset="utf-8"></script>
      <!--script src="js/d3-cloud/d3.layout.cloud.js"></script-->
      <script src="js/d3-cloud/d3.layout.cloud.js"></script>
      <!--script src="//cdnjs.cloudflare.com/ajax/libs/seedrandom/2.3.6/seedrandom.min.js"> </script-->
      <script src="js/seedrandom.min.js"> </script>

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
        <!--p> MID is <?php print_r($mid); ?> </p-->
        <!--button onclick="resetExperiment();"> Reset Experiment</button-->
      </div>
      <hr/>

      <div id="introduction" class="container">
        <h2 class="page-header"> Scenario <span id="problemNum">1</span></h2>
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
          <div id="experienceDisplay"></div>
        </div>

        <div>
          <br/>
          <button id="nextPhase" class="button" onclick="nextPhase()">Continue</button>
        </div>

      </div>

      <div id="selection" class="container" hidden>
        <div id="choiceButtons">
          <p class="question-text">
            Please indicate which of the following you prefer?
          </p>
          <button id="product1" class="button" value="1" onclick="recordChoice(0)">
            <span class="productText"></span>
            A
          </button>
          <button id="product2" class="button" value="2" onclick="recordChoice(1)">
            <span class="productText"></span> 
            B
          </button>
        </div>

        <form id="choiceForm" onsubmit="checkChoices(); return false;" hidden>

          <p id="choiceDisplay"> </p>

          <div id="strengthChoice" class="question" hidden>
            <p class="question-text">
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

          <div id="choiceWhy" class="question" hidden>
            <label class="question-text" for="why">
              Please explain why you choose this option?
            </label>
            <br/>
            <textarea id="why" name="why" rows="5" cols="100"></textarea>
          </div>

          <div id="recommendChoice" class="question" hidden>
            <p class="question-text">
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
          <button class="button">Continue</button>
        </form>
      </div>



      <div id="slider" class="container" hidden>
          <div id="sliders" class="question">

          <!-- TODO convert to php loop -->
            <div>

              <div class="question-text" id="slidertext">
                <p></p>
              </div>

              <label> 
                <span class="productText"></span> 
                A
              </label>

              <div class="sliders">
                  <ul  id="sliders_1" index="1">
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
            </div>

            <br/>

            <div>

              <label> 
                <span class="productText"></span> 
                B
              </label>

              <div class="sliders">
                  <ul id="sliders_2" index="1">
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
          </div>
          <button class="button" id="submitChoices" onclick="saveSliderChoices();">Continue</button>
        </div>



      <div id="interval" class="container" hidden>
        <form id="intervalForm" class="question" onsubmit="checkInterval(); return false;">

          <div id="intervaltext" class="question-text">
            <p></p>
          </div>
          
          <p>
            <label for="lowerEstimate">Lower estimate: </label>
            <input type="number" id="lowerEstimate" name="lowerEstimate" min="1" max="10" step="1" required>
          </p>
 
          <p>
            <label for="bestEstimate">Best estimate:</label>
            <input type="number" id="bestEstimate" name="bestEstimate" min="1" max="10" step="1" required>
          </p>
 
          <p>
            <label for="upperEstimate">Upper estimate:</label>
            <input type="number" id="upperEstimate" name="upperEstimate" min="1" max="10" step="1" required>
          </p>
          <button class="button" id="submitEstimates">Continue</button>
        </form>
      </div>

      <div id="attentionCheck" class="container" hidden>
        <form id="attentionForm" >
          <fieldset id="attncheck1">
            <legend>
              What type of products were reviewed throughout this study?
            </legend>

            <p>
              <input type="radio" id="attn1correct" name="format" value="1">
              <label for="attn1correct">
                <span class="productText"></span> 
              </label>
            </p>

            <p>
              <input type="radio" id="attn1wrong1" name="format" value="2">
              <label for="attn1wrong1">
                Computer games
              </label>
            </p>

            <p>
              <input type="radio" id="attn1wrong2" name="format" value="3">
              <label for="attn1wrong2">
                Laptops
              </label>
            </p>

            <p>
              <input type="radio" id="attn1wrong3" name="format" value="4">
              <label for="attn1wrong3">
                Operating systems
              </label>
            </p>

            <p>
              <input type="radio" id="attn1wrong4" name="format" value="5">
              <label for="attn1wrong4">
                Movies
              </label>
            </p>

            <p>
              <input type="radio" id="attn1wrong5" name="format" value="6">
              <label for="attn1wrong5">
                Tablets
              </label>
            </p>
          </fieldset>

          <fieldset id="attncheck2">
            <legend>
              In the task, how many review scores did you learn about for the first product?
            </legend>

            <p>
              <input type="radio" id="wrong1" name="samples" value="20">
              <label for="wrong1">
                20
              </label>
            </p>

            <p>
              <input type="radio" id="wrong2" name="samples" value="30">
              <label for="wrong2">
                30
              </label>
            </p>

            <p>
              <input type="radio" id="wrong3" name="samples" value="40">
              <label for="wrong3">
               40 
              </label>
            </p>

            <p>
              <input type="radio" id="wrong4" name="samples" value="50">
              <label for="wrong4">
               50 
              </label>
            </p>

            <p>
              <input type="radio" id="wrong5" name="samples" value="60">
              <label for="wrong5">
               60 
              </label>
            </p>

            <p>
              <input type="radio" id="correct" name="samples" value="1">
              <label id="correctlabel" for="correct">
              </label>
            </p>

          </fieldset>

          <input type="submit" value="Continue" class="button" id="submitAttention" 
                 onclick="checkAttention(); return false;"/>

        </form>
      </div>

      <div id="end" class="container" hidden>
        <p>
          Thank you for completing this task. 
          Note, the next review score for the product that you chose was 
          <span id="randomValueFromChoice"></span>.
        </p>
        <p>
          In order to complete this HIT you must submit a completition code back into the AMT website. 
          Your completion code is <strong>WIMF1_<span id="numSamples"><span></strong>
        </p>
      </div>
        
   </body>
</html>


