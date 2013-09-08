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
		console.log('test');
		var form = this;
		$.post('../controller/storedata.php', $(this).serialize())
			.done(function() { 
				var highlightId = '#optionMenu' + $(form).data('id');
				$(highlightId).closest('.detailsArea').addClass('danger');
				$(highlightId).attr('data-notes',$('#notes').val());
				$('#notes').val('');
				$('.modal').modal('hide');
				readjustMenu($(form).data('id'), 'rejected');
			})
			.fail(function() {
				alert("something went wrong");
			})
	});
	
	// Add address to hidden field
	$('.enterNotes').click(function() {
		var address = $(this).closest('.btn-group').attr('data-address');
		var id = ($(this).closest('.btn-group').attr('id')).split('optionMenu')[1];
		$('.rejectForm').data('id', id);
		$('#address').val(address);
		setTimeout(function() {
			$('#notes').focus();
		}, 500);
	});
	
	// Add notes to modal content area
	$('.seeNotes').click(function() {
		var notes = $(this).closest('.btn-group').attr('data-notes');
		$('#rejectNotes').text(notes);
	});
	
	// Add notes to modal content area
	$('.removeRejected').click(function(e) {
		e.preventDefault();
		var address = $(this).closest('.btn-group').attr('data-address');
		var id = ($(this).closest('.btn-group').attr('id')).split('optionMenu')[1];
		$.post('../controller/deletedata.php', { address: address})
			.done(function() { 
				var highlightId = '#optionMenu' + id;
				$(highlightId).closest('.detailsArea').removeClass('danger');
				readjustMenu(id, 'accepted');
			});
	});
	
	// Hide/show rejected
	$('.toggleRejected').click(function(e) {
		e.preventDefault();
		
		if ($(this).attr('id') == 'hideRejected') {
			$('.danger').addClass('hidden');
			$('#showRejected').removeClass('hidden');
		} else {
			$('.danger').removeClass('hidden');
			$('#hideRejected').removeClass('hidden');
		}
			
		$(this).addClass('hidden');		
	});
		
});

// Readjust menu when data is submitted
function readjustMenu (index, state) {
	
	var menuUL = '#optionMenu' + index + ' ul';
	
	
	$(menuUL).children('li').each(function() {
		
		if (state == 'rejected') {
			if ($(this).children('a').text() == 'Not For Us')
				$(this).addClass('hidden');
			else
				$(this).removeClass('hidden');
		} else {
			if ($(this).children('a').text() == 'See Notes' || $(this).children('a').text() == 'I like it!')
				$(this).addClass('hidden');
			else
				$(this).removeClass('hidden');
		}
	});
	
}