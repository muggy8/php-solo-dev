<meta charset="utf-8">
<meta name="author" content="Your Name">
<meta name="viewport" content="width=device-width, maximum-scale=1.0, minimun-scale=1.0, initial-scale-1.0">
<meta name="format-detection" content="telephone=no">
<?php
	$critCSS = "";
	foreach($inlineCSSList as $cssLib){
		$critCSS .= file_get_contents("$phpRoot/inc/css/$cssLib");
	}
?>
<style id="critical-css">
	<?php echo $critCSS; ?>
</style>