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
	$results =  $GLOBALS['db']->query( "SELECT date, path FROM updates WHERE ((type='Radio') AND ((watched = 0) AND (archive = 0)) AND (date > (SELECT DATETIME('now', '-7 day')))) ORDER BY date DESC, path ASC;" );
	while($rows[] = $results->fetchArray()) {}
	$count = 0;
	foreach($rows as $row) {
		$output = [
			"date"       => $row['date'],
			"series"     => " ",
			"series_num" => " ",
			"episode"    => " ",
			"key"        => $row['path'],
		];
		$sections = substr_count($row['path'], "/"); // Establish how slashes appear in the folder path
		$tok = strtok($row['path'], '/');
		$index = 0;
		while($tok != false) {
			if($index == 0) { // The series is always present
				$output['series']=trim($tok);
			}
			if($sections == 2) { // If there are two sections there will be a series number in path
				if($index == 1) {
					$output['series_num']=trim($tok);
				}
				if($index == 2) {
					$output['episode']=trim($tok);
				}
			}
			if($sections == 1) { // If there is one section only worry about the episode
				if($index == 1) {
					$output['episode']=trim($tok);
				}
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
	$smarty->display('radio_progs.tpl');
?>
