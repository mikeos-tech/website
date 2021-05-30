{* Smarty *}
{$home_page=false}
{$tv_progs=true}
{$radio_progs=false}
{include file="header.tpl"} 

	<div class="pagebody"> 
		<p>The date is: {$today_date}
		<br>This is the Downloaded TV program list:</p>
		<table>  
		{foreach from=$rows item=row}
			<tr>
			<td class="date_style" >{$row.date}</td>
			<td class="category_style" >{$row.category}</td>
			<td class="series_style" >{$row.series}</td>
			<td class="episode_style" >{$row.episode}</td>
                        <td>
                                <a href="http://192.168.2.9/confirm.html?action=1&name={$row.key}" class="button" >Watched</a>
                        </td>
                        <td>
                                <a href="http://192.168.2.9/confirm.html?action=0&name={$row.key}" class="button" >Archive</a>
                        </td>
			</tr>
		{/foreach}
		</table> 
	</div>

{include file="footer.tpl"}

