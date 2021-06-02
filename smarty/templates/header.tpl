<link rel="stylesheet" type="text/css" href="css/style.css" />
<title>Mad Mike&#39;s Music Machine...</title>
</head>
<body>

<div style="position: absolute; left: 20px; top: 20px;">
<img border="0" src="images/l_logo.png" width="220" height="260">
</div>

<h1><span class="title_cap" >M</span>ad <span class="title_cap" >M</span>ike&#39;s <span class="title_cap" >M</span>usic <span class="title_cap" >M</span>achine.<span class="title_cap" >.</span>.</h1>

<div style="position: absolute; left: 340px; top: 250px;">
{if not $home_page}
	<a href="http://192.168.2.9/index.php" class="button" >Home</a>
{/if}
<a href="https://192.168.2.9:10000" class="button" target="_blank" >Webmin</a>
<a href="http://192.168.2.9:9000/material/" class="button" target="_blank" >Media Player</a>
<a href="http://192.168.2.9:9091" class="button" target="_blank" >Transmissions Client</a>
<a href="http://192.168.2.9/MAL_start.php" class="button" >Media/Artist List</a>
<a href="http://192.168.2.9/environment.php" class="button" >Environment</a> 
{if not $backup}
	<a href="http://192.168.2.9/backup.php" class="button" >Media Backup</a>
{/if}
{if not $tv_progs}
	<a href="http://192.168.2.9/tv_progs.php" class="button" >TV Program Downloads</a>
{/if}
{if not $radio_progs}
	<a href="http://192.168.2.9/radio_progs.php" class="button" >Radio Program Downloads</a>
{/if}
</div>

<div style="position: absolute; right: 20px; top: 20px;">
<img border="0" src="images/r_logo.png" width="220" height="260">
</div>
<br><br><br>
