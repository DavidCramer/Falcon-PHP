<?php

$methodfile = trim($_REQUEST['methodfile'],'/');

	if(!empty($_POST)){



	if(!empty($_POST['save'])){
		$data = '';
		if(isset($_POST['value'])){
			$data = $_POST['value'];
			if(function_exists('get_magic_quotes_gpc')){
				if(get_magic_quotes_gpc()){
					$data = stripslashes($data);
				}
			}
			
		}
		$filename = 'methods/'.$methodfile;
		$saveFile = fopen($filename, 'w+');
		fwrite($saveFile, $data);
		fclose($saveFile);

		die;
	}



		if(file_exists('methods/'.$methodfile)){
			unlink('methods/'.$methodfile);
			echo '<div class="alert alert-success trigger" data-target="list-content" data-autoload="true" data-reload="true" data-request="module/routing/methods" style="border-radius: 0 0 0 0;height: 19px;">method: '.str_replace('.php','', $methodfile).' deleted successfully.</div>';
			die;
		}
	}
	$editorID = $_GET['id'];

?>
<div class="content editor-wrapper" id="wrapper<?php echo $editorID; ?>">
	<div class="editor-body">
		<div class="primary">
			<div class="nav-header">
				<div class="btn-group pull-right" style="margin:-1px 10px 0 0;">
					<?php /*<button class="btn btn-primary btn-mini" href="#"><i class="icon-download"></i> Download</button>*/ ?>
					<button class="btn btn-danger btn-mini dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-remove"></i> Delete File</button>
					<ul class="dropdown-menu">
						<li class="trigger" data-delete="true" data-request="module/routing/methods/load-file" data-methodfile="<?php echo $methodfile; ?>" data-method="POST" data-target="wrapper<?php echo $editorID; ?>"><a href="#"><i class="icon-remove"></i>&nbsp;&nbsp;Confirm Delete</a></li>
					</ul>
				</div>
				<?php echo str_replace('.php','', $methodfile); ?>
			</div>
		</div>
		<textarea class="hide" data-save="true" data-callback="saveFile" data-method="POST" data-request="module/routing/methods/load-file" data-methodfile="<?php echo $methodfile; ?>" id="textarea<?php echo $editorID; ?>"><?php echo htmlspecialchars(file_get_contents('methods/'.$methodfile)); ?></textarea>
	</div>
</div>