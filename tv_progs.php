<?php

include 'get_iplayer_functions.php';

ini_set('display_errors', true);
error_reporting(E_ALL + E_NOTICE);

require('/usr/local/lib/php/Smarty/Smarty.class.php');
$smarty = new Smarty();

$smarty->setTemplateDir('/var/www/html/smarty/templates');
$smarty->setCompileDir('/var/www/html/smarty/templates_c');
$smarty->setCacheDir('/var/www/html/smarty/cache');
$smarty->setConfigDir('/var/www/html/smarty/configs');

$smarty->assign('today_date', date("l d-m-Y"));

if(open_db()) {
	$rows = array();
	$results =  $GLOBALS['db']->query( "SELECT date, path FROM updates WHERE ((type='TV') AND ((watched = 0) AND (archive = 0)) AND (date > (SELECT DATETIME('now', '-7 day')))) ORDER BY date DESC, path ASC;" );
	while($rows[] = $results->fetchArray()) {}
	$count = 0;
        foreach($rows as $row) {
		$actual_path = $row['path'];
		$row['path'] = str_replace("Films", 		"TV_Archive/Films", 	$row['path']);
		$row['path'] = str_replace("/Series_", 		" - Series_", 		$row['path']);
		$row['path'] = str_replace("TV_Archive/", 	"", 			$row['path']);
		$row['path'] = str_replace("_", 		" ", 			$row['path']);

		$output = [
			"date" => $row['date'],
			"category" => " ",
			"series"   => " ",
			"episode"  => " ",
			"key"      => $actual_path,
		];
		$tok = strtok($row['path'], '/');
		$index = 0;
		while($tok != false) {
			if($index == 0) {
				$output['category']=str_replace("Music-Hold", "Music/hold", $tok);
			}
			if($index == 1) {
				$output['series']=$tok;
			}
			if($index == 2) {
				$output['episode']=$tok;
			}
			$tok = strtok('/');
			$index++;
		}
		$rows[$count] = $output;
		$count++;
        }
        unset($rows[($count - 1)]);
	$smarty->assign('rows', $rows);
}
$smarty->display('tv_progs.tpl');
?>
