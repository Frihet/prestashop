<script type="text/javascript">
<!--
	var baseDir = '{$base_dir_ssl}';
-->
</script>

{capture name=path}{l s='Your shopping cart' mod='orderpriceadjustment'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

{variablebox class="universal"}
<h2>{l s='Final price adjustment and review' mod='orderpriceadjustment'}</h2>
{variablebox_content}

{include file=$tpl_dir./module-order-steps.tpl}

{include file=$tpl_dir./errors.tpl}

<div class="warning">
 <div>{l s='Note: Prices are different for each country/currency!' mod='orderpriceadjustment'}</div>
 <div>{l s='Your currency is:' mod='orderpriceadjustment'} {$currency_name} ({$currency_iso_code})</div>
</div>

{l s='Precis are different in each country/currency. The prices below are shown in your local currency. In addition, any extra discounts available to your class of customers have been applied. Thease are the final prices in your order. Please inspect them carefully before proceeding.' mod='orderpriceadjustment'}

<form action="{$base_dir_ssl}order.php" method="post">
	<p>
		{l s='Your shopping cart contains' mod='orderpriceadjustment'} {$products|@count} {if $products|@count > 1}{l s='products' mod='orderpriceadjustment'}{else}{l s='product' mod='orderpriceadjustment'}{/if}
	</p>
	<div id="order-detail-content" class="table_block">
		<table id="cart_summary" class="std">
			<thead>
				<tr>
					<th class="cart_product first_item">{l s='Product' mod='orderpriceadjustment'}</th>
					<th class="cart_description item">{l s='Description' mod='orderpriceadjustment'}</th>
					<th class="cart_ref item">{l s='Ref.' mod='orderpriceadjustment'}</th>
					<th class="cart_availability item">{l s='Avail.' mod='orderpriceadjustment'}</th>
					<th class="cart_unit item">{l s='Unit price' mod='orderpriceadjustment'}</th>
					<th class="cart_quantity item">{l s='Qty' mod='orderpriceadjustment'}</th>
					<th class="cart_total last_item">{l s='Total' mod='orderpriceadjustment'}</th>
				</tr>
			</thead>
			<tfoot>
				{if $priceDisplay}
					<tr class="cart_total_price">
						<td colspan="6">{l s='Total products (tax excl.):' mod='orderpriceadjustment'}</td>
						<td class="price">{convertPrice price=$total_products}</td>
					</tr>
				{/if}
				{if !$priceDisplay || $priceDisplay == 2}
					<tr class="cart_total_price">
						<td colspan="6">{l s='Total products (tax incl.):' mod='orderpriceadjustment'}</td>
						<td class="price">{convertPrice price=$total_products_wt}</td>
					</tr>
				{/if}
				{if $total_discounts != 0}
					{if $priceDisplay}
						<tr class="cart_total_voucher">
							<td colspan="6">{l s='Total vouchers (tax excl.):' mod='orderpriceadjustment'}</td>
							<td class="price-discount">{convertPrice price=$total_discounts_tax_exc}</td>
						</tr>
					{/if}
					{if !$priceDisplay || $priceDisplay == 2}
						<tr class="cart_total_voucher">
							<td colspan="6">{l s='Total vouchers (tax incl.):' mod='orderpriceadjustment'}</td>
							<td class="price-discount">{convertPrice price=$total_discounts}</td>
						</tr>
					{/if}
				{/if}
				{if $total_wrapping > 0}
					{if $priceDisplay}
						<tr class="cart_total_voucher">
							<td colspan="6">{l s='Total gift-wrapping (tax excl.):' mod='orderpriceadjustment'}</td>
							<td class="price-discount">{convertPrice price=$total_wrapping_tax_exc}</td>
						</tr>
					{/if}
					{if !$priceDisplay || $priceDisplay == 2}
						<tr class="cart_total_voucher">
							<td colspan="6">{l s='Total gift-wrapping (tax incl.):' mod='orderpriceadjustment'}</td>
							<td class="price-discount">{convertPrice price=$total_wrapping}</td>
						</tr>
					{/if}
				{/if}
				{if $shippingCost > 0}
					{if $priceDisplay}
						<tr class="cart_total_delivery">
							<td colspan="6">{l s='Total shipping (tax excl.):' mod='orderpriceadjustment'}</td>
							<td class="price">{convertPrice price=$shippingCostTaxExc}</td>
						</tr>
					{/if}
					{if !$priceDisplay || $priceDisplay == 2}
						<tr class="cart_total_delivery">
							<td colspan="6">{l s='Total shipping (tax incl.):' mod='orderpriceadjustment'}</td>
							<td class="price">{convertPrice price=$shippingCost}</td>
						</tr>
					{/if}
				{/if}
				{if $priceDisplay}
					<tr class="cart_total_price">
						<td colspan="6">{l s='Total (tax excl.):' mod='orderpriceadjustment'}</td>
						<td class="price">{convertPrice price=$total_price_without_tax}</td>
					</tr>
					<tr class="cart_total_voucher">
						<td colspan="6">{l s='Total tax:' mod='orderpriceadjustment'}</td>
						<td class="price">{convertPrice price=$total_tax}</td>
					</tr>
				{/if}
				<tr class="cart_total_price">
					<td colspan="6">{l s='Total (tax incl.):' mod='orderpriceadjustment'}</td>
					<td class="price">{convertPrice price=$total_price}</td>
				</tr>
			</tfoot>
			<tbody>
			{foreach from=$products item=product name=productLoop}
				{assign var='productId' value=$product.id_product}
				{assign var='productAttributeId' value=$product.id_product_attribute}
				{assign var='quantityDisplayed' value=0}
				{* Display the product line *}
				{include file=$mdl_dir./module-product-line.tpl}
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
											{foreach from=$datas item='textField' name='typedText'}<li>{l s='Text #' mod='orderpriceadjustment'}{$smarty.foreach.typedText.index+1}{l s=':' mod='orderpriceadjustment'} {$textField.value}</li>{/foreach}
										</ul>
									{/if}
								{/foreach}
							</td>
							<td class="cart_quantity">
								<p>{$customization.quantity}</p>
							</td>
							<td class="cart_total"></td>
						</tr>
						{assign var='quantityDisplayed' value=$quantityDisplayed+$customization.quantity}
					{/foreach}
					{* If it exists also some uncustomized products *}
					{if $product.quantity-$quantityDisplayed > 0}{include file=$mdl_dir./module-product-line.tpl}{/if}
				{/if}
			{/foreach}
			</tbody>
		{if $discounts}
			<tbody>
			{foreach from=$discounts item=discount name=discountLoop}
				<tr class="cart_discount {if $smarty.foreach.discountLoop.last}last_item{elseif $smarty.foreach.discountLoop.first}first_item{else}item{/if}">
					<td class="cart_discount_name" colspan="2">{$discount.name}</td>
					<td class="cart_discount_description" colspan="3">{$discount.description}</td>
					<td class="cart_discount_delete"><a href="{$base_dir_ssl}order.php?step={$order_step}&process{$order_step}=1&deleteDiscount={$discount.id_discount}" title="{l s='Delete' mod='orderpriceadjustment'}"><img src="{$img_dir}icon/delete.gif" alt="{l s='Delete' mod='orderpriceadjustment'}" class="icon" /></a></td>
					<td class="cart_discount_price"><span class="price-discount">
						{if $discount.value_real > 0}
							{if !$priceDisplay || $priceDisplay == 2}{convertPrice price=$discount.value_real*-1}{if $priceDisplay == 2} {l s='+Tx' mod='orderpriceadjustment'}<br />{/if}{/if}
							{if $priceDisplay}{convertPrice price=$discount.value_tax_exc*-1}{if $priceDisplay == 2} {l s='-Tx' mod='orderpriceadjustment'}{/if}{/if}
						{/if}
					</span></td>
				</tr>
			{/foreach}
			</tbody>
		{/if}
		</table>
	</div>

	<p class="cart_navigation submit">
		<input type="hidden" class="hidden" name="step" value="{$order_step}" />
		<input type="hidden" name="back" value="{$back}" />
		<a href="{$base_dir_ssl}order.php?step={$order_step-1}{if $back}&back={$back}{/if}" title="{l s='Previous' mod='orderpriceadjustment'}" class="button">&laquo; {l s='Previous' mod='orderpriceadjustment'}</a>
		<input type="submit" name="process{$order_step}" value="{l s='Next' mod='orderpriceadjustment'} &raquo;" class="exclusive" />
	</p>
</form>
{/variablebox}
