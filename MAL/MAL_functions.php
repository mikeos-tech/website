<?php
/* Returns a boolean value depending on whether the artist passed to it by the artist_key is a composer for any tracks within the database.
artist_key should be the key for the artist you wish to check.
True indicates the artist has composed tracks.
*/
function has_composed($artist_key) {
    $found = false;
	if(open_db()) {
        $result = $GLOBALS['db']->query("SELECT tracks.title FROM tracks, contributors, contributor_track, albums WHERE (tracks.id = contributor_track.track) AND (contributor_track.contributor = contributors.id) AND (tracks.audio = 1) AND (contributors.id = $artist_key)  AND (tracks.album = albums.id) AND (contributor_track.role = 2) GROUP BY tracks.id");
        $found =  $result->fetchArray(SQLITE3_ASSOC);
     }
	return $found;
}
  
  
/* Replaces years stored in the database with a 0 value with four question marks.
year is the year variable you wish to display.
*/
function add_year($year) {
	return ($year > 0 ? "$year" : "????");
}

    
/* Returns the key for an artist based on the name passed to it.
name is the name of the artist the key is required for.
*/
function artist_key($name) {
	$key = 0;
	if(open_db()) {
	    $key = $GLOBALS['db']->querySingle("SELECT contributors.id FROM contributors WHERE contributors.name = '$name'");
	}
	return $key;
}


/* Returns the name for an artist based on the key passed to it.
key is the key of the artist the name is required for.
*/
function artist_name($key) {
	$name = "";
	if(open_db()) {
	    $name = $GLOBALS['db']->querySingle("SELECT contributors.name FROM contributors WHERE contributors.id = $key");
	}
	return $name;
}


/* Returns the name for an album based on the key passed to it.
key is the key of the album the name is required for.
*/
function album_name($key) {
	$name = "";
	if(open_db()) {
	    $name = $GLOBALS['db']->querySingle("SELECT Albums.title FROM Albums WHERE Albums.id = $key");
	}
	return $name;
}

/* Returns the name for a track based on the key passed to it.
key is the key of the track the name is required for.
*/
function track_name($key) {
	$name = "";
	if(open_db()) {
	    $name = $GLOBALS['db']->querySingle("SELECT title FROM tracks WHERE (tracks.id = $key)");
	}
	return $name;
}	 



/* Returns the path for the album based on the key passed to it.

*/
function album_path($key) {
	$path = "";
	if(open_db()) {
	    $path = $GLOBALS['db']->querySingle("SELECT url FROM tracks WHERE (album = $key) LIMIT 1;");
	}
	return $path;
}

    
/* Finds the cover art for an album within the album folder, using the LMS based list of folder art file names.
location is the folder (of the album) in which the file should exist.
*/
function image_file($location) {
    $output = "";
    $paths = array("cover.jpg", "cover.gif", "folder.jpg", "folder.gif", "album.jpg", "album.gif", "thumb.jpg", "thumb.gif", "albumartsmall.jpg", "albumartsmall.gif" );
    $file_list = scandir($location);
    foreach($paths  as $filename) {
        foreach($file_list as $file_name) {
            if(strcasecmp($filename, $file_name) == 0) {
                $output = $location . $file_name;
            }
        }
    }
    return $output;
}  


/* Makes a copy of the LMS library depending on whether the file exists in the target location and if the source is newer than the target.
source is the location of the LMS database file.
target is the location to which the MAL version of the file is written.
*/   
function clone_library($source, $target) {
    $file_copied = false;
    // Checks to see if the database file exists
    If(file_exists($target)) {
        if(filemtime($source) > filemtime($target)) { // If the file exists checks if it is the latest version
            if(!copy($source, $target)) { // Copies the database file
                $errors= error_get_last();
                echo "COPY ERROR: ".$errors['type'];
                echo "<br />\n".$errors['message'];
            } else {
                $file_copied = true;  // The file copied so all is good
            }
        } else {
            $file_copied = true; // The file was upto date so all is good
        }
    } else { // If the database file doesn't exist
        if(!copy($source, $target)) { // Copies the database file
            $errors= error_get_last();
            echo "COPY ERROR: ".$errors['type'];
            echo "<br />\n".$errors['message'];
        } else {
            $file_copied = true;	// The file is copied so all is good.
        }
    }
    return $file_copied;
}


/* Checks to see if the database is open, if it isn't checks to see if the copy is the latest version, copies if it isn't and opens the file.
source is the location of the LMS database file.
target is the location to which the MAL version of the file is written.
*/
function open_db() {
    $found = false;
	$source = '/var/lib/squeezeboxserver/cache/library.db';
    $target = '/tmp/library.sqlite';
    if(!array_key_exists('db', $GLOBALS)) {
        if(clone_library($source, $target)) {  // Check the file exists
            $GLOBALS['db']  = new SQLite3($target);
            $found = true;
        }
	} else {
        $found = true;
	}
	return $found;
}


