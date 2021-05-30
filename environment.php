<?php
ini_set('display_errors', true);
error_reporting(E_ALL + E_NOTICE);

require('/usr/local/lib/php/Smarty/Smarty.class.php');
$smarty = new Smarty();

$smarty->setTemplateDir('/var/www/html/smarty/templates');
$smarty->setCompileDir('/var/www/html/smarty/templates_c');
$smarty->setCacheDir('/var/www/html/smarty/cache');
$smarty->setConfigDir('/var/www/html/smarty/configs');

	$rootDir = "/var/www/html/environment/";
        $files = glob($rootDir . '*.png'); 
	$rows = array();
	foreach($files as $file) {   
	    $elements = explode("-", $file);    
	    $detail = [
	        "file_name" =>  " ",
		"path" => $rootDir,
		"date" => " ",
		"room" => $elements[1],
		"type" => " ",
	    ];
	    $detail['file_name']=str_replace("/var/www/html/", "", $file);   
            $detail['date']=substr_replace(substr_replace(str_replace($rootDir, "", $elements[0]), "-", 4, 0), "-", 7, 0);
	    $detail['type']=str_replace(".png", "", $elements[2]);
            array_push($rows, $detail);
	    rsort($rows);
	}
	if(count($rows) > 0) {
		$smarty->assign('rows', $rows);
		$smarty->display('environment.tpl');
	}
?>