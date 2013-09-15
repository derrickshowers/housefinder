$(document).ready(function(){

	// Get json data
	$.getJSON('controller/listingJSON.php', function(listingData) {
	
		var $tbody = $('<tbody>');
		var listings = listingData.properties;
		
		// Add stuff from database
		$.getJSON('controller/databaseJSON.php', function(userData) {
			
			$.each(listings, function(index, val) {
			
				var listing = val.listing;
				
				$.each(userData, function(index, val) {
					var thisListing = this;
					
					if (val.address == listing.address) {
						listing.notes = val.notes;
						listing.rejected = 'Y';
					}
					else {
						listing.rejected = 'N';
					}
					
				});
				
				
			
				// Make the row
				var $row = $('<tr class="detailsArea">');
				$row.append('<td>' + listing.age + '</td>');
				$row.append('<td>' + listing.township + '</td>');
				$row.append('<td>' + listing.address + '</td>');
				$row.append('<td>' + listing.price + '</td>');
				
				// Create the menu
				var $optionMenu = $('<div>', {
					class : 'btn-group pull-right',
					id : 'optionMenu' + (index + 1),
				});
				$optionMenu.attr('data-toggle', 'dropdown')
					.attr('data-address', listing.address)
					.attr('data-notes', listing.notes);
					
				$optionMenu.append('<button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-wrench"></span>&nbsp;<span class="caret"></span></button>');	
				$dropdown = $('<ul>', {
					class : 'dropdown-menu',
					role : 'menu'
				});
				$dropdown.append('<li role="presentation"><a role="menuitem" tabindex="-1" target="_blank" href="' + listing.url + '">More Details</a></li>');
				$dropdown.append('<li role="presentation"><a role="menuitem" tabindex="-1" target="_blank" href="mailto:?body=Check%20out%20this%20one!%20%20' + listing.url + '&amp;subject=A%20House%20I%20Like">Send Email</a></li>');
				$dropdown.append('<li ' + ((listing.rejected == 'N') ? 'class="hidden"' : '') + 'role="presentation"><a class="seeNotes" data-toggle="modal" role="menuitem" tabindex="-1" href="#modal_notes">See Notes</a></li>');
				$dropdown.append('<li ' + ((listing.rejected == 'N') ? 'class="hidden"' : '') + 'role="presentation"><a class="removeRejected" role="menuitem" tabindex="-1" href="#">I like it!</a></li>');
				$dropdown.append('<li ' + ((listing.rejected == 'Y') ? 'class="hidden"' : '') + 'role="presentation"><a class="enterNotes" data-toggle="modal" role="menuitem" tabindex="-1" href="#modal_form">Not For Us</a></li>');
				
				$optionMenu.append($dropdown);
				$optionMenu = $('<td>').append($optionMenu);
				
				$row.append($optionMenu);
				
				// Append to the table's body
				$tbody.append($row);
			
			});
			
			$($tbody).appendTo('#listingsGrid');
		
			addListeners();
			
		});
		
	});
	
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

function addListeners () {
	// Add address to hidden field
	$('.enterNotes').click(function() {
		var address = $(this).closest('.btn-group').attr('data-address');
		console.log('address: ' + address);
		var id = ($(this).closest('.btn-group').attr('id')).split('optionMenu')[1];
		console.log('id: ' + id);
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
}