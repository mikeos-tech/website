<?php
include 'MAL_SQL_queries.php';
include 'MAL_functions.php';

	header('Content-type: application/vnd.ms-excel');
    $file_name = $_GET['file']; 
    header('Content-disposition: attachment; filename=' . $file_name);

//ini_set('display_errors', 'On');
//error_reporting(E_ALL | E_STRICT);


	if(open_db()) {
		$fp = fopen('php://output', 'w');
		$album_key = $_GET['album'];
		// Get the album artists key
		$album_artist_key = album_artist_key($album_key);
	
		// Get the Album Detail
		$result = $GLOBALS['db']->query(get_sql('album_detail_album_info', $album_key));
		while($row = $result->fetchArray(SQLITE3_ASSOC)) {
			$title 				= 	$row['title'];
			$year  				=	add_year($row['year']);
			$num_discs 			= 	$row['discc'];
			$artist 			=   artist_name($album_artist_key);
			fwrite($fp, "\"Album Title:\",\"" . $title . "\"\n");
			fwrite($fp, "\"Album Artist:\",\"" . $artist . "\"\n");
			fwrite($fp, "\"Album released:\",\"" . $year . "\"\n");
			fwrite($fp, "\"Number disks:\",\"" . $num_discs . "\"\n");
	    }
	    
	    $disc_number = 0;  
	    fwrite($fp, "\nTrack No,Track Title,Track Artist,Genre,Duration\n"); // Add the column headings	    
		$result = $GLOBALS['db']->query(get_sql('album_detail_track_list_genre', $album_key)); // Read the track details
		// Output the track list table
	    while($row = $result->fetchArray(SQLITE3_ASSOC)) {
			$track_artists  =	"";
			$title 			= 	$row['title'];
			$track			=   $row['tracknum'];
			$disc 			=	$row['disc']; 
			$track_length	=	$row['secs'];
			$genre 			= 	$row['genre'];
			$track_artists  = get_track_artists($row['T_key'], 6, false);
			if(($disc > $disc_number) && ($num_discs > 1))  {  // If the disc number has changed display the disc number
				$disc_number = $disc;
				fprintf($fp,"\nDisc %02d\n", $disc);
			}	
			// Write a row of data to the table
			fprintf($fp, "\"%02d%02d\",\"%s\",\"%s\",\"%s\",\"%02d:%02d\"\n", $disc, $track, $title, $track_artists, $genre, ($track_length / 60), ($track_length % 60));
		}
		fclose($fp);
	}
?>
