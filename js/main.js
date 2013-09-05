$(document).ready(function(){
	
	$('#days').popover({
		content: 'Enter number of days and then press "Enter" or "Go"',
		placement: 'bottom',
		trigger: 'focus'
	});
	
	$('#priceFilter').click(function() {
		$(this).closest('form').submit();
	});
	
	// AJAX call for reject form
	$('.rejectForm').submit(function(e) {
		e.preventDefault();
		var form = this;
		$.post('../controller/storedata.php', $(this).serialize())
			.done(function() { 
				$(form).closest('tr').prev('.detailsArea').addClass('error');
				$(form).closest('tr').slideToggle('fast');
			});
	});
	
	// Show form
	$('.detailsArea').click(function() {
		$(this).next('.rejectArea').slideToggle('fast');
		
	});
		
});