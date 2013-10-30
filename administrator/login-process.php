<?php

	//$_SESSION['api_node'] = 'test';
if(!empty($_POST['user']) && !empty($_POST['pass'])){
	if($_POST['user'] == 'master' && $_POST['pass'] == 'Ekhrt29X'){
		$_SESSION['api_node'] = 100;
		$return = array(
			"data" => array(
				"authcode"	=> rand(100,199999),
				"usernode"	=> $_SESSION['api_node']
			)
		);
		return $return;
	}
}	
$return = array('error'=>'Invalid username and/or password.');
return $return;
?>
