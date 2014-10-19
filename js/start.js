window.onload = function() {
  checkJavaScriptValidity();
  $.get("readConfigFile.php", 
      function(config) { 
        $("#generalInstructions").html(config['problemInstructions']);
      }, 
      'json'
  );
}

//Check that the client browser has javascript enabled
function checkJavaScriptValidity() {
  // if JS is enabled, show the main content.
  document.getElementById("jsDisabled").hidden = true;
  // And hide the "You don't have JS enabled" warning message.
  document.body.children[0].hidden = false;
}

