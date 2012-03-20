<!-- Block search module HEADER created by szazman@wp.pl-->
{literal}
<script type="text/javascript">

	function openDialog(){
	$('select').hide();
	$('#dialog').css("top", (($(window).height() - $('#dialog').height()) / 2) + "px");
	$('#dialog').css("left", (($(window).width() - $('#dialog').width()) / 2) + "px");
	$('#dialog').fadeIn();
	//$('#dialog').fadeTo("slow", 0.33);

	}
	
function showAtrybuty(id){
$('.opcjeItem').hide("slow");
$('.opcjeItem input').attr('checked', false);
$('#opcje_'+id).toggle("slow");
}
function dialogToggle(){
$('#dialog').toggle("slow");
$('select').show();
}
function showChildren(parent){
$('#parent_'+parent).toggle("slow");

}

	
	
	</script>
	{/literal}
<div id="adv_search_block_top">

	<div class="advcontainer" id="open_dialog" onclick="openDialog();" >{l s='Advanced search' mod='yasearch'}</div>
	
	<div class="dialog" id="dialog">
	        {variablebox}
			<h2>{l s='Advanced search' mod='yasearch'}</h2>
	        {variablebox_content}
			<form method="get" action="{$base_url}/modules/yasearch/results.php">
				<div class="dialogform" id="dialogform">
					<input type="hidden" name="advSearch" value="1">
					<div class="adsearchOpcje">
						<h2>{l s='KEY WORD' mod='yasearch'}</h2>
						<div class="dialogOpcjeTitle">
						        <input type="text" id="search_queryy" name="search_queryy" value="{if isset($smarty.get.search_queryy)}{$smarty.get.search_queryy|htmlentities:$ENT_QUOTES:'utf-8'}{/if}" style="width:80%;">
						</div>
						<br>
						<h2>{l s='PRICE' mod='yasearch'}</h2>
						<div class="dialogOpcjeTitle">
							{l s='PRICE FROM' mod='yasearch'} <input type="text"  name="price_from" value="" style="width:20%;float:none;"><br><br>
							{l s='PRICE TO' mod='yasearch'}	<input type="text"  name="price_to" value="" style="width:20%;float:none;"><br>
						</div>
						<br>
						{$text}
						{$cats}
						{$sup_sel}
						{$man_sel}
						{$tagi}
						<br style="clear:both;">
					</div>
				</div>
				<div class="block_buttons">
					<input class="exclusive_small" type="submit" value="{l s='submit' mod='yasearch'}">
					<a class="button_small" onclick="dialogToggle();" href="javascript:">{l s='close' mod='yasearch'}</a>
				</div>
			</form>
		{/variablebox}
		
	</div>	
</div>

<!-- /Block search module HEADER -->
