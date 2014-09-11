// saves the settings specified by the experimenter by writing them to config.ini file
function saveSettings() {

  // TODO will need to come back and make this more general for when there are multiple problems
  // not using jquery's serialzeArray() here as unchecked checkboxes are needed for the moment
  // TODO make checkboxes ticked/unticked based on values in the config file
  // TODO use serialzeArray() and use the configFile values + post data to calculate unchecked boxes
  var filter = $('input:checkbox').map(function() {
    return { 
      name: this.name, 
      value: this.checked ? this.value : false
    };
  });

  $.post("writeConfigFile.php", 
    filter,
    function(file) {
      console.log(file);
      d(file);
    },
    'json'
  );

}

// TODO need to make this restricted to only the same div.
// something like 
// $("button that was clicked").closest("div").find("input:checkbox")
// which goes up to find the closest div, then down to find the checkboxes
function unCheckAll() {
  $("input:checkbox").prop('checked', false);
}

function checkAll() {
  $("input:checkbox").prop('checked', true);
}

// adds the string info to the page
function d(info) {
  console.log(info);
  $("#noticeboard").append("<br>" + info);
}

// ticks the checkboxes based on the values in the config file on the server
function populateCheckboxValues(config) {
  for (var key in config) {
    // if the key matches the id of any element on this page, check its box
    $("#" + key).each(
      function() {
        $(this).prop('checked', config[key]); 
      }
    )
  }
}

function getCheckboxValuesFromServer() {
  $.get("readConfigFile.php", 
    function(config) {
      populateCheckboxValues(config);
    },
    'json'
  );
}

window.onload = function() {
  getCheckboxValuesFromServer();
}

















































