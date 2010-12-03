{capture name=path}{l s='Compare products' mod='blockcompare'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

{variablebox}
<h2>{l s='Compare products' mod='blockcompare'}</h2>
{variablebox_content}

{include file=$tpl_dir./errors.tpl}

{assign var='id_lang' value=$cookie->id_lang|intval}

<table class="comparecart">
 <tr>
  <th></th>
  {foreach from=$products item='product' name='products'}
   {assign var='productId' value=$product.id}
   <th>
    <a class="compare_block_product_name" href="{$link->getProductLink($productId, $product.link_rewrite, $product.category)}" title="{$product.name[$id_lang]|escape:htmlall:'UTF-8'}">{t text=$product.name[$id_lang] length='16' encode='true'}</a>
   </th>
  {/foreach}
 </tr>

 {foreach from=$features key=feature_name item=feature}
  <tr>
   <th>{$feature_name|escape:'htmlall':'UTF-8'}</th>
   {foreach from=$products item='product' name='products'}
    <td>{if isset($feature[$product.id])}{$feature[$product.id]|escape:'htmlall':'UTF-8'}{else}-{/if}</td>
   {/foreach}
  </tr>
 {/foreach}

 <tr>
  <td></td>
  {foreach from=$products item='product' name='products'}
   <td>{$product.description[$id_lang]}</td>
  {/foreach}
 </tr>

</table>
{/variablebox}
