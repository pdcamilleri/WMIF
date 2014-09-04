// saves the settings specified by the experimenter by writing them to config.ini file
function saveSettings() {

  filter = {
    description: 1,
    frequency: 1,
    average: 1,
    distribution: 1,
    wordcloud: 0,
    simultaneous: 1,
    experience: 1,
  };

  $.post("writeConfigFile.php", 
      filter,
      function(file) {
        console.log(file);
        d(file);
      },
      'json'
  );

}

// adds the string info to the page
function d(info) {
  console.log(info);
  $("#noticeboard").append("<br>" + info);
}
