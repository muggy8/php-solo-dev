<?php 
	// Remember to point to this file properly at the start of ever file. If you are working out of root, you can use ./settings.php
	include_once("settings.php"); 
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Effecient Web Delivery Wrapper</title>
		<meta name="description" content="This is a bare bones PHP based HTML wrapper that helps solo webmasters create and deploy websites much like they would with a blank text editor. This wrapper takes care of minimizing the HTML responce and inlining the Critical CSS for web view and lazy loading larger CSS files. ">
		<?php include_once($phpRoot."/inc/head.php");?>
	</head>
	<body>
		<?php include_once("$phpRoot/inc/header.php");?>
		<main>
		
		</main>
		<?php include_once("$phpRoot/inc/footer.php");?>
		<?php include_once("$phpRoot/inc/floor.php");?>
	</body>
</html>