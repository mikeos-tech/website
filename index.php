<?php

ini_set('display_errors', true);
error_reporting(E_ALL + E_NOTICE);

require('/usr/local/lib/php/Smarty/Smarty.class.php');
$smarty = new Smarty();

$smarty->setTemplateDir('/var/www/html/smarty/templates');
$smarty->setCompileDir('/var/www/html/smarty/templates_c');
$smarty->setCacheDir('/var/www/html/smarty/cache');
$smarty->setConfigDir('/var/www/html/smarty/configs');

$smarty->assign('today_date', date("l d-m-Y"));
$smarty->assign('ip_address', shell_exec('hostname -I'));
$smarty->assign('hostname', shell_exec('hostname'));

$output = shell_exec('cat /etc/*release');
$lines = explode("\n", $output);
$distro  = substr($lines[4], strlen("NAME=\""), -1); 
$distro .= " - ";
$distro .= substr($lines[5], strlen("VERSION=\""), -1);

$smarty->assign('distro_version', $distro);
$smarty->assign('os_version', shell_exec('uname -r'));

$output = shell_exec('lscpu');
$lines = explode("\n", $output);
$cpu = substr($lines[13], strlen("Model name:"));
$smarty->assign('cpu', $cpu);

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

$smarty->display('index.tpl');
?>
