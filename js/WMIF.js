Phase = {
  INTRO: "intro",
  PRODUCT_ONE: "product_1",
  PRODUCT_TWO: "product_2",
  SELECTION: "selection",
  END: "end"
}

function createProduct() {
  return {
    samples: 1,
    samplesSoFar: 0,
    outcomeOrder: [],
    experienceValues: [],
  };
}

var state = {
  products: [createProduct(), createProduct()],
  choice: -1,
  choiceStrength: -1,
  friend: -1
};

var currentProblem = state.products[0];
var configs;
var phase = Phase.INTRO;

function enterFirstProductPhase() {
  currentProblem = state.products[0];
  $("#introduction").hide();
  $("#information").show();
}

// replace all the information on the page with new information
function enterSecondProductPhase() {
  currentProblem = state.products[1];
  createInformationDisplays();
  applyDisplayFilter(currentProblem.filter);
  // TODO thought - maybe stick this in the problem object?
  $("#expertiseText").html(configs['expertise2']);
  $("#productInformation").html(configs['productInformation2']);
  $("#experienceDisplay").html("Click the button to see the first value");
}

function enterSelectionPhase() {
  $("#information").hide();
  $("#selection").show();
}

function enterEndPhase() {
  $("#selection").hide();
  var values = state.products[state.choice - 1].values;
  var randomVal = values[Math.floor(Math.random() * values.length)];
  $("#randomValueFromChoice").html(randomVal);
  $("#end").show();
}

function sendDataToServer() {
  var url = 'saveChoices.php';

  // setup the ajax request
  $.ajax({
    type: 'POST',
    url: url,
    data: state,
    dataType: 'json',
    success: function(response) {
      d("success");
      console.log(response);
    },
    error: function(repsonse) {
      d("error"); },

  });

  return true;
}

function nextPhase() {
  window.scrollTo(0, 0);
  d("ending phase " + phase);
  if (phase == Phase.INTRO) {
    phase = Phase.PRODUCT_ONE;
    enterFirstProductPhase();
  } else if (phase == Phase.PRODUCT_ONE) {
    phase = Phase.PRODUCT_TWO;
    enterSecondProductPhase();
  } else if (phase == Phase.PRODUCT_TWO) {
    phase = Phase.SELECTION;
    enterSelectionPhase();
  } else if (phase == Phase.SELECTION) {
    phase = Phase.END;
    enterEndPhase();
  }
  d("entering phase " + phase);

}

function getShowOrHideFunction(id, isShow) {
  var ret;

  if (isShow != false) {
    ret = function() { $("#" + id).show(); };
  } else {
    ret = function() { $("#" + id).hide(); };
  }

  return ret;
}

// TODO getting a bit unwiedly, even moreso now
function readConfigFile() {
  // create filter, populate with garbage, just to create the object so we can iterate over its propeties

  state.products[0].filter = {
    description: 0,
    frequency: 0,
    average: 0,
    distribution: 0,
    wordcloud: 0,
    simultaneous: 0,
    experience: 0,
  };

  state.products[1].filter = {
    description: 0,
    frequency: 0,
    average: 0,
    distribution: 0,
    wordcloud: 0,
    simultaneous: 0,
    experience: 0,
  };

  state.products[0].randomiseFilter = {
  }

  state.products[1].randomiseFilter = {
  }

  return $.get("readConfigFile.php", 
      function(config) { 
        configs = config;
        // iterate over properties and set hide() or show() based on value in config file
        for (var prop in state.products[0].filter) {
          state.products[0].filter[prop] = getShowOrHideFunction(prop, config[prop]);
          state.products[1].filter[prop] = getShowOrHideFunction(prop, config[prop + "2"]);

          state.products[0].randomiseFilter[prop] = shouldRandomise(config["randomise" + prop]);
          state.products[1].randomiseFilter[prop] = shouldRandomise(config["randomise" + prop + "2"]);
        }

        applyDisplayFilter(state.products[0].filter);
        state.products[0].samples = config['samples'];
        state.products[1].samples = config['samples2'];
        $("#productInformation").html(config['productInformation']);
        $("#expertiseText").html(config['expertise']);
      }, 
      'json'
  );
}

function shouldRandomise(val) {
  return val != false;
}

