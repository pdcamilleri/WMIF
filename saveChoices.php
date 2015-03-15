<?php

define("NUM_SLIDERS_OUTCOMES", 10);

require_once("constants.php");
require_once("database.php");
require_once("createCSVfile.php");
require_once("errorlog.php");

function post($key) {
  if (isset($_POST[$key])) {
    return $_POST[$key];
  }
  return false;
}

function saveFilter($connection, $id, $problemID, $optn, $filter, $rFilter, $switched) {
  // TODO feels bad/wrong, if anything anywhere changes, so does all this....
  $insertQuery = sprintf("INSERT INTO %s VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');", 
                  'formats_shown', $id, $problemID, $optn, $switched?1:0,
                  $filter['average']?1:0, // cant randomis
                  $filter['description']?1:0, $rFilter['description']?1:0,
                  $filter['frequency']?1:0, $rFilter['frequency']?1:0,
                  $filter['distribution']?1:0, $rFilter['distribution']?1:0,
                  $filter['wordcloud']?1:0, // cant randomise
                  $filter['simultaneous']?1:0, $rFilter['simultaneous']?1:0,
                  $filter['experience']?1:0, $rFilter['experience']?1:0
                  );
  return mysqli_query($connection, $insertQuery);

}

function insertArrayIntoDatabase($connection, $id, $problemID, $optn, $dbTableName, $array) {
  $results = array();
  for ($i = 0; $i < count($array); $i++) {

    $insertQuery = sprintf("INSERT INTO %s VALUES ('%s', '%s', '%s', '%s', '%s');", 
                    $dbTableName, $id, $problemID, $i, $optn, $array[$i]);

    $result = mysqli_query($connection, $insertQuery);
    array_push($results, $result);
  }
  return $results;
}


function saveChoices($connection, $id, $problemID, $choice, $choiceStrength, $friend, $why) {
  $insertQuery = sprintf("INSERT INTO %s VALUES ('%s', '%s', '%s', '%s', '%s', '%s');", 
                    'choices', $id, $problemID, $choice, $choiceStrength, $friend, $why);

  return mysqli_query($connection, $insertQuery);
}

function saveSamples($connection, $id, $problemID, $optn, $samples1) {
  $insertQuery = sprintf("INSERT INTO %s VALUES ('%s', '%s', '%s', '%s');", 
                      'samples', $id, $problemID, $optn, $samples1);

  return mysqli_query($connection, $insertQuery);
}

function saveAttentionCheck($connection, $id, $problemID, $attnCheck) {
  $insertQuery = sprintf("INSERT INTO %s VALUES ('%s', '%s', '%s', '%s');", 
                      'attention_check', $id, $problemID, $attnCheck['missing'], $attnCheck['numsamples']);

  return mysqli_query($connection, $insertQuery);
}

function saveSimultaneousValues($connection, $id, $problemID, $optn, $array) {
  return insertArrayIntoDatabase($connection, $id, $problemID, $optn, 'simultaneous_values', $array);
}

function saveExperienceValues($connection, $id, $problemID, $optn, $array) {
  return insertArrayIntoDatabase($connection, $id, $problemID, $optn, 'experience_values', $array);
}

function saveOriginalValues($connection, $id, $problemID, $optn, $array) {
  return insertArrayIntoDatabase($connection, $id, $problemID, $optn, 'original_values', $array);
}

// TODO optn is not needed, correct?
function saveConfidenceInterval($connection, $id, $problemID, $optn, $lower, $best, $upper) {
  $insertQuery = sprintf("INSERT INTO %s VALUES ('%s', '%s', '%s', '%s', '%s', '%s');", 
                          'confidence_interval', $id, $problemID, 0, $lower, $best, $upper);
  return mysqli_query($connection, $insertQuery);
}

function saveSliderOutcomes($connection, $id, $problemID, $optn, $idx, $outcomes) {
  $results = array();
  for ($i = 0; $i < count($outcomes); ++$i) {
    $insertQuery = sprintf("INSERT INTO %s VALUES ('%s', '%s', '%s', '%s', '%s');", 
                            'slider_outcomes', $id, $problemID, $optn, $i, $outcomes[$i]);

    $result = mysqli_query($connection, $insertQuery);
    array_push($results, $result);
  }
  return $results;
}


//////////
// MAIN //
//////////

just_log(print_r($_POST, true));

$connection = getDatabaseConnection();

// get all the post paramaters
$survey = post('survey');
$mid = post('mid');

$choice = $survey['choice'];
$choiceStrength = $survey['choiceStrength'];
$friend = $survey['friend'];
$why = $survey['why'];
$upper = $survey['upper'];
$best = $survey['best'];
$lower = $survey['lower'];


$optns = post('products');
$optn1 = $optns[0];
$optn2 = $optns[1];
$samples1 = $optn1['samples'];
$samples2 = $optn2['samples'];
$configs = post('configs');

$filter1 = $optn1['filter'];
$rFilter1 = $optn1['randomiseFilter'];

$filter2 = $optn2['filter'];
$rFilter2 = $optn2['randomiseFilter'];

$sliders1 = $optn1['sliders'];
$sliders2 = $optn2['sliders'];
$switched = post('isSwitched');

$attnCheck = $survey['attentionCheck'];

#if (! ( $mid && $optn1 && $optn2 && $choice && $choiceStrength && $friend)) {
#  error($connection, "not all choice paramaters provided");
#}
//echo "$mid && $choice && $choiceStrength && $why && $upper && $lower && $best)\n";

$problemID = $survey['problemNum'];

// user already exists in database so get this users ID (not MID, as MID may not be unique)
// TODO doesnt make sense to get ID from MID if MID isnt unique. It should be unique
$id = getID($connection, $mid);


if(!saveChoices($connection, $id, $problemID, $choice, $choiceStrength, $friend, $why)) {
  log_and_exit("save choices failed\n" . print_r($_POST, true));
}

saveSamples($connection, $id, $problemID, 0, $samples1);
saveSamples($connection, $id, $problemID, 1, $samples1);

saveSimultaneousValues($connection, $id, $problemID, 0, $optn1['simultaneousValues']);
saveSimultaneousValues($connection, $id, $problemID, 1, $optn2['simultaneousValues']);

saveExperienceValues($connection, $id, $problemID, 0, $optn1['outcomeOrder']);
saveExperienceValues($connection, $id, $problemID, 1, $optn2['outcomeOrder']);

saveOriginalValues($connection, $id, $problemID, 0, $optn1['values']);
saveOriginalValues($connection, $id, $problemID, 1, $optn2['values']);

saveConfidenceInterval($connection, $id, $problemID, 0, $lower, $best, $upper);

saveSliderOutcomes($connection, $id, $problemID, 0, $idx, $sliders1);
saveSliderOutcomes($connection, $id, $problemID, 1, $idx, $sliders2);

// save the formats
saveFilter($connection, $id, $problemID, 0, $filter1, $rFilter1, $switched);
saveFilter($connection, $id, $problemID, 0, $filter2, $rFilter2, $switched);

// Save the answers to the attention check question
saveAttentionCheck($connection, $id, $problemID, $attnCheck);

//echo "creating csv";
createCSV($id);

// setup our response "object"
$resp = new stdClass();
$resp->success = false;
if($result) {
  $resp->success = true;
} else {
  $resp->error = "query failed";
}
mysqli_close($connection);

echo json_encode($resp);
?>

