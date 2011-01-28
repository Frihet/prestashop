<script type="text/javascript">
<!--
	var baseDir = '{$base_dir_ssl}';
-->
</script>

{capture name=path}{l s='Your shopping cart' mod='ordervendor'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

{variablebox class="universal"}
<h2>{l s='Choose vendors for each product' mod='ordervendor'}</h2>
{variablebox_content}

{include file=$tpl_dir./module-order-steps.tpl}

{include file=$tpl_dir./errors.tpl}

<form action="{$base_dir_ssl}order.php" method="post">
	<p>{l s='Please select a vendor within your area for rapair and support purposes for each product. Note that not all vendors do not support all products.' mod='ordervendor'}</p>
	<div id="order-detail-content" class="table_block">
		<table id="cart_summary" class="std">
			<thead>
				<tr>
					<th class="cart_product first_item">{l s='Product' mod='ordervendor'}</th>
					<th class="cart_description item">{l s='Description' mod='ordervendor'}</th>
					<th class="cart_ref item">{l s='Ref.' mod='ordervendor'}</th>
					<th class="cart_vendor last_item">{l s='Vendor' mod='ordervendor'}</th>
				</tr>
			</thead>
			<tbody>
			{foreach from=$product_lines item=product name=productLoop}
				<tr class="{if $smarty.foreach.productLoop.last}last_item{elseif $smarty.foreach.productLoop.first}first_item{/if} cart_item">
					<td class="cart_product">
						<a href="{$link->getProductLink($product.id_product, $product.link_rewrite, $product.category)|escape:'htmlall':'UTF-8'}"><img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'small')}" alt="{$product.name|escape:'htmlall':'UTF-8'}" /></a>
					</td>
					<td class="cart_description">
						<h5><a href="{$link->getProductLink($product.id_product, $product.link_rewrite, $product.category)|escape:'htmlall':'UTF-8'}">{$product.name|escape:'htmlall':'UTF-8'}</a></h5>
						{if $product.attributes}<a href="{$link->getProductLink($product.id_product, $product.link_rewrite, $product.category)|escape:'htmlall':'UTF-8'}">{$product.attributes|escape:'htmlall':'UTF-8'}</a>{/if}
					</td>
					<td class="cart_ref">{if $product.reference}{$product.reference|escape:'htmlall':'UTF-8'}{else}--{/if}</td>
                                        <td>
						<select name="vendor{$product.id_product}">
							{foreach from=$product.vendors item=vendor name=vendorLoop}
							        <option value="{$vendor.id_vendor}">{$vendor.title}{$vendor.postcode}</option>
							{/foreach}
						</select>
                                        </td>
                                </tr>
			{/foreach}
			</tbody>
		</table>
	</div>

	<p class="cart_navigation submit">
		<input type="hidden" class="hidden" name="step" value="{$order_step}" />
		<input type="hidden" name="back" value="{$back}" />
		<a href="{$base_dir_ssl}order.php?step={$order_step-1}{if $back}&back={$back}{/if}" title="{l s='Previous' mod='ordervendor'}" class="button">&laquo; {l s='Previous' mod='ordervendor'}</a>
		<input type="submit" name="process{$order_step}" value="{l s='Next' mod='ordervendor'} &raquo;" class="exclusive" />
	</p>
</form>
{/variablebox}
