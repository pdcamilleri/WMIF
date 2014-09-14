<?php
  session_start()
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
  <meta http-equiv="Expires" content="0" />
  <meta http-equiv="Cache-Control" content="no-cache" />
  <meta http-equiv="Pragma" content="no-cache" />
  <title>Decision-making Game</title>
  <link rel="stylesheet" type="text/css" href="css/main.css" />
  <script type="text/javascript" src="js/demographics.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
  <script type="text/javascript">
    var mid = <?php echo json_encode($_SESSION['mid']); ?>;
  </script>
</head>
<body>
  <div id="container">
    <form id="DemographicsForm" method="post" action="WMIF.php" onsubmit="return checkAnswers()">

      <p> 
        MTurk ID: <?php echo json_encode($_SESSION['mid']);    ?>
      </p>
      <p class="title">Demographics</p>

      <p>Please fill in your demographic details below.</p>

        <p class="question">
          <label for="Gender">Which of the following best describes your gender?</label> 
        </p>
        <p class="answer">  
          <select id="Gender" name="Gender" class="controlledWidth">
            <option value="">
            </option>
            
            <option value="2">
              Woman
            </option>

            <option value="1">
              Man
            </option>
          </select>
        </p>

        <p class="question">
          <label for="Age">Which of the following best describes your age?</label> 
        </p>
        <p class="answer">  
          <select id="Age" name="Age" class="controlledWidth">
            <option value="">
            </option>
            
            <option value="18">
              18
            </option>

            <option value="19">
              19
            </option>

            <option value="20">
              20
            </option>

            <option value="21">
              21
            </option>

            <option value="22">
              22
            </option>

            <option value="23">
              23
            </option>

            <option value="24">
              24
            </option>

            <option value="25">
              25
            </option>

            <option value="26">
              26
            </option>

            <option value="27">
              27
            </option>

            <option value="28">
              28
            </option>

            <option value="29">
              29
            </option>

            <option value="30">
              30
            </option>

            <option value="31">
              31
            </option>

            <option value="32">
              32
            </option>

            <option value="33">
              33
            </option>

            <option value="34">
              34
            </option>

            <option value="35">
              35
            </option>

            <option value="36">
              36
            </option>

            <option value="37">
              37
            </option>

            <option value="38">
              38
            </option>

            <option value="39">
              39
            </option>

            <option value="40">
              40
            </option>

            <option value="41">
              41
            </option>

            <option value="42">
              42
            </option>

            <option value="43">
              43
            </option>

            <option value="44">
              44
            </option>

            <option value="45">
              45
            </option>

            <option value="46">
              46
            </option>

            <option value="47">
              47
            </option>

            <option value="48">
              48
            </option>

            <option value="49">
              49
            </option>

            <option value="50">
              50
            </option>

            <option value="51">
              51
            </option>

            <option value="52">
              52
            </option>

            <option value="53">
              53
            </option>

            <option value="54">
              54
            </option>

            <option value="55">
              55
            </option>

            <option value="56">
              56
            </option>

            <option value="57">
              57
            </option>

            <option value="58">
              58
            </option>

            <option value="59">
              59
            </option>

            <option value="60">
              60
            </option>

            <option value="61">
              61
            </option>

            <option value="62">
              62
            </option>

            <option value="63">
              63
            </option>

            <option value="64">
              64
            </option>

            <option value="65">
              65
            </option>

            <option value="66">
              66
            </option>

            <option value="67">
              67
            </option>

            <option value="68">
              68
            </option>

            <option value="69">
              69
            </option>

            <option value="70">
              70
            </option>

            <option value="71">
              71
            </option>

            <option value="72">
              72
            </option>

            <option value="73">
              73
            </option>

            <option value="74">
              74
            </option>

            <option value="75">
              75
            </option>

            <option value="76">
              76
            </option>

            <option value="77">
              77
            </option>

            <option value="78">
              78
            </option>

            <option value="79">
              79
            </option>

            <option value="80">
              80
            </option>

            <option value="81">
              80+
            </option>
          </select>
        </p>

        <p class="question">
          <label for="Education">Which best describes your level of education?</label> 
        </p>
        <p class="answer">  
          <select id="Education" name="Education" class="controlledWidth">
            <option value="">
              
            </option>
            
            <option value="1">
              Some high school
            </option>

            <option value="2">
              High school graduate or equivalent
            </option>

            <option value="3">
              Trade or vocational degree
            </option>

            <option value="4">
              Some college/university
            </option>

            <option value="5">
              Associate degree
            </option>

            <option value="6">
              Bachelors degree
            </option>

            <option value="7">
              Graduate or professional degree
            </option>
          </select>
        </p>

        <p class="question">
          <label for="Employment">Which best describes your employment status?</label> 
        </p>
        <p class="answer">  
          <select id="Employment" name="Employment" class="controlledWidth">
            <option value="">
              
            </option>
            
            <option value="1">
              Employed full time
            </option>

            <option value="2">
              Employed part time
            </option>

            <option value="3">
              Not employed but looking for work
            </option>

            <option value="4">
              Not employed and not looking for work
            </option>

            <option value="5">
              Retired
            </option>

            <option value="6">
              Student
            </option>

            <option value="7">
              Homemaker
            </option>
          </select>
        </p>

        <p class="question">
          <label for="Marital">Which best describes your marital status?</label>
        </p>
        <p class="answer">  
          <select id="Marital" name="Marital" class="controlledWidth">
            <option value="">
              
            </option>
            
            <option value="1">
              Single - Not married
            </option>

            <option value="2">
              Married
            </option>

            <option value="3">
              Living with partner
            </option>

            <option value="4">
              Separated
            </option>

            <option value="5">
              Divorced
            </option>

            <option value="6">
              Widowed
            </option>
          </select>
        </p>

        <p class="question">
          <label for="Income">Which includes your total yearly income (before taxes, in US$)?</label> 
        </p>
        <p class="answer">  
          <select id="Income" name="Income" class="controlledWidth">
            <option value="">
              
            </option>
            
            <option value="1">
              Less than $20000
            </option>

            <option value="2">
              $20000 - $29999
            </option>

            <option value="3">
              $30000 - $39999
            </option>

            <option value="4">
              $40000 - $49999
            </option>

            <option value="5">
              $50000 - $69999
            </option>

            <option value="6">
              $70000 - $99999
            </option>

            <option value="7">
              $100000 or more
            </option>
          </select>
        </p>
      <button type="submit" class="button">Continue</button>
    </form>
  </div>
</body>
</html>
