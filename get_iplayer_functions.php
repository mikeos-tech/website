<?php


/* Checks to see if the database is open, if it isn't checks to see if the copy is the latest version, copies if it isn't and opens the file.
source is the location of the LMS database file.
target is the location to which the MAL version of the file is written.
*/
function open_db() {
    $found = false;
    $db_location = '/get_iplayer/database/get-iplayer_log.db';
    if(!array_key_exists('db', $GLOBALS)) {
            $GLOBALS['db']  = new SQLite3($db_location);
            $found = true;
   } else {
        $found = true;
   }
	return $found;
}

?>
