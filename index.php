<?php
	include('controller/pulldata.php');
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
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="css/normalize.css">
	<link rel="stylesheet" href="css/main.css">
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
	<link href="css/styles.css" rel="stylesheet" media="screen">
	<script src="js/vendor/modernizr-2.6.2.min.js"></script>
</head>
<body>
	<!--[if lt IE 7]><p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p><![endif]-->
	
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<header>
				<h1>Newest Houses</h1>
				<form method="get">
					<p id="daysText" class="pull-left">Showing Within Past <input id="days" class="input-sm" name="days" type="text" value="<?php echo $daysSelected ?>" /> Days</p>
				</form>
				<div class="btn-group pull-right">
					<button type="button" class="btn btn-primary btn-s dropdown-toggle" data-toggle="dropdown">
						View Options 
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu" role="menu" aria-labelledby="dropdown">
						<?php if ($priceFilter) : ?>
							<li role="presentation"><a show="showPrice" role="menuitem" tabindex="-1" href="<?php echo '/' . (!empty($_GET['days']) ? '?days=' . $_GET['days'] : '?') . ''?>"><span class="glyphicon glyphicon-usd"></span>&nbsp;&nbsp;Show All (Price)</a></li>
						<?php else: ?>
							<li role="presentation"><a id="hidePrice" role="menuitem" tabindex="-1" href="<?php echo '/' . (!empty($_GET['days']) ? '?days=' . $_GET['days'] . '&' : '?') . 'priceFilter=on'?>"><span class="glyphicon glyphicon-usd"></span>&nbsp;&nbsp;Hide -200k, +400k</a></li>
						<?php endif ?>
						<li role="presentation"><a class="hidden toggleRejected" id="hideRejected" role="menuitem" tabindex="-1" href="#"><span class="glyphicon glyphicon-thumbs-up"></span>&nbsp;&nbsp;Show Only Liked</a></li>
						<li role="presentation"><a class="toggleRejected" id="showRejected" role="menuitem" tabindex="-1" href="#"><span class="glyphicon glyphicon-thumbs-up"></span>&nbsp;&nbsp;Show All (Liked/Disliked)</a></li>
					</ul>
				</div>
			</header>
			<table class="table table-hover">
				<thead>
					<tr>
						<th class="hidden-xs">How New?</th>
						<th>Township</th>
						<th>Address</th>
						<th>Listing Price</th>
						<th><span class="pull-right">Options</span></th>
					</tr>
				</thead>
				<tbody>
					<?php echo $list; ?>
				</tbody>
			</table>
			<footer>
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
					Why Not? (Notes)
				</div>
				<div class="modal-body">
					<form class="rejectForm">
					<textarea id="notes" class="form-control" name="notes" rows="3"></textarea>
					<input type="hidden" id="address" name="address" value="none" />
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

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.2.min.js"><\/script>')</script>
	<script src="js	/plugins.js"></script>
	<script src="js/main.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>