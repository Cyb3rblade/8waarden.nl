<?php
require_once(dirname(__FILE__).'/settings.php');
$path = $_GET['q'];
$language = (isset($_GET['l']) ? $_GET['l'] : $_settings['patch-language']);
$original = dirname(__FILE__).DIRECTORY_SEPARATOR.'8values.github.io'.DIRECTORY_SEPARATOR;

///*debug*/ print_r($original); print_r($path);

switch(strtolower($path)){
	case '': case '/': $path = 'index.html'; break;
	default:
		$path = $path;
}

if(file_exists($original.$path)){
	switch(strtolower(pathinfo($path, PATHINFO_EXTENSION))){
		case 'jpg': case 'jpeg': header("Content-type: image/jpeg;"); break;
		case 'png': header("Content-type: image/png;"); break;
		case 'svg': header("Content-type: image/svg+xml;"); break;
		case 'js': header("Content-type: application/javascript;"); break;
		case 'css': header("Content-type: text/css;"); break;
	}
	$raw = file_get_contents($original.$path);
	$patchfile = './patch/'.$_settings['patch-short'].'-patch.'.$language.'.json';
	$db = json_decode(file_get_contents($patchfile), TRUE);
	if(is_array($db)){foreach($db as $i=>$line){
		$raw = str_replace(base64_decode($line['from']), base64_decode($line['to']), $raw);
	}}
	print $raw;
	exit;
}
?>