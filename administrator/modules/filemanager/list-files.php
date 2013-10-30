<?php



	function fm_listFiles($folder){
		if ($handle = opendir($folder)) {    
			while (false !== ($entry = readdir($handle))) {
				if($entry != '.' && $entry != '..'){
					$fileList[] = $folder.$entry;
				}
			}
			closedir($handle);
		}
		return $fileList;
	}

	$folder = ABSPATH;
	if(!empty($_GET['lib'])){
		$folder = $_GET['lib'];
		$base = dirname($_GET['lib']);
	}
	$files = fm_listFiles($folder);


	$list = array(

		"title"		=> "Files",
		"tools"		=> array(
			array(
				"icon"				=>	"plus",
				"class"				=> "btn-primary",
				"attributes"		=> array(
					"callback"		=> "loadRoute",
					"index"			=> "new",
					"request"		=> "module/filemanager/files/lib-detail",
					"active-class"	=> "null"
				)
			)
		),
		"attributes"		=> array(
			"request"		=> "module/filemanager/files",
			"active-class"	=> "item-selected",
			"data-group"	=> "list-routes",
			"target"		=> "list"
		)
	);

	if(!empty($files)){
		$basePath = '';
		if(!empty($_GET['lib'])){
			if($folder != ABSPATH){
				$basePath = str_replace(ABSPATH,'',$folder);
				$list['items'][] = array('header'=>$basePath);
				$list['items'][] = array(
					"title"			=> '<i class="icon-double-angle-left"></i> Back',
					"attributes"	=> array(
						"lib"		=> dirname($folder).'/',
						"clear"		=> 'main'
					)				
				);			
			}
		}
		for($i=0; $i<count($files); $i++){
			$key = 0;
			if(!empty($list['items'])){
				$key = count($list['items']);
			}
			if(is_file($files[$i])){
				$list['items'][$key]['title'] = '<i class="icon-file"></i> '.str_replace($basePath, '', str_replace(ABSPATH, '', $files[$i]));
				$list['items'][$key]['attributes']['request'] = 'module/filemanager/files/lib-detail';
				$list['items'][$key]['attributes']['target'] = 'main';
				$list['items'][$key]['attributes']['lib'] = $files[$i];
				$list['items'][$key]['attributes']['success'] = 'doHighlight';

			}else{
				$list['items'][$key]['title'] = '<i class="icon-folder-close"></i> '.str_replace($basePath, '', str_replace(ABSPATH, '', $files[$i]));
				$list['items'][$key]['attributes']['lib'] = $files[$i].'/';
			}
		}
	}

	return $list;
?>