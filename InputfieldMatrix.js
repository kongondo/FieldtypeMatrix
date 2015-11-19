$(document).ready(function() {

	//global variables
	table = 'table#';
	chkBox = 'input.MatrixCheckbox';
	importMode = 'input.MatrixImportMode';
	highlightRow = 'highlight_row';
	dt = 'data-table';//data-table attribute
	dr = 'data-row';//data-row attribute
	msgClearData = '';
	msgNoRowSelected = '<p>You need to select at least one row</p>';

	/*************************************************************/
	//FUNCTIONS

	//custom alert (jQuery UI Dialog)
	custom_alert = function (output_msg, title_msg) {

	    if(!title_msg) title_msg = 'Alert';
	    //if(!output_msg) output_msg = 'No Message to Display.';

	    $("<div></div>").html(output_msg).dialog({
	        title: title_msg,
	        width: 'auto',
	        height: 150,
	        modal: true,
	        dialogClass: 'alert',

	    });
	}

	//get the last blank row if in append mode
	lastBlankRow = function(tableID) {

		mTable = $(table + tableID);//the current matrix table

 		//get import mode. If it is overwrite, we have nothing to do; return
 		if($('input[data-table="' + tableID +'"]:checked').val() === '2') return false;

		//remove highlight from the 'older' last blank row first
		var t = $('tr.highlight_last_blank', table + tableID);
		if(t) t.removeClass('highlight_last_blank');
		
		//get the current last blank row
		$(table + tableID + ' tr').filter(
			function () {return $(this).find('input:text').length == $(this).find('td input:text[value=""]').length;
		}).filter(':last').addClass('highlight_last_blank');

		//get the id of the last blank row
		var lbr = $('tr.highlight_last_blank', table + tableID).attr('id');
		lastBlankRowID(tableID, lbr);

		cleanUp();
		
	}

	//pass the id of the last blank row to hidden input for last blank row ID
	lastBlankRowID = function(tableID, lbr) {
		var inputLBR = $('input[data-table="' + tableID +'"].MatrixLastBlankRow');
		if(tableID && lbr) inputLBR.val(lbr);
		else inputLBR.val('');
	}

	//remove empty 'class' attributes
	cleanUp = function() {
		$('*[class=""]').removeAttr('class');
	}



	/*************************************************************/

	$('*[class=""]').removeAttr('class');

	/** Clear data from matrix **/
	$('button.MatrixReset, button.MatrixResetReno').on('click', function() {

 		var tableID = ($(this).attr(dt));

		//push row IDs of selected rows in array
		var rows = [];
		$.each($(chkBox + ':checked', table + tableID), function(){
			var rowID = ($(this).attr(dr));
			rows.push(rowID);				
		});

		//if no checkbox selected, show error alert
		if(rows.length === 0) {			
			custom_alert(msgNoRowSelected);
			return false;
		}

		//clear data 
		$.each(rows, function (index, value) {
			var id = rows[index];//this matches the ID of the table row
			//clear only data from the matching matrix table in case there's more than one on this page
			$('input', table + tableID + ' tr#' + id).val('');

		});

		$(chkBox, table + tableID).prop('checked', false);
		$('input.MatrixToggleAll', table + tableID).prop('checked', false);
		$(chkBox, table + tableID).closest('tr').removeClass(highlightRow);

		//alert for clear data if configured		
		if(typeof config[tableID] !== "undefined") {
			var msg = config[tableID]['config']['cdAlertMsg'];
			if(msg) {
				msgClearData = '<p>' + msg + '</p>';
				custom_alert(msgClearData)
			}
		}

		//refresh button (remove 'ui-state-active') (only need for default theme)
		if(!$(this).hasClass('MatrixResetReno')) $(this).button().button('refresh');

		//get and highlight last blank row
		lastBlankRow(tableID);

	});

	/**remove ui-state-active from export button (it stays in the active state due to the nature of the export csv function in the module) **/
	$('button.MatrixExport, button.MatrixExportReno').click(function() {

		 var tableID = ($(this).attr(dt));

		//push row IDs of selected rows in array
		var rows = [];
		$.each($(chkBox + ':checked', table + tableID), function(){
			var rowID = ($(this).attr(dr));
			rows.push(rowID);				
		});

		//if no checkbox selected, show error alert
		if(rows.length === 0) {			
			custom_alert(msgNoRowSelected);
			return false;
		}

		//refresh button (remove 'ui-state-active')
		//$(this).removeClass('ui-state-active');//@@todo - doesn't work
		//$(this).button().button('refresh');//@@todo - works but requires double click to submit
		//hackish workaround; just grab the background and border styles from the MatrixReset button
		var background = $('button.MatrixReset').css("background");
		var border = $('button.MatrixReset').css("border");
		$(this).css({
			'background' : background,
			'border' : border
		});

	});

	/** Toggle select all checkboxes in last column of matrix/table grid **/
	$('input.MatrixToggleAll').click(function(){

		var tableID = ($(this).attr(dt));
		var mTable = table + tableID;
		if ($(this).prop('checked')) {
			$(chkBox, mTable).prop('checked', true);
			$(chkBox, mTable).closest('tr').addClass(highlightRow);
		}

		else {
			$(chkBox, mTable).prop('checked', false);
			$(chkBox, mTable).closest('tr').removeClass(highlightRow);
		}
	});

	/** Toggle select row with checked inputcheckbox **/
	$(chkBox).click(function(){
		if ($(this).prop('checked')) $(this).closest('tr').addClass(highlightRow);
		else $(this).closest('tr').removeClass(highlightRow);
	});

	//select multiple checkboxes in a range use SHIFT+CLIck
	//@awt2542 PR #867 for PROCESSWIRE
	$mTable = $("table.Matrix")
	var lastChecked = null;
	$(document).on('click', 'table.Matrix input[type=checkbox].MatrixCheckbox', function(e) {
		var $checkboxes = $(this).closest($mTable).find('input[type=checkbox].MatrixCheckbox');
		if(!lastChecked) {
			lastChecked = this;
			return;
		}
		if(e.shiftKey) {
			var start = $checkboxes.index(this);
			var end = $checkboxes.index(lastChecked);
			$checkboxes.slice(Math.min(start,end), Math.max(start,end)+ 1).attr('checked', lastChecked.checked);
		}

		lastChecked = this;
	});


});//end jquery

/*customise jQuery UI Dialog (alert box)*/
$(document).ready(function() {
	$('button.MatrixReset, button.MatrixResetReno').click(function(){
		$( 'div.ui-dialog .ui-dialog-title').remove();
	});
});


/*last blank row*/
$(document).ready(function() {

	/*on load if append mode is default, highlight last blank row*/
	$.each($(importMode + ':checked', 'div'), function(){
		if($(this).val() === '1') {
			var tableID = ($(this).attr(dt));
			lastBlankRow(tableID);
		}					
	});

	/* on radio button select change highligh last blank row */
	$(importMode).on('change', function(){
		var tableID = ($(this).attr(dt));
		var lbr = $(table + tableID + ' tr.highlight_last_blank');
		if ($(this).val() === '1') lastBlankRow(tableID);
		else if(lbr) {
			lbr.removeClass('highlight_last_blank');//remove highlight if 'overwrite' selected
			//reset last blank row ID to ''
			$('input[data-table="' + tableID +'"].MatrixLastBlankRow').val('');
		}
		//remove blank 'class' attributes
		cleanUp();

	});

	/* detect input changes and get last blank row if applicable */
	$('table.Matrix').on('input', function() {		
		lastBlankRow($(this).attr('id'));
	});


});//end last blank row

