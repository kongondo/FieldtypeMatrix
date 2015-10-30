$(document).ready(function() {

	//global variables
	chkBox = 'input.MatrixCheckbox';
	highlightRow = 'highlight_row';
	dt = 'data-table';//data-table attribute
	dr = 'data-row';//data-row attribute
	msgClearData = '';
	msgNoRowSelected = '<p>You need to select at least one row</p>';

	/*************************************************************/

	/** Clear data from matrix **/
	$('button.MatrixReset, button.MatrixResetReno').click(function() {

 		var tableID = ($(this).attr(dt));

		//push row IDs of selected rows in array
		var rows = [];
		$.each($(chkBox + ':checked', 'table#' + tableID), function(){
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
			$('input', 'table#' + tableID + ' tr#' + id).val('');

		});

		$(chkBox, 'table#' + tableID).prop('checked', false);
		$('input.MatrixToggleAll', 'table#' + tableID).prop('checked', false);
		$(chkBox, 'table#' + tableID).closest('tr').removeClass(highlightRow);

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

	});

	/**remove ui-state-active from export button (it stays in the active state due to the nature of the export csv function in the module) **/
	$('button.MatrixExport, button.MatrixExportReno').click(function() {

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
		var table = 'table#' + tableID;
		if ($(this).prop('checked')) {
			$(chkBox, table).prop('checked', true);
			$(chkBox, table).closest('tr').addClass(highlightRow);
		}

		else {
			$(chkBox, table).prop('checked', false);
			$(chkBox, table).closest('tr').removeClass(highlightRow);
		}
	});

	/** Toggle select row with checked inputcheckbox **/
	$(chkBox).click(function(){
		if ($(this).prop('checked')) $(this).closest('tr').addClass(highlightRow);
		else $(this).closest('tr').removeClass(highlightRow);
	});

	//custom alert (jQuery UI Dialog)
	function custom_alert(output_msg, title_msg) {

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


});//end jquery

/*customise jQuery UI Dialog (alert box)*/
$(document).ready(function() {
	$('button.MatrixReset, button.MatrixResetReno').click(function(){
		$( 'div.ui-dialog .ui-dialog-title').remove();
	});
});

