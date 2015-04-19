
//Adds snippets on the editor page...

$('#addsnippet').click(function() {
	
	var num = $('.codesnippet').length; // how many "duplicatable" input fields we currently have

	var newNum  = new Number(num + 1);      // the numeric ID of the new input field being added

	// create the new element via clone(), and manipulate it's ID using newNum value
	var newElem = $('#snippet' + num).clone().attr('id', 'snippet' + newNum);

	// manipulate the name/id values of the input inside the new elements
	
	newElem.children('h3').html('Code Snap ' + newNum);
	
	newElem.children('#introlabel' + num).attr('for', 'intro' + newNum).attr('id', 'introlabel' + newNum);
	newElem.children('#intro' + num).attr('name', 'intro' + newNum).attr('id', 'intro' + newNum);
	
	newElem.children('#caplabel' + num).attr('for', 'caption' + newNum).attr('id', 'caplabel' + newNum);
	newElem.children('#caption' + num).attr('name', 'caption' + newNum).attr('id', 'caption' + newNum);
	
	newElem.find('#default' + num).attr('name', 'lang' + newNum).attr('id', 'default' + newNum).prop('checked', true);
	newElem.find('#css' + num).attr('name', 'lang' + newNum).attr('id', 'css' + newNum).prop('checked', false);
	
	newElem.find('#codelabel' + num).attr('for', 'code' + newNum).attr('id', 'code' + newNum);
	newElem.find('#code' + num).attr('name', 'code' + newNum).attr('id', 'code' + newNum);

	// insert the new element after the final "duplicatable" input field
	$('#snippet' + num).after(newElem);

	// business rule: you can only add 100 snippets
	if (newNum == 100)
		$('#addsnippet').attr('disabled','disabled');
});

//Submit the form to the processor, which makes the file...
/*$("#codeform").submit(function(event){
	
	var dataString = $(this).serialize();
	
	//alert(dataString);
	
	$.ajax({
		type: "POST",
		url: "includes/processor.php",
		data: dataString,
		success: function(data){ $("#formfeedback").html(data); }
	});
	
	event.preventDefault();
});
*/
