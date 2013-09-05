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
	<meta name="viewport" content="width=device-width">
	<link rel="stylesheet" href="css/normalize.css">
	<link rel="stylesheet" href="css/main.css">
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="css/styles.css" rel="stylesheet" media="screen">
	<script src="js/vendor/modernizr-2.6.2.min.js"></script>
</head>
<body>
	<!--[if lt IE 7]><p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p><![endif]-->
	
	<div class="row-fluid">
		<div class="offset1 span10">
			<header>
				<h1>Newest Houses</h1>
				<form method="get">
					<p id="daysText" class="pull-left">Showing Within Past <input id="days" class="input-small" name="days" type="text" value="<?php echo $daysSelected ?>" /> Days</p>
					<div class="checkbox pull-right">
						<label>
							<input id="priceFilter" name="priceFilter" type="checkbox" <?php echo ($priceFilter) ? "checked" : ""; ?> /> Only show houses in our price range ($200,00 - $400,000)
						</label>
					</div>
				</form>
			</header>
			<table class="table table-hover">
				<thead>
					<tr>
						<th>How New?</th>
						<th>Township</th>
						<th>Address</th>
						<th>Listing Price</th>
						<th>Check It Out!</th>
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

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.2.min.js"><\/script>')</script>
	<script src="js	/plugins.js"></script>
	<script src="js/main.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>