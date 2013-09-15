<?php
	// include('controller/pulldata.php');
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>House Finder</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">	
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
	<link rel="stylesheet" href="css/main.css">
	<link href="css/styles.css" rel="stylesheet" media="screen">
	<script src="js/vendor/modernizr-2.6.2.min.js"></script>
	
	<link rel="shortcut icon" href="favicon.ico?v=1.1">
	<link rel="apple-touch-icon" href="apple-touch-57.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="apple-touch-72.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="apple-touch-114.png" />
	<link rel="apple-touch-icon" sizes="144x144" href="apple-touch-144.png" />
</head>
<body>
	<header>
		<div class="navbar navbar-inverse navbar-fixed-top bs-docs-nav">
			<div class="row">
				<h1 class="hidden-xs">HOUSE FINDER</h1>
				<h3 class="visible-xs">HOUSE FINDER</h3>
				<div class="col-md-3 col-md-offset-1">
					<form method="get">
						<p id="daysText" class="pull-left">Showing Past <input id="days" class="input-sm" name="days" type="text" value="<?php echo (empty($_GET['days'])) ? 5 : $_GET['days'] ?>" /> Days</p>
					</form>
				</div>
				<div class="col-md-3 col-md-offset-4">
					<div class="btn-group pull-right">
						<button type="button" class="btn btn-primary btn-s dropdown-toggle" data-toggle="dropdown">
							View Options 
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu" role="menu">
							<li role="presentation"><a class="hidden toggleRejected" id="hideRejected" role="menuitem" tabindex="-1" href="#"><span class="glyphicon glyphicon-thumbs-up"></span>&nbsp;&nbsp;Show Only Liked</a></li>
							<li role="presentation"><a class="toggleRejected" id="showRejected" role="menuitem" tabindex="-1" href="#"><span class="glyphicon glyphicon-thumbs-up"></span>&nbsp;&nbsp;Show All (Liked/Disliked)</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</header>
	
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<table class="table table-hover" id="listingsGrid">
				<thead>
					<tr>
						<th class="hidden-xs">How New?</th>
						<th>Township</th>
						<th>Address</th>
						<th><small><a id="showAllPrice" href="javascript:void(0)">Show All</a></small><br />Listing Price</th>
						<th><span class="pull-right">Options</span></th>
					</tr>
				</thead>
			</table>
			<footer>
				<a target="_blank" href="http://derrickshowers.com"><img class="pull-left" src="img/logo.png" /></a>
				<p class="pull-right">
					<small>Data provided by <a target="_blank" href="http://everyhome.com">http://everyhome.com</a></small>
				</p>
			</footer>
		</div>
	</div>
	
	<div id="modal_form" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					Add Some Notes
				</div>
				<div class="modal-body">
					<form class="notesForm">
					<textarea id="notes" class="form-control" name="notes" rows="3"></textarea>
					<input type="hidden" id="address" name="address" value="none" />
					<input type="hidden" id="rejected" name="rejected" value="Y" />
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary" >Save changes</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</form>
				</div>
			</div>
		</div>
	</div>
	
	<div id="modal_notes" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					Notes
				</div>
				<div class="modal-body">
					<p id="rejectNotes"></p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	
	<noscript>
		<div id="noscriptError" class="alert alert-danger">
			<p>You Need JavaScript turned on for this to work. What are you thinking?! :)</p>
		</div>
		<div id="errorOverlay"></div>
	</noscript>
	
	<!--[if lt IE 9]>
		<div id="ieError" class="alert alert-danger">
			<p>You are using an outdated browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame.</p>
		</div>
		<div id="errorOverlay"></div>
	<![endif]-->

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.2.min.js"><\/script>')</script>
	<script src="js	/plugins.js"></script>
	<script src="js/main.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>