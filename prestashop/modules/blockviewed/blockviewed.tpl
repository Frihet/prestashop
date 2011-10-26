<!-- Block Viewed products -->
{if $productsViewedObj}
	<div id="viewed-products_block_left" class="block products_block">
		<h4>{l s='Viewed products' mod='blockviewed'}</h4>
		<div class="block_content">
			<ul class="products">
			{foreach from=$productsViewedObj item=viewedProduct name=myLoop}
				<li class="{if $smarty.foreach.myLoop.last}last_item{elseif $smarty.foreach.myLoop.first}first_item{else}item{/if}">
					<a href="{$link->getProductLink($viewedProduct)}" title="{l s='More about' mod='blockviewed'} {$viewedProduct->name|escape:htmlall:'UTF-8'}"><img src="{$link->getImageLink($viewedProduct->link_rewrite, $viewedProduct->cover, 'medium')}" height="{$mediumSize.height}" width="{$mediumSize.width}" alt="{$viewedProduct->legend|escape:htmlall:'UTF-8'}" /></a>
					<h5><a href="{$link->getProductLink($viewedProduct)}" title="{l s='More about' mod='blockviewed'} {$viewedProduct->name|escape:htmlall:'UTF-8'}">{$viewedProduct->name|escape:htmlall:'UTF-8'|truncate:25}</a></h5>
					<p>{m s=$viewedProduct->description_short|strip_tags:'UTF-8'|truncate:44 n=12}<a href="{$link->getProductLink($viewedProduct)}" title="{l s='More about' mod='blockviewed'} {$viewedProduct->name|escape:htmlall:'UTF-8'}"><img src="{$img_dir}bullet.gif" alt="&gt;&gt;"  /></a></p>
				</li>
			{/foreach}
			</ul>
		</div>
	</div>
{/if}

{if $articlesViewedObj}
	<div id="viewed-products_block_left" class="block products_block">
		<h4>{l s='Viewed articles' mod='blockviewed'}</h4>
		<div class="block_content">
			<ul class="products">
			{foreach from=$articlesViewedObj item=viewedArticle name=myLoop}
				<li class="{if $smarty.foreach.myLoop.last}last_item{elseif $smarty.foreach.myLoop.first}first_item{else}item{/if}">
					<a href="{$link->getProductLink($viewedArticle)}" title="{l s='More about' mod='blockviewed'} {$viewedArticle->name|escape:htmlall:'UTF-8'}"><img src="{$link->getImageLink($viewedArticle->link_rewrite, $viewedArticle->cover, 'medium')}" height="{$mediumSize.height}" width="{$mediumSize.width}" alt="{$viewedArticle->legend|escape:htmlall:'UTF-8'}" /></a>
					<h5><a href="{$link->getProductLink($viewedArticle)}" title="{l s='More about' mod='blockviewed'} {$viewedArticle->name|escape:htmlall:'UTF-8'}">{$viewedArticle->name|escape:htmlall:'UTF-8'|truncate:25}</a></h5>
					<p>{m s=$viewedArticle->description_short|strip_tags:'UTF-8'|truncate:44 n=12}<a href="{$link->getProductLink($viewedArticle)}" title="{l s='More about' mod='blockviewed'} {$viewedArticle->name|escape:htmlall:'UTF-8'}"><img src="{$img_dir}bullet.gif" alt="&gt;&gt;"  /></a></p>
				</li>
			{/foreach}
			</ul>
		</div>
	</div>
{/if}

<!--
{l s='Buy' mod='blockviewed'}
{l s='Add to cart' mod='blockviewed'}
-->


