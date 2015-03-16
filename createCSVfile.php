<?php 

// dump demographic data
// for choice in choiceTable
//   (always have these)
// - choice
// - confidence interval
// - samples for each problem
// - slider outcomes
// - original values 
// - formats shown and randomisation property
//   (only somtimes have these)
// - exp values
// - simul values
// endfor


require_once("constants.php");
require_once("database.php");
date_default_timezone_set('Australia/Sydney');

// connect to database
function createCSV($id) {
  $cxn = getDatabaseConnection();
  $mid = getMIDfromId($cxn, $id);
  $string = createCSVforId($cxn, $id);
  //$filename = "results" . DIRECTORY_SEPARATOR . $id . "." . $mid . "." . date("Y-m-d.H:i:s") . ".csv";
  $filename = "results" . DIRECTORY_SEPARATOR . $mid . ".txt";
  file_put_contents($filename, $string) or die ("Unable to write to file: " . $filename);
  return;
}

function getDemographicHeader() {
  $str = "";
  $str .= "id,";
  $str .= "mid,";
  $str .= "male,";
  $str .= "age,";
  $str .= "education,";
  $str .= "employment,";
  $str .= "marital,";
  $str .= "income,";
  return $str;
}

function getDemographicString($cxn, $id) {
  $query = sprintf("SELECT * FROM demographics WHERE id = '%s';", $id);
  $result = mysqli_query($cxn, $query);
  if (!$result && $result['num_rows'] != 1) {
    error($cxn, "select query failed: $query");
  }

  $row = mysqli_fetch_array($result);

  $str = "";
  $str .= "$row[id],";
  $str .= "$row[mid],";
  $str .= "$row[male],";
  $str .= "$row[age],";
  $str .= "$row[education],";
  $str .= "$row[employment],";
  $str .= "$row[marital],";
  $str .= "$row[income],";
  return $str;

}

function createChoiceHeader() {
  $str = "";
  $str .= "problem_id,";
  $str .= "chose_risky,";
  $str .= "choice_strength,";
  $str .= "friend_recommendation,";
  $str .= "why,";

  return $str;
}

function createChoiceString($cxn, $row) {
  $str = "";
  $str .= "$row[problem_id],";
  $str .= "$row[chose_risky],";
  $str .= "$row[choice_strength],";
  $str .= "$row[friend_recommendation],";
  $str .= "$row[why],";
  return $str;
}

function createSampleHeader() {
  $str = "";
  $str .= "num samples for A,";
  $str .= "num samples for B,";
  return $str;
}

function createSampleString($cxn, $id, $problemId) {
  $query = sprintf("SELECT * FROM samples WHERE id = '%s' AND problem_id = '%s';", $id, $problemId);

  $result = mysqli_query($cxn, $query);

  if (!$result) {
    error($cxn, "select query failed: $query");
  }

  $str = "";
  while ($row = mysqli_fetch_array($result)) {
    $str .= "$row[num_samples],";
  }

  return $str;

}

function createConfidenceIntervalHeader() {
  $str = "";
  $str .= "lower,";
  $str .= "best,";
  $str .= "upper,";
  return $str;
}

function createConfidenceIntervalString($cxn, $id, $problemId) {
  $query = sprintf("SELECT * FROM confidence_interval WHERE id = '%s' AND problem_id = '%s';", $id, $problemId);

  $result = mysqli_query($cxn, $query);

  if (!$result) {
    error($cxn, "select query failed: $query");
  }

  $row = mysqli_fetch_array($result);

  $str = "";
  $str .= "$row[lower],";
  $str .= "$row[best],";
  $str .= "$row[upper],";
  return $str;

}

function createSliderHeader() {
  $str = "";
  for ($i = 1; $i <= 10; $i++) {
    $str .= "slider a $i,";
  }
  for ($i = 1; $i <= 10; $i++) {
    $str .= "slider b $i,";
  }
  return $str;
}

function createSliderString($cxn, $id, $problemId) {
  $query = sprintf("SELECT * FROM slider_outcomes WHERE id = '%s' AND problem_id = '%s';", $id, $problemId);

  $result = mysqli_query($cxn, $query);

  if (!$result) {
    error($cxn, "select query failed: $query");
  }

  $str = "";
  while ($row = mysqli_fetch_array($result)) {
    $str .= "$row[value],";
  }

  return $str;

}

