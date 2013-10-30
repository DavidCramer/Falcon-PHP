<?php 

$heading = 'Upload new library file';
if(!empty($_GET['lib'])){
	$heading = str_replace(ABSPATH,'',$_GET['lib']);
}
if(!empty($_POST)){
	dump($_FILES);
	dump($_POST);
}

?><div class="editor-body">
	<div class="primary">
		<div class="nav-header">
			<?php echo $heading; ?>
		</div>
	</div>
	<div class="content-body">
		<?php 
		if(empty($_GET['lib'])){
			?>
			<form id="uploadForm" enctype="multipart/form-data" data-target="main" method="POST" action="module/routing/libraries/lib-detail" data-progress="uploadProgress">
				<label>Select file: </label><input class="trigger" type="file" name="libFile[]" data-for="uploadForm" data-event="change" multiple>
			</form>
			<hr>
			<div id="uploadProgress" class="progress"><div class="bar" style=""></div></div>

			<?php
		}else{
			echo '<textarea style="display:none;" id="readCode">'.htmlentities(file_get_contents($_GET['lib'])).'</textarea>';
			echo '<div id="dumpVars"></div>';
		}
		?>		
	</div>
</div>