Phase = {
  INTRO: "intro",
  PRODUCT_ONE: "product_1",
  PRODUCT_TWO: "product_2",
  SELECTION: "selection",
  SLIDER: "slider",
  INTERVAL: "interval",
  ATTENTION_CHECK: "attention check",
  END: "end"
};

function createState() {
  return {
    products: [createProduct(), createProduct()],
    survey: { 
      choice: -1,
      choiceStrength: -1,
      friend: -1,
      why: "-",
      upper: -1,
      best: -1,
      lower: -1,
      attentionCheck: {
        missing: -1, //TODO change to format
        numsamples: -1 // TODO change to samples
      }
    },
    isSwitched: false,
    phase: Phase.INTRO
  };
}

function createProduct() {
  return {
    samples: 1,
    samplesSoFar: 0,
    outcomeOrder: [],
    experienceValues: [],
  };
}

var state;
var currentProblem;// = state.products[0];
var configs;

// TODO change to disableButton(#buttonid) { ...
function disableContinueButton() {
  document.getElementById("nextPhase").disabled = true;
  document.getElementById("nextPhase").style.color = 'grey';
}

function enableContinueButton() {
  document.getElementById("nextPhase").disabled = false;
  document.getElementById("nextPhase").style.color = '';
}

function enterFirstProductPhase() {
  currentProblem = state.products[0];
  if (currentProblem.filter['experience'] == true) {
    disableContinueButton();
  } else {
    enableContinueButton();
  }

  $("#introduction").hide();
  $("#information").show();
}

// replace all the information on the page with new information
function enterSecondProductPhase() {
  // TODO can this line be removed?
  currentProblem = state.products[1];
  if (currentProblem.filter['experience'] == true) {
    disableContinueButton();
  } else {
    enableContinueButton();
  }

  // TODO better place for this?
  $("#productInformation").children("h3").text(configs['productText'] + " B Information");
  createInformationDisplays(state.products[1]);
  applyDisplayFilter(currentProblem.filter);
  // TODO thought - maybe stick this in the problem object?
  $("#expertiseText").html(configs['expertise2']);
  $("#productInformation").children("p").html(configs['productInformation2']);
  $("#experienceDisplay").html("");
}

function enterSelectionPhase() {
  $("#information").hide();
  $("#selection").show();
}

function enterSliderPhase() {
  $("#selection").hide();
  $("#slider").show();
}

function enterIntervalPhase() {
  $("#slider").hide();
  $("#interval").show();
}

function enterAttentionCheckPhase() {
  // TODO this needs to change based on what was presented to the user first
  // see issue #55 on github
  document.getElementById("correctlabel").innerHTML = state.products[0].samples;

  // TODO change the values for the numsamples from 0 and 1 if correct to 0
  // if correct and the value of the radio button otherwise

  // set the correct answer based on what was first shown to the user.
  // TODO hack
  if (state.products[0].filter.description == "1") {
    $("#attndescription").val('1');
  } else if (state.products[0].filter.experience == "1") {
    $("#attnexperience").val('1');
  }

  // randomise the order of the radio button options
  // TODO hack
  var arr = $("#attncheck1 p").toArray()
  for (var i = arr.length - 1; i > 0; i--) {
    var j = Math.floor(Math.random() * (i + 1));
    swapNodes(arr[i], arr[j]);
  }

  var arr = $("#attncheck2 p").toArray()
  for (var i = arr.length - 1; i > 0; i--) {
    var j = Math.floor(Math.random() * (i + 1));
    swapNodes(arr[i], arr[j]);
  }

  $("#interval").hide();
  $("#attentionCheck").show();
}

// http://stackoverflow.com/a/698440
function swapNodes(a, b) {
  var aparent = a.parentNode;
  var asibling = a.nextSibling === b ? a : a.nextSibling;
  b.parentNode.insertBefore(a, b);
  aparent.insertBefore(b, asibling);
}

