var problem = {
  samples: 1,
  samplesSoFar: 0,
  outcomeOrder: []
};

function nextStage() {
  $("#introduction").hide();
  $("#information").show();
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

// TODO have a config file that is manipulated by the admin console.
// grab the true/false values from this
function populateDisplayFilter() {
  // create filter, populate with garbage, just to create the object so we can iterate over its propeties

  problem.filter = {
    description: 0,
    frequency: 0,
    average: 0,
    distribution: 0,
    wordcloud: 0,
    simultaneous: 0,
    experience: 0,
  };

  $.get("readConfigFile.php", 
      function(config) { 
        // iterate over properties and set hide() or show() based on value in config file
        for (var prop in problem.filter) {
          problem.filter[prop] = getShowOrHideFunction(prop, config[prop]);
        }
        applyDisplayFilter(problem.filter);
        problem.samples = config['samples'];
        $("#productInformation").html(config['productInformation']);
      }, 
      'json'
  );
}

window.onload = function() {
  populateDisplayFilter();
  populateInputValues();
}

// shows and hides certain divs based on the display filter selected by the experimenter
function applyDisplayFilter(filter) {
  for (var prop in filter) {
    filter[prop]();
  }
}

function createInformationDisplays(values) {
  createDescription(values);
  createFrequency(values);
  createAverage(values);
  createDistribution(values);
  createWordCloud(values);
  createSimultaneous(values);
  // experience, TODO how to set this up without global?

}

// AJAX call to grab the input values from the server
function populateInputValues() {
  $.ajax({
    type: "GET",
    url: "getInputData.php",
    success: function(problemValues) {
      problem.values = problemValues;
      // i guess problem.values doesnt need to be global then? except for experience
      // closure? ideal would be to be able to delete the above line problem.values = problemValues
      createInformationDisplays(problem.values);
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

function createAverage(values) {
  var average = calculateAverage(values);
  var paragraph = document.createElement("p");
  paragraph.innerHTML = "Average is " + average + ".";
  document.getElementById("average").appendChild(paragraph);
}

function createDescription(values) {
  var description = createDescriptionString(values);
  var paragraph = document.createElement("p");
  paragraph.innerHTML = description;
  document.getElementById("description").appendChild(paragraph);
}

// want to understand the percentages of each outcome in the entire distribution
// e.g. 20% chance of 4, 80% chance of 3
function createDescriptionString(values) {
  var counts = getFrequencyArray(values);

  var description = "";
  for (var i = 0; i < counts.length; ++i) {
    if (counts[i] != 0) {
      description += (counts[i] * 100.0 / counts.length).toFixed(0) + "% chance of " + i + "<br/>";
    }
  }

  // Upper case first letter, remove trailing ", "
  description = description.charAt(0).toUpperCase() + description.slice(1, -2) + ".";
  return description;

}

// displays the next value to the participant when in the experience condition
function getNextExperienceValue(values) {

  if (problem.samplesSoFar >= problem.samples) {
    d("seen all samples");
    return;
  }

  problem.samplesSoFar++;

  // disable the button, until the end of the animation
  document.getElementById("experienceButton").disabled = true;
  $("#experienceButton").css({ "color" : "red" });
  
  // only define these variables if not already defined
  if (typeof experienceValues === 'undefined') {
    experienceValues = values.slice(0);
  }

  if (experienceValues.length != 0) {
    var el = experienceValues.shift();
    // save the order that each outcome appeared for later
    problem.outcomeOrder.push(el);

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
function createSimultaneous(values) {

  // create a div and table to hold our values
  var div = document.createElement("div");
  var table = document.createElement("table");
  table.classList.add("simultaneousTable");
  table.classList.add("table");
  table.classList.add("table-bordered");
  div.appendChild(table);
  var tbody = table.createTBody();

  // find an element to place this table inside of
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

function createDistribution(values) {
  var counts = getFrequencyArray(values);
  // need to convert this frequency data into an object for our needs
  /*
  var data = [ 
    {name: "Locke", value: 4},
    {name: "Reyes", value: 8},
    {name: "sdf", value: 15},
    {name: "sdf", value: 16},
    {name: "Lvfv", value: 23},
    {name: "asdf", value: 42}
  ];
  */

  var data = [];
  for (var i = 0; i < counts.length; i++) {
    if (counts[i] != 0) {
      data.push({name: i.toString, value: counts[i]});
    }
  }

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

  bar.append("rect")
      .attr("width", function(d) { return x(d.value); })
      .attr("height", barHeight - 1)
      .attr("fill", "url(#colorgradient)");

  bar.append("rect")
      .attr("width", function(d) { return barWidth - x(d.value); })
      .attr("transform", function(d) { return "translate(" + x(d.value) + ", 0)"; })
      .attr("height", barHeight - 1)
      .attr("fill", "url(#greygradient)");
      
  bar.append("text")
      .attr("y", barHeight / 2)
      .attr("transform", function(d) { return "translate(" + (barWidth + 15) + ", 0)"; })
      .attr("dy", ".35em")
      .text(function(d) { return d.value; });

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

function createFrequency(values) {
  var text = createFrequencyString(values);
  var paragraph = document.createElement("p");
  paragraph.innerHTML = text;
  document.getElementById("frequency").appendChild(paragraph);
}

function createFrequencyString(values) {
  var counts = getFrequencyArray(values);
  var text = "";
  for (var i = 0; i < counts.length; ++i) {
    if (counts[i] != 0) {
      text += counts[i] + " / " + counts.length 
           + " people gave the product a review score of " + i + "<br/>";
    }
  }
  text = text.charAt(0).toUpperCase() + text.slice(1, -2) + ".";
  return text;

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
      .words( input
          )
      .padding(5)
      .rotate(function() { return ~~(Math.random() * 2) * 90; })
      .font("Impact")
      .fontSize(function(d) { return d.size; })
      .on("end", draw)
      .start();

  function draw(words) {
    d3.select("div#wordcloud").append("svg")
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
