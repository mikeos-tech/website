<?php
include 'MAL_functions.php';

// ini_set('display_errors', 'On');
// error_reporting(E_ALL | E_STRICT);

	header('Content-type: application/vnd.ms-excel');
    $file_name = $_GET['file']; 
    header('Content-disposition: attachment; filename=' . $file_name);

	if(open_db()) {
		$search = $_GET['query'];
		$fp = fopen('php://output', 'w');
		// Add the column headings to the file
		$headers = $GLOBALS['db']->query($search);
		$fields = array_keys($headers->fetchArray(PDO::FETCH_OBJ));
  	    fputcsv($fp, $fields);

  	    // Add the rows of data to the file
		$result = $GLOBALS['db']->query($search);
     	while($row = $result->fetchArray(PDO::FETCH_ASSOC)) {
        	fputcsv($fp, $row);
    	}
   			  fclose($fp);
    }
?>
