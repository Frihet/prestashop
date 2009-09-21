{include file=$tpl_dir./errors.tpl}
{if $errors|@count == 0}
<script type="text/javascript">
// <![CDATA[

// PrestaShop internal settings
var jqZoomEnabled = {if $jqZoomEnabled}true{else}false{/if};

//JS Hook
var oosHookJsCodeFunctions = new Array();

// Parameters
var id_product = '{$product->id|intval}';

// Customizable field
var img_ps_dir = '{$img_ps_dir}';

// Images
var img_prod_dir = '{$img_prod_dir}';
var combinationImages = new Array();
{foreach from=$combinationImages item='combination' key='combinationId' name='f_combinationImages'}
combinationImages[{$combinationId}] = new Array();
{foreach from=$combination item='image' name='f_combinationImage'}
combinationImages[{$combinationId}][{$smarty.foreach.f_combinationImage.index}] = {$image.id_image|intval};
{/foreach}
{/foreach}

combinationImages[0] = new Array();
{foreach from=$images item='image' name='f_defaultImages'}
combinationImages[0][{$smarty.foreach.f_defaultImages.index}] = {$image.id_image};
{/foreach}

// Translations
var doesntExist = '{l s='The product does not exist in this model. Please choose another.' js=1}';
var doesntExistNoMore = '{l s='This product is no longer in stock' js=1}';
var doesntExistNoMoreBut = '{l s='with those attributes but is available with others' js=1}';
var uploading_in_progress = '{l s='Uploading in progress, please wait...' js=1}';
var fieldRequired = '{l s='Please fill all required fields' js=1}';


//]]>
</script>

{include file=$tpl_dir./breadcrumb.tpl}

