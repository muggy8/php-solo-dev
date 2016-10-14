<?php
	$phpRoot = dirname(__FILE__);
	$htRoot = "http://".$_SERVER["HTTP_HOST"]."";
	
	// local assets should live in /inc/js/... or /inc/css/... the php script will automatically convert all relative scripts to absolute scripts from those directories. external scripts (eg: jQuery, angular, bootstrap, etc) should start with http:// or https:// and will be loaded off the net 
	
	// If you are in dev mode, you probably do not want your assets to cache so to do this you want to add a timestamp to all of your assets so they dont cache. Below, the CSS for Open Sans will cache but the CSS for general wont. In deployment, you would replace the ?t=".time() with just a lone ending quote and the asset will cache. JS assets are cached in the same way with Basket.js (which is inlined in floor.php). You can use browser native caching as well but if you do not know how to set up your .htaccess file or do not have access to it changing server settings, basket js is probably better. Basket JS is provided by addy osmani at https://addyosmani.com/basket.js/
	
	// critical styles for page first view will be inlined into the page (You can inline more than just 1 css file.)
	$inlineCSSList = array("critical.css"); 
	
	// other not so important CSS will be added into the page via <link> tag
	$linkedCSSList = array("https://fonts.googleapis.com/css?family=Open+Sans", "general.css?t=".time());
	
	// scripts are loaded and cached in localstorage via basket.js. 
	$cachedScripts = array("scripts.js?t=".time()); 
	
	// If you put a version on here, all assets (images included) will be loaded with ?v=$localAssetVersion at the end. This way if you update your assets in production and things are cached (eg: with a CDN) you can update changes easily. in testing $localAssetVersion should be set to time() so each reload loads new assets.
	$localAssetVersion = "";
	
	include_once("$phpRoot/inc/php-tools.php");
?>