window.onload = function() {
  state.mid = mid;
  $.when(readConfigFile(), populateInputValues()).done(function() {
    // need to wait for config values (for #samples) and input values before creating
    // information displays

    createInformationDisplays();


  })
  .fail(function() {
    d("one of the AJAX calls failed!")
  });
 
}

// shows and hides certain divs based on the display filter selected by the experimenter
function applyDisplayFilter(filter) {
  for (var prop in filter) {
    filter[prop]();
  }
}

function createInformationDisplays() {
  // i guess problem.values doesnt need to be global then? except for experience
  // closure? ideal would be to be able to delete the above line problem.values = problemValues

  var values = currentProblem.values;

  createDescription(values);
  createFrequency(values);
  createAverage(values);
  createDistribution(values);
  createWordCloud(values);
  createSimultaneous();
  createExperience(values);
  // experience, TODO how to set this up without global?

}

function recordChoice(val) {
  state.choice = val;
  $("#choiceDisplay").html("You chose product " + val);

  $("#choiceButtons").hide();

  // only enable the radio buttons related to this product
  $(".product" + val).attr("disabled", false);
  $("#strengthChoice").show();
  $("#recommendChoice").show();
}

// ensures that the participant has selected answers for both questions
function checkChoices() {
  var formData = $("#choiceForm").serializeArray();
  if (formData.length != 2) {
    alert("Please answer both questions");
    d("Please answer both questions");
  }

  // form is valid, record form data and go to next phase
  state.choiceStrength = formData[0].value;
  state.friend = formData[1].value;
  d("sending data");
  delete state.products[0].filter;
  delete state.products[1].filter;
  sendDataToServer();
  nextPhase();

}

// AJAX call to grab the input values from the server
function populateInputValues() {
  return $.ajax({
    type: "GET",
    url: "getInputData.php",
    success: function(problemValues) {
      state.products[0].values = problemValues[0];
      state.products[1].values = problemValues[1];
    },
    dataType: 'json',
    error: function(jqXHR, textStatus, errorThrown) {
      d("Error! Could not get problemValues from server!");
      d(jqXHR.responseText);
    }
  }); 
}

// move around some columns
// usage: swapColumns($("#content-left"),$("#content-right"));
//var swapColumns = function(mover, target) {
function swapColumns(mover, target) {
  mover.insertAfter(target);
}

// counts the frequency of each integer in the original array.
// the returned array has a length equal to the max value of the paramater array.
// each array cell contains the number of items in the original array
// equal to that cells index.
// assumes the array only contains positive values. TODO
// TODO make this work for floating point values, assumes only integers in input list
function getFrequencyArray(values) {
  var max = 1 + Math.max.apply(null, values);

  // TODO check if values can be negative. if so, abs
  var counts = Array(max - 1);
  for (var i = 0; i < values.length; ++i) {
    counts[i] = 0;
  }

  for (var i = 0; i < values.length; ++i) {
    ++counts[values[i]];
  }

  return counts;
}

// calculates the average value from a list
function calculateAverage(values) {
  var sum = 0;
  for (var i = 0; i < values.length; ++i) {
    sum += values[i];
  }
  return sum /= values.length;
}

function createExperience(values) {
  if (currentProblem.randomiseFilter['experience']) {
    currentProblem.experienceValues = shuffle(values.slice(0));
  } else {
    currentProblem.experienceValues = values.slice(0);
  }
}

function createAverage(values) {
  var average = calculateAverage(values);
  var paragraph = document.createElement("p");
  paragraph.innerHTML = "Average is " + average + ".";
  document.getElementById("average").innerHTML = "";
  document.getElementById("average").appendChild(paragraph);
}

function createDescription(values) {
  var description = createDescriptionString(values);
  var paragraph = document.createElement("p");
  paragraph.innerHTML = description;
  document.getElementById("description").innerHTML = "";
  document.getElementById("description").appendChild(paragraph);
}

// want to understand the percentages of each outcome in the entire distribution
// e.g. 20% chance of 4, 80% chance of 3
function createDescriptionString(values) {
  var counts = getFrequencyArray(values);

  var descriptions = [];
  for (var i = 0; i < counts.length; ++i) {
    if (counts[i] != 0) {
      descriptions.push((counts[i] * 100.0 / counts.length).toFixed(0) + "% chance of " + i + "<br/>");
    }
  }

  if (currentProblem.randomiseFilter['description']) {
    shuffle(descriptions);
  }

  var str = "";
  while (descriptions.length != 0) {
    str += descriptions.shift();
  }
  
  return str;

}

