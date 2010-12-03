{* $order_steps => Array of step titles *}
{* $order_step => index of current step *}
<!-- Steps -->
<ul class="step" id="order_step">
	{foreach from=$order_steps key=index item=title}
		<li
                 {if $index == count($order_steps) - 1}id="step_end"{/if}
                 class="{if $index==$order_step}step_current{else}{if $index < $order_step}step_done{else}step_todo{/if}{/if}"
                >
			{if $index < $order_step}
				<a href="{$base_dir_ssl}order.php?step={$index}">
					{l s=$title}
				</a>
			{else}
			        {l s=$title}
			{/if}
		</li>
	{/foreach}
</ul>
<div class="clear"></div>
<!-- /Steps -->
