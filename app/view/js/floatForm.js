var textContent;
var workspace;
var floatForm;

var mousePos;


var offsetAnnot;
// var nbWrap;
// var beforeAnnot;
// var afterAnnot;

var offsetLeftDefault = 800;

window.addEventListener("load", function ()
{
	textContent = document.querySelector("#textContent");
	workspace = document.querySelector("#annotespace");
	floatForm = $("#floatForm");


	textContent.addEventListener("mousedown", traiteMouseDown);
	textContent.addEventListener("mouseup", traiteMouseUp);
});

function traiteMouseDown(evt) {
	// tester si clic gauche
	if (evt.button === 0) {
		// mousePos = getMousePos(workspace, evt);
	}
}
function traiteMouseUp(evt) {
	// tester si clic gauche 
	if (evt.button === 0) {
		// tester si on surligne (avoir ...)
		//console.log("floatForm.style.top : " + floatForm.style.top + " = mousePos.y : " + mousePos.y)
		if(surligne()) {
			floatForm.show();

			floatForm.offset({
				top : $('.highlight-tmp').offset().top,
				left : floatForm.offset().left
			});
		}
		else {
			unSelect();

		}

	}
}
function getMousePos(div, evt) {
	var rect = div.getBoundingClientRect();
	return {
		x: evt.clientX - rect.left,
		y: evt.clientY - rect.top
	};
}

function setTarget () {
	document.querySelector('#target').value = getSelected();
	$('#target').val();
}

function getSelected() {
	if(window.getSelection) {
		var sel = window.getSelection();
		return window.getSelection();
	}
	else if(document.getSelection) { return document.getSelection(); }
	else {
		var selection = document.selection && document.selection.createRange();
		if(selection.text) { return selection.text; }
	}
	return false;
}

function surligne() {
	if(window.getSelection) {
		var sel = window.getSelection();
		// console.log(sel);
		var range = sel.getRangeAt(0);
		// console.log(range);
		var startOffset = range.startOffset;
		var endOffset = range.endOffset;
		var startNode = range.endContainer;
		var endNode = range.endContainer;

		if (startOffset === endOffset) {
			// console.log('equal off set');
			return false;
		}
		/* TODO SELECTION DANS DIFFERENTES BALISES
		if (startNode !== endNode) {
			console.log('diff node');
			return false;
		}*/

		var strText = startNode.textContent;

		var identifier = Date.now();
		highlight(strText, startOffset, endOffset, identifier);

		// TODO les espace d'un retour a la ligne sont pas compté ...

		var offsetAnnot = startOffset;//+countLineWrap('#textContent', startOffset);
		floatForm.show();
		$('#offset').val(offsetAnnot);
		$('#target').val(strText.substring(startOffset,endOffset));

		$('#content').focus();
		return true;
	}

	alert('pas de gestion range');
	return false;
}

function highlight(strText, startOffset, endOffset, identifier) {
	var strBefore = strText.substring(0,startOffset);
	var strIn = strText.substring(startOffset,endOffset);
	var strAfter = strText.substring(endOffset);

	var newStrText = strBefore+lineWrap('#textContent', strBefore, strIn)+strAfter;

	$('#textContent').html(newStrText);

	cloneOnBackground('.tmpSize', identifier);

	$('#textContent').html(strText);
}




function cloneOnBackground(selector, identifier) {

	var cpt = 0;
	// var ts = Date.now();
	var tmpSpan = $(selector);
	$(".highlight-tmp").remove();
	tmpSpan.each(function(){
		var offset = $(this).offset();
		var w = $(this).width();
		var h = $(this).height();


		$("#backHighlight").append('<span id="hl-'+identifier+'-'+cpt+'" class="highlight-tmp hl-'+identifier+'"></span>')
		var newHighlight = $('#hl-'+identifier+'-'+cpt);
		newHighlight.offset({ top: offset.top, left: offset.left });
		newHighlight.width(w);
		newHighlight.height(h);

		cpt++;
		$(this).remove;
	});
}

/*
 * lineWrap fonction qui rajoute les mots annotés un par un pour trouver les retours a la ligne et pouvoir en déduir l'annotation encapsulé dans des span a chaque retour a la ligne de la selection
 * param selector : lieux ou le texte est affiché sur la page
 * param strBefore : contenue du texte avant le morceau annoté
 * param strBefore : le morceau voulant etre annoté
 * return une string qui contient strIn mais ou chaque ligne est encapsulé dans un span
 */
