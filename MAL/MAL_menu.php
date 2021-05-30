<?php
include 'MAL_functions.php';

// Builds and displays the main menu displaying the items that appear on every page.
function app_menu()
{
	echo "<div class='no-print' id=\"menu_text\" >\n<ul id=\"menu\" >\n";
	if(getcwd() == '/var/www/html') {
		echo "\t<li id=\"menu_item\" ><a href=\"MAL_start.php\"><div id='menu_text'>Start Page</div></a></li>\n";
		echo "\t<li id=\"menu_item\" ><a href=\"MAL/MAL_full_album_list.php\"><div id='menu_text'>Full Album List</div></a></li>\n";	
		echo "\t<li id=\"menu_item\" ><a href=\"MAL/MAL_full_track_list.php?a=2\"><div id='menu_text'>Full Track List</div></a></li>\n";
		echo "\t<li id=\"menu_item\" ><a href=\"MAL/MAL_full_track_list.php?a=0\"><div id='menu_text'>Full Track List (Freq)</div></a></li>\n";
		echo "\t<li id=\"menu_item\" ><a href=\"MAL/MAL_help.html\"><div id='menu_text'>Help</div></a></li>\n";
	} else {
		echo "\t<li id=\"menu_item\" ><a href=\"../MAL_start.php\"><div id='menu_text'>Start Page</div></a></li>\n";
		echo "\t<li id=\"menu_item\" ><a href=\"MAL_full_album_list.php\"><div id='menu_text'>Full Album List</div></a></li>\n";
		echo "\t<li id=\"menu_item\" ><a href=\"MAL_full_track_list.php?a=2\"><div id='menu_text'>Full Track List</div></a></li>\n";
		echo "\t<li id=\"menu_item\" ><a href=\"MAL_full_track_list.php?a=0\"><div id='menu_text'>Full Track List (Freq)</div></a></li>\n";
		echo "\t<li id=\"menu_item\" ><a href=\"MAL_help.html\"><div id='menu_text'>Help</div></a></li>\n";
	}
	echo "</ul></div>\n";
}


