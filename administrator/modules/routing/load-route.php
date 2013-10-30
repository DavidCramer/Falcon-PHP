<?php
include_once 'administrator/modules/routing/functions.php';

$index = $_REQUEST['index'];


$route = array(
	"name"	=> "",
	"desc"	=> ""
);
if(!empty($_POST['callback'])){

	//dump($_POST);

	$routes = json_decode(file_get_contents('resources/routes.json'), true);
	// sorter
	$list = array();
	for($i = 0; $i<count($routes); $i++){
		$list[$routes[$i]['name']] = $routes[$i];
	}
	ksort($list);	
	$routes = array_values($list);

	
	if(empty($_POST['delete'])){

		if(!empty($_POST['preheaders'])){
			for($h=0; $h<count($_POST['preheaders']['header']); $h++){
				$value = urldecode($_POST['preheaders']['value'][$h]);				
				if(function_exists('get_magic_quotes_gpc')){
					if(get_magic_quotes_gpc()){
						$value = stripslashes($value);
					}
				}
				$_POST['headers'][$_POST['preheaders']['header'][$h]] = $value;
			}
			unset($_POST['preheaders']);
		}
		// clean up name
		if(!empty($_POST['name'])){
			$_POST['name'] = ltrim(preg_replace("/[\/]{2,}/", '/', $_POST['name']),'/');
		}		
		$routes[$index] = $_POST;
	}else{
	//$data=json_encode($_POST);
		unset($routes[$index]);
		$routes = array_values($routes);
	}


	// write
	$file = fopen('resources/routes.json', 'w+');
	fwrite($file, json_encode($routes));
	fclose($file);
	echo '<span class="trigger" data-request="module/routing/routes" data-reload="true" data-route="'.$index.'" data-target="list-content" data-autoload="clean"></span>';
	echo '<span class="trigger" data-callback="showDeleteButton" data-autoload="clean"></span>';
	if(!empty($_POST['delete'])){
		echo '<div class="content"><div class="content-header"><div class="alert alert-success">Route deleted</div></div></div>';
		return;
	}
}else{
	$routes = json_decode(file_get_contents('resources/routes.json'), true);
	// sorter
	$list = array();
	for($i = 0; $i<count($routes); $i++){
		$list[$routes[$i]['name']] = $routes[$i];
	}
	ksort($list);	
	$routes = array_values($list);	
}

if($index == 'new'){
	$index = count($routes);
	$hideDel = true;
}else{
	$route = $routes[$index];
}

function r_buildSelectOptions($list){
	$folder = trim($list[0]);
	unset($list[0]);
	$options = '';

	if(!empty($list[1])){
		if(!empty($list)){
			if($folder != 'methods' && !is_array($list[1])){
				$options .= '<optgroup label="'.str_replace('methods/','', $folder).'">';
			}
			foreach($list as &$file){
				if(is_array($file)){
					$options .= r_buildSelectOptions($file);
				}else{
					$options .= '<option value="'.$folder.'/'.$file.'">'.str_replace('.php','',basename($file)).'</option>';
				}
			}
			if($folder != 'methods' && !is_array($list[1])){
				$options .= '</optgroup>';
			}
		}
	}
return $options;
}

$list = r_listFolderContents('methods');
$methods = r_buildSelectOptions($list);



