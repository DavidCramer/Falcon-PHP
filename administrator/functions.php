<?php


function admin_buildModulesNav(){
	global $scripts, $styles;

	$modulesDir = 'administrator/modules';
	$modules = array();
	if ($handle = opendir($modulesDir)) {
		while (false !== ($entry = readdir($handle))) {
			if($entry != '.' && $entry != '..'){
				if(is_dir($modulesDir.'/'.$entry)){
					if(file_exists($modulesDir.'/'.$entry.'/module.json')){
						$module = json_decode(file_get_contents($modulesDir.'/'.$entry.'/module.json'), true);
						if(isset($module['module'])){
							if(!isset($module['priority'])){
								$module['priority'] = 10;
							}
							if(!isset($module['position'])){
								$module['position'] = 'side';
							}							
							$modules[$module['position']][$module['priority']][$entry] = $module;
						}
					}
				}
			}
		}
		closedir($handle);
	}
	if(!empty($modules['side'])){
		ksort($modules['side']);
	}
	if(!empty($modules['top'])){
		ksort($modules['top']);
	}
	$return = '';
	$nav = array();
	foreach($modules['side'] as &$set){
		foreach($set as $entry=>&$module){
			if(isset($module['module'])){
				if(!empty($module['stylesheets'])){
					foreach($module['stylesheets'] as &$style)
						$styles[] = $modulesDir.'/'.$entry.'/'.$style;
				}
				if(!empty($module['scripts'])){
					foreach($module['scripts'] as &$script)
						$scripts[] = $modulesDir.'/'.$entry.'/'.$script;
				}
				$brand = '';
				$return .= '<li class="pure-menu-heading">'.$module['module'];
					if(!empty($module['brand-icon'])){
						$return .= '<div class="brand-icon">'.$module['brand-icon'].'</div>';
					}
				$return .= '</li>';
				if(!empty($module['sections'])){
					foreach($module['sections'] as $section=>$config){
						$sectionStyles = array();
						$sectionScripts = array();
						if(!empty($config['stylesheets'])){
							foreach($config['stylesheets'] as &$style)
								$styles[] = $modulesDir.'/'.$entry.'/'.$style;
						}
						if(!empty($config['scripts'])){
							foreach($config['scripts'] as &$script)
								$scripts[] = $modulesDir.'/'.$entry.'/'.$script;
						}

						$defaults = array(
							'name'	=> 'Module',
							'desc'	=> '',
							'icon'	=> 'cog'
						);

						$defaultatts = array(
							'clear'			=> 'main',
							'request'		=> 'module/'.$entry.'/'.$section,
							'active-class'	=> 'pure-menu-selected',
							'target'		=> 'list',
							'before'		=> 'checkForScripts',

						);
						if(empty($config['attributes'])){
							$config['attributes'] = array();
						}
						$config['attributes'] = array_merge($defaultatts, $config['attributes']);
						$atts = array();
						if(!empty($config['attributes'])){
							foreach($config['attributes'] as $att=>$val){
								if($val != '' && $val != null && $val != false && $val != 'null' && $val != 'false'){
									$atts[] = 'data-'.$att.'="'.$val.'"';
								}
							}
						}

						// add ID
						// trigger class
						if(empty($config['link'])){
							$link = '#'.$section;
							$atts[] = 'class="trigger"';
						}else{
							$link = $config['link'];
						}

						if(!empty($sectionStyles)){
							$atts[] = 'data-styles="'.implode(',', $sectionStyles).'"';
						}
						if(!empty($sectionScripts)){
							$atts[] = 'data-scripts="'.implode(',', $sectionScripts).'"';
						}
						$config = array_merge($defaults, $config);
						$return .= '<li '.implode(' ',$atts).' id="'.$entry.'-'.$section.'"><a href="'.$link.'"><i class="icon-'.$config['icon'].' pull-right"></i>'.$config['name'].'</a></li>';
					}
				}
			}
		}
		$nav['side'] = $return;
	}

	return $nav;
}
?>
