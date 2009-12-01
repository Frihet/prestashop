{include file=$tpl_dir./breadcrumb.tpl} 
{include file=$tpl_dir./errors.tpl}

<table class="centre_column_content variablebox"><tr><td class="variablebox_top_left"></td><td class="variablebox_top_center"></td><td class="variablebox_top_right"></td></tr><tr><td class="variablebox_center_left"></td><td class="variablebox_center_center">
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
				<div class="block">
					<h4>{l s="Current products"}</h4>
					<div class="products_block">
					        {include file=$tpl_dir./product-list-category.tpl products=$products}
					</div>
				</div>
			{/if}
		{elseif !isset($subcategories)}
			<p class="warning">{l s='There is no product in this category.'}</p>
		{/if}
	{elseif $category->id}
		<p class="warning">{l s='This category is currently unavailable.'}</p>
	{/if}
</td><td class="variablebox_center_right"></td></tr><tr><td class="variablebox_bottom_left"></td><td class="variablebox_bottom_center"></td><td class="variablebox_bottom_right"></td></tr></table>