<div id="primary_block">

	<h2>{$product->name|escape:'htmlall':'UTF-8'}</h2>
	{if $confirmation}
	<p class="confirmation">
		{$confirmation}
	</p>
	{/if}

	<!-- right infos-->
	<div id="pb-right-column">
		<!-- product img-->
		<div id="image-block">
		{if $have_image}
				<img src="{$link->getImageLink($product->link_rewrite, $cover.id_image, 'large')}" {if $jqZoomEnabled}class="jqzoom" alt="{$link->getImageLink($product->link_rewrite, $cover.id_image, 'thickbox')}"{else} title="{$product->name|escape:'htmlall':'UTF-8'}" alt="{$product->name|escape:'htmlall':'UTF-8'}" {/if} id="bigpic"/>
		{else}
			<img src="{$img_prod_dir}{$lang_iso}-default-large.jpg" alt="" title="{$product->name|escape:'htmlall':'UTF-8'}" />
		{/if}
		</div>

		{if count($images) > 0}
		<!-- thumbnails -->
		<div id="views_block" {if count($images) < 2}class="hidden"{/if}>
		{if count($images) > 3}<span class="view_scroll_spacer"><a id="view_scroll_left" class="hidden" title="{l s='Other views'}" href="javascript:{ldelim}{rdelim}">{l s='Previous'}</a></span>{/if}
		<div id="thumbs_list">
			<ul style="width: {math equation="width * nbImages" width=80 nbImages=$images|@count}px" id="thumbs_list_frame">
				{foreach from=$images item=image name=thumbnails}
				{assign var=imageIds value=`$product->id`-`$image.id_image`}
				<li id="thumbnail_{$image.id_image}">
					<a href="{$link->getImageLink($product->link_rewrite, $imageIds, 'thickbox')}" rel="other-views" class="{if !$jqZoomEnabled}thickbox{/if} {if $smarty.foreach.thumbnails.first}shown{/if}" title="{$image.legend|htmlspecialchars}">
						<img id="thumb_{$image.id_image}" src="{$link->getImageLink($product->link_rewrite, $imageIds, 'medium')}" alt="{$image.legend|htmlspecialchars}" height="{$mediumSize.height}" width="{$mediumSize.width}" />
					</a>
				</li>
				{/foreach}
			</ul>
		</div>
		{if count($images) > 3}<a id="view_scroll_right" title="{l s='Other views'}" href="javascript:{ldelim}{rdelim}">{l s='Next'}</a>{/if}
		</div>
		{/if}
		{if count($images) > 1}<p class="align_center clear"><a id="resetImages" href="{$link->getProductLink($product)}" onclick="return (false);">{l s='Display all pictures'}</a></p>{/if}

		<!-- usefull links-->
		<ul id="usefull_link_block">
			{if $HOOK_EXTRA_LEFT}{$HOOK_EXTRA_LEFT}{/if}
			<li><a href="javascript:print();">{l s='Print'}</a><br class="clear" /></li>
			{if $have_image && !$jqZoomEnabled}
			<li><span id="view_full_size" class="span_link">{l s='View full size'}</span></li>
			{/if}
		</ul>

	</div>

	<!-- left infos-->
	<div id="pb-left-column">
		{if isset($accessories) AND $accessories}
			<!-- accessories -->
			<div class="block">
                                <h4>Products used</h4>
				<div class="block_content">
					<table>
					{foreach from=$accessories item=accessory name=accessories_list}
						{assign var='accessoryLink' value=$link->getProductLink($accessory.id_product, $accessory.link_rewrite, $accessory.category)}
						<tr>
							<td>
                                                         <a
                                                          href="{$accessoryLink|escape:'htmlall':'UTF-8'}"
                                                          title="{$accessory.name|escape:'htmlall':'UTF-8'}: {$accessory.description_short|strip_tags|truncate:100:'...'}"
                                                         >
                                                          {$accessory.name|truncate:15:'..'|escape:'htmlall':'UTF-8'}

                                                         </a>
                                                        </td>
							<td>{displayWtPrice p=$accessory.price}</td>
							<td><a class="button_mini ajax_add_to_cart_button" href="{$base_dir}cart.php?qty=1&amp;id_product={$accessory.id_product|intval}&amp;token={$static_token}&amp;add" rel="ajax_id_product_{$accessory.id_product|intval}" title="{l s='Add to cart'}">{l s='Buy'}</a></td>

                                                        <!--
							        <td><a href="{$accessoryLink|escape:'htmlall':'UTF-8'}" title="{$accessory.legend|escape:'htmlall':'UTF-8'}"><img src="{$link->getImageLink($accessory.link_rewrite, $accessory.id_image, 'medium')}" alt="{$accessory.legend|escape:'htmlall':'UTF-8'}" /></a></td>
							        <td><a href="{$accessoryLink|escape:'htmlall':'UTF-8'}" title="{l s='More'}">{$accessory.description_short|strip_tags|truncate:100:'...'}</a></td>
                                                        -->
                                                </tr>
                                                <tr>

						</tr
					{/foreach}
					</table>
				</div>
			</div>
		{/if}

		{if $HOOK_EXTRA_RIGHT}{$HOOK_EXTRA_RIGHT}{/if}
	</div>
</div>

<div class="clear">

{if $product->description_short}
	<div id="short_description_content" class="rte align_justify">{$product->description_short}</div>
{/if}

<!-- description and features -->
{if $product->description || $HOOK_PRODUCT_TAB || $attachments}
        {$product->description}

        {if $attachments}
                <hr />
		{foreach from=$attachments item=attachment}
			<li><a href="{$base_dir}attachment.php?id_attachment={$attachment.id_attachment}">{$attachment.name|escape:'htmlall':'UTF-8'}</a><br />{$attachment.description|escape:'htmlall':'UTF-8'}</li>
		{/foreach}
        {/if}

        {if $HOOK_PRODUCT_TAB_CONTENT}
                <hr />
  	        {$HOOK_PRODUCT_TAB_CONTENT}
        {/if}
{/if}

{if $HOOK_PRODUCT_FOOTER}
        <hr />
        {$HOOK_PRODUCT_FOOTER}
{/if}

</div>

{/if}

