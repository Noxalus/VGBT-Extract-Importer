function databaseInsert(page, data)
{
	$(document).ready(function() {

		$.ajax({
	        type: "POST",
	        url: page,
	        data: data
        })
        .done(function(msg) {
        	alert(msg);
        })
		.fail(function() {
			alert("Error");
		});
	});
}

function changeAllSelect(className, value)
{
	var selects = document.getElementsByClassName(className);

	var i;
	for (i = 0; i < selects.length; i++) {
		selects[i].value = value;
	}
}