<?php
// load routes
$routes = json_decode(file_get_contents('resources/routes.json'), true);


// show header
if(empty($_GET['reload'])){
?><div class="item primary">
	<div class="nav-header">Routes
		<div class="btn-group pull-right">
			<button class="btn btn-small btn-inverse icon-collapse" onclick="toggleRouteDesc();"></button>
			<button class="btn btn-small btn-primary icon-plus primary trigger" data-active-class="null" data-request="module/routing/routes/load-route" data-index="new" data-callback="loadRoute"></button>
		</div>
	</div>
</div>
<div class="content" id="list-content">
<?php
}
$hide = true;
if(isset($_COOKIE['route-desc'])){
	$hide = $_COOKIE['route-desc'];
}


if (!empty($routes)) {
	// sorter
	$list = array();
	for($i = 0; $i<count($routes); $i++){
		$list[$routes[$i]['name']] = $routes[$i];
	}
	ksort($list);	
	$routes = array_values($list);
	for($i = 0; $i<count($routes); $i++){
		$class = '';
		if(isset($_GET['route'])){
			if($i == $_GET['route']){
				$class = ' item-selected';
			}
		}
		?>
		<div class="item">
			<div id="route<?php echo $i; ?>" class="entry trigger<?php echo $class; ?>" data-group="list-routes" data-id="<?php echo $i; ?>" data-active-class="item-selected" data-request="module/routing/routes/load-route" data-index="<?php echo $i; ?>" data-load-element="main" data-callback="loadRoute">
				<div id="routeentry<?php echo $i; ?>">
					<div class="subject">
						<?php echo preg_replace("/:([a-zA-Z0-9_]+)/", '<code class="code">$1</code>', $routes[$i]['name']); ?>
					</div>
					<?php if(!empty($routes[$i]['desc'])){ ?>
					<div class="desc routedesc <?php if($hide == 'true'){ echo 'hide'; } ?>">
						<?php echo $routes[$i]['desc']; ?>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php
        
    }

}else{
	echo '<div class="well well-small muted">No routes defined</div>';
}
if(empty($_GET['reload'])){
?></div>
<?php } ?>