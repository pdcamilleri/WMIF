var data;

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
function getFrequencyArray(values) {
  var max = 1 + Math.max.apply(null, values);

  // TODO check if values can be negative. if so, abs
  var counts = Array(max);
  for (var i = 0; i < max; ++i) {
    counts[i] = 0;
  }

  for (var i = 0; i < max; ++i) {
    ++counts[values[i]];
  }

  return counts;
}

// want to understand the percentages of each outcome in the entire distribution
// e.g. 20% chance of 4, 80% chance of 3
function createDescription(values) {
  d("creating description");

  var counts = getFrequencyArray(values);

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

// displays the next value to the participant when in the experience condition
function getNextExperienceValue(values) {

  // disable the button, until the end of the animation
  document.getElementById("experienceButton").disabled = true;
  $("#experienceButton").css({ "color" : "red" });
  
  // only define these variables if not already defined
  if (typeof experienceValues === 'undefined') {
    experienceValues = values.slice(0);
  }

  // make the current 

  if (experienceValues.length != 0) {
    var el = experienceValues.shift();

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

  d("creating distribution");
  var data = [ 
    {name: "Locke", value: 4},
    {name: "Reyes", value: 8},
    {name: "sdf", value: 15},
    {name: "sdf", value: 16},
    {name: "Lvfv", value: 23},
    {name: "asdf", value: 42}
  ];


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

  var labelbar = labels.selectAll("g")
      .data(data)
    .enter().append("g")
      .attr("transform", function(d, i) { return "translate(0," + i * barHeight + ")"; });

  labelbar.append("text")
      .attr("y", barHeight / 2)
      .attr("dy", ".35em")
      .text(function(d) { return d.value; });


  function type(d) {
    d.value = +d.value; // coerce to number
    return d;
  }

}

function createFrequency(values) {
  d("creating frequency");
  var counts = getFrequencyArray(values);
  var text = "";
  for (var i = 0; i < counts.length; ++i) {
    if (counts[i] != 0) {
      text += counts[i] + " / " + counts.length 
           + " people have the product a review score of " + i + ", ";
    }
  }
  text[0] = text.charAt(0).toUpperCase() + text.slice(1, -2);
  d(text);

}

// uses D3, D3-cloud and seedrandom to create a word cloud
function createWordCloud(values) {
  d("creating word cloud");

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
