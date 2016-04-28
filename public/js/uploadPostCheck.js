$(document).ready(function(){
	$("#postForm").submit(function(e){
		var $inputFile = $("#fileToUpload").val();
			
		if($inputFile == "" || $("#postMessage").val() == ""){
			$("#postFeedback").html("The fields cannot be empty.");
			e.preventDefault();
		}else if($inputFile != "" && $("#postMessage") != ""){
			$("#postForm").submit();
			$("#postFeedback").html("");
		}
	});
});
