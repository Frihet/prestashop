<div id="category_tabs" class="toptabs">
	<ul class="idTabs idTabsShort">
	{foreach from=$blockCategTree.children item=child name=blockCategTree}
		{assign var=last value=$smarty.foreach.blockCategTree.last}
		<li {if isset($last) && $last == 'true'}class="last"{/if}>
			<a
			 href="{$child.link|escape:htmlall:'UTF-8'}"
			 {if isset($currentCategoryId) && in_array($child.id, $currentCategoryPath)}class="selected"{/if}
			 title="{$child.desc|escape:htmlall:'UTF-8'}">
				{$child.name|escape:htmlall:'UTF-8'}
			</a>
		</li>
	{/foreach}
	</ul>
</div>