function createFrequency(values) {
  var text = createFrequencyString(values);
  var paragraph = document.createElement("p");
  paragraph.innerHTML = text;
  document.getElementById("frequency").innerHTML = "";
  document.getElementById("frequency").appendChild(paragraph);
}

function createFrequencyString(values) {
  var counts = getFrequencyArray(values);
  var text = [];
  for (var i = 0; i < counts.length; ++i) {
    if (counts[i] != 0) {
      text.push(counts[i] + " / " + counts.length 
           + " people gave the product a review score of " + i + "<br/>");
    }
  }

  if (currentProblem.randomiseFilter['frequency']) {
    shuffle(text);
  }

  var str = "";
  while (text.length != 0) {
    str += text.shift();
  }

  return str;

}

/**
* Randomize array element order in-place.
* Using Fisher-Yates shuffle algorithm.
* http://stackoverflow.com/a/12646864
*/
function shuffle(array) {
  for (var i = array.length - 1; i > 0; i--) {
    var j = Math.floor(Math.random() * (i + 1));
    var temp = array[i];
    array[i] = array[j];
    array[j] = temp;
  }
  return array;
}

// displays the next value to the participant when in the experience condition
function getNextExperienceValue() {

  if (currentProblem.samplesSoFar >= currentProblem.samples) {
    d("seen all samples");
    return;
  }

  currentProblem.samplesSoFar++;

  // disable the button, until the end of the animation
  document.getElementById("experienceButton").disabled = true;
  $("#experienceButton").css({ "color" : "red" });

  if (currentProblem.experienceValues.length != 0) {
    var el = currentProblem.experienceValues.shift();
    // save the order that each outcome appeared for later
    currentProblem.outcomeOrder.push(el);

    var priorFont = $("#experienceDisplay").css("font-size");
    // animate the outcome
    $("#experienceDisplay").animate({ "left": "+=100px", "font-size" : "0px" }, 1500, function() {
      // function callback on completion of the animation.

      // get the new value to be displayed
      $(this).html(el);
      $(this).css({"left" : "-=200px"});
      $(this).animate({ "left": "+=100px", "font-size" : priorFont }, 1500, function() {
        // enable the button again
        document.getElementById("experienceButton").disabled = false;
        $("#experienceButton").css({ "color" : "black" });
      });
    });
  } // else experience counter is >= data.length so do nothing
}


// creates a table that displays all the values to the participant
// at the same time, i.e. simultaneously.
function createSimultaneous() {

  if (currentProblem.randomiseFilter['simultaneous']) {
    currentProblem.simultaneousValues = shuffle(currentProblem.values.slice(0));
  } else {
    currentProblem.simultaneousValues = currentProblem.values.slice(0);
  }

  createSimultaneousTable(currentProblem.simultaneousValues);
}

function createSimultaneousTable(values) {

  // create a div and table to hold our values
  var div = document.createElement("div");
  var table = document.createElement("table");
  table.classList.add("simultaneousTable");
  table.classList.add("table");
  table.classList.add("table-bordered");
  div.appendChild(table);
  var tbody = table.createTBody();

  // find the element to place this table inside of
  document.getElementById("simultaneous").innerHTML = "";
  document.getElementById("simultaneous").appendChild(div);

  // create the table
  var valuesClone = values.slice(0);
  var width = Math.ceil(Math.sqrt(valuesClone.length));

  for (var i = 0; i < width; ++i) {
    var row = tbody.insertRow();
    var j = 0;
    while (valuesClone.length != 0 && j < width) {
      row.insertCell().innerHTML = valuesClone.shift();
      ++j;
    }
    
  }

}


/*
data variable needs to be in this format to create the graph
  var data = [ 
    {name: "Locke", value: 4},
    {name: "Reyes", value: 8},
    {name: "sdf", value: 15},
    {name: "sdf", value: 16},
    {name: "Lvfv", value: 23},
    {name: "asdf", value: 42}
  ];
*/
function createDistribution(values) {
  // clear the previous distribution graph (if any)
  $(".chart > g").remove()

  var counts = getFrequencyArray(values);

  var data = [];
  for (var i = 0; i < counts.length; i++) {
    if (counts[i] != 0) {
      data.push({name: i.toString(), value: counts[i]});
    }
  }

  if (currentProblem.randomiseFilter['distribution']) {
    shuffle(data);
  }

  createChart(data);
}

