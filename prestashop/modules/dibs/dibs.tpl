<p class="payment_module">
	<a href="javascript:$('#dibs_form').submit();" title="{l s='Pay with Dibs' mod='dibs'}">
		<img src="{$module_template_dir}dibs.jpg" style="float:left" alt="{l s='Pay with Dibs' mod='dibs'}" />
		<strong>{l s='Pay with Dibs' mod='dibs'}</strong><br />{l s='Use Dibs to pay with Visa, Mastercard or Bank transfer' mod='dibs'}<br style="clear:both;" />
	</a>
</p>

<form action="{$dibsUrl}" method="post" id="dibs_form" class="hidden">

<input type="hidden" name="merchant" value="{$merchant}" />
{if ($dibstest)=='1'}<input type="hidden" name="test" value="true" />{/if}
<input type="hidden" name="amount" value="{$ordertotal}" />
<input type="hidden" name="currency" value="{$currencyString}" />
<input type="hidden" name="orderid" value="{$id_cart}" />
<input type="hidden" name="md5key" value="{$md5key}" />
<input type="hidden" name="accepturl" value="{$accepturl}" />
<input type="hidden" name="callbackurl" value="{$callbackurl}" />
<input type="hidden" name="cancelurl" value="{$accepturl}" />
<input type="hidden" name="lang" value="{$windowlanguage}" />
<input type="hidden" name="priceinfo1.shippingmethod" value="{$shippingMethod}" />
<input type="hidden" name="priceinfo1.shippingcosts" value="{$shipping}" />
<input type="hidden" name="delivery01" value="{$address->firstname}" />
<input type="hidden" name="delivery02" value="{$address->lastname}" />
<input type="hidden" name="delivery03" value="{$address->address1}" />
{if !empty($address->address2)}<input type="hidden" name="delivery04" value="{$address->address2}" />{/if}
	<input type="hidden" name="delivery05" value="{$address->city}" />
	<input type="hidden" name="delivery06" value="{$address->postcode}" />
	<input type="hidden" name="delivery07" value="{$country->iso_code}" />
	<input type="hidden" name="delivery08" value="{$customer->email}" />
	
{foreach from=$products item=product}
	<input type="hidden" name="ordline{$i}-1" value="{$product.name}{if isset($product.attributes)} - {$product.attributes}{/if}" />
	<input type="hidden" name="ordline{$i}-2" value="{$product.ProductAmount}" />
	<input type="hidden" name="ordline{$i}-3" value="{$product.quantity}" />
	{counter print=false}
	{/foreach}



</form>