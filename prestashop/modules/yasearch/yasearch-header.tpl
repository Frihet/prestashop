<!-- Block search module HEADER created by szazman@wp.pl-->
{literal}
<style type="text/css">
.dialog{
width:100%;
display:none;
text-align:center;
color:#fff;
background:#000000;
border-bottom:3px solid #f0f0f0;
}
.dialogcontent{

width:650px;
margin:0 auto;
text-align:left;
}
.grouptitle{
font-weight:bold;
}
.dialog span{
color:#fff;
}
.closeAdvSearch{
color:#fff;
padding:2px;
width:150px;
text-align:center;
border:1px solid #fff;
cursor:pointer;
}
.dialogOpcjeTitle{
font-weight:bold;
cursor:pointer;
padding:3px;
color:#000;
width:100%;
}
#advSearchTags{
float:left;
font-weight:bold;
cursor:pointer;
padding:3px;
color:#000;
}
.opcjeItem{
display:none;
color:#000;
padding:3px;
}
.dialogform{
padding:20px;
margin:0 auto;
width:600px;

}
.dialogform span{
color:#fff;
}
.adsearchOpcje{
background:#fff;
padding:50px;
margin:10px;
}
.dialoginput{
clear:both;
float:none;
background:none;
padding:3px;
border:0;
}
.catGroup{
cursor:pointer;;padding:3px;
}
.advcontainer{
cursor:pointer;text-decoration:underline;clear:both;padding:3px;
}

</style>

<script type="text/javascript">

	function openDialog(){
	$('select').hide();
	$('#dialog').css("z-index","101");
	$('#dialog').css("position","absolute");
	$('#dialog').css("top","0");
	$('#dialog').css("left","0");
	$('#dialog').width($(document).width());
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
		<div class="dialogcontent">	
			<div class="dialogform" id="dialogform">
			<span>{l s='Advanced search' mod='yasearch'}</span>
				<form method="get" action="{$base_url}/modules/yasearch/results.php">
				<input type="hidden" name="advSearch" value="1">
				<div class="adsearchOpcje">
				<h2>{l s='KEY WORD' mod='yasearch'}</h2>
				<div class="dialogOpcjeTitle"><input type="text" id="search_queryy" name="search_queryy" value="{if isset($smarty.get.search_queryy)}{$smarty.get.search_queryy|htmlentities:$ENT_QUOTES:'utf-8'}{/if}" style="width:80%;"/></div><br>
				<h2>{l s='PRICE' mod='yasearch'}</h2>
				<div class="dialogOpcjeTitle">
					{l s='PRICE FROM' mod='yasearch'}	<input type="text"  name="price_from" value="" style="width:20%;float:none;"/><br/><br/>
					{l s='PRICE TO' mod='yasearch'}	<input type="text"  name="price_to" value="" style="width:20%;float:none;"/><br/>
					</div><br>
				{$text}
				{$cats}
				{$sup_sel}
				{$man_sel}
				{$tagi}
						<br style="clear:both;"/><br/><input type="submit" value="{l s='submit' mod='yasearch'}">
				</div>
						</form>
			<div class="closeAdvSearch" onclick="dialogToggle();">{l s='close' mod='yasearch'}</div>
			</div>
		</div>
		
	</div>	
</div>

<!-- /Block search module HEADER -->