function lineWrap(selector, strBefore, strIn) {
	var current = $(selector);
	var totaleInStr = '';
	current.text(strBefore);

	// var text = current.text();
	if (strIn.substr(0, 1) === ' ') {
		strIn = strIn.substr(1);
		totaleInStr = ' ';
		current.text(current.text() + ' ');
	}
	var words = strIn.split(' ');

	current.text(current.text() + words[0]);
	var height = current.height();

	var prevTexte = words[0];

	var isLastWordEmpty = false;

	nbWrap = 0;

	for(var i = 1; i < words.length; i++){
		current.text(current.text() + ' ' + words[i]);

		if(current.height() > height){
			nbWrap++;
			height = current.height();
			// (i-1) is the index of the word before the text wraps
			// console.log(words[i-1]);
			// taille texte - le dernier mot ajouté - le nombre d'espace du au retour a la ligne - l'espace de cette ligne
			totaleInStr += '<span class="tmpSize" style="background-color: rgba(255,255,10,0.3);">'
			+ prevTexte
			+ '</span> ';// zolie espace
			prevTexte = words[i];
		}
		else {
			if (words[i] != '') {
				prevTexte += ' ' + words[i];
				isLastWordEmpty = false;
			}
			else {
				isLastWordEmpty = true;
			}
		}
	}
	//tester si prevtexte est vide
	totaleInStr += '<span class="tmpSize" style="background-color: rgba(255,255,10,0.3);">'
	+ prevTexte
	+ '</span>';
	if(isLastWordEmpty) {
		totaleInStr += ' ';
	}

	// console.log(totaleInStr);
	// console.log(wrapsIndex);
	return totaleInStr;
}

/*
function countLineWrap(selector, offset) {
	var current = $(selector);
	var strText = current.text();
	var strBefore = strText.substring(0, offset);

	var words = strText.split(' ');
	var nbcaractere = 0;

	current.text('');

	var height = current.height();

	var countWrap = -1;

	// console.log(words);
	// console.log('height : '+ height+' count : ' + countWrap);
	// console.log(height);

	for(var i = 1;( i < words.length) && (nbcaractere < offset); i++){
		current.text(current.text() + ' ' + words[i]);
		nbcaractere += words[i].length + 1;
		if(current.height() > height){
			countWrap++;
			height = current.height();
	// console.log('height : '+ height+' count : ' + countWrap);
		}

	}

	// console.log('height : '+ height+' count : ' + countWrap);

	current.text(strText);
// countWrap = 0;
	// gerer cas 0
	return (countWrap < 0 ? 0 : countWrap);
}
*/

/* gestion des focus pour les annotaiton deja effectuées */

function displayAnnotInfo(identifier, body, auteur, auteurUri) {
	$('#annotInfosModel').clone().attr('id', 'ai-'+identifier).appendTo('#workspace');
	$('#ai-'+identifier+' .authorName').html('<a href="'+auteurUri+'" >'+auteur+'</a>');
	$('#ai-'+identifier+' .annotBody').text(body);
	var inTextOffset = $('#hl-'+identifier+'-0').offset();
	$('#ai-'+identifier).offset({ top: inTextOffset.top, left: offsetLeftDefault });
}

function changeAnnotFocused(newFocus) {
	var identifier = newFocus.attr('id').split('-')[1];
	// console.log(identifier);
	//clear other
	$('.annotFocused').removeClass('annotFocused');
	$('.annotHighlightFocused').removeClass('annotHighlightFocused');

	// add to new annot info
	$('#ai-'+identifier).addClass('annotFocused');
	// hl correspondant
	$('.hl-'+identifier).addClass('annotHighlightFocused');

}

/* gestion du positionnement des annotations */

function manageAnnotInfo() {

	var annots = {};
	// var annotHighlightOffset = $('.annotHighlightFocused').offset();

	var annotInfos = $('.annotInfos');
	var annotInfosOffset = $('.annotInfos').offset();

	$.each( annotInfos, function( i, ai ){

		var aiOffset = $(this).offset();
		if (annots[aiOffset.top]) {
			annots[aiOffset.top]++;
		}
		else
		{
			annots[aiOffset.top] = 1;
		}
		$(this).offset({
			top: aiOffset.top,
			left: offsetLeftDefault + 30*annots[aiOffset.top]
		});
	});

	return ;
}


function unSelect() {
	floatForm.hide();
	$('#offset').val('');
	$('#target').val('');
	$('.highlight-tmp').remove();
}

function annotInfoEditMode(annotInfo) {
	unSelect();
	annotInfo.children('.editAnnot').hide();
	annotInfo.children('.delAnnot').hide();
	annotInfo.children('.cancelAnnot').show();

	annotInfo.children('.cancelAnnot').click(function() {
		annotInfoViewMode($(this).parent());
	});
	annotInfo.find('.sendBtn').click(function() {
		editAnnot($(this).parent().parent());
	});

	annotInfo.find('.contentEdit').val(annotInfo.children('.annotBody').text());
	annotInfo.children('.annotBody').hide();
	annotInfo.children('.annotBodyForm').show();

}
function annotInfoViewMode(annotInfo) {
	annotInfo.children('.editAnnot').show();
	annotInfo.children('.delAnnot').show();
	annotInfo.children('.cancelAnnot').hide();

	annotInfo.children('.annotBody').show();
	annotInfo.children('.annotBodyForm').hide();
}