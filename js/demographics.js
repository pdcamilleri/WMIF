//Ensure that every question is answered, otherwise alert an error
function checkAnswers() {
  var questionIds = [
    'Gender', 
    'Age', 
    'Education', 
    'Employment', 
    'Marital', 
    'Income'
  ];

  var errorMessage = [
    'Please indicate your gender.', 
    'Please indicate your age.', 
    'Please indicate your level of education.', 
    'Please indicate your employment status.', 
    'Please indicate your marital status.', 
    'Please indicate your income.'
  ];

  for (var i = 0; i < questionIds.length; i++) {
    var response = window.document.getElementById(questionIds[i]).value;
    if (response == null || response == "") {
      alert(errorMessage[i]);
      return false;
    }
  }

  // user has entered correct data into the form, send to the database

  //var DemographicsForm = document.getElementById("DemographicsForm");
  var url = 'database.php';

  // fetch the data for the form
  var data = $("#DemographicsForm").serializeArray();
  // add in the MTurkId
  // TODO mid is a variable obtained from PHP session. can do this a better way
  data.push({name: "mid", value: mid}); 

  // setup the ajax request
  $.ajax({
    type: 'POST',
    url: url,
    data: data,
    dataType: 'json',
    //success: function() {
    //  alert("posted!");
    //}
  });

  return true;
}
