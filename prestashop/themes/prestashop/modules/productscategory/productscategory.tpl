{if count($categoryProducts) > 0}
<script type="text/javascript">var middle = {$middlePosition};</script>
<script type="text/javascript" src="{$content_dir}modules/productscategory/js/productscategory.js"></script>

<div class="block">
	<h4>{l s='In the same category' mod='productscategory'}</h4>
	<div class="products_block">
		{include file=$tpl_dir./product-list-category.tpl products=$categoryProducts hide_description=true}
	</div>
</div>
{/if}