function createChart(data) {

  var SVGwidth = 450,
      barWidth = 400,
      barHeight = 20;

  var x = d3.scale.linear()
      .range([0, barWidth]);

  // set the domain of x to be [0, sum of all data points]
  x.domain([0, d3.sum(data, function(d) { return d.value; })]);

  var chart = d3.select(".chart")
      .attr("width", SVGwidth);

  chart.attr("height", barHeight * data.length);

  var bar = chart.selectAll("g")
      .data(data)
    .enter().append("g")
      .attr("transform", function(d, i) { return "translate(0," + i * barHeight + ")"; });

  // add the filled out section of the bar (the yellow part)
  bar.append("rect")
      .attr("width", function(d) { return x(d.value); })
      .attr("height", barHeight - 1)
      .attr("fill", "url(#colorgradient)");

  // add the backing section of the bar, the longer full grey bar
  bar.append("rect")
      .attr("width", function(d) { return barWidth - x(d.value); })
      .attr("transform", function(d) { return "translate(" + x(d.value) + ", 0)"; })
      .attr("height", barHeight - 1)
      .attr("fill", "url(#greygradient)");

  // add the labels to each bar
  bar.append("text")
      .attr("y", barHeight / 2)
      .attr("transform", function(d) { return "translate(" + (barWidth + 15) + ", 0)"; })
      .attr("dy", ".35em")
      .text(function(d) { return d.name; });

  // do the exact same thing but in a different svg to align the labels for each bar.
  // dont really need this tho
  //var labels = d3.select(".labels")
  //    .attr("width", barWidth)
  //    .attr("height", barHeight * data.length);

  /*
  var labelbar = labels.selectAll("g")
      .data(data)
    .enter().append("g")
      .attr("transform", function(d, i) { return "translate(0," + i * barHeight + ")"; });

  labelbar.append("text")
      .attr("y", barHeight / 2)
      .attr("dy", ".35em")
      .text(function(d) { return d.value; });
  */

  function type(d) {
    d.value = +d.value; // coerce to number
    return d;
  }

}

// uses D3, D3-cloud and seedrandom to create a word cloud
function createWordCloud(values) {
  // local RNG so that we create the exact same Word Cloud is created for all participants
  Math.seedrandom('wmif'); 

  var length = Math.max.apply(null, values); 
  var counts = Array(length);

  for (var i = 0; i < values.length; ++i) {
    counts[i] = 0;
  }

  for (var i = 0; i < values.length; ++i) {
    ++counts[values[i]];
  }

  var uniqueValues = [];
  for (var i = 0; i < counts.length; ++i) {
    if (counts[i] != 0) {
      uniqueValues.push(i);
    }
  }

  var baseTextSize = 20;
  var textMultiplier = 10;
  var input = uniqueValues.map(function(v) { 
      return {text: v, size: baseTextSize + (textMultiplier * counts[v])}; 
  });

  var fill = d3.scale.category20();
  var size = 300;

  d3.layout.cloud().size([size, size])
      .words(input)
      .padding(5)
      .rotate(function() { return ~~(Math.random() * 2) * 90; })
      .font("Impact")
      .fontSize(function(d) { return d.size; })
      .on("end", draw)
      .start();

  function draw(words) {
    d3.select("div#wordcloud")
        .html("") // clear previous content first
        .append("svg")
        .attr("width", size)
        .attr("height", size)
      .append("g")
        .attr("transform", "translate(100,100)")
      .selectAll("text")
        .data(words)
      .enter().append("text")
        .style("font-size", function(d) { return d.size + "px"; })
        .style("font-family", "Impact")
        //.style("fill", function(d, i) { return fill(i); })
        .attr("text-anchor", "middle")
        .attr("transform", function(d) {
          return "translate(" + [d.x, d.y] + ")rotate(" + d.rotate + ")";
        })
        .text(function(d) { return d.text; });
  }

  // re-seed the RNG with something 
  Math.seedrandom();
}

// adds the string info to the page
function d(info) {
  console.log(info);
  $("#noticeboard").append("<br>" + info);
}
