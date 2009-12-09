{if $product->type}
 {assign var=type value=$product->type}
{else}
 {assign var=type value='product'}
{/if}
{include file=$tpl_dir./product_type_$type.tpl'}
