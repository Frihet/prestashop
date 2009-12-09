<script type="text/javascript">
<!--
	var baseDir = '{$base_dir_ssl}';
-->
</script>

{capture name=path}{l s='Your shopping cart'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

{variablebox class="universal"}
<h2>{l s='Shopping cart summary'}</h2>
{variablebox_content}

{include file=$tpl_dir./module-order-steps.tpl}

{include file=$tpl_dir./errors.tpl}

{if isset($empty)}
	<p class="warning">{l s='Your shopping cart is empty.'}</p>

{else}
<p>
	{l s='Your shopping cart contains'} {$products|@count} {if $products|@count > 1}{l s='products'}{else}{l s='product'}{/if}
</p>
<div id="order-detail-content" class="table_block">
	<table id="cart_summary" class="std">

	        {if !isset($empty) AND isset($lastProductAdded) AND $lastProductAdded}
			<thead>
				<tr>
					<th class="cart_product first_item" colspan="7">{l s='Last added product'}</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$products item=product}
					{if $product.id_product == $lastProductAdded.id_product AND (!$product.id_product_attribute OR ($product.id_product_attribute == $lastProductAdded.id_product_attribute))}
						<tr>
							<td class="cart_product">
								<a href="{$link->getProductLink($product.id_product, $product.link_rewrite, $product.category)|escape:'htmlall':'UTF-8'}"><img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'small')}" alt="{$product.name|escape:'htmlall':'UTF-8'}" /></a>
							</td>
							<td class="cart_description">
								<h5><a href="{$link->getProductLink($product.id_product, $product.link_rewrite, $product.category)|escape:'htmlall':'UTF-8'}">{$product.name|escape:'htmlall':'UTF-8'}</a></h5>
								{if $product.attributes}<a href="{$link->getProductLink($product.id_product, $product.link_rewrite, $product.category)|escape:'htmlall':'UTF-8'}">{$product.attributes|escape:'htmlall':'UTF-8'}</a>{/if}
							</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					{/if}
				{/foreach}
			</tbody>
		{/if}

		<thead>
			<tr>
				<th class="cart_product first_item">{l s='Product'}</th>
				<th class="cart_description item">{l s='Description'}</th>
				<th class="cart_ref item">{l s='Ref.'}</th>
				<th class="cart_availability item">{l s='Avail.'}</th>
				<th class="cart_unit item">{l s='Unit price'}</th>
				<th class="cart_quantity item">{l s='Qty'}</th>
				<th class="cart_total last_item">{l s='Total'}</th>
			</tr>
		</thead>
		<tfoot>
			{if $priceDisplay}
				<tr class="cart_total_price">
					<td colspan="6">{l s='Total products (tax excl.):'}</td>
					<td class="price">{convertPrice price=$total_products}</td>
				</tr>
			{/if}
			{if !$priceDisplay || $priceDisplay == 2}
				<tr class="cart_total_price">
					<td colspan="6">{l s='Total products (tax incl.):'}</td>
					<td class="price">{convertPrice price=$total_products_wt}</td>
				</tr>
			{/if}
			{if $total_discounts != 0}
				{if $priceDisplay}
					<tr class="cart_total_voucher">
						<td colspan="6">{l s='Total vouchers (tax excl.):'}</td>
						<td class="price-discount">{convertPrice price=$total_discounts_tax_exc}</td>
					</tr>
				{/if}
				{if !$priceDisplay || $priceDisplay == 2}
					<tr class="cart_total_voucher">
						<td colspan="6">{l s='Total vouchers (tax incl.):'}</td>
						<td class="price-discount">{convertPrice price=$total_discounts}</td>
					</tr>
				{/if}
			{/if}
			{if $total_wrapping > 0}
				{if $priceDisplay}
					<tr class="cart_total_voucher">
						<td colspan="6">{l s='Total gift-wrapping (tax excl.):'}</td>
						<td class="price-discount">{convertPrice price=$total_wrapping_tax_exc}</td>
					</tr>
				{/if}
				{if !$priceDisplay || $priceDisplay == 2}
					<tr class="cart_total_voucher">
						<td colspan="6">{l s='Total gift-wrapping (tax incl.):'}</td>
						<td class="price-discount">{convertPrice price=$total_wrapping}</td>
					</tr>
				{/if}
			{/if}
			{if $shippingCost > 0}
				{if $priceDisplay}
					<tr class="cart_total_delivery">
						<td colspan="6">{l s='Total shipping (tax excl.):'}</td>
						<td class="price">{convertPrice price=$shippingCostTaxExc}</td>
					</tr>
				{/if}
				{if !$priceDisplay || $priceDisplay == 2}
					<tr class="cart_total_delivery">
						<td colspan="6">{l s='Total shipping (tax incl.):'}</td>
						<td class="price">{convertPrice price=$shippingCost}</td>
					</tr>
				{/if}
			{/if}
			{if $priceDisplay}
				<tr class="cart_total_price">
					<td colspan="6">{l s='Total (tax excl.):'}</td>
					<td class="price">{convertPrice price=$total_price_without_tax}</td>
				</tr>
				<tr class="cart_total_voucher">
					<td colspan="6">{l s='Total tax:'}</td>
					<td class="price">{convertPrice price=$total_tax}</td>
				</tr>
			{/if}
			<tr class="cart_total_price">
				<td colspan="6">{l s='Total (tax incl.):'}</td>
				<td class="price">{convertPrice price=$total_price}</td>
			</tr>
			{if $free_ship > 0}
			<tr class="cart_free_shipping">
				<td colspan="6" style="white-space: normal;">{l s='Remaining amount to be added to your cart in order to obtain free shipping:'}</td>
				<td class="price">{convertPrice price=$free_ship}</td>
			</tr>
			{/if}
		</tfoot>
		<tbody>
		{foreach from=$products item=product name=productLoop}
			{assign var='productId' value=$product.id_product}
			{assign var='productAttributeId' value=$product.id_product_attribute}
			{assign var='quantityDisplayed' value=0}
			{cycle values="odd_item,even_item" assign=item_oddity name=item_oddity}
			{* Display the product line *}
			{include file=$tpl_dir./shopping-cart-product-line.tpl}
			{* Then the customized datas ones*}
			{if isset($customizedDatas.$productId.$productAttributeId)}
				{foreach from=$customizedDatas.$productId.$productAttributeId key='id_customization' item='customization'}
					<tr class="{$item_oddity} cart_item">
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
							<a class="cart_quantity_delete" href="{$base_dir_ssl}cart.php?delete&amp;id_product={$product.id_product|intval}&amp;ipa={$product.id_product_attribute|intval}&amp;id_customization={$id_customization}&amp;token={$token_cart}"><img src="{$img_dir}icon/delete.gif" alt="{l s='Delete'}" title="{l s='Delete this customization'}" class="icon" /></a>
							<p>{$customization.quantity}</p>
							<a class="cart_quantity_up" href="{$base_dir_ssl}cart.php?add&amp;id_product={$product.id_product|intval}&amp;ipa={$product.id_product_attribute|intval}&amp;id_customization={$id_customization}&amp;token={$token_cart}" title="{l s='Add'}"><img src="{$img_dir}icon/quantity_up.gif" alt="{l s='Add'}" /></a><br />
							<a class="cart_quantity_down" href="{$base_dir_ssl}cart.php?add&amp;id_product={$product.id_product|intval}&amp;ipa={$product.id_product_attribute|intval}&amp;id_customization={$id_customization}&amp;op=down&amp;token={$token_cart}" title="{l s='Substract'}"><img src="{$img_dir}icon/quantity_down.gif" alt="{l s='Substract'}" /></a>
						</td>
						<td class="cart_total"></td>
					</tr>
					{assign var='quantityDisplayed' value=$quantityDisplayed+$customization.quantity}
				{/foreach}
				{* If it exists also some uncustomized products *}
				{if $product.quantity-$quantityDisplayed > 0}{include file=$tpl_dir./shopping-cart-product-line.tpl}{/if}
			{/if}
		{/foreach}
		</tbody>
	{if $discounts}
		<tbody>
		{foreach from=$discounts item=discount name=discountLoop}
			{cycle values="odd_item,even_item" assign=item_oddity name=item_oddity}
			<tr class="cart_discount {if $smarty.foreach.discountLoop.last}last_item{elseif $smarty.foreach.discountLoop.first}first_item{else}item{/if} {$item_oddity}">
				<td class="cart_discount_name" colspan="2">{$discount.name}</td>
				<td class="cart_discount_description" colspan="3">{$discount.description}</td>
				<td class="cart_discount_delete"><a href="{$base_dir_ssl}order.php?step={$order_step}&process{$order_step}=1&deleteDiscount={$discount.id_discount}" title="{l s='Delete'}"><img src="{$img_dir}icon/delete.gif" alt="{l s='Delete'}" class="icon" /></a></td>
				<td class="cart_discount_price"><span class="price-discount">
					{if $discount.value_real > 0}
						{if !$priceDisplay || $priceDisplay == 2}{convertPrice price=$discount.value_real*-1}{if $priceDisplay == 2} {l s='+Tx'}<br />{/if}{/if}
						{if $priceDisplay}{convertPrice price=$discount.value_tax_exc*-1}{if $priceDisplay == 2} {l s='-Tx'}{/if}{/if}
					{/if}
				</span></td>
			</tr>
		{/foreach}
		</tbody>
	{/if}
	</table>
