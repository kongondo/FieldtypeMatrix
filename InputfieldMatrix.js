$(document).ready(function() {

	$(function() {
	    $('button.InputfieldMatrixReset').click(function() {

	    	var tableID = ($(this).attr('data-table'));
	    	$(':input','table#' + tableID).val('');//clear only data from the matching matrix table in case there's more than one on this page

	        alert('Until you save the page, your old data still exists. Reload the page without saving if you want it back.');
	    });
	}); 

}); 
