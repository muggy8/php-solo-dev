<?php
	include_once("../settings.php");
	
	function cache_clean($dir, $indent = ""){
		$scanRes = scandir($dir);
		
		foreach ($scanRes as $potentialDir){
			if ($potentialDir != ".." && $potentialDir != "."){
				if (is_dir("$dir/$potentialDir")){
					//echo ("$indent $dir/$potentialDir is a directory <br>");
					cache_clean("$dir/$potentialDir", "$indent&nbsp;");
				} else {
					$fileType = pathinfo("$dir/$potentialDir", PATHINFO_EXTENSION);
					
					if ($fileType == "html"){
						$fileName = basename("$dir/$potentialDir", ".html");
						if (file_exists("$dir/$fileName.php")){
							//echo "$dir/$potentialDir is a cached HTML file and will now be removed";
							unlink("$dir/$potentialDir");
						}
					}
					
					//echo ("$indent $dir/$potentialDir is a $fileType file <br>");
				}
			}
		}
	}
	
	cache_clean($phpRoot);
?>