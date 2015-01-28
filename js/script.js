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

function createUpdateForm(extract_id)
{
	var element = document.getElementById("extract_" + extract_id);

	var extract_exclude = element.getElementsByClassName("extract_exclude")[0];

	var input_extract_exclude = document.createElement("input");
	input_extract_exclude.type = "checkbox";
	input_extract_exclude.checked = extract_exclude.textContent == 'Y';
	extract_exclude.textContent = '';
	
	extract_exclude.appendChild(input_extract_exclude);

	var extract_name = element.getElementsByClassName("extract_name")[0];

	var input = document.createElement("input");
	input.type = "text";
	input.value = extract_name.textContent;
	extract_name.textContent = '';

	extract_name.appendChild(input);
}