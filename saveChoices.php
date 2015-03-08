<?php
# to connect to the database
# % mysql -u wmifuser -pwmifpassword wmifdatabase

define("NUM_SLIDERS_OUTCOMES", 10);

// TODO factor out this code

require_once("constants.php");
require_once("database.php");

function post($key) {
  if (isset($_POST[$key])) {
    return $_POST[$key];
  }
  return false;
}

// TODO, move to common database place
//returns the id corresponding to the given MID
function getID($connection, $mid) {

  $getIDquery = sprintf("SELECT id FROM demographics WHERE mid = '%s';", $mid);

  $result = mysqli_query($connection, $getIDquery);

  if (!$result) {
    error($connection, "select query failed: $getIDquery");
  }

  $row = mysqli_fetch_array($result);
  if ($row == NULL) {
    error($connection, "no rows returned from select query: $getIDquery");
  }

  $id = $row['id'];

  return $id;
}

function saveFilter($connection, $id, $problemID, $optn, $filter /*$rFilter*/) {
  // TODO feels bad/wrong, if anything anywhere changes, so does all this....
  $insertQuery = sprintf("INSERT INTO %s VALUES ('%s', '%s', '%s', '%s', '%s');", 
                  'formats_shown', $id, $problemID, $optn,
                  $filter['average'], // cant randomis
                  $filter['description'], $rFilter['description'],
                  $filter['frequency'], $rFilter['frequency'],
                  $filter['distribution'], $rFilter['distribution'],
                  $filter['wordcloud'], // cant randomise
                  $filter['simultaneous'], $rFilter['simultaneous'],
                  $filter['experience'], $rFilter['experience']
                  );


}

function insertArrayIntoDatabase($connection, $id, $problemID, $optn, $dbTableName, $array) {
  //print_r(count($array) . "\n");
 
  for ($i = 0; $i < count($array); $i++) {
    $insertQuery = sprintf("INSERT INTO %s VALUES ('%s', '%s', '%s', '%s', '%s');", 
                    $dbTableName, $id, $problemID, $i, $optn, $array[$i]);
                    //$dbTableName, '2', '0', $i, $array[$i]);
    $result = mysqli_query($connection, $insertQuery);
    //print_r($insertQuery . "\n");
    //print_r($result . "\n");
  }

}


function saveChoices($connection, $id, $problemID, $choice, $choiceStrength, $friend) {
  $insertQuery = sprintf("INSERT INTO %s VALUES ('%s', '%s', '%s', '%s', '%s');", 
                    'choices', $id, $problemID, $choice, $choiceStrength, $friend);

  return= mysqli_query($connection, $insertQuery);
}

function saveSamples($connection, $id, $problemID, $optn, $samples1) {
  $insertQuery = sprintf("INSERT INTO %s VALUES ('%s', '%s', '%s', '%s');", 
                      'samples', $id, $problemID, $optn, $samples1);

  return mysqli_query($connection, $insertQuery);
}


function saveSimultaneousValues($connection, $id, $problemid, $optn, $array) {
  insertArrayIntoDatabase($connection, $id, $problemID, $optn, 'simultaneous_values', $array);
}

function saveExperienceValues($connection, $id, $problemid, $optn, $array) {
  insertArrayIntoDatabase($connection, $id, $problemID, $optn, 'experience_values', $array);
}

function saveExperienceValues($connection, $id, $problemid, $optn, $array) {
  insertArrayIntoDatabase($connection, $id, $problemID, $optn, 'original_values', $array);
}

// TODO optn is not needed, correct?
function saveConfidenceInterval($connection, $id, $problemID, $optn, $lower, $best, $upper) {
  $insertQuery = sprintf("INSERT INTO %s VALUES ('%s', '%s', '%s', '%s', '%s', '%s');", 
                      'confidence_interval', $id, $problemID, 0, $lower, $best, $upper);

  return mysqli_query($connection, $insertQuery);
}

function saveSliderOutcomes($connection, $id, $problemID, $optn, $outcomes) {

  // TODO this needs to be seriously refactored
  //$sliderOutcomes = (1, 2, 3, 4, 5, 6, 7, 8, 9, 0);
  for ($i = 0; $i < count($outcomes); ++$i) {
    $insertQuery = sprintf("INSERT INTO %s VALUES ('%s', '%s', '%s', '%s', '%s');", 
                        'slider_outcomes', $id, $problemID, $optn, $i, $outcomes[$i]);

    $result = mysqli_query($connection, $insertQuery);
    // TODO doing anything with the result here?
  }
}



$connection = getDatabaseConnection();

// get all the post paramaters
$mid = post('mid');
$optns = post('products');
$optn1 = $optns[0];
$optn2 = $optns[1];
$choice = post('choice');
$choiceStrength = post('choiceStrength');
$friend = post('friend');
$samples1 = $optn1['samples'];
$samples2 = $optn2['samples'];
$configs = post('configs');
$filter1 = $optn1['randomiseFilter'];
#$filter2 = $optn2['randomiseFilter'];
$sliders1 = $optn1['sliders'];
$sliders2 = $optn2['sliders'];

// TODO - different problem IDs for when there are multiple problems
$problemID = 1;

// user already exists in database so get this users ID (not MID, as MID may not be unique)
$id = getID($connection, $mid);


// TODO what to do if $msqli_query fails
saveChoices($connection, $id, $problemID, $choice, $choiceStrength, $friend);

saveSamples($connection, $id, $problemID, 0, $samples1);
saveSamples($connection, $id, $problemID, 1, $samples1);

saveSimultaneousValues($connection, $id, $problemID, 0, $optn1['simultaneousValues']);
saveSimultaneousValues($connection, $id, $problemID, 1, $optn2['simultaneousValues']);

saveExperienceValues($connection, $id, $problemID, 0, 'experience_values', $optn1['outcomeOrder']);
saveExperienceValues($connection, $id, $problemID, 1, 'experience_values', $optn2['outcomeOrder']);

saveOriginalValues($connection, $id, $problemID, 0, 'original_values', $optn1['values']);
saveOriginalValues($connection, $id, $problemID, 1, 'original_values', $optn2['values']);

// save the confidence interval
// TODO
// if ($choice)

$lower = 0;
$best = 0;
$upper = 0;
// TODO remove optn?
saveConfidenceInterval($connection, $id, $problemID, 0, $lower, $best, $upper);

saveSliderOutcomes($connection, $id, $problemID, 0, $idx, $sliders1);
saveSliderOutcomes($connection, $id, $problemID, 1, $idx, $sliders2);

// save the formats
saveFilter($connection, $id, $problemID, 0, $filter1);


if (! ( $mid && $optn1 && $optn2 && $choice && $choiceStrength && $friend)) {
  error($connection, "not all choice paramaters provided");
}


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
