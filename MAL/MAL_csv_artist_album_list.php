<?php
//ini_set('display_errors', 'On');
//error_reporting(E_ALL | E_STRICT);
 
include 'MAL_SQL_queries.php';
include 'MAL_functions.php';
 
	if(open_db()) {
		$key = $_GET['a'];
		$artist_name = artist_name($key);
		$sub_heads=array(
		"\"Albums by $artist_name:\"\n",
		"\"Albums on which $artist_name is a guest:\"\n",
		"\"Various-Artist Albums that include $artist_name tracks:\"\n",
		"\"Albums on which $artist_name composed tracks:\"\n"
		);
	
		header('Content-type: application/vnd.ms-excel');
        $file_name = $_GET['file']; 
        header('Content-disposition: attachment; filename=' . $file_name);
		$fp = fopen('php://output', 'w');
		// Write the data to the file
        for($i = 4; $i <= 7; $i++) {
			$include_heading = true;
            // Add the rows of data to the file
            $result = $GLOBALS['db']->query(sql_artist_albums($i, $key));
            while($row = $result->fetchArray(PDO::FETCH_ASSOC)) {
				if($include_heading) {	// The section heading is within the loop so it is only included while there is data.
					echo $sub_heads[$i - 4]; // Add the section heading.
					// Add the column headings
					$headers = $GLOBALS['db']->query(sql_artist_albums($i, $key));
					$fields = array_keys($headers->fetchArray(PDO::FETCH_OBJ));
                    echo ',';  // Start the headings in the second column as the section heading is in the first.
					fputcsv($fp, $fields);
					$include_heading = false;
				}
				echo ',';	// Start the column row in the second column as the section heading is in the first.
                fputcsv($fp, $row);
            }
            echo "\n";  // Add a additional line to be between each section.
        }
        fclose($fp);
    }
?>