/* Builds a file name, that is prefixed with the date, that includes a specified base name for the file and optionaly includes the artists name the file relates to. 
base_name - A value specified when the function is called that is part of the name.
artist_key - defaults to no artist, other wise is the key for an artist name to be included in the title.
*/
function build_filename($base_name, $artist_key = 0) {
    date_default_timezone_set('UTC');
    if($base_name[0] == "'") {
        $base_name = substr($base_name, 1, strlen($base_name) - 2);
    }
    $file_name = date("Ymd_");
    if($artist_key > 0) {
        $file_name .= artist_name($artist_key);
    }
    $chars_to_remove = '\t\n\r[]:;|=,$<>\"*\0\x0B';
    $file_name .= $base_name;
    $file_name = str_replace('/', '-', $file_name);
    $file_name = str_replace("\\", '-', $file_name);
    $file_name = str_replace($chars_to_remove, ' ', $file_name);
    $file_name = trim($file_name);
    $file_name = str_replace('  ', ' ', $file_name);
    $file_name = str_replace(' ', '_', $file_name);
    $file_name = str_replace('&', 'And', $file_name);
    return  $file_name;
}


/* Builds a list of the artist resposnisble for a track, comma separated, but with an & between the last 
   two names. 
track_key - 	The key for the track of interest.
artist_type - 	Defaults to 6 for recording artist, 5 is composer.
is_html - 		If true the output is HTML, otherwise it is plain text.
*/
function get_track_artists($track_key, $artist_type = 6, $is_html = true) {
	$names = "";
	if(open_db()) {
		if(!$is_html) {	// Plain text output
			$names = $GLOBALS['db']->querySingle("SELECT group_concat(contributors.name, ', ') AS Artists FROM contributors, contributor_track WHERE (contributor_track.role = $artist_type) AND (contributors.id = contributor_track.contributor) AND (contributor_track.track = $track_key)");
			$index = strrpos($names, ',');
			if($index === true) {
				$start = substr($names, 0, $index);
				$finish = substr($names, $index + 2);
				$names = $start . " & " . $finish;
			}
		} else {	// HTML output
			$result = $GLOBALS['db']->query("SELECT \"<a href=\"\"MAL_artist_album_list.php?a=\" ||  contributors.id ||   \"\"\"> <span id=<\"\"artist\"\">\"  || contributors.name  || \"</span></a>\" AS Artists FROM contributors, contributor_track WHERE (contributor_track.role = 6) AND (contributors.id = contributor_track.contributor) AND (contributor_track.track = $track_key)");
			$total = 0;
			while($row = $result->fetchArray(SQLITE3_ASSOC)) {
				$total++;
			}
			$count = 0;
			while($row = $result->fetchArray(SQLITE3_ASSOC)) {
				if($count > 0) {
					if($count == ($total - 1)) {
						$names .= " & ";
					} else {
						$names .= ", ";
					}
				}
				$names .= $row['Artists'];
				$count++;
			}
		}
	}
	return $names;
}



/* Returns A count for the number of Genre on the Album with the key passed to it.
key is the key of the Album the Genre count is required for.
*/
function album_genre_count($key) {
	$genre_count = 0;
	if(open_db()) {
		$result = $GLOBALS['db']->query("SELECT (SELECT genres.name FROM genre_track, genres WHERE (genre_track.genre = genres.id) AND (genre_track.track = tracks.id) ) AS genre_name FROM tracks WHERE (tracks.album = $key) GROUP BY genre_name;");
		while($row = $result->fetchArray(SQLITE3_ASSOC)) {
			$genre_count++;	// Counts the number of genre that occour
		}
	}
	return $genre_count;
}


/* Returns A count for the number of Genre on the Album with the key passed to it.
key is the key of the Album the Genre count is required for.
*/
function album_genre_name($key) {
	$genre = "";
	if(open_db()) {
		$result = $GLOBALS['db']->query("SELECT (SELECT genres.name FROM genre_track, genres WHERE (genre_track.genre = genres.id) AND (genre_track.track = tracks.id) ) AS genre_name FROM tracks WHERE (tracks.album = $key) GROUP BY genre_name;");
		while($row = $result->fetchArray(SQLITE3_ASSOC)) {
			$genre 		= 	$row['genre_name'];		
		}
	}
	return $genre;
}

        
    
    
/* Returns the key for the album artist based on the album key passed to it.
The key pasted to it is the key for the album the artist is required for.
*/
function album_artist_key($album_key) {
	$artist_key = "";
	if(open_db()) {
	    $artist_key = $GLOBALS['db']->querySingle("SELECT (SELECT contributors.id AS album_artist_key FROM contributor_album, contributors WHERE (contributors.id = contributor_album.contributor) AND (contributor_album.album = albums.id) AND (contributor_album.role = 5)) AS album_artist_key FROM albums WHERE (albums.id = $album_key) LIMIT 1");
	}
	return $artist_key;
}

?>
