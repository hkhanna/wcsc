function newImage(arg) {
	if (document.images) {
		rslt = new Image();
		rslt.src = arg;
		return rslt;
	}
}

function changeImages() {
	if (document.images && (preloadFlag == true)) {
		for (var i=0; i<changeImages.arguments.length; i+=2) {
			document[changeImages.arguments[i]].src = changeImages.arguments[i+1];
		}
	}
}

var preloadFlag = false;
function preloadImages() {
	if (document.images) {
		home_over = newImage("images/home-over.gif");
		constitution_over = newImage("images/constitution-over.gif");
		leg_board_over = newImage("images/leg_board-over.gif");
		events_board_over = newImage("images/events_board-over.gif");
		judicial_board_over = newImage("images/judicial_board-over.gif");
		elections_over = newImage("images/elections-over.gif");
		pictures_over = newImage("images/pictures-over.gif");
		calendar_over = newImage("images/calendar-over.gif");
		forms_docs_over = newImage("images/forms_docs-over.gif");
		member_area_over = newImage("images/member_area-over.gif");
		preloadFlag = true;
	}
}