<?php
include_once 'administrator/modules/routing/functions.php';

function r_buildFileEntries($dir, $default = false){	
	$folder = trim($dir[0]);
	unset($dir[0]);
	if($folder != 'methods' && !empty($dir[1])){
		if(!is_array($dir[1])){
		?>
		<div class="nav-header">
			<?php echo str_replace('methods/', '', $folder); ?>
		</div>
		<?php
		}
	}
	$folder = str_replace('methods', '', $folder);
	foreach($dir as &$entry){
		if(is_array($entry)){
			r_buildFileEntries($entry, $default);
			continue;
		}
	    $autoload = false;
		
		if(!empty($default)){
			if(basename($default) == $entry){
				$autoload=true;
			}
		}
		$itemid = uniqid('itm');
		?>
		<div id="item<?php echo $itemid; ?>" class="item trigger" <?php if(!empty($autoload)){ echo 'data-autoload="true"';} ?> data-callback="newEdit" data-before="isOpen" data-group="list-items" data-id="<?php echo $itemid; ?>" data-active-class="item-selected" data-request="module/routing/methods/load-file" data-methodfile="<?php echo $folder.'/'.$entry; ?>">
			<div class="entry" data-id="<?php echo $itemid; ?>" style="margin-right: 30px;">
				<div class="btn-group tools pull-right hide" style="margin-right: -30px; margin-top: -2px;">
					<button class="btn btn-mini btn-success" data-save="true"><i class="icon-save"></i></button>
				</div>
				<?php echo '<i class="icon-code"></i> '.str_replace('.php','',$entry); ?>
			</div>
		</div>
		<?php
	}	
}

if(!empty($_POST)){
	if(!empty($_POST['value'])){
		$filebase = sanitize_file_name(strtolower($_POST['value']));
		$filename = 'methods/'.$filebase.'.php';
		
		if(empty($filebase) || $filename == 'methods/.php'){
			 echo '<div class="item"><div class="entry text-error">Invalid file name</div></div>';
		}else{
			if(file_exists($filename)){
				echo '<div class="item"><div class="entry text-error">File already exists</div></div>';
			}else{
				// lets make folders
				$dirname = dirname($filename);
				$path = explode('/',$dirname);
				$branch = 'methods';
				foreach($path as &$dir){
					if(empty($dir)){
						unsert($dir);
						continue;
					}
					if($dir == 'methods'){
						continue;
					}
					$branch .= '/'.$dir;
					if(!file_exists($branch)){
						mkdir($branch);
					}
				}
				$template = "<?php
/*

Caldoza Engine ------------------------

File	:	".substr($filename, 8)."
Created	: 	".date('Y-m-d')."

*/




?>";
				$newFile = fopen($filename, 'w+');
				fwrite($newFile, $template);
				fclose($newFile);
			}
		}
	}
}else{
	if(empty($_GET['reload'])){
?>
<div class="item primary" id="methodToolbar">
	<div class="nav-header"><div class="btn-group pull-right">
			<button class="btn btn-small btn-primary icon-plus primary trigger" data-callback="newMethod"></button>
		</div>
		Method Files
	</div>
</div>
<div class="content" id="list-content">

<?php	
	}
}


$files = r_listFolderContents('methods');

if(count($files) == 1){
	echo '<div class="well well-small muted">No method files defined</div>';
}else{
	echo r_buildFileEntries($files);
}


if(empty($_GET['reload'])){
?></div>
<?php } ?>