%{* Smarty *}
{$home_page=false}
{$tv_progs=false}
{$radio_progs=false}
{include file="header.tpl"} 

	<div class="pagebody"> 
		<p>Available Graphs:</p>
		<table>  
		{foreach from=$rows item=row}
			<tr>
			<td class="date_style" >{$row.date}</td>
			<td class="category_style" >{$row.room}</td>
			<td class="series_style" >{$row.type}</td>
			<td class="episode_style" ><a href="http://192.168.2.9/load_graph.php?picture={$row.file_name}">Load</a></td>
			</tr>
		{/foreach}
		</table> 
	</div>

{include file="footer.tpl"}

