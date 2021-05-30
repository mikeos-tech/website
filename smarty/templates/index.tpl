{* smarty *}
{$home_page=true}
{$radio_progs=false}
{$tv_progs=false}
{include file="header.tpl"} 

<style>

.question {
	color: red;
	padding-left: 20px;
	font-size: 25px;
}

.indent {
        margin: 20px;
}

.answer {
	color: white;
	padding-left: 20px;
	font-size: 25px;
}

.disk_info {
  border: 1px solid white;
  width: auto;
  color: white;
  text-align: center;
}

.filesystem_size {
  width: 14%;
  background: red;
}

.capacity_size {
  width: 8%;
  background: green;
}

.iconImage {
    padding-top: 8px;
    width: 100px;
    height: 100px;
    text-align: center;
}

.indexmenu {
  position: absolute;
  top: 300px;
  width: 140px;
  height: 600px;
  border: 3px solid #f46f1a;
  font-size: 16px; 
  text-align: center;
  color: #f46f1a;
}

.indexbody {
  position: absolute;
  top: 300px;
  left: 150px;
  width: 1740px;
  height: 600px;
  border: 3px solid #f46f1a;
}



</style>


<div class="indexmenu" >
	<a href="update.php" >
	<img class="iconImage" src="images/update.png" title="OS Updates"></a>
	<br>OS Updates
        <a href="shutdown.php" >
        <img class="iconImage" src="images/shutdown.png" title="OS Updates"></a>
        <br>Shutdown
        <a href="restart.php" >
        <img class="iconImage" src="images/restart.png" title="OS Updates"></a>
        <br>Restart
</div>

	<div class="indexbody"> 
		<table>
		<tr><td class="question" >The date is:     </td><td class="answer" >{$today_date}</td></tr>
		<tr><td class="question" >Server IP addess:</td><td class="answer" >{$ip_address}</td></tr>
		<tr><td class="question" >Server Hostname: </td><td class="answer" >{$hostname}  </td></tr>
		<tr><td class="question" >Distribution:    </td><td class="answer" >{$distro_version}</td></tr>
                <tr><td class="question" >Linux Kernal:    </td><td class="answer" >{$os_version}</td></tr>
                <tr><td class="question" >CPU:             </td><td class="answer" >{$cpu}</td></tr>
		</table>
		<h2 class="question" >Storage:</h2>
		<table class="disk_info, indent" >
		<colgroup>
                        <col class="filesystem_size" />
			<col class="capacity_size" />
			<col class="capacity_size" />
			<col class="capacity_size" />
			<col class="capacity_size" />
		</colgroup>
		<tr>
			<th class="disk_info" >FileSystem</th>
			<th class="disk_info" >Size</th>
			<th class="disk_info" >Used</th>
			<th class="disk_info" >Avail</th>
			<th class="disk_info" >Used</th>
		</tr>
		<tr>
			<td class="disk_info" >{$home_drive[5]}</td>
			<td class="disk_info" >{$home_drive[1]}</td>
			<td class="disk_info" >{$home_drive[2]}</td>
			<td class="disk_info" >{$home_drive[3]}</td>
			<td class="disk_info" >{$home_drive[4]}</td>
		</tr>
                <tr>
                        <td class="disk_info" >{$storage_drive[5]}</td>
                        <td class="disk_info" >{$storage_drive[1]}</td>
                        <td class="disk_info" >{$storage_drive[2]}</td>
                        <td class="disk_info" >{$storage_drive[3]}</td>
                        <td class="disk_info" >{$storage_drive[4]}</td>
                </tr>
		</table>
	</div>

{include file="footer.tpl"}

