<?php
session_start();
// global params
global $params;
/// DEFINE ERROR HANDLE
function errorHandler($errno, $errstr, $errfile, $errline){
	///echo "$errno, $errstr, $errfile, $errline";
    throw new Exception;
    die;
}
// SOME CCONSTANTS
define('ABSPATH', dirname(__FILE__) . '/');
define('ABSURL', str_replace('//', '/', dirname($_SERVER['SCRIPT_NAME']).'/'));

// GET URL
$trueuri = ltrim(str_replace(str_replace(trim($_SERVER['DOCUMENT_ROOT'],'/'),'',trim(realpath(__dir__),'/')), '', $_SERVER['REQUEST_URI']),'/');
$trueuri = preg_replace("/[\/]{2,}/", '/', $trueuri);
$urlstruct = parse_url($trueuri);
$trueuri = $urlstruct['path'];
$pathVars = explode('/', $urlstruct['path']);

// LOAD ROUTES
$globalLibs 	= array();
$globalHeaders 	= array();
$globalErrFiles	= array();

if ($handle = opendir('resources/')) {
    while (false !== ($entry = readdir($handle))) {
    	if(!empty($perfect)){
    		// if a perfect match was found the perfect var breaks parsing additional files
    		break;
    	}
        $fileInf = pathinfo('resources/'.$entry);        
        if($fileInf['extension'] == 'json'){
            $fileData = json_decode(file_get_contents('resources/'.$entry), true);
			for($r=0; $r<count($fileData);$r++){
				// DEBUGGING
				if(isset($fileData[$r]['type'])){
					if($fileData[$r]['type'] == 'global'){
						// Ingluve GLOBAL options
						if(!empty($fileData[$r]['debug'])){
							//set_error_handler("errorHandler");
							ini_set('display_errors',1); 
							error_reporting(E_ALL);
						}	
						if(!empty($fileData[$r]['errors'])){
							$globalErrFiles = $fileData[$r]['errors'];
						}
						if(!empty($fileData[$r]['libraries'])){
							$globalLibs = array_merge($globalLibs, $fileData[$r]['libraries']);
						}
						if(!empty($fileData[$r]['headers'])){
							$globalHeaders = array_merge($globalHeaders, $fileData[$r]['headers']);
						}
					}
				}
				if(isset($fileData[$r]['methods'])){
					if(isset($fileData[$r]['version'])){
						$fileData[$r]['name'] = $fileData[$r]['version'].'/'.$fileData[$r]['name'];
					}
					if(!empty($fileData[$r]['name'])){
						$routeVars = explode('/', $fileData[$r]['name']);
					}
					//echo count($routeVars)." !== ".count($pathVars).'<br>';
					if(count($routeVars) !== count($pathVars)){
						continue;
					}
					if($trueuri == $fileData[$r]['name']){
						$route = $fileData[$r];
						$perfect = true; // break here since its a perfect match
						break;
					}
					if(false !== strpos($fileData[$r]['name'], ':')){ //has vars, check if match
						$testUrl = preg_replace("/\\\:([a-zA-Z0-9_]+)/", '([a-zA-Z0-9_\-\%]+)', preg_quote($fileData[$r]['name'],'/'));
						preg_match_all("/".$testUrl."/", $urlstruct['path'], $urlvars);
						if(empty($urlvars[1])){
							continue; //no var match
						}
						$route = $fileData[$r]; // var match use route
						preg_match_all("/:([a-zA-Z0-9_]+)/", $fileData[$r]['name'],$routevars);
						for($i=1;$i<count($urlvars);$i++){$params[] = $urlvars[$i][0];}
						$params = array_combine($routevars[1], $params);
					}
				}
			}
        }
    }
    closedir($handle);
}
if(isset($route)){
	// Turn on DEBUG is set
	if(!empty($route['debug'])){
		ini_set('display_errors',1); 
		error_reporting(E_ALL);
	}
	// Start Routing
	// wrapped in a try to catch exceptions and to trace errors
	// include libraries first
	try {

		// CHECK METHOD IS ALLOWED
		if(empty($route['methods'][$_SERVER['REQUEST_METHOD']])){
			header('HTTP/1.1 405 Method Not Allowed'); // deny if no method defined for route
			header('Allow: '.implode(', ', array_keys($route['methods'])), true, 405);
			if(!empty($globalErrFiles['405'])){
				if(file_exists($globalErrFiles['405'])){
					include $globalErrFiles['405'];
					return;
				}
			}
			echo '<h1>405: Method Not Allowed</h1>';
			return;
		}	
		// ROUTE FILE
		if(file_exists($route['methods'][$_SERVER['REQUEST_METHOD']]['file'])){
			ob_start();
			// set output buffer
			// LIBS LOADED WITHIN THE ROUTE TO ALLOW FOR RETURN VALUES ETC.
			// LOAD GLOBAL LIBRARIES 
			if(!empty($globalLibs)){
				//dump($globalLibs);
				for($l=0;$l<count($globalLibs); $l++){
					if(file_exists($globalLibs[$l])){
						if(empty($_output) || $_output === 1){ // check that last header did not return;
							$_output = include_once $globalLibs[$l];
						}
					}
				}
			}
			// LOAD LIBRARIES
			if(!empty($route['libraries'])){
				for($l=0;$l<count($route['libraries']); $l++){
					if(file_exists($route['libraries'][$l])){
						if(empty($_output) || $_output === 1){ // check that last header did not return;
							$_output = require_once $route['libraries'][$l];
						}
					}
				}
			}
			// LOAD METHOD LIBRARIES
			if(empty($_output) || $_output === 1){
				if(!empty($route['methods'][$_SERVER['REQUEST_METHOD']]['libraries'])){
					for($l=0;$l<count($route['methods'][$_SERVER['REQUEST_METHOD']]['libraries']); $l++){
						if(file_exists($route['methods'][$_SERVER['REQUEST_METHOD']]['libraries'][$l])){
							$_output = require_once $route['methods'][$_SERVER['REQUEST_METHOD']]['libraries'][$l];
						}
					}
				}
			}
			// Once Libs are loaded - send headers
			//SEND GLOBAL HEADERS
			if(!empty($globalHeaders)){
				foreach($globalHeaders as $header=>&$value){
					header($header.': '.$value);
				}
			}
			// SEND ROUTE HEADERS
			if(!empty($route['headers'])){
				foreach($route['headers'] as $header=>&$value){

					if(false !== strpos($value, '[[') && false !== strpos($value, ']]')){
						// header value can be a PHP function if wrapped in [[ phpfunction/code ]]
						$_value = function(&$value){
							// to prevent contaminated values;
							$value = eval('return '.substr($value,2,strlen($value)-4).';');
						};
						//$_value(&$value);
					}
					header($header.': '.$value, true); // true to overide any sent by globals
				}
			}
			// SEND METHOD HEADERS
			if(!empty($route['methods'][$_SERVER['REQUEST_METHOD']]['headers'])){
				foreach($route['methods'][$_SERVER['REQUEST_METHOD']]['headers'] as $header=>&$value){
					header($header.': '.$value, true); // true to overide any sent by route
				}
			}

			if(empty($_output) || $_output === 1){ // check that last header did not return;
				$_output = include $route['methods'][$_SERVER['REQUEST_METHOD']]['file'];
			}

			$buffer = ob_get_clean();
			if($_output === 1 || !empty($buffer)){
				echo $buffer;
			}elseif (!empty($_output)){
				if(is_array($_output) || is_object($_output)){
	                header("Content-Type: application/json charset=utf8", true);
	                echo json_encode($_output);
				}else{
					echo $_output;
				}
			}
			exit;
		}else{
			if(!empty($globalErrFiles['404'])){
				if(file_exists($globalErrFiles['404'])){
					include $globalErrFiles['404'];
					die;
				}
			}
			header("HTTP/1.1 404 Not Found");
			echo '<h1>404: page not found</h1>';
		}
	} catch (Exception $e) {
		$trace = $e->getTrace();
		echo '<h2>App Error</h2>';
		echo '<p>'.$trace[0]['args'][1].' on line '.$trace[0]['args'][3].'</p>';
		echo '<p>in file: '.str_replace(__dir__.'/','', $trace[0]['args'][2]).'</p>';
		die;
	}
}else{
	try {
		if(!empty($globalErrFiles['404'])){
			if(file_exists($globalErrFiles['404'])){
				include $globalErrFiles['404'];
				die;
			}
		}
	} catch (Exception $e) {
		$trace = $e->getTrace();
		echo '<h2>App Error</h2>';
		echo '<p>'.$trace[0]['args'][1].' on line '.$trace[0]['args'][3].'</p>';
		echo '<p>in file: '.str_replace(__dir__.'/','', $trace[0]['args'][2]).'</p>';
		die;
	}		
	header("HTTP/1.1 404 Not Found");
	echo '<h1>404: page not found</h1>';
}
?>
