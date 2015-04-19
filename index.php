<?php require_once('includes/functions.php'); ?>
<?php session_handler(); ?>
<?php $link = db_connect(); ?>
<?php auth_check($link); ?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>CodeSnap &mdash; Personal Code Library</title>

<link href='http://fonts.googleapis.com/css?family=Source+Code+Pro:300,400,600' rel='stylesheet' type='text/css'>
<link href="styles.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>

</head>

<body>

	<div id="page">
    
    	<?php load_page($link); ?>

    </div>

</body>


<?php jsLoader(); ?>

</html>
<?php db_disconnect($link); ?>