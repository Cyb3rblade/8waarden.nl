<?php
session_start();
require_once(dirname(__FILE__).'/../settings.php');
if(in_array(strtolower($_GET['action']), array('exit','logoff','sign-out'))){ $user = $_SESSION['username'] = NULL; $pass = $_SESSION['password'] = NULL; }
else{
	$user = $_SESSION['username'] = (isset($_POST['username']) ? $_POST['username'] : $_SESSION['username']);
	$pass = $_SESSION['password'] = (isset($_POST['password']) ? $_POST['password'] : $_SESSION['password']);
}
$short = $_settings['patch-short'];
$language = $_settings['patch-language'];
$patchfile = './'.$short.'-patch.'.$language.'.json';
//*debug*/ print_r($_settings);
//*debug*/ print_r($_POST);

if(isset($_settings['account'][base64_encode($user)]) && $_settings['account'][base64_encode($user)]['secret'] == md5($pass)){
	$db = json_decode(file_get_contents($patchfile), TRUE); /*fix*/if(!is_array($db)){ $db = array(); }
	//*debug*/ print_r($db);
	switch(strtolower($_GET['action'])){
		case 'add':
			if(isset($_POST["translate"]) && isset($_POST["from"]) && isset($_POST["to"])){
				$db[] = array(
					'translate' => $_POST["translate"],
					'from' => base64_encode($_POST["from"]),
					'to' => base64_encode($_POST["to"])
					//, 'caller' => $_POST["caller"], 'default' => $_POST["default"]
				);
				file_put_contents($patchfile, json_encode($db));
			}
			print file_get_contents('./editor/edit.html');
			break;
		default: print 'authenticated!';
	}
}
else {
	print file_get_contents('./editor/login.html'); exit;
}
?>