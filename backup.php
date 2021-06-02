<?php

//ini_set('display_errors', true);
//error_reporting(E_ALL + E_NOTICE);

require('/usr/local/lib/php/Smarty/Smarty.class.php');
$smarty = new Smarty();

$smarty->setTemplateDir('/var/www/html/smarty/templates');
$smarty->setCompileDir('/var/www/html/smarty/templates_c');
$smarty->setCacheDir('/var/www/html/smarty/cache');
$smarty->setConfigDir('/var/www/html/smarty/configs');

$smarty->assign('today_date', date("l d-m-Y"));


$output = shell_exec('mountpoint -- /backup/');
if(strpos($output, "is not a mount")) {
	$smarty->assign('disk_present',␣FALSE);
} else {
	$smarty->assign('disk_present',␣TRUE);
}

	$output = shell_exec('df -h /');
	$lines  = explode("\n", $output);
	$output = preg_replace('/\s+/', ' ',$lines[1]);
	$home   = explode(" ", $output);

	$output   = shell_exec('df -h /storage/');
	$lines    = explode("\n", $output);
	$output = preg_replace('/\s+/', ' ',$lines[1]);
	$storage  = explode(" ", $output);
	$smarty->assign('home_drive', $home);
	$smarty->assign('storage_drive', $storage);

$smarty->display('backup.tpl');
?>
