// saves the settings specified by the experimenter by writing them to config.ini file
function saveSettings() {

  // TODO will need to come back and make this more general for when there are multiple problems
  var filter = $('input:checkbox').map(function() {
    return { 
      name: this.name, 
      value: this.checked ? this.value : false
    };
  });

  // TODO use serialzeArray?
  filter.push({ 
    name: "samples", 
    value: $("#samples").val() 
  });

  filter.push({ 
    name: "productInformation", 
    value: $("#productInformation").val()
  });

  filter.push({ 
    name: "expertise", 
    value: $("#expertise").val()
  });


  filter.push({ 
    name: "samples2", 
    value: $("#samples2").val() 
  });

  filter.push({ 
    name: "productInformation2", 
    value: $("#productInformation2").val()
  });

  filter.push({ 
    name: "expertise2", 
    value: $("#expertise2").val()
  });




  $.ajax({
    type: "POST",
    url: "writeConfigFile.php",
    data: filter,
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

// adds the string info to the page
function d(info) {
  console.log(info);
  $("#noticeboard").append("<br>" + info);
}





































