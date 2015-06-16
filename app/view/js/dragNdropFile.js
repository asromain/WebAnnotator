var dragFileZone;
var dropTextZone;
var fileSelect;

/**
 * TODO TESTER EXTENSION FICHIER
 * TODO COMMENTER
 */

$( document ).ready(function() {

	dragFileZone = document.getElementById("text_form");

	fileSelect = document.getElementById("fileSelect");

	dropTextZone = $("#dropTextZone");


	if (window.File && window.FileReader && window.FileList && window.Blob) {
		init();
	} else {
		alert('The File APIs are not fully supported in this browser.');
	}
});



function init() {

	fileSelect.addEventListener("change", fileSelectHandler, false);

	dragFileZone.addEventListener("dragover", fileDragHover, false);
	dragFileZone.addEventListener("dragleave", fileDragLeave, false);
	dragFileZone.addEventListener("drop", fileSelectHandler, false);

}

function fileDragHover(e) {
	e.stopPropagation();
	e.preventDefault();
	dropTextZone.removeClass("dropTextZone").addClass("fileDragHover");
}

function fileDragLeave() {
	
	dropTextZone.removeClass("fileDragHover").addClass("dropTextZone");
}

function fileSelectHandler(e) {
	
	e.stopPropagation();
	e.preventDefault();

	fileDragLeave();

	dropTextZone.val("");

	// liste des objets fichier
	var files = e.target.files || e.dataTransfer.files;

	// recupere toute la liste des objets fichier
	for (var i = 0, f; f = files[i]; i++) {
		// console.log(f.type);
		if (f.type.match('image.*')) {
			// on evite d'ajouter des images
			continue;
		}
		addFile(f);
	}
}

function addFile(file) {
	var reader = new FileReader();
	reader.onload = function(e) {
		var content = e.target.result;
		dropTextZone.val(dropTextZone.val()+content);
	}
	reader.readAsText(file);
}

