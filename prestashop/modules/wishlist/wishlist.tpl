<script type="text/javascript">
<!--
	var baseDir = '{$base_dir_ssl}';
-->
</script>

{capture name=path}{l s='Wishlist'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

<h2>{l s='Wishlist'}</h2>

{include file=$tpl_dir./errors.tpl}

<form action="{$base_dir_ssl}modules/wishlist/display.php?id={$cart_hash_id}" method="post">
	<p>
		{l s='The wishlist contains'} {$products|@count} {if $products|@count > 1}{l s='products'}{else}{l s='product'}{/if}
	</p>
	<div id="order-detail-content" class="table_block">
		<table id="cart_summary" class="std">
			<thead>
				<tr>
					<th class="cart_product first_item">{l s='Product'}</th>
					<th class="cart_description item">{l s='Description'}</th>
					<th class="cart_ref item">{l s='Ref.'}</th>
					<th class="cart_availability item">{l s='Avail.'}</th>
					<th class="cart_unit item">{l s='Unit price'}</th>
					<th class="cart_quantity item">{l s='Qty'}</th>
					<th class="cart_total item">{l s='Total'}</th>
					<th class="cart_total last_item"></th>
				</tr>
			</thead>
			<tfoot>
				{if $priceDisplay}
					<tr class="cart_total_price">
						<td colspan="6">{l s='Total products (tax excl.):'}</td>
						<td class="price">{convertPrice price=$total_products}</td>
						<td></td>
					</tr>
				{/if}
				{if !$priceDisplay || $priceDisplay == 2}
					<tr class="cart_total_price">
						<td colspan="6">{l s='Total products (tax incl.):'}</td>
						<td class="price">{convertPrice price=$total_products_wt}</td>
						<td></td>
					</tr>
				{/if}
			</tfoot>
			<tbody>
			{foreach from=$products item=product name=productLoop}
				{assign var='productId' value=$product.id_product}
				{assign var='productAttributeId' value=$product.id_product_attribute}
				{assign var='quantityDisplayed' value=0}
				{* Display the product line *}
				{include file=$mdl_dir./wishlist-product-line.tpl}
				{* Then the customized datas ones*}
				{if isset($customizedDatas.$productId.$productAttributeId)}
					{foreach from=$customizedDatas.$productId.$productAttributeId key='id_customization' item='customization'}
						<tr class="alternate_item cart_item">
							<td colspan="5">
								{foreach from=$customization.datas key='type' item='datas'}
									{if $type == $CUSTOMIZE_FILE}
										<div class="customizationUploaded">
											<ul class="customizationUploaded">
												{foreach from=$datas item='picture'}<li><img src="{$pic_dir}{$picture.value}_small" alt="" class="customizationUploaded" /></li>{/foreach}
											</ul>
										</div>
									{elseif $type == $CUSTOMIZE_TEXTFIELD}
										<ul class="typedText">
											{foreach from=$datas item='textField' name='typedText'}<li>{l s='Text #'}{$smarty.foreach.typedText.index+1}{l s=':'} {$textField.value}</li>{/foreach}
										</ul>
									{/if}
								{/foreach}
							</td>
							<td class="cart_quantity">
								<p>{$customization.quantity}</p>
							</td>
							<td class="cart_total"></td>
							<td><button type="submit" name="addCustomizationToCart" value="{$id_customization}" class="button_small">{l s='Add to cart'}</button></td>
						</tr>
						{assign var='quantityDisplayed' value=$quantityDisplayed+$customization.quantity}
					{/foreach}
					{* If it exists also some uncustomized products *}
					{if $product.quantity-$quantityDisplayed > 0}{include file=$mdl_dir./wishlist-product-line.tpl}{/if}
				{/if}
			{/foreach}
			</tbody>
		</table>
		<button type="submit" name="addAllToCart" value="1" class="button_small">{l s='Add all to cart'}</button>
	</div>
</form>
