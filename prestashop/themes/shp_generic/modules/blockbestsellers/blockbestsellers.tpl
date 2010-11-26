<!-- MODULE Block best sellers -->
<div id="best-sellers_block_right" class="block products_block">
	<h4><a href="{$base_dir}best-sales.php">{l s='Top sellers' mod='blockbestsellers'}</a></h4>
	<div class="block_content">
	{if $best_sellers|@count > 0}

		<table class="product_list">
		{foreach from=$best_sellers item='product' name=product}
			{assign var='productLink' value=$link->getProductLink($product.id_product, $product.link_rewrite)}
			<tr class="ajax_block_product">
				<td class="product_desc">
				 <a
				  href="{$productLink|escape:'htmlall':'UTF-8'}"
				  title="{$product.name|escape:'htmlall':'UTF-8'}: {$product.description_short|strip_tags|truncate:100:'...'}"
				 >
				  {$product.name|truncate:15:'..'|escape:'htmlall':'UTF-8'}

				 </a>
				</td>
				<td>{$product.price}</td>
				<td><a class="button_mini ajax_add_to_cart_button" href="{$base_dir}cart.php?qty=1&amp;id_product={$product.id_product|intval}&amp;token={$static_token}&amp;add" rel="ajax_id_product_{$product.id_product|intval}" title="{l s='Add to cart'}">{l s='Buy'}</a></td>
			</tr>
			<tr>

			</tr>
		{/foreach}
		</table>

	{else}
		<p>{l s='No best sellers at this time' mod='blockbestsellers'}</p>
	{/if}
	</div>
	<div class="block_buttons">
		{if $best_sellers|@count > 0}
		         <a href="{$base_dir}best-sales.php" title="{l s='All best sellers' mod='blockbestsellers'}" class="button_small">{l s='More...' mod='blockbestsellers'}</a>
		{/if}
        </div>
</div>
<!-- /MODULE Block best sellers -->
