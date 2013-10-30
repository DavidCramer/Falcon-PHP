<?php

global $scripts, $styles;

if(isset($_GET['logoff'])){
	unset($_SESSION['api_node']);
	$node = $_COOKIE['auth-node'];
	setcookie("auth-node", '', time() - 3600);
	setcookie($node, '', time() - 3600);

	header('location: '.ABSURL.'admin/');
	die;
}
if(empty($_SESSION['api_node'])){
	
	//$_SESSION['api_node'] = $_COOKIE['auth-node'];
	include 'login.php';
	return;
}

$modulesNav = admin_buildModulesNav();

?><!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $route['desc']; ?></title>
	
	<link rel='stylesheet' id='codemirror-css'  href='<?php echo ABSURL; ?>static/codemirror/lib/codemirror.css' type='text/css' media='all' />	
	<!-- <link rel="stylesheet" href="<?php echo ABSURL; ?>static/css/pure-min.css"> -->
	<link rel="stylesheet" href="<?php echo ABSURL; ?>static/css/src/flatly.min.css">
	<link rel="stylesheet" href="<?php echo ABSURL; ?>static/css/font/font-awesome.min.css">
	<?php
	if(is_array($styles)){
		foreach ($styles as &$style) {
			echo '<link rel="stylesheet" href="'.ABSURL.$style.'">'."\r\n";
		}
	}
	?>

	<script type='text/javascript' src='<?php echo ABSURL; ?>static/codemirror/lib/codemirror.js'></script>
	<script type='text/javascript' src='<?php echo ABSURL; ?>static/codemirror/addon/mode/overlay.js'></script>
	<script type='text/javascript' src='<?php echo ABSURL; ?>static/codemirror/mode/css/css.js'></script>
	<script type='text/javascript' src='<?php echo ABSURL; ?>static/codemirror/mode/javascript/javascript.js'></script>
	<script type='text/javascript' src='<?php echo ABSURL; ?>static/codemirror/mode/xml/xml.js'></script>
	<script type='text/javascript' src='<?php echo ABSURL; ?>static/codemirror/mode/clike/clike.js'></script>
	<script type='text/javascript' src='<?php echo ABSURL; ?>static/codemirror/mode/php/php.js'></script>
	<script type='text/javascript' src='<?php echo ABSURL; ?>static/codemirror/mode/htmlmixed/htmlmixed.js'></script>
	
	<script type='text/javascript' src='<?php echo ABSURL; ?>static/codemirror/addon/hint/javascript-hint.js'></script>
	<script type='text/javascript' src='<?php echo ABSURL; ?>static/codemirror/addon/dialog/dialog.js'></script>
	<script type='text/javascript' src='<?php echo ABSURL; ?>static/codemirror/addon/search/searchcursor.js'></script>
	<script type='text/javascript' src='<?php echo ABSURL; ?>static/codemirror/addon/mode/multiplex.js'></script>
	<script type='text/javascript' src='<?php echo ABSURL; ?>static/codemirror/addon/search/search.js'></script>
	<script type='text/javascript' src='<?php echo ABSURL; ?>static/codemirror/addon/runmode/runmode.js'></script>



	<style type="text/css">
	/* editor panels | custome highlight */
	.cm-mustache {color: #0ca;}
	.cm-mustacheinternal {color: #ff7040;}
	.cm-include {color: #ff00aa;}
	.cm-phptag{color: #20af20;font-weight: bold;}
	.cm-command{color: #20afe0;font-weight: bold;}
	
	html{
		background: #333;
		transition: background 300ms;
	}
	body{
		display: none;
	}	
	</style>
	<?php
		if(!empty($_COOKIE['custom-primary'])){
			echo '<style id="prim-picker" type="text/css">.btn-primary,.primary{background-color: '.$_COOKIE['custom-primary'].' !important;}</style>';
		} 
	?>


	<!-- <link rel="stylesheet" href="static/css/style.css"> -->
</head>
<body>

	<div class="<?php if(!empty($_COOKIE['full-screen'])){ echo 'full-screen';} ?> <?php if(!empty($_COOKIE['wide-nav'])){ echo 'wide';} ?>" id="layout">


			<div class="navbar" id="list-tool-bar">
				<div class="navbar-inner primary">
					<span class="brand"><?php echo $route['desc']; ?></span>
					<div class="container">
						<?php
						/*
						?>
						<!-- <span class="badge badge-inverse">0</span> -->
						<form action="" class="navbar-form pull-right">
							<input type="text" placeholder="Search" class="search-query span4">
						</form>
						<ul class="nav pull-right">
							<li class="active"><a href="#">Home</a></li>
							<li><a href="#">Link</a></li>
							<li class="dropdown">
								<a data-toggle="dropdown" class="dropdown-toggle" href="#">Dropdown <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li>
										<div>
											Stuff!
										</div>
									</li>
									<li><a href="#">Action</a></li>
									<li><a href="#">Another action</a></li>
									<li><a href="#">Something else here</a></li>
									<li class="divider"></li>
									<li class="nav-header">Nav header</li>
									<li><a href="#">Separated link</a></li>
									<li><a href="#">One more separated link</a></li>
								</ul>
							</li>							
							<li class="trigger" data-delay="3000" data-request="showtime" data-target=".search-query"><a href="/timetest" >Link</a></li>
							<li class="divider-vertical"></li>
						</ul>
						<?php
						*/
						?>
					</div>
				</div><!-- /navbar-inner -->
			</div>

		<div class="pure-u primary" id="nav">
			<a href="#nav" class="nav-menu-button">Menu</a>

			<div class="nav-inner">
				<div class="pure-menu pure-menu-open">
					<ul class="nav">
					<?php echo $modulesNav['side']; ?>
					</ul>
				</div>
			</div>
		</div>

		<div id="main-container">

			<div class="pure-u" id="list">
			</div>

			<div class="pure-u" id="main">
				<?php

					$colors = array();
					/*
					'@black' 		=> 		'#000',
					'@grayDarker' 	=> 		'#222',
					'@grayDark' 	=> 		'#7e7e7e',
					'@gray' 		=> 		'#959595',
					'@grayLight' 	=> 		'#b4b4b4',
					'@grayLighter' 	=> 		'#ECECEC',
					'@white' 		=> 		'#fff',

					'@blue' 		=> 		'#30A5FF',
					'@blueDark' 	=> 		'#4C4C4C',
					'@green' 		=> 		'#53A93F',
					'@red' 			=> 		'#DD4B39',
					'@yellow' 		=> 		'#F4B400',
					'@orange' 		=> 		'#FF9400',
					'@pink' 		=> 		'#CF6269',
					'@purple' 		=> 		'#7E548D'
					);*/
					foreach($colors as $name=>$color){
				?>
				<div style="padding:5px; width:100px; height:100px; float:left; background-color:<?php echo $color; ?>;"><div><?php echo $name; ?></div><div><?php echo $color; ?></div></div>
				<?php } ?>
			</div>
		</div>
	</div>
	
	<script src="<?php echo ABSURL; ?>static/js/jquery.min.js"></script>
	<script src="<?php echo ABSURL; ?>static/js/bootstrap-tab.js"></script>
	<script src="<?php echo ABSURL; ?>static/js/bootstrap-dropdown.js"></script>
	<script src="<?php echo ABSURL; ?>static/js/bootstrap-typeahead.js"></script>
	<script src="<?php echo ABSURL; ?>static/js/bootstrap-modal.js"></script>

	<script src="<?php echo ABSURL; ?>static/js/jquery.baldrick.js"></script>
	<script src="<?php echo ABSURL; ?>static/js/main.js"></script>
	<?php
	if(is_array($scripts)){
		foreach ($scripts as &$script) {
			echo '<script src="'.ABSURL.$script.'"></script>'."\r\n";
		}
	}
	?>

	<script>


	function showtime(){
		return Date();
	}
	function successTest(){
		console.log(arguments);
	}
	//jQuery(document).ready(function() {
		
		setTimeout(function(){
			jQuery('html').css('background', '#fff');
	    	jQuery('body').fadeIn(1000);
		}, 500);
		//alert('d');
	//}

	</script>
</body>
</html>
