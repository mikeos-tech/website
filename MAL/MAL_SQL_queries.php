<?php

/* Returns the SQL Query for the Albums by an artist script
0   Albums by the artist
1   Albums on which the artist is a guest
2   Various artist Albums on which the artist appears
3   Ablums on which the artist composed tracks.
4   Albums by the artist for csv
5   Albums on which the artist is a guest for csv
6   Various artist Albums on which the artist appears for csv
7   Ablums on which the artist composed tracks for csv.
*/
function sql_artist_albums($choice, $key) {
    $query=array(
    
"SELECT 
	\"<a href=\"\"MAL_album_detail.php?a=\" || albums.id || \"\"\"> <span id=<\"\"album\"\">\" || albums.title || \"</span></a>\" AS 'Album Title', 
	albums.year 
FROM 
	albums 
LEFT JOIN 
	contributors ON albums.contributor = contributors.id 
WHERE 
	(albums.contributor = $key) 
GROUP BY 
	albums.id 
ORDER BY 
	albums.year, 
	albums.titlesort;",
    
    
"SELECT 
	\"<a href=\"\"MAL_album_detail.php?a=\" || albums.id || \"\"\"> <span id=<\"\"album\"\">\" || albums.title || \"</span></a>\" AS 'Album Title', 
	(SELECT 
		contributors.name 
	FROM 
		contributor_album, 
		contributors 
	WHERE 
		(contributors.id = contributor_album.contributor) AND 
		(contributor_album.album = albums.id) AND 
		(contributor_album.role = 5)) AS artist, 
	albums.year 
FROM 
	contributor_track, 
	tracks, 
	albums, 
	contributor_album 
WHERE 
	(contributor_track.track = tracks.id) AND 
	(tracks.album = albums.id) AND 
	(contributor_album.album = albums.id) AND 
	(contributor_track.contributor = $key) AND 
	(contributor_track.role = 6) AND 
	(albums.contributor != $key) AND 
	(albums.contributor != (	SELECT 
							contributors.id 
						FROM 
							contributors 
						WHERE 
							contributors.name = 'Various Artists' COLLATE NOCASE)) 
GROUP BY 
	albums.title 
ORDER BY 
	albums.year;",
    
    
"SELECT 
	\"<a href=\"\"MAL_album_detail.php?a=\" || albums.id || \"\"\"> <span id=<\"\"album\"\">\" || albums.title || \"</span></a>\" AS 'Album Title', 
	albums.year 
FROM 
	contributor_track, 
	tracks, 
	albums, 
	contributor_album 
WHERE 
	(contributor_track.track = tracks.id) AND 
	(tracks.album = albums.id) AND 
	(contributor_album.album = albums.id) AND 
	(contributor_track.contributor = $key) AND 
	(contributor_track.role = 6) AND 
	(albums.contributor != $key) AND 
	(albums.contributor = (	SELECT 
							contributors.id 
						FROM 
							contributors 
						WHERE 
							contributors.name = 'Various Artists' COLLATE NOCASE)) 
GROUP BY 
	albums.title 
ORDER BY 
	albums.year;",
    
    
"SELECT 
	\"<a href=\"\"MAL_album_detail.php?a=\" || albums.id || \"\"\"> <span id=<\"\"album\"\">\" || albums.title || \"</span></a>\" AS 'Album Title', 
	(SELECT 
		contributors.name 
	FROM 
		contributor_album, 
		contributors 
	WHERE 
		(contributors.id = contributor_album.contributor) AND 
		(contributor_album.album = albums.id) AND 
		(contributor_album.role = 5)) AS artist,  
	albums.year 
FROM 
	contributors, 
	contributor_track, 
	tracks, 
	albums 
WHERE 
	(contributors.id = contributor_track.contributor) AND 
	(contributor_track.track = tracks.id) AND 
	(tracks.album = albums.id) AND 
	(contributor_track.role = 2) AND 
	(contributors.id = $key) 
GROUP BY 
	albums.title 
ORDER BY 
	albums.year;",
    
    
"SELECT 
	albums.title AS 'Album Title', 
	albums.year AS 'Year' 
FROM 
	albums 
LEFT JOIN 
	contributors ON albums.contributor = contributors.id 
WHERE 
	(albums.contributor = $key) 
GROUP BY 
	albums.id 
ORDER BY 
	albums.year, 
	albums.titlesort;",
    
    
"SELECT 
	albums.title AS 'Album Title', 
	(SELECT 
		contributors.name 
	FROM 
		contributor_album, 
		contributors 
	WHERE 
		(contributors.id = contributor_album.contributor) AND 
		(contributor_album.album = albums.id) AND 
		(contributor_album.role = 5)) AS 'Album Artist', 
	albums.year AS 'Year' 
FROM 
	contributor_track, 
	tracks, 
	albums, 
	contributor_album 
WHERE 
	(contributor_track.track = tracks.id) AND 
	(tracks.album = albums.id) AND 
	(contributor_album.album = albums.id) AND 
	(contributor_track.contributor = $key) AND 
	(contributor_track.role = 6) AND 
	(albums.contributor != $key) AND 
	(albums.contributor != (	SELECT 
							contributors.id 
						FROM 
							contributors 
						WHERE 
							contributors.name = 'Various Artists' COLLATE NOCASE)) 
GROUP BY 
	albums.title 
ORDER BY 
	albums.year;",
    
    
"SELECT 
	albums.title AS 'Album Title', 
	albums.year AS 'Year' 
FROM 
	contributor_track, 
	tracks, 
	albums, 
	contributor_album 
WHERE 
	(contributor_track.track = tracks.id) AND 
	(tracks.album = albums.id) AND 
	(contributor_album.album = albums.id) AND 
	(contributor_track.contributor = $key) AND
	(contributor_track.role = 6) AND 
	(albums.contributor != $key) AND 
	(albums.contributor = (	SELECT 
							contributors.id 
						FROM 
							contributors 
						WHERE 
							contributors.name = 'Various Artists' COLLATE NOCASE)) 
GROUP BY 
	albums.title 
ORDER BY 
	albums.year;",
    
    
"SELECT 
	albums.title AS 'Album Title', 
	(SELECT 
		contributors.name 
	FROM 
		contributor_album, 
		contributors 
	WHERE 
		(contributors.id = contributor_album.contributor) AND 
		(contributor_album.album = albums.id) AND 
		(contributor_album.role = 5)) AS 'Album Artist',  
	albums.year AS 'Year' 
FROM 
	contributors, 
	contributor_track, 
	tracks, 
	albums 
WHERE 
	(contributors.id = contributor_track.contributor) AND 
	(contributor_track.track = tracks.id) AND 
	(tracks.album = albums.id) AND 
	(contributor_track.role = 2) AND 
	(contributors.id = $key) 
GROUP BY 
	albums.title 
ORDER BY 
	albums.year;"
    
    );
    return($query[$choice]);
}




