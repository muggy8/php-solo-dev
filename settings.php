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
	
	// minify HTML responce. and replaces all instances of {htRoot} with whatever your declared html root is. 
	
	// usage: if your root is "http://example.com/test" and you have something like src="{htRoot}/img/asset.jpg" somewhere, the HTML responce will have http://example.com/test/img/asset.jpg in the src attribute.
	function sanitize_output($buffer) {
		global $htRoot;
		$search = array(
			'!/\*[^*]*\*+([^/][^*]*\*+)*/!', // strip CSS comments if there's any in line
			'/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\')\/\/.*))/', // remove js comments first
			'/<!--(.*)-->/Uis',   // strip html comments
			'/([ \t]|\n)/',       // remove tabs or new line
			'/\>[^\S ]+/s',  // strip whitespaces after tags, except space
			'/[^\S ]+\</s',  // strip whitespaces before tags, except space
			'/(\s)+/s',       // shorten multiple whitespace sequences
			'/\{htRoot\}/i'
		);

		$replace = array(
			'',
			'',
			' ',
			' ',
			'>',
			'<',
			'\\1',
			$htRoot
		);

		$buffer = preg_replace($search, $replace, $buffer);
		$buffer = str_replace('> <', '><', $buffer);
		return $buffer;
	}

	ob_start("sanitize_output");
	
	// You might not need the following but if you have SVGs, you can use this function to inline the SVG into the HTML responce and eliminating the need for additional requests
	
	// usage: <img src="{htRoot}/img/my-asset.svg" class="my-class"> becomes <?php echo_svg($phpRoot."/img/my-asset.svg", "my-class"); other code maybe...
	function echo_svg($path, $classAdd = "", $htFallback = ""){
		if (!file_exists($path)){
			echo "$path was not found";
		}
		else{
			$svgStr = preg_replace('/\<\?xml(.|\n)*\<svg/iU', '<svg', file_get_contents($path));
			
			if ($htFallback !== ""){
				$svgFallbackLink = '<image src="'.$htFallback.'" xlink:href="">';
				
				$svgStr = preg_replace('/\<\/svg\>/iU', $svgFallbackLink."</svg>", $svgStr);
			}
			
			$fileName = preg_replace("/\s/" , "-" , basename($path, ".svg"));
			
			// add file name to classes
			$classAdd = $fileName . " " . $classAdd;
			$svgStr = preg_replace('/\<svg/iU', '<svg class="'.$classAdd.'"', $svgStr);
			
			// localize the colors to the current file
			$svgStr = preg_replace_callback(
				"/\<style(.|\n)+\<\/style\>/imU",
				function($styleEle) use ($fileName){
					return ( preg_replace_callback(
						"/\..+{/imU",
						function($rule) use ($fileName){
							return ("." . $fileName . " " . $rule[0]);
						},
						$styleEle[0]
					));
				},
				$svgStr
			);
			
			echo $svgStr;
		}
	}
?>