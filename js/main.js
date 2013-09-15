$(document).ready(function(){

	// Start the housefinder
	housefinder.init();	
	
	// Some little stuff
	$('#days').popover({
		content: 'Enter number of days and then press "Enter" or "Go"',
		placement: 'bottom',
		trigger: 'focus'
	});
	
	$('#priceFilter').click(function() {
		$(this).closest('form').submit();
	});
		
});

// Readjust menu when data is submitted
var housefinder = {
	
	settings : {
		priceMin : 200000,
		priceMax : 400000,
		maxDays : $('#days').val()
	},
	
	init : function() {
		
		housefinder.ajaxSpinner();
		housefinder.getJSON();
		
	},
	
	getJSON : function () {
		
		// Get json data
		$.getJSON('controller/listingJSON.php?days=' + housefinder.settings.maxDays, function(listingData) {
		
			var $tbody = $('<tbody>');
			var listings = listingData.properties;
			
			// Add stuff from database
			$.getJSON('controller/databaseJSON.php', function(userData) {
			
				$('#loading').hide();
				
				$.each(listings, function(index, val) {
				
					var listing = val.listing;
					
					if (parseInt(listing.age) < housefinder.settings.maxDays) {
					
						listing.rejected = 'N';
					
						$.each(userData, function(index, val) {
							if (val.address == listing.address) {
								listing.notes = val.notes;
								listing.rejected = val.rejected;
							}
							
						});
					
						// Make the row
						var $row = $('<tr class="detailsArea">');
						if (listing.age == 0)
							$row.append('<td class="hidden-xs">New Today!</td>');
						else if (listing.age == 1)
							$row.append('<td class="hidden-xs">' + listing.age + ' Day</td>');
						else
							$row.append('<td class="hidden-xs">' + listing.age + ' Days</td>');
						$row.append('<td>' + listing.township + '</td>');
						$row.append('<td>' + listing.address + '</td>');
						$row.append('<td>' + listing.price + '</td>');
						
						// Filter by price
						listing.priceInt = parseInt(listing.price.replace(/\$|\,/g, ''));
						if (listing.priceInt < housefinder.settings.priceMin || listing.priceInt > housefinder.settings.priceMax) {
							$row.addClass('priceOutOfRange');
						}
						
						if (listing.rejected == 'Y') {
							$row.addClass('listingRejected');
						}
						
						// Create the menu
						var $optionMenu = $('<div>', {
							class : 'btn-group pull-right',
							id : 'optionMenu' + (index + 1),
						});
						$optionMenu.attr('data-address', listing.address)
							.attr('data-notes', listing.notes);
							
						$optionMenu.append('<button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-wrench"></span>&nbsp;<span class="caret"></span></button>');	
						$dropdown = $('<ul>', {
							class : 'dropdown-menu',
							role : 'menu'
						});
						$dropdown.append('<li role="presentation"><a role="menuitem" tabindex="-1" target="_blank" href="' + listing.url + '">More Details</a></li>');
						$dropdown.append('<li role="presentation"><a role="menuitem" tabindex="-1" target="_blank" href="mailto:?body=Check%20out%20this%20one!%20%20' + listing.url + '&amp;subject=A%20House%20I%20Like">Send Email</a></li>');
						$dropdown.append('<li ' + ((listing.notes == undefined) ? 'class="hidden"' : '') + 'role="presentation"><a class="seeNotes" data-toggle="modal" role="menuitem" tabindex="-1" href="#modal_notes">See Notes</a></li>');
						$dropdown.append('<li ' + ((listing.rejected == 'Y') ? 'class="hidden"' : '') + 'role="presentation"><a class="enterNotes" data-toggle="modal" role="menuitem" tabindex="-1" href="#modal_form">' + ((listing.notes == undefined) ? 'Add Notes' : 'Edit Notes') + '</a></li>');
						$dropdown.append('<li ' + ((listing.rejected == 'N') ? 'class="hidden"' : '') + 'role="presentation"><a class="removeRejected" role="menuitem" tabindex="-1" href="#">I like it!</a></li>');
						$dropdown.append('<li ' + ((listing.rejected == 'Y') ? 'class="hidden"' : '') + 'role="presentation"><a class="enterNotes reject" data-toggle="modal" role="menuitem" tabindex="-1" href="#modal_form">Not For Us</a></li>');
						
						$optionMenu.append($dropdown);
						$optionMenu = $('<td>').append($optionMenu);
						
						$row.append($optionMenu);
						
						// Append to the table's body
						$tbody.append($row);
					
					}
					
				
				});
				
				$($tbody).appendTo('#listingsGrid');
				housefinder.addListeners();
				housefinder.notesForm();
				
			});
			
		});
		
	},
	
	addListeners : function() {
		
		// Add address to hidden field
		$('.enterNotes').click(function() {
			var address = $(this).closest('.btn-group').attr('data-address');
			var notes = $(this).closest('.btn-group').attr('data-notes');
			var id = ($(this).closest('.btn-group').attr('id')).split('optionMenu')[1];
			$('#notes').val(notes);
			$('.notesForm').data('id', id);
			$('#address').val(address);
			(this.className.indexOf('reject') > -1) ? $('#rejected').val('Y') : $('#rejected').val('N');
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
					$(highlightId).closest('.detailsArea').removeClass('listingRejected');
					housefinder.readjustMenu(id, 'accepted');
				});
		});
		
		// Hide/show rejected
		$('.toggleRejected').click(function(e) {
			e.preventDefault();
			
			if ($(this).attr('id') == 'hideRejected') {
				$('.listingRejected').hide();
				$('#showRejected').removeClass('hidden');
			} else {
				$('.listingRejected').show();
				$('#hideRejected').removeClass('hidden');
			}
				
			$(this).addClass('hidden');		
		});
		
		$('#showAllPrice').click(function() {
			housefinder.togglePrice(this);
		})
		
	},
	
	readjustMenu : function(index, state) {
	
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

	},
	
	togglePrice : function(el) {
		
		if (housefinder.settings.priceView == 'all') {
			$('.priceOutOfRange').hide();
			housefinder.settings.priceView = 'filtered';
			$(el).text('Show All');
		} else {
			$('.priceOutOfRange').show();
			housefinder.settings.priceView = 'all';
			$(el).text('Show Filtered');
		}
		
	},
	
	notesForm : function() {
		$('.notesForm').submit(function(e) {
			e.preventDefault();
			var form = this;
			$.post('../controller/storedata.php', $(this).serialize())
				.done(function() { 
					var highlightId = '#optionMenu' + $(form).data('id');
					$(highlightId).attr('data-notes',$('#notes').val());
					$('#notes').val('');
					$('.modal').modal('hide');
					if ($('#rejected').val() == 'Y') {
						$(highlightId).closest('.detailsArea').addClass('listingRejected');
						housefinder.readjustMenu($(form).data('id'), 'rejected');
					}
				})
				.fail(function() {
					alert("something went wrong");
				})
		});
	},
	
	ajaxSpinner : function() {
		
		var loadingDiv = $('<div>', {
			id : 'loading'
		});
		loadingDiv.append('<img src="../img/loading.gif" />');
		loadingDiv.append('<p>Please Wait.</p><p>Finding the Latest Houseys</p>');
		
		$('body').append(loadingDiv);

		
	}

}
