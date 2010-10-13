<!-- Block categories module -->
<script type="text/javascript" src="{$modules_dir}sortcategories/jquery-1.3.2.js"></script>
<script type="text/javascript" src="{$modules_dir}sortcategories/ui.core.js"></script>
<script type="text/javascript" src="{$modules_dir}sortcategories/ui.sortable.js"></script>

<div id="categories_block_left" class="block">
	<div class="block_content" id="sort">
{foreach from=$elements item=element name=categorie}
		<div class="sortables" id="sort_{$element.id_category}">
			<input type="hidden" name="new_order[{$element.id_category}][id_category]" value="{$element.id_category}" id="id_{$element.id_category}" >
			<input type="hidden" name="new_order[{$element.id_category}][position]" value="{$element.position}" id="position_{$element.id_category}" >
			<input type="hidden" name="new_order[{$element.id_category}][nom]" value="{$element.nom}" id="nom_{$element.id_category}">
			{$element.nom} 
				{if $element.link}
				<a href="{$element.link}">{l s='sort this category' mod='sortcategories'}</a>
				{/if}
		</div>
{/foreach}
	</div>
</div>
<!-- /Block categories module -->