function enterEndPhase() {
  $("#attentionCheck").hide();
  var values = state.products[state.survey.choice].values;
  var randomVal = values[Math.floor(Math.random() * values.length)];

  unrandomise();
  sendDataToServer();
  // TODO
  // TODO if there are more products to display, then restart the experiment
  // TODO
  /*
     shift state.products
     shift state.products
     window.onload()

   */

  $("#randomValueFromChoice").html(randomVal);
  $("#numSamples").html(randomVal);
  $("#end").show();
}

function unrandomise() {
  if (state.isSwitched) {
    // final choice
    state.survey.choice = (state.survey.choice + 1) % 2;
    // choice strength
    state.survey.choiceStrength = 7 - state.survey.choiceStrength + 1;
    // the sliders, best/worst estimate, etc are stored in the product
    // so they don't need to be individually switched

    // Swap the products themselves back
    var tmp = state.products[0];
    state.products[0] = state.products[1];
    state.products[1] = tmp;
  }
}

function sendDataToServer() {
  var url = 'saveChoices.php';

  state.config = configs;
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
  d("ending state.phase " + state.phase);
  if (state.phase == Phase.INTRO) {
    state.phase = Phase.PRODUCT_ONE;
    enterFirstProductPhase();
  } else if (state.phase == Phase.PRODUCT_ONE) {
    state.phase = Phase.PRODUCT_TWO;
    enterSecondProductPhase();
  } else if (state.phase == Phase.PRODUCT_TWO) {
    state.phase = Phase.SELECTION;
    enterSelectionPhase();
  } else if (state.phase == Phase.SELECTION) {
    state.phase = Phase.SLIDER;
    enterSliderPhase();
  } else if (state.phase == Phase.SLIDER) {
    state.phase = Phase.INTERVAL;
    enterIntervalPhase();
  } else if (state.phase == Phase.INTERVAL) {
    state.phase = Phase.ATTENTION_CHECK;
    enterAttentionCheckPhase();
  } else if (state.phase == Phase.ATTENTION_CHECK) {
    state.phase = Phase.END;
    enterEndPhase();
  }
  d("entering state.phase " + state.phase);
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

  return $.get("readConfigFile.php", //?time=" + $.now(), 
      function(config) { 
        // TODO refactor config to be part of state?
        configs = config;
        // iterate over properties and set hide() or show() based on value in config file
        for (var prop in state.products[0].filter) {
          //state.products[0].filter[prop] = getShowOrHideFunction(prop, config[prop]);
          //state.products[1].filter[prop] = getShowOrHideFunction(prop, config[prop + "2"]);
          // set whether or not to show this particular information format?
          state.products[0].filter[prop] = config[prop];
          state.products[1].filter[prop] = config[prop + "2"];

          state.products[0].randomiseFilter[prop] = shouldRandomise(config["randomise" + prop]);
          state.products[1].randomiseFilter[prop] = shouldRandomise(config["randomise" + prop + "2"]);
        }

        applyDisplayFilter(state.products[0].filter);
        state.products[0].samples = parseInt(config['samples']);
        state.products[1].samples = parseInt(config['samples2']);
        $("#productInformation").children("p").html(config['productInformation']);
        $("#expertiseText").html(config['expertise']);
        $("#introduction").children("p").html(config['problemInstructions']);
        $("#slidertext").children("p").html(config['slidertext']);
        $("#intervaltext").children("p").html(config['intervaltext']);
        $(".productText").html(config['productText']);

      }, 
      'json'
  );
}

function shouldRandomise(val) {
  return val != false;
}

// Resets the experiments for the next problem.
function resetExperiment() {
  $(".container").hide();
  $("#introduction").show();
  $("#experienceDisplay").html("");
  setupExperiment();
  // Reset the radio buttons.
  $("input:radio").prop("checked", false);
  // reset the "Why you chose this option?" free response.
  $("#why").val("");

  // Sliders are reset and button is disabled, good :), but the text still says 100% in green.
  $(".sliderScore").css("color", "red");
  $(".sliderScore").css("color", "red").html("0%");

  // Reset the upper, lower and best estimates.
  $("#intervalForm").find("input").val("");

  // TODO https://github.com/pdcamilleri/WMIF/issues/62
  // Reset the sanity check questions. Or is this only done at the end of the experiment...?
  // Next two sanity check questions are still filled out from the previous question.

  resetChoice();

}

