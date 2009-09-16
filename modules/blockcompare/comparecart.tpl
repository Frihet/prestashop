{capture name=path}{l s='Compare products'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

<h2>{l s='Compare products'}</h2>

{include file=$tpl_dir./errors.tpl}

{assign var='id_lang' value=$cookie->id_lang|intval}

<table>
 <tr>
  <th></th>
  {foreach from=$products item='product' name='products'}
   {assign var='productId' value=$product.id}
   <th>
    <a class="compare_block_product_name" href="{$link->getProductLink($productId, $product.link_rewrite, $product.category)}" title="{$product.name[$id_lang]|escape:htmlall:'UTF-8'}">{t text=$product.name[$id_lang] length='16' encode='true'}</a>
   </th>
  {/foreach}
 </tr>

 <tr>
  <td></td>
  {foreach from=$products item='product' name='products'}
   <td>{$product.description[$id_lang]}</td>
  {/foreach}
 </tr>

 {foreach from=$features key=feature_name item=feature}
  <tr>
   <td>{$feature_name|escape:'htmlall':'UTF-8'}</td>
   {foreach from=$products item='product' name='products'}
    <td>{$feature[$product.id]|escape:'htmlall':'UTF-8'}</td>
   {/foreach}
  </tr>
 {/foreach}
</table>
