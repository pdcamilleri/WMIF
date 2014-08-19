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


// creates a table that displays all the values to the participant
// at the same time, i.e. simultaneously.
function createSimultaneous(values) {

  // create a div and table to hold our values
  var div = document.createElement("div");
  var table = document.createElement("table");
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


  var SVGwidth = 420,
      barWidth = 400,
      barHeight = 20;

  var x = d3.scale.linear()
      .range([0, barWidth]);

  var chart = d3.select(".chart")
      .attr("width", SVGwidth);

  x.domain([0, d3.max(data, function(d) { return d.value; })]);

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

  // do the exact same thing but in a different svg to align the labels for each bar, dont really need this tho
  var labels = d3.select(".labels")
      .attr("width", barWidth);

  var labelbar = labels.selectAll("g")
      .data(data)
    .enter().append("g")
      .attr("transform", function(d, i) { return "translate(0," + i * barHeight + ")"; });

  labelbar.append("text")
      .attr("y", barHeight / 2)
      .attr("dy", ".35em")
      .text(function(d) { return d.value; });

  labels.attr("height", barHeight * data.length);

  function type(d) {
    d.value = +d.value; // coerce to number
    return d;
}




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
    d3.select("body").append("svg")
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