/* Builds and displays the main menu configured for the screen on which it is displayed.
The artist, album and track key parameter is the key for the currently selected item.
The context indicates to the function the configuration for the menu.
*/
function display_menu($artist_key, $context, $album_key = 0, $track_key = 0) {
//    echo "Artist: " . $artist_key . " Context: " . $context . " Album_key: " . $album_key . " Track Key: " . $track_key;


	echo "<div class='no-print' id=\"menu_text\" >\n<ul id=\"menu\" >\n";
	if($context == 0) { 		// Start page
		$query = get_sql('full_artist_list_csv');
		$file_name = build_filename('artist_list.csv');
		echo "\t<li id=\"menu_item\" ><a href=\"MAL/MAL_csv_file.php?file=$file_name&query=$query\"><div id='menu_text'>CSV File</div></a></li>\n";
	}
	
	if($context == 1) { 		// Full Album List
		$query = get_sql('full_album_list_csv');
        $file_name = build_filename('full_album_list.csv');
		echo "\t<li id=\"menu_item\" ><a href=\"MAL_csv_file.php?file=$file_name&query=$query\"><div id='menu_text'>CSV File</div></a></li>\n";
	}
	
	if($context == 2) {			// Artist album List
		echo "\t<li id=\"menu_item\"><a href=\"MAL_artist_track_list.php?a=$artist_key\"><div id='menu_text'>Artist Track List</div></a></li>\n";
		if(has_composed($artist_key)) {
		    echo "\t<li id=\"menu_item\"><a href=\"MAL_artist_composer_list.php?a=$artist_key\"><div id='menu_text'>Artist Composed Tracks</div></a></li>\n";
		}
        $file_name = build_filename('_album_list.csv', $artist_key);
		echo "\t<li id=\"menu_item\" ><a href=\"MAL_csv_artist_album_list.php?a=$artist_key&file=$file_name\"><div id='menu_text'>CSV File</div></a></li>\n";
	}

	if($context == 3) {			// Album Detail
		echo "\t<li id=\"menu_item\"><a href=\"MAL_artist_album_list.php?a=$artist_key\"><div id='menu_text'>Artist Album List</div></a></li>\n";
		echo "\t<li id=\"menu_item\"><a href=\"MAL_artist_track_list.php?a=$artist_key\"><div id='menu_text'>Artist Track List</div></a></li>\n";
		if(has_composed($artist_key)) {
		    echo "\t<li id=\"menu_item\"><a href=\"MAL_artist_composer_list.php?a=$artist_key\"><div id='menu_text'>Artist Composed Tracks</div></a></li>\n";
		}
        $file_name = build_filename('-' . album_name($album_key) . '-album_detail.csv', $artist_key);
		echo "\t<li id=\"menu_item\" ><a href=\"MAL_csv_album_detail.php?file=$file_name&album=$album_key\"><div id='menu_text'>CSV File</div></a></li>\n";
	}

	if($context == 4) {			// Artist track list
		echo "\t<li id=\"menu_item\"><a href=\"MAL_artist_album_list.php?a=$artist_key\"><div id='menu_text'>Artist Album List</div></a></li>\n";
		if(has_composed($artist_key)) {
		    echo "\t<li id=\"menu_item\"><a href=\"MAL_artist_composer_list.php?a=$artist_key\"><div id='menu_text'>Artist Composed Tracks</div></a></li>\n";
		}
        $csv_query = get_sql('artist_track_list_csv', 0, $artist_key, 0, 6);
        $file_name = build_filename('_track_list.csv', $artist_key);
		echo "\t<li id=\"menu_item\" ><a href=\"MAL_csv_file.php?file=$file_name&query=$csv_query\"><div id='menu_text'>CSV File</div></a></li>\n";
		
        $m3u_query = get_sql('artist_track_list_m3u');
        $file_name = build_filename('_track_list.m3u', $artist_key);
		echo "\t<li id=\"menu_item\" ><a href=\"MAL_m3u_file.php?file=$file_name&query=$m3u_query\"><div id='menu_text'>M3U Playlist</div></a></li>\n";
		
	}
	if($context == 5) {			// Tracks Composed by Artist list
		echo "\t<li id=\"menu_item\"><a href=\"MAL_artist_album_list.php?a=$album_key\"><div id='menu_text'>Artist Album List</div></a></li>\n";
		echo "\t<li id=\"menu_item\"><a href=\"MAL_artist_track_list.php?a=$artist_key\"><div id='menu_text'>Artist Track List</div></a></li>\n";
        $query = get_sql('artist_track_list_csv', 0, $artist_key, 0, 2);
        $file_name = build_filename('_composed_list.csv', $artist_key);
		echo "\t<li id=\"menu_item\" ><a href=\"MAL_csv_file.php?file=$file_name&query=$query\"><div id='menu_text'>CSV File</div></a></li>\n";
	}
	
	if($context == 6) { 		// Full Track List - frequency
		$query = get_sql('full_track_list_freq_csv');
        $file_name = build_filename('full_track_list(freq).csv');
		echo "\t<li id=\"menu_item\" ><a href=\"MAL_csv_file.php?file=$file_name&query=$query\"><div id='menu_text'>CSV File</div></a></li>\n";
	}
	
	if($context == 7) { 		// Track artists responsible
		$query = get_sql('artist_responsible_list_csv', 0, 0, $track_key);
        $file_name = build_filename(track_name($track_key) . '-artists_responsible_list.csv');
		echo "\t<li id=\"menu_item\" ><a href=\"MAL_csv_file.php?file=$file_name&query=$query\"><div id='menu_text'>CSV File</div></a></li>\n";
	}
	
	if($context == 8) { 		// Full Track List - alphabetical
		$query = get_sql('full_track_list_title_csv');
        $file_name = build_filename('full_track_list(alpha).csv');
		echo "\t<li id=\"menu_item\" ><a href=\"MAL_csv_file.php?file=$file_name&query=$query\"><div id='menu_text'>CSV File</div></a></li>\n";
	}
	echo "</ul></div>\n";
}

    
/* Displays a side menu listing starting letters and numbers for the items in the page on which it appears.
The query needs to be the query used to generate the list of items.
The query must have a field with the title 'index_name' applied to the field from which the list is built.
*/  
function side_menu($query) {
	if(open_db()) {
		// Collect the data for the side menu
		$result = $GLOBALS['db']->query($query);
		$key = 0;
		$letter_list[$key] = "Top";
		$letter_list[$key + 1] = "";
		while ($row = $result->fetchArray()) {
			$name = $row['index_name'];
            if($name !== '') {
				$first_char = strtoupper($name[0]); // the uppercasing is just precutionary could remove it
				if(strcmp($first_char, $letter_list[$key]) != 0) {
					$key++;
					$letter_list[$key] = $first_char;
				}
			}
		}
		$key++;
		$letter_list[$key] = "End";
        $linkmenu = "\n<div class='no-print'><ul id=\"side_menu\" >";
        foreach($letter_list as $value) {
            $linkmenu = $linkmenu . "\n\t<li id=\"side_menu_item\" ><a href=\"#" . $value . "_index\"><div id='menu_text'>$value</div></a></li>";
        }
        $linkmenu = $linkmenu . "\n</ul></div>";
        echo $linkmenu;
    }
}  
?>
