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
//		echo '<pre>'; var_dump($cover_art); echo '</pre>';

	if(open_db()) {
		$album_key = $_GET['a'];
		echo "<h1 id=\"Top_index\"><center>Media/Artist List - Album Detail</center></h1>\n";
		// Get the album artists key
		$album_artist_key = album_artist_key($album_key);
		app_menu();
		display_menu($album_artist_key, 3, $album_key);

		// Cover Art Section
		$cover_art = rawurldecode(album_path($album_key)); 	                // Give a url pointing to a track in the folder where the art is
		$cover_art = substr($cover_art, 0, strrpos($cover_art, '/') + 1);   // Remove the track name
		$cover_art = str_replace("file://", "", $cover_art);				// Make it a path rather than a url
		$cover_art = image_file($cover_art);								// find the appropriate graphics file in the folder

		// Genre Section
		$row_count = 4; // Sets the number of rows for the Album table
		$genre_count = album_genre_count($album_key);
		$genre = "";

		if($genre_count == 1) {  // If the genre is for the whole album, then add a row to hold Genre within the album detail
			$row_count++;		// Adds an additional row for the genre
			$genre = album_genre_name($album_key);
		}
	
		// Get the Album Detail
        $result = $GLOBALS['db']->query(get_sql('album_detail_album_info', $album_key));
		while($row = $result->fetchArray(SQLITE3_ASSOC)) {
			$title 				= 	$row['title'];
			$year  				=	add_year($row['year']);
			$num_discs 			= 	$row['discc'];
			$artist 			=   artist_name($album_artist_key);
			
			// Start and format the Album detail table 
			if(file_exists($cover_art)) { // Format it to include Album Art
				echo "<center>\n<table>\n\t<colgroup>\n\t\t<col style=\"width:20%\">\n\t\t<col style=\"width:30%\">\n\t\t<col style=\"width:50%\">\n\t</colgroup>\n\t<tbody>\n";
			} else {	// Format it not to include Album Art
				echo "<center>\n<table>\n\t<colgroup>\n\t\t<col style=\"width:40%\" >\n\t\t<col style=\"width:60%\">\n\t</colgroup>\n\t<tbody>\n";
			}
		
			// Display the Album Detail
			echo "\t\t<tr><td>Album Title</td><td><div id=\"album\">$title</div></td>";
			if(file_exists($cover_art)) {  // Add the Album Art if it exists
				$cover_art = str_replace("/storage/music/flac/", "/music/", $cover_art);
				echo "<td rowspan=\"$row_count\"><img src=\"$cover_art\" alt=\"$title\" style=\"width:300px;height:300px;\"></td>";
			}
			echo "</tr>\n";
			echo "\t\t<tr><td>Album Artist</td><td><a href=\"MAL_artist_album_list.php?a=$album_artist_key\"><div id=\"artist\">$artist</div></a></td></tr>\n";
			echo "\t\t<tr><td>Album released</td><td>$year</td></tr>\n";
			if($genre_count == 1) { // Insert the Genre if it applies to the whole album
				echo "\t\t<tr><td>Album Genre</td><td>$genre</td></tr>\n";
			}
			echo "\t\t<tr><td>Number disks</td><td>$num_discs</td></tr>\n";
			echo "\t</tbody>\n</table>\n</center>\n";  // End the Album table detail
	    }
	    echo "<br />";
	    
	    // Build the Track detail Query
	    $col_count = 4;  // sets the number of columns for the track table - not including genre
	    $disc_number = 0;  
	    
	    if($genre_count > 1) { // Include the Genre element of query if they are track specific
                $col_count++;   // Adds and additional column for the genre
        }
	    
	    if($genre_count > 1) { // Start amd format the track table to include the genre with each track
            $result = $GLOBALS['db']->query(get_sql('album_detail_track_list_genre', $album_key));
	    
			echo "<center>\n<table>\n\t<colgroup>\n\t\t<col style=\"width:8%\">\n\t\t<col style=\"width:34%\">\n\t\t<col style=\"width:30%\">\n\t\t<col style=\"width:20%\">\n\t\t<col style=\"width:8%\">\n\t</colgroup>\n\t<tbody>\n";
			echo "\t\t<tr><th>Number</th><th>Track Title</th><th>Track Artist</th><th>Genre</th><th>Length</th></tr>";		
		} else {	// Start amd format the track table not to include the genre with each track
            $result = $GLOBALS['db']->query(get_sql('album_detail_track_list', $album_key));
		
			echo "<center>\n<table>\n\t<colgroup>\n\t\t<col style=\"width:10%\">\n\t\t<col style=\"width:40%\">\n\t\t<col style=\"width:40%\">\n\t\t<col style=\"width:10%\">\n\t</colgroup>\n\t<tbody>\n";
			echo "\t\t<tr><th>Number</th><th>Track Title</th><th>Track Artist</th><th>Length</th></tr>\n";
		}	

		// Output the track list table
	    while($row = $result->fetchArray(SQLITE3_ASSOC)) {
			$track_artists  =	"";
			$title 			= 	$row['Track Title'];
			$track			=   $row['tracknum'];
			$disc 			=	$row['disc']; 
			$track_length	=	$row['secs'];
			$track_artists  = get_track_artists($row['T_key'], 6, true);
			if($genre_count > 1) { // Read the genre information if required
				$genre 		= 	$row['genre'];
			}
			
			if(($disc > $disc_number) && ($num_discs > 1))  {  // If the disc number has changed display the disc number
				$disc_number = $disc;
				echo "\t\t<tr><td colspan=\"$col_count\"></td></tr>\n"; // Add a blank row spanning all the columns
				echo sprintf("\t\t<tr><td colspan=\"$col_count\"><b>Disc %02d</b></td></tr>\n", $disc); // Add a number of disc row spanning all the columns
			}
			
			// Write a row of data to the table
			echo "\t\t<tr><td>" . sprintf('%02d%02d', $disc, $track) . "</td><td>$title</td><td>$track_artists</td>";
			if($genre_count > 1) {  // If the genre isn't the same as the album genre add the genre column
				echo "<td>$genre</td>";
			} 
			echo "<td>" . sprintf('%02d:%02d', ($track_length / 60), ($track_length % 60)) . "</td></tr>\n";			

		}
		echo "\t</tbody>\n</table>\n</center>\n"; // Finish the Track information table
	
		echo "<br /><center class='no-print' id=\"End_index\"> - The End - </center><br />\n";
	}
?>
</body>
</html>
