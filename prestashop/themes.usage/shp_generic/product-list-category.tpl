
{foreach from=$products item=product name=homeFeaturedProducts}
	{variablebox border_left="hidden" border_top="hidden" class="product_list_category_item ajax_block_product"}
		<div style="height:300px;">
			<h5><a href="{$product.link}" title="{$product.name|truncate:32:'...'|escape:'htmlall':'UTF-8'}">{$product.name|truncate:27:'...'|escape:'htmlall':'UTF-8'}</a></h5>
			{if !isset($hide_description)}
				<p class="product_desc"><a href="{$product.link}" title="{l s='More'}">{$product.description_short|strip_tags|truncate:130:'...'}</a></p>
			{/if}
			<a href="{$product.link}" title="{$product.legend|escape:htmlall:'UTF-8'}" class="product_image"><img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home')}" height="{$homeSize.height}" width="{$homeSize.width}" alt="{$product.legend|escape:htmlall:'UTF-8'}" /></a>
			{if ($product.type != 'article')}
			        {if !$priceDisplay || $priceDisplay == 2}<p class="price_container"><span class="price">{convertPrice price=$product.price}</span>{if $priceDisplay == 2} {l s='+Tx'}{/if}</p>{/if}
				{if $priceDisplay}<p class="price_container"><span class="price">{convertPrice price=$product.price_tax_exc}</span>{if $priceDisplay == 2} {l s='-Tx'}{/if}</p>{/if}
			{/if}
		</div>
		<div>
			<a class="button" href="{$product.link}" title="{l s='View'}">{l s='View'}</a>
			{if ($product.type != 'article') && !isset($hide_actions)}
				{hookExec hook="productListActions" product=$product}
				{if ($product.quantity > 0 OR $product.allow_oosp) AND $product.customizable != 2}
				        <a class="exclusive ajax_add_to_cart_button" rel="ajax_id_product_{$product.id_product}" href="{$base_dir}cart.php?qty=1&amp;id_product={$product.id_product}&amp;token={$static_token}&amp;add" title="{l s='Add to cart'}">{l s='Add to cart'}</a>
				{else}
				        <span class="exclusive">{l s='Add to cart'}</span>
				{/if}
			{/if}
		</div>
	{/variablebox}
{/foreach}
<div style="clear: both;"></div>