function resetChoice() {
  // Show the two buttons for the participant to choose between.
  $("#choiceButtons").show();

  // Hide the survey form.
  $("#choiceForm").hide();

  // Re-enabled all radio buttons 
  // (as some would be disabled, depending on the choice for the previous problem).
  $( "input[name='strength']" ).attr("disabled", false);

}

window.onload = setupExperiment;

function setupExperiment() {
  //$(".container").show();
  state = createState();
  state.mid = mid;
  $.when(readConfigFile(), populateInputValues()).done(function() {
    // need to wait for config values (for #samples) and input values before creating
    // information displays

    // TODO...
    $("#productInformation").children("h3").text(configs['productText'] + " A Information");

    // TODO need to expand for multiple products?
    for (var i = 0; i < state.products.length; i += 2) {
      state.products[i].values = state.products[i].values.slice(0, state.products[i].samples);
      state.products[i + 1].values = state.products[i + 1].values.slice(0, state.products[i + 1].samples);
    }

    randomiseOptions();

    // TODO - factor this line out by removing references to currentProblem
    currentProblem = state.products[0]; // should not need this, should be set when entering first product stage
    // TODO

    createInformationDisplays(state.products[0]);
    initiateSliders();
    disableSliderSubmit();

    populateOutcomeValuesInSlider();

    clickToSelectionPhase(); // DO NOT COMMIT

  })
  .fail(function() {
    d("one of the AJAX calls failed!")
  });
}

// switches options 1 and 2 around 50% of the time
function randomiseOptions() {
  // TODO randomiation turned off, need to fix
  if (Math.random() > 0.5 && false) {
    console.log("Switching!");
    isSwitched = true;
    var tmp = state.products[0];
    state.products[0] = state.products[1];
    state.products[1] = tmp;
  }

}

function clickToSelectionPhase() {
  //$(".button")[0].click(); // click the start exp button so i dont have to
  //$(".button")[0].click(); // click the start exp button so i dont have to
  /*
  $(".button")[0].click(); // click the start exp button so i dont have to
  $("#product1").click();
  $("#strengthstrong1").click();
  $("#friendstrong1").click();
  */
}

// shows and hides certain divs based on the display filter selected by the experimenter
function applyDisplayFilter(filter) {
  for (var prop in filter) {
    // TODO change this to getShowOrHide thing
    func = getShowOrHideFunction(prop, filter[prop]);
    func();
  }
}

function createInformationDisplays(problem) {
  // i guess problem.values doesnt need to be global then? except for experience
  // closure? ideal would be to be able to delete the above line problem.values = problemValues

  var values = problem.values;

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
  state.survey.choice = val;

  $("#choiceDisplay").html("You chose: <br/> <h3 style='font-weight: 700;'>" + configs['productText'] +
      " " + String.fromCharCode(65 + val) + "</h3>");

  $("#choiceButtons").hide();

  // only enable the radio buttons related to this product
  $(".product" + (val + 1)).attr("disabled", false);

  $("#choiceForm").show();
  // TODO remove some of these, just hide the outer guy?
  $("#strengthChoice").show();
  $("#recommendChoice").show();
  $("#choiceWhy").show();
  $("#sliders").show();

}

function initiateSliders() {
  var sliders = $(".sliders .ui-slider");
  sliders.slider({ 
    value: 0,
    min: 0,
    max: 100,
    step: 1,
    slide: function(event, ui) {
      var handle = ui.handle;

      handle.innerHTML = ui.value;

      var total = 0;
      // sums up the total value of each slider that is not the current slider
      $(this).parent().parent().parent().find(".ui-slider").not(this).each(function() {
        total += $(this).slider("option", "value");
      });

      // Need to do this because apparently jQ UI
      // does not update value until this event completes
      total += ui.value;

      // show the value to the user

      // first get the score associated with this series of sliders
      var sliderScore = $(this).parent().parent().parent().parent().find(".sliderScore");
      sliderScore.html(total + '%');

      // update the color if the sliders total to 100%
      if (total == 100) {
        sliderScore.css('color','green');
      } else {
        sliderScore.css('color','red');
      }

      // see if we should enable the submit button
      checkSliderTotals();

    }
  }); 

  // set the initial value of the slider to be 0
  $(".ui-slider-handle").text("0");
}