if(empty($_POST['callback'])){
?>
<form class="pure-form pure-form-stacked trigger" action="module/routing/routes/load-route" data-index="<?php echo $index; ?>" method="POST" data-callback="saveRoute" data-load-element="routeSaver">
<div class="content">
	<div class="content-header" id="routeSaver">
	<?php } ?>
		<div class="content-title"><input type="text" id="routeAddress" name="name" placeholder="route address" value="<?php echo $route['name']; ?>" required></div>
		<?php if(!empty($route['desc'])){ ?><h4 class="content-subtitle"><?php echo $route['desc']; ?></h4><?php } ?>
	<?php
	if(!empty($_POST['callback'])){
		return;
	}
	?>
	</div>
	<div class="content-body">

			<div class="tabbable tabs-left">
				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#general">General</a></li>
					<li><a data-toggle="tab" href="#headers">Headers</a></li>
					<li><a data-toggle="tab" href="#libraries">Libraries</a></li>
					<li><a data-toggle="tab" href="#methods">Methods</a></li>
				</ul>
				<div class="tab-content">
					<div id="general" class="tab-pane active">
						<h5>General</h5>
						<label class="control-label" for="inputDesc">Description</label>
						<textarea id="inputDesc" name="desc" class="span5" placeholder="description"><?php echo $route['desc']; ?></textarea>
						<label for="option-one" class="pure-checkbox">
							<input id="option-one" type="checkbox" name="debug" value="true" <?php if(!empty($route['debug'])){ echo 'checked="checked"';} ?>>&nbsp;
							Enable Debugging
						</label>
					</div>
					<div id="headers" class="tab-pane pure-u-1-2">
						<h5>Headers</h5>
						<p><small class="textinfo">Set response headers for request. (PHP functions can be used if wrapped in [[ ]]. e.g [[date('r', strtotime('+7 hours'));]]</small></p>
						<div id="headersAdd" style="margin-bottom:14px;">
							<input type="text" placeholder="header" id="route-head" autocomplete="off" data-source="[&quot;Access-Control-Allow-Origin&quot;,&quot;Accept-Ranges&quot;,&quot;Age&quot;,&quot;Allow&quot;,&quot;Cache-Control&quot;,&quot;Connection&quot;,&quot;Content-Encoding&quot;,&quot;Content-Language&quot;,&quot;Content-Length&quot;,&quot;Content-Location&quot;,&quot;Content-MD5&quot;,&quot;Content-Disposition&quot;,&quot;Content-Range&quot;,&quot;Content-Type&quot;,&quot;Date&quot;,&quot;ETag&quot;,&quot;Expires&quot;,&quot;Last-Modified&quot;,&quot;Link&quot;,&quot;Location&quot;,&quot;P3P&quot;,&quot;Pragma&quot;,&quot;Proxy-Authenticate&quot;,&quot;Refresh&quot;,&quot;Retry-After&quot;,&quot;Server&quot;,&quot;Set-Cookie&quot;,&quot;Status&quot;,&quot;Strict-Transport-Security&quot;,&quot;Trailer&quot;,&quot;Transfer-Encoding&quot;,&quot;Vary&quot;,&quot;Via&quot;,&quot;Warning&quot;,&quot;WWW-Authenticate&quot;]" data-items="8" data-provide="typeahead" style="margin: 0 auto;" class="input-medium routeHeads">
							<input type="text" placeholder="value" id="head-val" style="margin: 0 auto;" class="span3 routeHeads">
							<button class="btn btn-small btn-success trigger" data-callback="addRouteHeader" type="button"><i class="icon-plus"></i></button>
						</div>
						
						<div id="headList">
						<?php
							$headerIndex = 0;
							if(!empty($route['headers'])){
								foreach($route['headers'] AS $header => &$value){
									echo '<div class="row headersDefine" id="header'.$headerIndex.'" style="margin: 5px 0;">';
										echo '<div class="span2">';
											echo '<span style="text-align:left; opacity: 1;">'.$header.'</span>';
											echo '<input type="hidden" name="preheaders[header][]" value="'.$header.'" />';
										echo '</div>';
										echo '<div class="span3">';
											echo '<span style="text-align:left; opacity: 1;">'.$value.'</span>';
											echo '<input type="hidden" name="preheaders[value][]" value="'.$value.'" />';
										echo '</div>';

										echo '<div class="span1">';
											echo '<button class="btn btn-small btn-danger" type="button" onclick="jQuery(\'#header'.$headerIndex.'\').remove();"><i class="icon-remove"></i></button>';
										echo '</div>';
									echo '</div>';

									$headerIndex++;
								}
							}
						?>
						</div>
					</div>
					<div id="libraries" class="tab-pane pure-u-1-2">
						<h5>Libraries</h5>
						<p><small class="textinfo">Load additional libraries and classes before routing request.</small></p>
						<div id="methodAdd">
							<div class="input-append">
								<select id="lib-file" class="routeLib">
									<option></option>
									<?php
										echo $methods;
									?>
								</select>
								<button class="btn btn-small btn-success trigger" data-callback="addRouteLib" type="button"><i class="icon-plus"></i></button>
							</div>
						</div>
						<div id="libList">
						<?php
						
						$noLibs = true;
						if(!empty($route['libraries'])){
							
							foreach($route['libraries'] as $libind=>&$file){

								$noLibs = false;
								echo '<div class="row routeLibDefine" id="routeLib'.$libind.'" style="margin: 5px 0;">';
									echo '<div class="span5">';
										echo '<span style="text-align:left; opacity: 1;">'.$file.'</span>';
										echo '<input type="hidden" name="libraries[]" value="'.$file.'" />';
									echo '</div>';
									echo '<div class="span1">';
										echo '<button class="btn btn-small btn-danger" type="button" onclick="jQuery(\'#routeLib'.$libind.'\').remove();"><i class="icon-remove"></i></button>';
									echo '</div>';
								echo '</div>';
							}
							
						}
						if(!empty($noLibs)){
							echo '<div id="noLibs" class="alert">No libraries have been added</div>';
						}

						?>
						</div>
					</div>
					<div id="methods" class="tab-pane">
					<h5>Methods</h5>
					<p><small class="textinfo">Direct request methods to designated method files. Undefined methods will not be served.</small></p>
						<div id="methodAdd">
							<div class="input-prepend input-append">
								
								<select id="route-method" class="routeMethod" style="width: auto;">
									<option value="" selected="">select method</option>
									<option value="GET">GET</option>
									<option value="POST">POST</option>
									<option value="PUT">PUT</option>
									<option value="PATCH">PATCH</option>
									<option value="DELETE">DELETE</option>
									<option value="COPY">COPY</option>
									<option value="HEAD">HEAD</option>
									<option value="OPTIONS">OPTIONS</option>
									<option value="LINK">LINK</option>
									<option value="UNLINK">UNLINK</option>
									<option value="PURGE">PURGE</option>										
								</select>
								<select id="method-file" class="routeMethod">
									<option></option>
									<?php
										echo $methods;
									?>
								</select>
								<button class="btn btn-small btn-success trigger" data-callback="addRouteMethod" type="button"><i class="icon-plus"></i></button>
							</div>

						</div>
						<div id="methodsList">
							<?php 
							//dump($route['methods'],0);
								$noMethods = true;
								if(!empty($route['methods'])){
									
									foreach($route['methods'] as $method=>$conf){
										if(empty($conf['file'])){
											continue;
										}
										$noMethods = false;
										echo '<div class="row routeMethodDefine" id="routeMethod'.$method.'" style="margin: 5px 0;">';
											echo '<div class="span1" style="margin-left: 0;">';
												echo '<span class="btn btn-primary btn-small btn-block disabled" style="opacity: 1;">'.$method.'</span>';
											echo '</div>';
											echo '<div class="span5">';
												echo '<span style="text-align:left; opacity: 1;">'.$conf['file'].'</span>';
												echo '<input type="hidden" name="methods['.$method.'][file]" value="'.$conf['file'].'" />';
											echo '</div>';
											echo '<div class="span1">';
												echo '<button class="btn btn-small btn-danger" type="button" onclick="jQuery(\'#routeMethod'.$method.'\').remove();"><i class="icon-remove"></i></button>';
											echo '</div>';
										echo '</div>';
									}
									
								}
								if(!empty($noMethods)){
									echo '<div id="noMethods" class="alert">No methods have been defined</div>';
								}
							?>
						</div>
					</div>
				</div>
			</div>

			<hr>
			<?php if(empty($hideDel)){ ?>
				<button type="submit" class="btn btn-small btn-primary primary"><i class="icon-save"></i> &nbsp;Save Changes</button>&nbsp;&nbsp;&nbsp;
			<?php }else{ ?>
				<button id="routeSaveButton" type="submit" class="btn btn-small btn-primary primary"><i class="icon-save"></i> &nbsp;Create Route</button>&nbsp;&nbsp;&nbsp;
			<?php } ?>
				<button id="routeDeleteButton" type="button" class="btn btn-small btn-danger <?php if(!empty($hideDel)){ ?>hide<?php } ?> trigger" data-before="confirmDeleteRoute" data-request="module/routing/routes/load-route" data-index="<?php echo $index; ?>" data-method="POST" data-delete="true" data-callback="loadRoute"><i class="icon-remove"></i> &nbsp;Delete Route</button>

		<?php
//		dump($routes[$index],0);
		?>
	</div>
</div>
</form>


