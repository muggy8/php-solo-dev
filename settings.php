<?php
	$phpRoot = dirname(__FILE__);
	$htRoot = "http://".$_SERVER["HTTP_HOST"]."";
	
	// local assets should live in /inc/js/... or /inc/css/... the php script will automatically convert all relative scripts to absolute scripts from those directories. external scripts (eg: jQuery, angular, bootstrap, etc) should start with http:// or https:// and will be loaded off the net 
	
	// JS assets are cached with Basket.js (which is inlined in floor.php). You can use browser native caching as well but if you do not know how to set up your .htaccess file or do not have access to it changing server settings, basket js will be able to cache it for you. Basket JS is provided by addy osmani at https://addyosmani.com/basket.js/
	
	// critical styles for page first view will be inlined into the page (You can inline more than just 1 css file.)
	$inlineCSSList = array("critical.css"); 
	
	// other not so important CSS will be added into the page via <link> tag
	$linkedCSSList = array("https://fonts.googleapis.com/css?family=Open+Sans", "general.css");
	
	// scripts are loaded and cached in localstorage via basket.js. 
	$cachedScripts = array("scripts.js"); 
	
	// If you put a version on here, all assets will be loaded with ?v=$localAssetVersion at the end. This way if you update your assets in production and things are cached (eg: with a CDN) you can update changes easily. in testing $localAssetVersion should be set to time() so each reload loads new assets.
	$localAssetVersion = time();
	
	// regex for image assets kind of expensive so you can disable it here if you want.
	$imageVersioning = true;
	
	// (unimplemented yet) if this is true, any page that is visited by a user will also output the page_name.html and save it in the same directory as the file itself. eg: if the file exists at {htRoot}/index.php it will output a file at {htRoot}/index.html with whatever responce it gets. this is desirable if you have a large number of inlines and includes in pages that you dont need to refresh often. 
	// note 1: apache will default to serve index.html over index.php when both exists in the same directory. 
	// note 2: you can set this to true in the document as well so you only chache pages that are mostly unchanging. 
	// note 3: you can enable multiview with .htaccess and {htRoot}/asset.php can be accessed via the link "{htRoot}/asset". priority will be given in this way: {htRoot}/asset/index.html -> {htRoot}/asset/index.php -> {htRoot}/asset.html - {htRoot}/asset.php
	$cacheResponce = false;
	
	include_once("$phpRoot/inc/php-tools.php");
?>