function get_sql($choice, $album_key = 0, $artist_key = 0, $track_key = 0, $artist_type = 0) {
    $query = array(

// Produces the general album information                                        
"album_detail_album_info" =>       
"SELECT 
	albums.title,
	albums.year,
	albums.discc 
FROM 
	albums 
WHERE 
	(albums.id = $album_key);",
    

// Produces a track list
"album_detail_track_list" =>        
"SELECT 
	\"<a href=\"\"MAL_tracks_responsible_artists_list.php?a=\" || tracks.id || \"\"\"> <span id=<\"\"track\"\">\" || title || \"</span></a>\" AS 'Track Title', 
	tracks.tracknum,
	tracks.disc,
	tracks.primary_artist, 
	tracks.secs, 
	tracks.id AS T_key 
FROM 
	tracks 
WHERE 
	(tracks.album = $album_key) 
ORDER BY 
	tracks.disc, 
	tracks.tracknum;",
    
    
// Produces a track list including the Track Genre
"album_detail_track_list_genre" =>  
"SELECT 
	\"<a href=\"\"MAL_tracks_responsible_artists_list.php?a=\" || tracks.id || \"\"\"> <span id=<\"\"track\"\">\" || title || \"</span></a>\" AS 'Track Title', 
	tracks.title,
	tracks.tracknum, 
	tracks.disc, 
	tracks.primary_artist, 
	tracks.secs, 
	tracks.id AS T_key, 
	(SELECT 
		genres.name 
	FROM 
		genre_track, 
		genres 
	WHERE 
		(genre_track.genre = genres.id) AND
		(genre_track.track = tracks.id)) AS genre  
FROM 
	tracks 
WHERE 
	(tracks.album = $album_key) 
ORDER BY 
	tracks.disc, 
	tracks.tracknum;",
                                            
                                            
// Produces a list of tracks by the artist for the webpage
"artist_track_list_content" =>      
"SELECT 
	tracks.title, 
	\"<a href=\"\"MAL_tracks_responsible_artists_list.php?a=\" ||  tracks.id ||   \"\"\"> <span id=<\"\"track\"\">\"  || tracks.title  || \"</span></a>\" AS 'Track Title', 
	tracks.titlesort, 
	\"<a href=\"\"MAL_album_detail.php?a=\" || albums.id || \"\"\"> <span id=<\"\"album\"\">\" || albums.title || \"</span></a>\" AS 'Album Title', 
	albums.year, 
	albums.id AS album_key, 
	(SELECT 
		contributors.name 
	FROM 
		contributors 
	WHERE 
		(contributors.id = tracks.primary_artist)) as artist 
FROM 
	tracks, 
	contributors, 
	contributor_track, 
	albums 
WHERE 
	(tracks.id = contributor_track.track) AND 
	(contributor_track.contributor = contributors.id) AND 
	(tracks.audio = 1) AND (contributors.id = $artist_key)  AND 
	(tracks.album = albums.id) AND 
	(contributor_track.role = $artist_type) 
GROUP BY 
	tracks.id 
ORDER BY 
	tracks.titlesort;",
    
    
// Produces an index for the list of tracks by the artist
"artist_track_list_index" =>
"SELECT 
	tracks.titlesort AS 'index_name' 
FROM 
	tracks, 
	contributors, 
	contributor_track, 
	albums 
WHERE 
	(tracks.id = contributor_track.track) AND 
	(contributor_track.contributor = contributors.id) AND 
	(tracks.audio = 1) AND 
	(contributors.id = $artist_key) AND 
	(tracks.album = albums.id) AND 
	(contributor_track.role = $artist_type) 
GROUP BY 
	tracks.id 
ORDER BY 
	index_name;",

                                            
// Produces a list of tracks by the artist for a csv file
"artist_track_list_csv" =>
"SELECT 
	tracks.title AS 'Track Title', 
	albums.title AS 'Album Title', 
	albums.year AS 'Year', 
	(SELECT 
		contributors.name 
	FROM 
		contributors 
	WHERE 
		(contributors.id = tracks.primary_artist)) AS 'Track Artist' 
FROM 
	tracks, 
	contributors, 
	contributor_track, 
	albums 
WHERE 
	(tracks.id = contributor_track.track) AND 
	(contributor_track.contributor = contributors.id) AND 
	(tracks.audio = 1) AND (contributors.id = $artist_key)  AND 
	(tracks.album = albums.id) AND 
	(contributor_track.role = $artist_type) 
GROUP BY 
	tracks.id 
ORDER BY 
	tracks.titlesort;",
                                            
                                            
// Produces a list of artists responsible for tracks by the artist for a webpage
"artist_responsible_list_content" =>
"SELECT 
	\"<a href=\"\"MAL_artist_album_list.php?a=\" || contributors.id || \"\"\"> <span id=<\"\"artist\"\">\"  || contributors.name  || \"</span></a>\" AS 'Track Artist', 
	\"<a href=\"\"MAL_album_detail.php?a=\" || albums.id || \"\"\"> <span id=<\"\"album\"\">\" || albums.title || \"</span></a>\" AS 'Album Title',
	albums.year AS 'Year' 
FROM 
	tracks, 
	albums, 
	contributor_track, 
	contributors 
WHERE
	(albums.id = tracks.album) AND 
	(tracks.id = contributor_track.track) AND 
	(contributor_track.contributor = contributors.id) AND 
	(contributor_track.role = 6) AND 
	(tracks.title = 
		(SELECT 
			title 
		FROM 
			tracks 
		WHERE 
			id = $track_key)) 
ORDER BY 
	contributors.namesort, 
	albums.year, 
	albums.titlesort;",
   
   
        // Produces a list of artists responsible for a tracks for a csv file
        "artist_responsible_list_csv" =>            "SELECT 
                                                            contributors.name AS 'Track Artist', 
                                                            albums.title AS 'Album Title', 
                                                            albums.year AS Year 
                                                        FROM 
                                                            tracks, 
                                                            albums, 
                                                            contributor_track, 
                                                            contributors 
                                                        WHERE 
                                                            (albums.id = tracks.album) AND 
                                                            (tracks.id = contributor_track.track) AND 
                                                            (contributor_track.contributor = contributors.id) AND 
                                                            (contributor_track.role = 6) AND 
                                                            (tracks.title = (SELECT 
                                                                                title 
                                                                            FROM 
                                                                                tracks 
                                                                            WHERE 
                                                                                (id = $track_key))) 
                                                        ORDER BY 
                                                            contributors.namesort, 
                                                            albums.year, 
                                                            albums.titlesort;",
                                                            
                                                            
// Produces an index for the full album list                                                        
"full_album_list_index" =>
"SELECT 
	contributors.namesort AS 'index_name' 
FROM 
	albums, 
	contributors 
WHERE
	(albums.contributor = contributors.id) 
ORDER BY 
	contributors.namesort, 
	albums.year,  
	albums.titlesort;",
        
        
// Produces page contenct for the full album list webpage
"full_album_list_content" =>
"SELECT 
	\"<a href=\"\"MAL_artist_album_list.php?a=\" || contributors.id || \"\"\"> <span id=<\"\"artist\"\">\"  || contributors.name  || \"</span></a>\" AS 'Artist', 
	contributors.namesort, 
	\"<a href=\"\"MAL_album_detail.php?a=\" || albums.id || \"\"\"> <span id=<\"\"album\"\">\"   || albums.title || \"</span></a>\" AS 'Album Title', 
	albums.year 
FROM 
	albums, 
	contributors 
WHERE  
	(albums.contributor =  contributors.id) 
ORDER BY 
	contributors.namesort, 
	albums.year,  
	albums.titlesort;",
                                                            
                                                            
// Produces page contenct for the full album list csv file
"full_album_list_csv" =>
"SELECT 
	contributors.name AS 'Artist Name', 
	albums.title AS 'Album Name', 
	albums.year AS 'Year' 
FROM 
	albums, 
	contributors 
WHERE  
	(albums.contributor = contributors.id) 
ORDER BY 
	contributors.namesort, 
	albums.year,  
	albums.titlesort;",
                                                            
                                                            
// Produces page content for the full artist list webpage                                                           
"full_artist_list_content" =>
"SELECT 
	\"<a href=\"\"MAL/MAL_artist_album_list.php?a=\" || contributors.id || \"\"\"> <span id=<\"\"artist\"\">\"  || contributors.name  || \"</span></a>\" AS 'Artist', 
	contributors.namesort AS 'index_name' 
FROM 
	contributors, 
	contributor_album 
WHERE 
	(contributors.id = contributor_album.contributor) AND 
	(contributor_album.role in (1, 5, 6))
GROUP BY 
	contributors.id 
ORDER BY 
	contributors.namesort;",
    
    
// Produces page index for the full artist list webpage    
"full_artist_list_index" =>
"SELECT 
	contributors.namesort AS 'index_name' 
FROM 
	contributors, 
	contributor_album 
WHERE 
	(contributors.id = contributor_album.contributor) AND 
	(contributor_album.role in (1, 5, 6)) 
GROUP BY 
	contributors.id     
ORDER BY 
	contributors.namesort;",

                                                                
// Produces page content for the full artist list csv file    
"full_artist_list_csv" =>
"SELECT 
	contributors.name AS 'Artist Names' 
FROM 
	contributors, 
	contributor_album 
WHERE 
	(contributors.id = contributor_album.contributor) AND 
	(contributor_album.role in (1, 5, 6))
GROUP BY 
	contributors.id 
ORDER BY 
	contributors.namesort;",                                                            
    
    
    
// Produces page content for the full track list webpage ordered by fequency
"full_track_list_freq_content" =>
"SELECT 
	\"<a href=\"\"MAL_tracks_responsible_artists_list.php?a=\" || tracks.id || \"\"\"> <span id=<\"\"track\"\">\" || title || \"</span></a>\" AS 'Track Title', 
	count(*) AS Freqency 
FROM 
	tracks 
GROUP BY 
	title 
ORDER BY 
	Freqency DESC, 
	titlesort ASC;",


// Produces csv file content for the full track list webpage ordered by fequency
"full_track_list_freq_csv" =>
"SELECT 
	title AS 'Track Title', 
	count(*) AS Freqency 
FROM 
	tracks 
GROUP BY 
	title 
ORDER BY 
	Freqency DESC, 
	titlesort ASC;",
        
    
// Produces page content for the full track list webpage ordered by title
"full_track_list_title_content" =>
"SELECT 
	\"<a href=\"\"MAL_tracks_responsible_artists_list.php?a=\" ||  tracks.id ||   \"\"\"> <span id=<\"\"track\"\">\"  || title  || \"</span></a>\" AS 'Track Title', 
	count(*) AS Freqency, 
	tracks.titlesort AS 'index_name' 
FROM 
	tracks 
GROUP BY
	title 
ORDER BY 
	titlesort ASC, 
	Freqency DESC;",
        
    
// Produces csv file content for the full track list webpage ordered by fequency
"full_track_list_title_csv" =>
"SELECT 
	title AS 'Track Title', 
	count(*) AS Freqency 
FROM 
	tracks 
GROUP BY 
	title 
ORDER BY 
	titlesort ASC, 
	Freqency DESC;",
        
    

// Produces a page index page content for the full track list webpage when ordered by title     
"full_track_list_title_index" =>
"SELECT 
	tracks.titlesort AS 'index_name' 
FROM 
	tracks 
GROUP BY 
	title 
ORDER BY 
	titlesort ASC;", 
                                                                    
                                                                    
// Delete the temp table if it exists                                                                    
"drop_gen_rand" =>                  
"DROP TABLE IF EXISTS 
	gen_rand;",
                                                                                                                          
	

// Reads the data from the temp table formating it in the output
"build_list_m3u" =>
"SELECT   
	'#EXTURL:' || url || '\n#EXTINF:'|| secs || ',' || title || '\n' || replace(replace(url, 'file://', ''), '%20', ' ') || '\n' AS m3u_entry 
FROM 
	gen_rand 
WHERE  
	(_ROWID_ >= (abs(random()) % (	SELECT 
								max(_ROWID_) 
							FROM gen_rand)));",							
							
							
							
// Produces a list of tracks by the artist for a m3u file
"artist_track_list_m3u" =>
"CREATE TEMP TABLE 
	gen_rand AS
        SELECT 
			title, 
			url,
			secs
        FROM 
            tracks, 
            contributors, 
            contributor_track, 
            albums 
        WHERE 
            (tracks.id = contributor_track.track) AND 
            (contributor_track.contributor = contributors.id) AND 
            (tracks.audio = 1) AND (contributors.id = $artist_key)  AND 
            (tracks.album = albums.id) AND 
            (contributor_track.role = $artist_type) 
        GROUP BY 
            tracks.id;",							
							
							
"fill_gen_rand" =>
"CREATE TEMP TABLE 
	gen_rand AS
		SELECT 
			title, 
			url,
			secs
		FROM 
  			tracks;",							
							
							
    
    );
    return($query[$choice]);
}
?>
