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

    <script src="js/jquery.min.js"></script>

    <script src="js/admin.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="css/main.css" />
    <link rel="stylesheet" type="text/css" href="css/table.css" />

    <title>Admin Control Panel</title>
  </head>
  <body>
    <div id="noticeboard">
      Noticeboard
    </div>

    <hr/>


    <form>
      <label for="generalInstructions">General Instruction:</label>
      <br/>
      <textarea id="generalInstructions" name="generalInstructions" rows="10" cols="100">
        info for the intro page
      </textarea>

      <label for="problemInstructions">Problem Information:</label>
      <br/>
      <textarea id="problemInstructions" name="problemInstructions" rows="10" cols="100">
        Information to be displayed on the first page
      </textarea>
    </form>

    <div id="problem1" class="col-md-6">
      <h4>First problem</h4>
      <form id="mainform">
        <table class="niceTable">
          <thead>
            <tr>
              <th>
                Information formats
              </th>
              <th>
                Randomise?
              </th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <input type="checkbox" id="description" name="description" value="1">
                <label for="description">Description</label>
              </td>
              <td>
                <input type="checkbox" id="randomisedescription" name="randomisedescription" value="1"/>
              </td>
            </tr>

            <tr>
              <td>
                <input type="checkbox" id="frequency" name="frequency" value="1">
                <label for="frequency">Frequency</label>
              </td>
              <td>
                <input type="checkbox" id="randomisefrequency" name="randomisefrequency" value="1"/>
              </td>
            </tr>

            <tr>
              <td>
                <input type="checkbox" id="average" name="average" value="1">
                <label for="average">Average</label>
              </td>
              <td>
                Cannot be randomised
              </td>
            </tr>

            <tr>
              <td>
                <input type="checkbox" id="distribution" name="distribution" value="1">
                <label for="distribution">Distribution</label>
              </td>
              <td>
                <input type="checkbox" id="randomisedistribution" name="randomisedistribution" value="1"/>
              </td>
            </tr>

            <tr>
              <td>
                <input type="checkbox" id="wordcloud" name="wordcloud" value="1">
                <label for="wordcloud">Wordcloud</label>
              </td>
              <td>
                Cannot be randomised
              </td>
            </tr>

            <tr>
              <td>
                <input type="checkbox" id="simultaneous" name="simultaneous" value="1">
                <label for="simultaneous">Simultaneous</label>
              </td>
              <td>
                <input type="checkbox" id="randomisesimultaneous" name="randomisesimultaneous" value="1"/>
              </td>
            </tr>

            <tr>
              <td>
                <input type="checkbox" id="experience" name="experience" value="1">
                <label for="experience">Experience</label>
              </td>
              <td>
                <input type="checkbox" id="randomiseexperience" name="randomiseexperience" value="1"/>
              </td>
            </tr>
          </tbody>
        </table>





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
        <p>
          <label for="expertise">Expertise</label>
          <br/>
          <textarea id="expertise" name="expertise" rows="5" cols="100">
            Little blurb about the reviewers expertise when it comes to the product.
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

    <div id="problem2" class="col-md-6">
      <h4>Second problem</h4>
      <form id="form2">
        <table class="niceTable">
          <thead>
            <tr>
              <th>
                Information formats
              </th>
              <th>
                Randomise?
              </th>
            </tr>
          </thead>
          <tbody>
          <tr>
            <td>
              <input type="checkbox" id="description2" name="description2" value="1"/>
              <label for="description">Description</label>
            </td>
            <td>
              <input type="checkbox" id="randomisedescription2" name="randomisedescription2" value="1"/>
            </td>
          </tr>
          <tr>
            <td>
              <input type="checkbox" id="frequency2" name="frequency2" value="1"/>
              <label for="frequency2">Frequency</label>
            </td>
            <td>
              <input type="checkbox" id="randomisefrequency2" name="randomisefrequency2" value="1"/>
            </td>
          </tr>
          <tr>
            <td>
              <input type="checkbox" id="average2" name="average2" value="1"/>
              <label for="average2">Average</label>
            </td>
            <td>
              Cannot be randomised
            </td>
          </tr>
          <tr>
            <td>
              <input type="checkbox" id="distribution2" name="distribution2" value="1"/>
              <label for="distribution2">Distribution</label>
            </td>
            <td>
              <input type="checkbox" id="randomisedistribution2" name="randomisedistribution2" value="1"/>
            </td>
          </tr>
          <tr>
            <td>
              <input type="checkbox" id="wordcloud2" name="wordcloud2" value="1"/>
              <label for="wordcloud2">Wordcloud</label>
            </td>
            <td>
             Cannot be randomised
            </td>
          </tr>
          <tr>
            <td>
              <input type="checkbox" id="simultaneous2" name="simultaneous2" value="1"/>
              <label for="simultaneous2">Simultaneous</label>
            </td>
            <td>
              <input type="checkbox" id="randomisesimultaneous2" name="randomisesimultaneous2" value="1"/>
            </td>
          </tr>
          <tr>
            <td>
              <input type="checkbox" id="experience2" name="experience2" value="1"/>
              <label for="experience2">Experience</label>
            </td>
            <td>
              <input type="checkbox" id="randomiseexperience2" name="randomiseexperience2" value="1"/>
            </td>
          </tr>
          </tbody>
        </table>

        <p>
          <label for="samples2">Number of samples:</label>
          <input type="number" id="samples2" name="samples2" min="1" max="100" step="1" value="10">
        </p>

        <p>
          <label for="productInformation2">Product Information:</label>
          <br/>
          <textarea id="productInformation2" name="productInformation2" rows="10">
            Information to be displayed at the top of the Page
          </textarea>
        </p>
        <p>
          <label for="expertise2">Expertise</label>
          <br/>
          <textarea id="expertise2" name="expertise2" rows="5" cols="100">
            Little blurb about the reviewers expertise when it comes to the product.
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




