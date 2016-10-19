<?php
	// minify HTML responce. and replaces all instances of {htRoot} with whatever your declared html root is. 
	
	// usage: if your root is "http://example.com/test" and you have something like src="{htRoot}/img/asset.jpg" somewhere, the HTML responce will have http://example.com/test/img/asset.jpg in the src attribute.
	function sanitize_output($buffer) {
		global $localAssetVersion;
		global $imageVersioning;
		global $htRoot;
		global $cacheResponce;
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
		
		// add version to image assets
		if ($imageVersioning){
			$buffer = preg_replace_callback('/(?<=(src=\"|src=\'))http.+(?=(\'|\"))/U', function($assetLink)use ($localAssetVersion, $imageVersioning){
				if (preg_match('/\?/i', $assetLink[0])){ // has "?"
					return $assetLink[0]."&v=$localAssetVersion";
				}
				else{
					return $assetLink[0]."?v=$localAssetVersion";
				}
			}, $buffer);
		}
		
		if ($cacheResponce){
			$thisFileName = basename($_SERVER['SCRIPT_NAME'], ".php" );
			$pathName = pathinfo($_SERVER['SCRIPT_FILENAME']);
			$pathName = $pathName['dirname'];
			
			if (!file_put_contents("$pathName/$thisFileName.html", $buffer)){
				$buffer .= "error trying to cache page. Please check directory permissions";
			}
		}
		return $buffer;
	}

	ob_start("sanitize_output");// call sanitize_output on return responce
	
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
	
	if (file_exists("$phpRoot/inc/plugins.php")){
		include_once("$phpRoot/inc/plugins.php");
	}
?>