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

ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

	if(open_db()) {
		$track_key = $_GET['a'];
		echo "\n<h1 id=\"Top_index\">Media/Artist List - Artists Responsible for: " . track_name($track_key) . "</h1>\n";
		app_menu();
		display_menu(0, 7, 0, $track_key);
		
		$result = $GLOBALS['db']->query(get_sql('artist_responsible_list_content', 0, 0, $track_key));
		echo "<center>\n<table>\n";
		echo "<tr><th>Track Artist</th><th>Album</th><th>Year</th></tr>\n";
		while ($row = $result->fetchArray()) {
			$artist 	=	$row['Track Artist'];			
			$album  	= 	$row['Album Title'];
			$year   	= 	add_year($row['Year']);
			echo "<tr><td>$artist</td><td>$album</td><td>$year</td></tr>\n";
		}
		echo "</center>\n</table>\n";
		echo "<br /><center class='no-print' id=\"End_index\"> - The End - </center><br />";
		echo "\n</div>\n";
	}
?>
</body>
</html>
