<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Media/Artist Lists</title>
<link rel="stylesheet" type="text/css" href="MAL.css">
</head>
<body> 
 <?php
include 'MAL_menu.php';
include 'MAL_SQL_queries.php';

	if(open_db()) {
		$key = $_GET['a'];
		$artist_name = artist_name($key);
		
		side_menu(get_sql('artist_track_list_index', 0, $key, 0, 6));	// Create the side menu

		echo "\n<div id='for_side_menu'>";
		echo "<h1 id=\"Top_index\"><center>Media/Artist List - Artist Track List</center></h1>\n";
		echo "<h2><center>Complete List of Tracks within the collection that include <i>'$artist_name'</i></center></h2><br />\n";
		app_menu();
	 	display_menu($key, 4);
	
	
	
		$result = $GLOBALS['db']->query(get_sql('artist_track_list_content', 0, $key, 0, 6));
		$current_track = "";
		$current_letter = "";
		echo "<center>\n<table>\n";
		while ($row = $result->fetchArray()) {
			$track_title 	= 	$row['Track Title'];
			$album   		=	$row['Album Title'];
			$dedup   		=	$row['title'];  // This just holds the title and is used for deduplicating in the list.
			$year  			=	add_year($row['year']);
			$titlesort		=	$row['titlesort'];
			if($titlesort == '') {	// Skip over the row  in the database that has blank name
				continue;
			}				
			$first_char = strtoupper($titlesort[0]);	// the uppercasing is just precutionary could remove it
			echo "\t<tr>";	// Start the row
			if(strcmp($current_track, $dedup) !== 0) {
				echo "<td id=\"" . $first_char . "_index\">$track_title</td>";
				$current_track = $dedup;
			}else {
				echo "<td></td>";
			}
			echo "<td>$album - ($year)</td>";
			echo "</tr>\n"; // End the row
		}
		echo "</center>\n</table>\n";
		echo "<br /><center class='no-print' id=\"End_index\"> - The End - </center><br />";
		echo "\n</div>\n";
	}
?>
</body>
</html>
