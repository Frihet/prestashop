{*************************************************************************************************************************************}
{* IMPORTANT : If you change some data here, you have to report these changes in the ./blockcompare-json.js (to let ajaxCompare available) *}
{*************************************************************************************************************************************}

{if $ajax_allowed}
<script type="text/javascript" src="{$content_dir}js/jquery/iutil.prestashop-modifications.js"></script>
{* to perfectly play the tranfert animation, the script ifx.js has to be called here, but it creates a method conflict with jquery.serialScroll.js file *}
<script type="text/javascript" src="{$content_dir}js/jquery/ifxtransfer.js"></script>
<script type="text/javascript" src="{$content_dir}modules/blockcompare/ajax-compare.js"></script>
{/if}

{assign var='id_lang' value=$cookie->id_lang|intval}

<!-- MODULE Block compare -->
<div id="compare_block" class="block exclusive">
	<h4>
		<a href="{$base_dir_ssl}modules/blockcompare/compare.php">{l s='Compare' mod='blockcompare'}</a>
		{if $ajax_allowed}
		<span id="block_compare_expand" {if $colapseExpandStatus eq 'expanded'}class="hidden"{/if}>&nbsp;</span>
		<span id="block_compare_collapse" {if $colapseExpandStatus eq 'collapsed' || !isset($colapseExpandStatus)}class="hidden"{/if}>&nbsp;</span>
		{/if}
	</h4>
	<div class="block_content">
		<!-- block list of products -->
		<div id="compare_block_list" class="{if true || !$ajax_allowed || $colapseExpandStatus eq 'expanded'}expanded{else}collapsed{/if}">
			{if $products}
				<dl class="products">
				{foreach from=$products item='product' name='myLoop'}
					{assign var='productId' value=$product.id}

					<dt id="compare_block_product_{$productId}" class="{if $smarty.foreach.myLoop.first}first_item{elseif $smarty.foreach.myLoop.last}last_item{else}item{/if}">
						<span class="compare_block_product_description"><a class="compare_block_product_name" href="{$link->getProductLink($productId, $product.link_rewrite, $product.category)}" title="{$product.name[$id_lang]|escape:htmlall:'UTF-8'}">{t text=$product.name[$id_lang] length='16' encode='true'}</a></span>
						<span class="remove_link"><a class="ajax_compare_block_remove_link" href="{$base_dir}modules/blockcompare/compare.php?delete&amp;id_product={$productId}&amp;token={$static_token}" title="{l s='remove this product from my compare' mod='blockcompare'}">&nbsp;</a></span>
					</dt>

				{/foreach}
				</dl>
			{/if}
			<p {if $products}class="hidden"{/if} id="compare_block_no_products">{l s='No products' mod='blockcompare'}</p>

			<p id="compare-buttons">
				<a href="{$base_dir_ssl}modules/blockcompare/comparecart.php" class="button_small" title="{l s='Compare' mod='blockcompare'}">{l s='Compare' mod='blockcompare'}</a>

			</p>
		</div>
	</div>
</div>
<!-- /MODULE Block compare -->
