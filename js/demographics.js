//Ensure that every question is answered, otherwise alert an error
function checkAnswers() {
  //var DemographicsForm = document.getElementById("DemographicsForm");
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
}
