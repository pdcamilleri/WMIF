// saves the settings specified by the experimenter by writing them to config.ini file
function saveSettings() {

  var checkboxes = $('input:checkbox').map(function() {
    return { 
      name: this.name, 
      value: this.checked ? this.value : false
    };
  });

  var allInputs = $("form").serializeArray();

  var combinedInputs = $.makeArray(checkboxes).concat(allInputs);
  console.log(combinedInputs);

  $.ajax({
    type: "POST",
    url: "writeConfigFile.php",
    data: combinedInputs,
    success: function() {
      d("Settings Saved!");
    },
    dataType: 'json',
    error: function(jqXHR, textStatus, errorThrown) {
      d("Error! Settings were not saved!");
      d(jqXHR.responseText);
    }
  }); 
}

function unCheckAll() {
  $("input:checkbox").prop('checked', false);
}

function checkAll() {
  $("input:checkbox").prop('checked', true);
}

// puts the information from the config file into the forms on the admin page
function populateValues(config) {
  for (var key in config) {
    $("#" + key).each(
      function() {
        if ($(this).is(':checkbox')) {
          // if its a checkbox, we want to check it, not set its value
          $(this).prop('checked', config[key]); 
        } else {
          $(this).val(config[key]);
        }
      }
    )
  }
}

function getCheckboxValuesFromServer() {
  $.get("readConfigFile.php", 
    function(config) {
      populateValues(config);
    },
    'json'
  );
}

window.onload = function() {
  getCheckboxValuesFromServer();
}

// adds the string info to the page
function d(info) {
  console.log(info);
  $("#noticeboard").append("<br>" + info);
}





































