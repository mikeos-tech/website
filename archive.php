<!DOCTYPE html>
<html>
<body>


<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

include 'get_iplayer_functions.php';

$key = $_GET['name'];

    if (open_db()) {
        $sql = 'UPDATE updates SET archive=1 WHERE ( path=\'' . $key . '\')';
        $result = $GLOBALS['db']->exec($sql);
        if ($result == FALSE) {
            echo 'Failed to update the database!';
        } else {
            if(stripos($key, ".mp4") == false) {
               header('Location: radio_progs.php');
            } else {
               header('Location: tv_progs.php');
            }
       }
   } else {
       echo 'Failed to access the database!';
   }

?>
</body>
</html>


