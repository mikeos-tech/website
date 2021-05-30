<?php
include 'MAL_functions.php';

ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

	header('Content-type: text/plain');
    $file_name = $_GET['file']; 
    header('Content-disposition: attachment; filename=' . $file_name);
   
	if(open_db()) {
        $search = $_GET['query'];
        $GLOBALS['db']->exec(get_sql('drop_gen_rand'));
        $GLOBALS['db']->exec($search);
		
		$fp = fopen('php://output', 'w');
		
        file_put_contents($fp, '#EXTM3U\n'); // Add the header to the file
		
  	    // Add the rows of data to the file
		$result = $GLOBALS['db']->query(get_sql('build_list_m3u');
     	while($row = $result->fetchArray(PDO::FETCH_ASSOC)) {
            $entry 				= 	$row['m3u_entry'];
            $entry .= '\n';
    	file_put_contents($fp, $entry);
    	}
   		fclose($fp);
        $GLOBALS['db']->exec('drop_gen_rand');
    }
?>
