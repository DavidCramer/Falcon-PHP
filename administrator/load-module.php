<?php

if(file_exists('administrator/modules/'.$params['module'].'/module.json')){
	$config = json_decode(file_get_contents('administrator/modules/'.$params['module'].'/module.json'), true);	
	if(!empty($config['sections'][$params['section']]) && isset($params['method'])){
		if(file_exists('administrator/modules/'.$params['module'].'/'.$params['method'].'.php')){

			if(!empty($config['sections'][$params['section']]['libraries'])){
				foreach($config['sections'][$params['section']]['libraries'] as &$lib){
					include_once $lib;
				}
			}			

			return include_once 'administrator/modules/'.$params['module'].'/'.$params['method'].'.php';
		}
	}
	if(!empty($config['sections'][$params['section']]['file'])){
		if(file_exists('administrator/modules/'.$params['module'].'/'.$config['sections'][$params['section']]['file'])){

			if(!empty($config['sections'][$params['section']]['libraries'])){
				foreach($config['sections'][$params['section']]['libraries'] as &$lib){
					include_once $lib;
				}
			}

			$list = include_once 'administrator/modules/'.$params['module'].'/'.$config['sections'][$params['section']]['file'];
			
			if(is_array($list)){
				if(isset($list['title'])){
					echo '<div id="'.$params['section'].'Toolbar" class="item primary"><div class="nav-header">'.$list['title'];
						if(isset($list['tools'])){
							echo '<div class="btn-group pull-right">';
							foreach($list['tools'] as $tool){
								$tooldefaults = array(
									"class"	=> "btn-primary",
									"text"	=> ""
								);
								$atts = array();
								if(!empty($tool['attributes'])){
									foreach($tool['attributes'] as $att=>$val){
										$atts[] = 'data-'.$att.'="'.$val.'"';
									}
								}
								$tool = array_merge($tooldefaults, $tool);
								echo '<button class="btn btn-small '.$tool['class'].' icon-'.$tool['icon'].' primary trigger" '.implode($atts).'></button>';
							}
							echo '</div>';
						}
					echo '</div></div>';


					// list
					if(!empty($list['items'])){
						echo '<div id="list-content" class="content '.$params['module'].'-'.$params['section'].'">';
						foreach($list['items'] as $key=>$item){

							if(isset($item['header'])){
								echo '<div class="nav-header">'.$item['header'].'</div>';
								continue;
							}


							echo '<div class="item">';
								
								$defaultatts = array(
									'group'		=> 'list-items',
									'active-class'	=> 'item-selected'
								);
								$item['attributes'] = array_merge($defaultatts, $list['attributes'], $item['attributes']);
								$atts = array();
								if(!empty($item['attributes'])){
									foreach($item['attributes'] as $att=>$val){
										$atts[] = 'data-'.$att.'="'.$val.'"';
									}
								}


								echo '<div id="entryrow'.$key.'" class="entry trigger" '.implode($atts).'>';

									echo '<div id="entry'.$key.'">';

										echo '<div class="subject">'.$item['title'].'</div>';
										if(!empty($item['desc'])){
											echo '<div class="desc">'.$item['desc'].'</div>';
										}

									echo '</div>';

								echo '</div>';

							echo '</div>';		
						}
						echo '</div>';
					//


					}

				}
			}
		}
	}
}
?>