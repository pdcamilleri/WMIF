var data;

// move around some columns
// usage: swapColumns($("#content-left"),$("#content-right"));
//var swapColumns = function(mover, target) {
function swapColumns(mover, target) {
  mover.insertAfter(target);
}

// want to understand the percentages of each outcome in the entire distribution
// e.g. 20% chance of 4, 80% chance of 3
function createDescription(values) {
  d("creating description");

  var max = Math.max.apply(null, values);

  // TODO check if values can be negative. if so, abs
  var counts = Array(max);
  for (var i = 0; i < values.length; ++i) {
    counts[i] = 0;
  }

  for (var i = 0; i < values.length; ++i) {
    ++counts[values[i]];
  }

  var description = "";
  for (var i = 0; i < counts.length; ++i) {
    if (counts[i] != 0) {
      description += (counts[i] * 100.0 / counts.length).toFixed(0) + "% chance of " + i + ", ";
    }
  }

  // Upper case first letter, remove trailing ", "
  description[0] = description.charAt(0).toUpperCase() + description.slice(1, -2);
  d(description);

}

function createDistribution(values) {
  console.log(values[0]);

}

function createWordCloud(values) {
  console.log(values[0]);
}

window.onload = function() {
  getInputValues();
}

// AJAX call to grab the input values from the server
function getInputValues() {
  $.get("getInputData.php", 
      function(inputData) { 
        console.log(data);
        data = inputData;
      }, 
      'json'
  );
}

// adds the string info to the page
function d(info) {
  console.log(info);
  $("#noticeboard").append("<br>" + info);
}
