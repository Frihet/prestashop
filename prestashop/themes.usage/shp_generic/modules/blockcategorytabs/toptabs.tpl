<div id="category_tabs" class="toptabs">
     	<div class="tabHead">{l s='Also visit these pages:' mod='blockcategorytabs'}</div>
	<ul class="idTabs idTabsShort">
	{foreach from=$blockCategTree.children item=child name=blockCategTree}
		{assign var=last value=$smarty.foreach.blockCategTree.last}

		{if isset($currentCategoryId) && in_array($child.id, $currentCategoryPath)}
		{assign var=currentCategory value=$child}
		{/if}

		<li {if isset($last) && $last == 'true'}class="last"{/if}>
			<a
			 href="{$child.link|escape:htmlall:'UTF-8'}"
			 {if isset($currentCategoryId) && in_array($child.id, $currentCategoryPath)}class="selected"{/if}
			 title="{$child.desc|strip_tags}">
				{$child.name|escape:htmlall:'UTF-8'}
			</a>
		</li>
	{/foreach}
	</ul>
</div>

{if isset($currentCategory)}
    <h1 id="logo"><a href="{$currentCategory.link|escape:htmlall:'UTF-8'}" title="{$currentCategory.desc|strip_tags}"></a></h1>
{else}
    <h1 id="logo"><a href="{$base_dir}" title="{$shop_name|strip_tags}"></a></h1>
{/if}
