<link href="./modules/productsalsobuy/productsalsobuy.css" rel="stylesheet" type="text/css" media="all" />
<br /><br />
<div id="also_bought" class="block also_block">
			<div class="block_content">
<h4>{l s='Customers who bought this product also bought.....' mod='productsalsobuy'}</h4>
<ul style="height: 80px;">

		{foreach from=$product item='product' name=product}
						{assign var='productLink' value=$link->getProductLink($product.id_product, $product.link_rewrite)}
<li class="ajax_block_product item">
		<ul class="product_images">
			<a href="{$link->getProductLink($product.id_product, $product.link_rewrite, $product.category)}" title="{$product.name|htmlspecialchars}">
				<img src="{$img_prod_dir}{$product.id_product}-{$product.id_image}-home.jpg" alt="{$product.name|htmlspecialchars}" />
			</ul></a><br/>
	
			<h5><a href="{$link->getProductLink($product.id_product, $product.link_rewrite, $product.category)}" title="{$product.name|htmlspecialchars}">
			{$product.name|truncate:40}</h5>
			</a>
								<p>

				<span class="price">{displayWtPrice p=$product.price}</span>								
				<a class="button" href="{$productLink}" title="{l s='View' mod='productsalsobuy'}">{l s='View' mod='productsalsobuy'}</a>
					
							
							{if $allow_buy_when_out_of_stock OR ($product.quantity AND $product.quantity > 0) OR $product.allow_oosp}
								<a class="exclusive ajax_add_to_cart_button" rel="ajax_id_product_{$product.id_product}" href="{$base_dir}cart.php?qty=1&amp;id_product={$product.id_product}&amp;token={$static_token}&amp;add" title="{l s='Add to cart' mod='productsalsobuy'}">{l s='Add to cart' mod='productsalsobuy'}</a>
							{else}
								<span class="exclusive">{l s='Add to cart' mod='homefeatured'}</span>
							{/if}
						
			
</p>

		</li>
		{/foreach}
</ul>
</div>
</div>
<br /><br />
<br />