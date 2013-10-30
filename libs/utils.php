<?php
function dump($a, $d = true){
	echo '<pre id="varDump">';
	print_r($a);
	echo '</pre>';
	if(!empty($d))
		die;
}

function sanitize_file_name( $filename ) {
	$filename_raw = $filename;
	$special_chars = array("?", "[", "]", "./", "../", "/..", "/.", "..", ".", "//", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}", chr(0));
	$filename = str_replace($special_chars, '', $filename);
	$filename = preg_replace('/[\s-]+/', '-', $filename);
	$filename = trim($filename, '.-_');

	// Split the filename into a base and extension[s]
	$parts = explode('.', $filename);

	// Process multiple extensions
	$filename = array_shift($parts);
	$extension = array_pop($parts);

	return $filename;
}


?>