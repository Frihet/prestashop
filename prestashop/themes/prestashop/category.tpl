{include file=$tpl_dir./breadcrumb.tpl} 
{include file=$tpl_dir./errors.tpl}

{if $category->id AND $category->active}
        {if $path|regex_replace:"/.*a href.*/":"" == ""}
		<h2 class="category_title">
			{$category->name|escape:'htmlall':'UTF-8'}
			<span>{$nb_products|intval}&nbsp;{if $nb_products>1}{l s='products'}{else}{l s='product'}{/if}</span>
		</h2>
	{/if}

	{if $scenes}
		<!-- Scenes -->
		{include file=$tpl_dir./scenes.tpl scenes=$scenes}
	{else}
		<!-- Category image -->
		{if $category->id_image}
			<img src="{$link->getCatImageLink($category->link_rewrite, $category->id_image, 'category')}" alt="{$category->name|escape:'htmlall':'UTF-8'}" title="{$category->name|escape:'htmlall':'UTF-8'}" id="categoryImage" />
		{/if}
	{/if}

	{if $category->description}
		<div class="cat_desc">{$category->description}</div>
	{/if}

	{if $path|regex_replace:"/.*a href.*/":"" == ""}
		{if isset($subcategories)}
			<!-- Subcategories -->
			<div id="subcategories">
				<h3>{l s='Subcategories'}</h3>
				<ul class="inline_list">
				{foreach from=$subcategories item=subcategory}
					<li>
						<a href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'htmlall':'UTF-8'}" title="{$subcategory.name|escape:'htmlall':'UTF-8'}">
							{if $subcategory.id_image}
								<img src="{$link->getCatImageLink($subcategory.link_rewrite, $subcategory.id_image, 'medium')}" alt="" />
							{else}
								<img src="{$img_cat_dir}default-medium.jpg" alt="" />
							{/if}
						</a>
						<br />
						<a href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'htmlall':'UTF-8'}">{$subcategory.name|escape:'htmlall':'UTF-8'}</a>
					</li>
				{/foreach}
				</ul>
				<br class="clear"/>
			</div>
		{/if}
	{/if}

	{if $products}
		{if $path|regex_replace:"/.*a href.*/":"" == ""}
			{include file=$tpl_dir./product-sort.tpl}
			{include file=$tpl_dir./product-list.tpl products=$products}
			{include file=$tpl_dir./pagination.tpl}
		{else}
			{assign var='liHeight' value=331}
			{assign var='nbItemsPerLine' value=4}
			{assign var='nbLi' value=$products|@count}
			{assign var='nbLines' value=$nbLi/$nbItemsPerLine|ceil}
			{assign var='ulHeight' value=$nbLines*$liHeight}
			<div class="block">
				<h4>{l s="Current products"}</h4>
				<div class="products_block">
					<ul style="height:{$ulHeight}px;">
						{foreach from=$products item=product name=homeFeaturedProducts}
							<li class="ajax_block_product {if $smarty.foreach.homeFeaturedProducts.first}first_item{elseif $smarty.foreach.homeFeaturedProducts.last}last_item{else}item{/if} {if $smarty.foreach.homeFeaturedProducts.iteration%$nbItemsPerLine == 0}last_item_of_line{elseif $smarty.foreach.homeFeaturedProducts.iteration%$nbItemsPerLine == 1}first_item_of_line{/if} {if $smarty.foreach.homeFeaturedProducts.iteration > ($smarty.foreach.homeFeaturedProducts.total - ($smarty.foreach.homeFeaturedProducts.total % $nbItemsPerLine))}last_line{/if}">
								<h5><a href="{$product.link}" title="{$product.name|truncate:32:'...'|escape:'htmlall':'UTF-8'}">{$product.name|truncate:27:'...'|escape:'htmlall':'UTF-8'}</a></h5>
								<p class="product_desc"><a href="{$product.link}" title="{l s='More' mod='homefeatured'}">{$product.description_short|strip_tags|truncate:130:'...'}</a></p>
								<a href="{$product.link}" title="{$product.legend|escape:htmlall:'UTF-8'}" class="product_image"><img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home')}" height="{$homeSize.height}" width="{$homeSize.width}" alt="{$product.legend|escape:htmlall:'UTF-8'}" /></a>
								<div>
									{if !$priceDisplay || $priceDisplay == 2}<p class="price_container"><span class="price">{convertPrice price=$product.price}</span>{if $priceDisplay == 2} {l s='+Tx' mod='homefeatured'}{/if}</p>{/if}
									{if $priceDisplay}<p class="price_container"><span class="price">{convertPrice price=$product.price_tax_exc}</span>{if $priceDisplay == 2} {l s='-Tx' mod='homefeatured'}{/if}</p>{/if}
									<a class="button" href="{$product.link}" title="{l s='View' mod='homefeatured'}">{l s='View' mod='homefeatured'}</a>
									{if ($product.quantity > 0 OR $product.allow_oosp) AND $product.customizable != 2}
									<a class="exclusive ajax_add_to_cart_button" rel="ajax_id_product_{$product.id_product}" href="{$base_dir}cart.php?qty=1&amp;id_product={$product.id_product}&amp;token={$static_token}&amp;add" title="{l s='Add to cart' mod='homefeatured'}">{l s='Add to cart' mod='homefeatured'}</a>
									{else}
									<span class="exclusive">{l s='Add to cart' mod='homefeatured'}</span>
									{/if}
								</div>
							</li>
						{/foreach}
					</ul>
				</div>
			</div>
		{/if}
	{elseif !isset($subcategories)}
		<p class="warning">{l s='There is no product in this category.'}</p>
	{/if}
{elseif $category->id}
	<p class="warning">{l s='This category is currently unavailable.'}</p>
{/if}