function createOriginalValuesHeader($cxn, $id, $problemId) {

  $query = sprintf("SELECT * FROM original_values WHERE id = '%s' AND problem_id = '%s' AND optn = '%s'", $id, $problemId, 0);

  $result = mysqli_query($cxn, $query);

  if (!$result) {
    error($cxn, "select query failed: $query");
  }

  $str = "";
  while ($row = mysqli_fetch_array($result)) {
    $str .= "option a value,";
  }

  $query = sprintf("SELECT * FROM original_values WHERE id = '%s' AND problem_id = '%s' AND optn = '%s'", $id, $problemId, 1);

  $result = mysqli_query($cxn, $query);

  if (!$result) {
    error($cxn, "select query failed: $query");
  }

  while ($row = mysqli_fetch_array($result)) {
    $str .= "option b value,";
  }

echo "$str";
  return $str;

}

// TODO make the 0/1 optn a variable, refactoring required
// do same for function above
function createOriginalValuesString($cxn, $id, $problemId) {

  $query = sprintf("SELECT * FROM original_values WHERE id = '%s' AND problem_id = '%s' AND optn = '%s'", $id, $problemId, 0);

  $result = mysqli_query($cxn, $query);

  if (!$result) {
    error($cxn, "select query failed: $query");
  }

  $str = "";
  while ($row = mysqli_fetch_array($result)) {
    $str .= "$row[value],";
  }

  $query = sprintf("SELECT * FROM original_values WHERE id = '%s' AND problem_id = '%s' AND optn = '%s'", $id, $problemId, 1);

  $result = mysqli_query($cxn, $query);

  if (!$result) {
    error($cxn, "select query failed: $query");
  }

  while ($row = mysqli_fetch_array($result)) {
    $str .= "$row[value],";
  }

  return $str;

}

// TODO parameterise optn, same as above
function createExperienceValuesHeader($cxn, $id, $problemId) {
  $query = sprintf("SELECT * FROM experience_values WHERE id = '%s' AND problem_id = '%s' AND optn = '%s';", $id, $problemId, 0);

  $result = mysqli_query($cxn, $query);

  if (!$result) {
    error($cxn, "select query failed: $query");
  }

  $str = "";
  while ($row = mysqli_fetch_array($result)) {
    $str .= "experience a value,";
  }

  $query = sprintf("SELECT * FROM experience_values WHERE id = '%s' AND problem_id = '%s' AND optn = '%s';", $id, $problemId, 1);

  $result = mysqli_query($cxn, $query);

  if (!$result) {
    error($cxn, "select query failed: $query");
  }

  while ($row = mysqli_fetch_array($result)) {
    $str .= "experience b value,";
  }

  return $str;

}

// TODO parameterise optn, as above
function createExperienceValuesString($cxn, $id, $problemId) {

  $query = sprintf("SELECT * FROM experience_values WHERE id = '%s' AND problem_id = '%s' AND optn = '%s';", $id, $problemId, 0);

  $result = mysqli_query($cxn, $query);

  if (!$result) {
    error($cxn, "select query failed: $query");
  }

  $str = "";
  while ($row = mysqli_fetch_array($result)) {
    $str .= "$row[value],";
  }

  $query = sprintf("SELECT * FROM experience_values WHERE id = '%s' AND problem_id = '%s' AND optn = '%s';", $id, $problemId, 1);

  $result = mysqli_query($cxn, $query);

  if (!$result) {
    error($cxn, "select query failed: $query");
  }

  while ($row = mysqli_fetch_array($result)) {
    $str .= "$row[value],";
  }

  return $str;

}

function createSimultaneousValuesHeader($cxn, $id, $problemId) {
  $query = sprintf("SELECT * FROM simultaneous_values WHERE id = '%s' AND problem_id = '%s' AND optn = '%s';", $id, $problemId, 0);

  $result = mysqli_query($cxn, $query);

  if (!$result) {
    error($cxn, "select query failed: $query");
  }

  $str = "";
  while ($row = mysqli_fetch_array($result)) {
    $str .= "value,";
  }

  $query = sprintf("SELECT * FROM simultaneous_values WHERE id = '%s' AND problem_id = '%s' AND optn = '%s';", $id, $problemId, 1);

  $result = mysqli_query($cxn, $query);

  if (!$result) {
    error($cxn, "select query failed: $query");
  }

  while ($row = mysqli_fetch_array($result)) {
    $str .= "value,";
  }

  return $str;
}

function createSimultaneousValuesString($cxn, $id, $problemId) {
  $query = sprintf("SELECT * FROM simultaneous_values WHERE id = '%s' AND problem_id = '%s' AND optn = '%s';", $id, $problemId, 0);

  $result = mysqli_query($cxn, $query);

  if (!$result) {
    error($cxn, "select query failed: $query");
  }

  $str = "";
  while ($row = mysqli_fetch_array($result)) {
    $str .= "$row[value],";
  }

  $query = sprintf("SELECT * FROM simultaneous_values WHERE id = '%s' AND problem_id = '%s' AND optn = '%s';", $id, $problemId, 1);

  $result = mysqli_query($cxn, $query);

  if (!$result) {
    error($cxn, "select query failed: $query");
  }

  while ($row = mysqli_fetch_array($result)) {
    $str .= "$row[value],";
  }

  return $str;

}

