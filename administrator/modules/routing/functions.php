<?php
	function is_dir_empty($dir) {
		if (!is_readable($dir)) return NULL; 
		return (count(scandir($dir)) == 2);
	}
	function r_listFolderContents($folder){
		
		$folder = rtrim($folder,'/');
		$fileList = array(
			'folder'	=> "\t\r\n".$folder
		);
		if ($handle = opendir($folder)) {    
		    while (false !== ($entry = readdir($handle))) {
		    	if($entry != '.' && $entry != '..'){
		    		if(is_dir($folder.'/'.$entry)){
		    			$fileList[$folder.'/'.$entry] = r_listFolderContents($folder.'/'.$entry);
		    		}else{
			    		$pthinfo = pathinfo($entry);
			    		if(strtolower($pthinfo['extension']) !== 'php'){
			    			continue;
			    		}
			    		$fileList[] = $entry;
			    	}
		    	}
		    }
		    closedir($handle);
		}
		if(count($fileList) == 1){
			if(is_dir_empty(trim($fileList['folder']))){
				if(trim($fileList['folder']) != 'methods'){
					rmdir(trim($fileList['folder']));
				}
			}
		}
		sort($fileList);
		return $fileList;
	}
?>