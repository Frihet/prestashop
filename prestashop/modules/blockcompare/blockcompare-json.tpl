{assign var='id_lang' value=$compare_cart->id_lang}
{ldelim}
'products': [
{if $products}{foreach from=$products item=product}
{assign var='productId' value=$product.id_product}
	{ldelim}
		'id':            {$product.id_product},
		'link':          '{$link->getProductLink($product.id_product, $product.link_rewrite, $product.category)|addslashes}',
		'name':          '{$product.name[$id_lang]|addslashes|truncate:16:'...'|escape:'htmlall':'UTF-8'}',
	{rdelim},
{/foreach}{/if}
],

{if isset($errors) && $errors}
'hasError' : true,
errors : [
{foreach from=$errors key=k item=error name='errors'}
	'{$error|addslashes|html_entity_decode:2:'UTF-8'}'
	{if !$smarty.foreach.errors.last},{/if}
{/foreach}
]
{else}
'hasError' : false
{/if}

{rdelim}