function createFormatsShownHeader() {
  $str = "";
  $str .= "average_shown,";
  $str .= "description_shown,";
  $str .= "description_random,";
  $str .= "frequency_shown,";
  $str .= "frequency_random,";
  $str .= "distribution_shown,";
  $str .= "distribution_random,";
  $str .= "wordcloud_shown,";
  $str .= "simultaneous_shown,";
  $str .= "simultaneous_random,";
  $str .= "experience_shown,";
  $str .= "experience_random,";
  $str .= "is_switched,";
  return $str;
}

function createFormatsShownString($cxn, $id, $problemId) {
  
  $query = sprintf("SELECT * FROM formats_shown WHERE id = '%s' AND problem_id = '%s';", $id, $problemId);
echo $query;

  $result = mysqli_query($cxn, $query);

  if (!$result) {
    error($cxn, "select query failed: $query");
  }

  $str = "";
  $row = mysqli_fetch_array($result);
  $str .= "$row[average_shown],";
  $str .= "$row[description_shown],";
  $str .= "$row[description_random],";
  $str .= "$row[frequency_shown],";
  $str .= "$row[frequency_random],";
  $str .= "$row[distribution_shown],";
  $str .= "$row[distribution_random],";
  $str .= "$row[wordcloud_shown],";
  $str .= "$row[simultaneous_shown],";
  $str .= "$row[simultaneous_random],";
  $str .= "$row[experience_shown],";
  $str .= "$row[experience_random],";
  $str .= "$row[is_switched],";

echo $str;
  return $str;

}

function createAttentionCheckHeader() {
  $str = "";
  $str .= "attention_format,";
  $str .= "attention_samples,";
  return $str;
}

function createAttentionCheckString($cxn, $id, $problemId) {
  $query = sprintf("SELECT * FROM attention_check WHERE id = '%s' AND problem_id = '%s';", $id, $problemId);

  $result = mysqli_query($cxn, $query);

  if (!$result) {
    error($cxn, "select query failed: $query");
  }

  $row = mysqli_fetch_array($result);

  $str = "";
  $str .= "$row[attention_format],";
  $str .= "$row[attention_samples],";
  return $str;

}

function createHeaderString($cxn, $id) {
  $problemId = 0;
  $str = "";
  $str .= getDemographicHeader();
  $str .= createChoiceHeader();
  $str .= createSampleHeader();
  $str .= createConfidenceIntervalHeader();
  $str .= createSliderHeader();
  $str .= createOriginalValuesHeader($cxn, $id, $problemId);
  $str .= createFormatsShownHeader();
  $str .= createExperienceValuesHeader($cxn, $id, $problemId);
  $str .= createSimultaneousValuesHeader($cxn, $id, $problemId);
  $str .= createAttentionCheckHeader();
  $str .= "\n";

  return $str;
}

// create a CSV file for particular id
function createCSVforId($cxn, $id) {

  /*
  select * from choices where id = $id
    string = demographic data
    for row in result
      string += create csv based on row
    endfor
    */

  $str = createHeaderString($cxn, $id);

  $demographics = getDemographicString($cxn, $id);

  $query = sprintf("SELECT * FROM choices WHERE id = '%s';", $id);

  $result = mysqli_query($cxn, $query);

  if (!$result) {
    error($cxn, "select query failed: $query");
  }

  while ($row = mysqli_fetch_array($result)) {
    $str .= $demographics;

    $str .= createChoiceString($cxn, $row);

    $problemId = $row['problem_id'];

    $str .= createSampleString($cxn, $id, $problemId);
    $str .= createConfidenceIntervalString($cxn, $id, $problemId);
    $str .= createSliderString($cxn, $id, $problemId);
    $str .= createOriginalValuesString($cxn, $id, $problemId);
    $str .= createFormatsShownString($cxn, $id, $problemId);
    $str .= createExperienceValuesString($cxn, $id, $problemId);
    $str .= createSimultaneousValuesString($cxn, $id, $problemId);
    $str .= createAttentionCheckString($cxn, $id, $problemId);
    $str .= "\n";
  }

  return $str;
  
}

/*
// to manually generate CSV files
function main() {
  createCSV(34);
}
main();
*/

?>