</div>

{if $voucherAllowed}
<div id="cart_voucher" class="table_block">
	{if $errors_discount}
		<ul class="error">
		{foreach from=$errors_discount key=k item=error}
			<li>{$error|escape:'htmlall':'UTF-8'}</li>
		{/foreach}
		</ul>
	{/if}
	<form action="{$base_dir_ssl}order.php" method="post" id="voucher">
		<input type="hidden" class="hidden" name="step" value="{$order_step}" />
		<input type="hidden" class="hidden" name="addDiscount" value="1" />
		<fieldset>
			<h4>{l s='Vouchers'}</h4>
			<p>
				<label for="discount_name">{l s='Code:'}</label>
				<input type="text" id="discount_name" name="discount_name" value="{if $discount_name}{$discount_name}{/if}" />
			</p>
			<p class="submit"><input type="submit" name="process{$order_step}" value="{l s='Add'}" class="button" /></p>
		</fieldset>
	</form>
</div>
{/if}
{$HOOK_SHOPPING_CART}
<p class="cart_navigation">
	<a href="{$base_dir_ssl}order.php?step={$order_step+1}" class="exclusive" title="{l s='Next'}">{l s='Next'} &raquo;</a>
	<a href="{if $smarty.server.HTTP_REFERER && strstr($smarty.server.HTTP_REFERER, 'order.php')}{$base_dir}index.php{else}{$smarty.server.HTTP_REFERER|escape:'htmlall':'UTF-8'}{/if}" class="button_large" title="{l s='Continue shopping'}">&laquo; {l s='Continue shopping'}</a>
	{$HOOK_SHOPPING_CART_EXTRA}
</p>
{/if}
{/variablebox}