function checkSliderTotals() {
  var allSlidersAre100 = true;

  // check all the sliders. if just one is off, disable the submit button
  $(".sliderScore").each(function() {
    if ($(this).html() != '100%') {
      allSlidersAre100 = false;
    }
  });

  if (allSlidersAre100) {
    enableSliderSubmit();
  } else {
    disableSliderSubmit();
  }

}

function disableSliderSubmit() {
  document.getElementById("submitChoices").disabled = true;
  document.getElementById("submitChoices").style.color = 'grey';
}

function enableSliderSubmit() {
  document.getElementById("submitChoices").disabled = false;
  document.getElementById("submitChoices").style.color = '';
}

// this function sets the outcomes for the sliders to correspond to the particular choice set/problem
function populateOutcomeValuesInSlider() {
  var numSliderSets = state.products.length;
  for (var i = 1; i < numSliderSets + 1; ++i) {
    var values = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

    $("#sliders_" + i).find(".outcomeValues").each(function() {
      $(this).html(values.shift());
    });

  }

}

// http://stackoverflow.com/a/1890233 
function unique(arr) {
  var hash = {}, result = [];
  for ( var i = 0, l = arr.length; i < l; ++i ) {
    if ( !hash.hasOwnProperty(arr[i]) ) { //it works with objects! in FF, at least
      hash[ arr[i] ] = true;
      result.push(arr[i]);
    }
  }
  return result;
}

// ensures that the participant has selected answers for both questions
function checkChoices() {
  var formData = $("#choiceForm").serializeArray();
  // TODO bit of a hack here
  var count = 0;
  for (var i = 0; i < formData.length; i++) {
    if (formData[i].name == "strength") {
      state.survey.choiceStrength = formData[i].value;
      count++;
    } else if (formData[i].name == "friend") {
      state.survey.friend = formData[i].value;
      count++;
    } else if (formData[i].name == "why") {
      state.survey.why = formData[i].value;
      // we don't count the why field, as it is optional
    }
  }

  if (count != 2) {
    alert("Please answer both questions");
    d("Please answer both questions");
    return;
  }

  // form is valid, record form data and go to next state.phase
  //state.survey.choiceStrength = formData[0].value;
  //state.survey.friend = formData[1].value;
  d("sending data to server");
  // TODO map to convert this to true/falses based on show/hide
  // maybe one extra level of indirection, store the true/false in the product
  // and call the show/hide based on that
  //delete state.products[0].filter;
  //delete state.products[1].filter;
  //sendDataToServer();
  //state.survey.friend = $('input[name=strength]:checked', '#choiceForm').val();
  nextPhase();

}

function checkInterval() {
  state.survey.lower = $("#lowerEstimate").val();
  state.survey.best  = $("#bestEstimate").val();
  state.survey.upper = $("#upperEstimate").val();
  nextPhase();
}

// saves the slider choices into the state
function saveSliderChoices() {

  // TODO 
  //$("#sliders_1 .slider-box > .outcomeValues") // to only grab out the first sliders stuff
  //$(".slider-box .ui-slider-handle")[0].innerHTML == "100"
  //$(".slider-box > .outcomeValues")[0].innerHTML == "1"

  for (var i = 0; i < 2; ++i) {
    var sliders = []; 
    $("#sliders_" + (i + 1) + " .slider-box .ui-slider-handle").each(function() { 
      sliders.push($(this).html());
    });
    state.products[i].sliders = sliders;
  }
  nextPhase();

}

