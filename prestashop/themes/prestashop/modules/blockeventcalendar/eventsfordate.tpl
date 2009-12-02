{capture name=path}{l s='Search'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

{variablebox}
<h2>{l s='Events for '}{$event_date}</h2>
{variablebox_content}

{include file=$tpl_dir./errors.tpl}

{if !$nbProducts}
	<p class="warning">
		{if $event_date}
			{l s='No results found for '}{$event_date}
		{else}
			{l s='Please select a date'}
		{/if}
	</p>
{else}
	<h3><span class="big">{$nbProducts|intval}</span>&nbsp;{if $nbProducts == 1}{l s='result has been found.'}{else}{l s='results have been found.'}{/if}</h3>
	{include file=$tpl_dir./product-list.tpl products=$products}
	{include file=$tpl_dir./pagination.tpl}
{/if}
{/variablebox}
