<?php>
  session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <meta http-equiv="Expires" content="0" />
    <meta http-equiv="Cache-Control" content="no-cache" />
    <meta http-equiv="Pragma" content="no-cache" />

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

    <script src="js/admin.js"></script>

    <title>Admin Control Panel</title>
  </head>
  <body>
    <div id="noticeboard">
      Noticeboard
    </div>

    <hr/>

    <div id="problem1">
      <h4>First problem</h4>
      <p>
        Select the information formats that you wish to be displayed for problem 1:
      </p>
      <form id="mainform">
        <p>
          <input type="checkbox" id="description" name="description" value="1">
          <label for="description">Description</label>
        </p>
        <p>
          <input type="checkbox" id="frequency" name="frequency" value="1">
          <label for="frequency">Frequency</label>
        </p>
        <p>
          <input type="checkbox" id="average" name="average" value="1">
          <label for="average">Average</label>
        </p>
        <p>
          <input type="checkbox" id="distribution" name="distribution" value="1">
          <label for="distribution">Distribution</label>
        </p>
        <p>
          <input type="checkbox" id="wordcloud" name="wordcloud" value="1">
          <label for="wordcloud">Wordcloud</label>
        </p>
        <p>
          <input type="checkbox" id="simultaneous" name="simultaneous" value="1">
          <label for="simultaneous">Simultaneous</label>
        </p>
        <p>
          <input type="checkbox" id="experience" name="experience" value="1">
          <label for="experience">Experience</label>
        </p>
        <p>
          <label for="samples">Number of samples:</label>
          <input type="number" id="samples" name="samples" min="1" max="100" step="1" value="10">
        </p>
        <p>
          <label for="productInformation">Product Information:</label>
          <br/>
          <textarea id="productInformation" name="productInformation" rows="10" cols="100">
            Information to be displayed at the top of the Page
          </textarea>
        </p>

      </form>
      <p>
        <button class="button" onclick="unCheckAll()">Uncheck All</button>
      </p>
      <p>
        <button class="button" onclick="checkAll()">Check All</button>
      </p>
      <p>
        <button class="button" onclick="saveSettings()"> Save Setting</button>
      </p>
    </div>

  </body>
</html> 




