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