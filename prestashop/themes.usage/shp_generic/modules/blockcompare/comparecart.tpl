{capture name=path}{l s='Compare products' mod='blockcompare'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

{variablebox}
<h2>{l s='Compare products' mod='blockcompare'}</h2>
{variablebox_content}

{include file=$tpl_dir./errors.tpl}

{assign var='id_lang' value=$cookie->id_lang|intval}

<table class="comparecart">
 <tr>
  <th class="first"></th>
  {foreach from=$products item='product' name='products'}
   {assign var='productId' value=$product.id}
   <th>
    <a class="compare_block_product_name" href="{$link->getProductLink($productId, $product.link_rewrite, $product.category)}" title="{$product.name[$id_lang]|escape:htmlall:'UTF-8'}">{t text=$product.name[$id_lang] length='16' encode='true'}</a>
   </th>
  {/foreach}
 </tr>

{assign var='feature_header' value=''}

 {foreach from=$features key=feature_name item=feature}
    {assign var='feature_name' value="/"|explode:$feature_name|escape:'htmlall':'UTF-8'}
    {if $feature_name[0] != $feature_header}
  <tr class="feature_header">
    <th colspan="2">{$feature_name[0]}</th>
  </tr>
       {assign var='feature_header' value=$feature_name[0]}
    {/if}
  <tr>
   <td>{$feature_name[1]}</td>
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
