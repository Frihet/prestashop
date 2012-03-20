<p class="payment_module">
	<a href="javascript:$('#netaxept_form').submit();" title="{l s='Pay with Netaxept' mod='netaxept'}">
		<img src="{$module_template_dir}netaxept.gif" alt="{l s='Pay with Netaxept' mod='netaxept'}">
		{l s='Pay with Netaxept' mod='netaxept'}
	</a>
</p>

<form action="{$netaxept_url}" method="post" id="netaxept_form" class="hidden">
	{$setup_result}
</form>