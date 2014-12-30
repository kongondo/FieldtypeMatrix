$(document).ready(function() {

	$(function() {
	    $('input.InputfieldMatrixReset').click(function() {
	        $(':input','table.InputfieldMatrix')
	            .val('')
	    });
	}); 

}); 
