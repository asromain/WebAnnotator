
$(document).ready(function () {
	getAnnot();
});

function getBaseUrl() {
	/*
	var protocol = location.href.split( '/' )[0];
	var domainName = location.href.split( '/' )[2];
	if (domainName === 'localhost') {
		domainName += '/WebAnnotator';
	}
	return protocol+'//'+domainName;
	*/
	return $('#urlForJs').text();
}

function sendAnnot() {
	console.log($('#target').val());
	console.log($('#content').val());
	console.log($('#offset').val());
	$.ajax({
		url: getBaseUrl()+'/annotation/add/',
		type : 'POST',
		data : 'target=' + $('#target').val()
			+'&content=' + $('#content').val()
			+'&offsetAnnot=' + $('#offset').val(),
		async: true,
		dataType: 'html',
		success: function(data) {
			console.log(data);

			var newAnnot = jQuery.parseJSON(data);
			// console.log(newAnnot);
			unSelect();
			$('#content').val('');

			var offsetStart = newAnnot.offset;// - countLineWrap('#textContent', annotations[i].offset);
			var offsetEnd = offsetStart - (-newAnnot.target.length); // val1 - (- val2) evite la concatenation de chaine
			highlight($('#textContent').text(), offsetStart, offsetEnd, newAnnot.annotatedAt);
			$('.highlight-tmp').addClass('highlight-annot').removeClass('highlight-tmp');
			displayAnnotInfo(newAnnot.annotatedAt, newAnnot.body, newAnnot.annotatedBy, newAnnot.annotatedByUri);

			setEvents();
			manageAnnotInfo();
			floatForm.hide();
		}
	});
}

function delAnnot(annotToDel) {
	var id = annotToDel.attr("id");
	var ts = id.split("ai-")[1];

	$.ajax({
		url: getBaseUrl()+'/annotation/delete/',
		type : 'POST',
		data : 'timestamp=' + ts,
		async: true,
		dataType: 'html',
		success: function(data) {
			console.log(data);

			if (data === "1") {
				annotToDel.remove();
				$('.hl-'+ts).remove();
			}
			manageAnnotInfo();
		}
	});
}

function editAnnot(annotToEdit) {
	var id = annotToEdit.attr("id");
	var ts = id.split("ai-")[1];

	$.ajax({
		url: getBaseUrl()+'/annotation/update/',
		type : 'POST',
		data : 'timestamp=' + ts
			+'&newBody=' + annotToEdit.find('.contentEdit').val(),
		async: true,
		dataType: 'html',
		success: function(data) {
			console.log(data);

			if (data === "1") {
				annotToEdit.children('.annotBody').text(annotToEdit.find('.contentEdit').val());
				annotInfoViewMode(annotToEdit);
			}
		}
	});
}

function getAnnot() {
	$.ajax({
		url: getBaseUrl()+'/annotation/get/',
		async: true,
		dataType: 'html',
		success: function(data) {
			console.log(data);
			var annotations = jQuery.parseJSON(data);
			// console.log(annotations);
			for (var i = 0; i < annotations.length; i++) {
				var offsetStart = annotations[i].offset;// - countLineWrap('#textContent', annotations[i].offset);
				var offsetEnd = offsetStart - (-annotations[i].target.length); // val1 - (- val2) evite la concatenation de chaine
				highlight($('#textContent').text(), offsetStart, offsetEnd, annotations[i].annotatedAt);
				$('.highlight-tmp').addClass('highlight-annot').removeClass('highlight-tmp');
				displayAnnotInfo(annotations[i].annotatedAt, annotations[i].body, annotations[i].annotatedBy, annotations[i].annotatedByUri);
			};

			setEvents();
			manageAnnotInfo();
			floatForm.hide();
		}
	});
}


function setEvents() {
	$('.annotInfos').click(function() {
		console.log('test');

		changeAnnotFocused($(this));
	});
	$('.delAnnot').click(function() {
		console.log('del');

		delAnnot($(this).parent());
	});
	$('.editAnnot').click(function() {
		console.log('edit');

		annotInfoEditMode($(this).parent());
	});
	$('.highlight-annot').click(function() {
		console.log('test');

		changeAnnotFocused($(this));
	});
}
