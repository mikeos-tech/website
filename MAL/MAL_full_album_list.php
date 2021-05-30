<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Media/Artist List</title>
<link rel="stylesheet" type="text/css" href="MAL.css">
</head>
<body>
<?php
include 'MAL_menu.php';
include 'MAL_SQL_queries.php';

//ini_set('display_errors', 'On');
//error_reporting(E_ALL | E_STRICT);

	if(open_db()) {
		side_menu(get_sql('full_album_list_index'));	// Create the side menu
		echo "\n<div id='for_side_menu'>";
		echo "\n<h1 id=\"Top_index\">Media/Artist List - Full Album List</h1>\n";
		app_menu();
		display_menu(0, 1);

		$result = $GLOBALS['db']->query(get_sql('full_album_list_content'));
		echo "<center>\n<table>\n";
		$current_artist = "";
		$current_letter = "";
		while ($row = $result->fetchArray()) {
			$artist 	=	$row['Artist'];
			$album  	= 	$row['Album Title'];
			$year   	= 	add_year($row['year']);
			$namesort 	= 	$row['namesort'];
			if($namesort == '') {	// Skip over the row  in the database that has a blank name
				continue;
			}
			$first_char = strtoupper($namesort[0]);	// the uppercasing is just precutionary could remove it
			echo "\t<tr>";	// Start the row
			if (strcmp($current_artist, $artist) == 0) {
				echo "<td></td>";
			}else {
				echo "<td id=\"" . $first_char . "_index\">$artist</td>";
				$current_artist = $artist;
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