// checks that at least one opiton was selected
function checkAttention() {
  var formData = $("#attentionForm").serializeArray();
  if (formData.length != 2) {
    alert("Please answer both questions before continuing");
    return;
  }
  state.survey.attentionCheck[formData[0].name] = formData[0].value;
  state.survey.attentionCheck[formData[1].name] = formData[1].value;
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
  var counts = Array(max);
  for (var i = 0; i < counts.length; ++i) {
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

  document.getElementById("totalScores").innerHTML = currentProblem.samples;

  // fix the height of the experience element
  //$("#experience").css({height : $("#experience").height() + 10});
  $("#experience").css({height : "150px"});

  enableExperienceButton(1);

}

function createAverage(values) {
  var average = calculateAverage(values).toFixed(1);
  var paragraph = document.createElement("p");
  paragraph.innerHTML = "Average score was " + average;
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
      descriptions.push((counts[i] * 100.0 / values.length).toFixed(0) + "% of scores were " + i + "<br/>");
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
      text.push(counts[i] + " / " + values.length 
           + " scores were " + i + "<br/>");
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

  disableExperienceButton();

  if (currentProblem.experienceValues.length != 0) {
    var el = currentProblem.experienceValues.shift();
    // save the order that each outcome appeared for later
    currentProblem.outcomeOrder.push(el);

    //var animationLength = 1500;
    var animationLength = 100; // debug animation length

    var priorFont = $("#experienceDisplay").css("font-size");
    // animate the outcome
    $("#experienceDisplay").animate({ "left": "+=100px", "font-size" : "0px" }, animationLength, function() {

      // get the new value to be displayed
      $(this).html(el);
      $(this).css({"left" : "-=200px"});
      $(this).animate({ "left": "+=100px", "font-size" : priorFont }, animationLength, function() {

        if (currentProblem.samples != currentProblem.samplesSoFar) {
          enableExperienceButton(currentProblem.samplesSoFar + 1);
        } else {
          document.getElementById("experienceButton").innerHTML = "All scores have been seen";
          enableContinueButton();
        }

      });
    });
  } // else experience counter is >= data.length so do nothing
}

function disableExperienceButton() {
  document.getElementById("experienceButton").disabled = true;
  $("#experienceButton").css({ "color" : "red" });
}

function enableExperienceButton(num) {
  document.getElementById("experienceButton").disabled = false;
  $("#experienceButton").css({ "color" : "black" });
  document.getElementById("experienceButton").innerHTML = 
    "See score " + num + " of " + currentProblem.samples;
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
  var tbody = table.appendChild(document.createElement('tbody'));

  // find the element to place this table inside of
  //document.getElementById("simultaneous").innerHTML = "";
  document.getElementById("simultaneous-table").innerHTML = "";
  document.getElementById("simultaneous-table").appendChild(div);

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
  $(".chart > g").remove();

  // get data in the correct format (see above comment) 
  var counts = getFrequencyArray(values);
  var data = [];
  // add extra 0's if we need them
  for (var i = counts.length; i <= 10; i++) {
    counts.push(0);
  }
  for (var i = 0; i < counts.length; i++) {
    // add extra 0's if we need them
    data.push({name: i.toString(), value: counts[i]});
  }

  if (currentProblem.randomiseFilter['distribution']) {
    shuffle(data);
  } else {
    data.reverse();
  }

  createChart(data);
}

function createChart(data) {

  var SVGwidth = 450,
      labelsWidth = 100,
      labelsHeight = 20,
      barWidth = 400,
      barHeight = labelsHeight;

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
      .text(function(d) { return d.value; });

  // TODO table this up like amazon does
  var labels = d3.select(".labels")
      .attr("width", labelsWidth)
      .attr("height", labelsHeight * data.length);

  var labelbar = labels.selectAll("g")
      .data(data)
    .enter().append("g")
      .attr("transform", function(d, i) { return "translate(0," + i * barHeight + ")"; });

  labelbar.append("text")
      .attr("y", barHeight / 2)
      .attr("dy", ".35em")
      .text(function(d) { return d.name + " stars"; });

  function type(d) {
    d.value = +d.value; // coerce to number
    return d;
  }

}

// uses D3, D3-cloud and seedrandom to create a word cloud
function createWordCloud(values) {
  // local RNG so that we create the exact same Word Cloud is created for all participants
  Math.seedrandom('wmif'); 

  var counts = getFrequencyArray(values);

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
    d3.select("div#wordcloud > #cloud")
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
  //$("#noticeboard").append("<br>" + info);
}



