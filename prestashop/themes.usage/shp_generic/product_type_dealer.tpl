{include file=$tpl_dir./breadcrumb.tpl}
{include file=$tpl_dir./errors.tpl}

{if $errors|@count == 0}

{variablebox}
	<div id="primary_block">
	
		<div style="float:left;width:60%;">
			<h1>{$product->name|escape:'htmlall':'UTF-8'}</h1>
			
			{if $product->description_short}
				<div style="margin: 15px 0 0 0;">{$product->description_short}</div>
			{/if}
			
			<div style="margin: 20px 0 0 0;">
				{$product->description}
			</div>
			<!--
			<div style="background:#b0b0b0;width:450px;height:150px;margin-top:20px;">Bilde</div>
			<div style="background:#b0b0b0;width:450px;height:150px;margin-top:20px;">Bilde</div>
			-->
			
			<a href="/dealers.php?id_category={$category->id_category}" title="" class="button" style="margin-top:30px;">Gå til kartvisning</a>
<!--
v.title,
v.hansa_classification,
v.distance,
c.email,
addr.address1,
addr.address2,
addr.postcode,
addr.city,
addr.phone,
addr.phone_mobile
-->
		</div>
		<div style="float:right;">
			{if $have_image}
				<div style="width:150px; height:80px;background:#d0d0d0;">LOGO</div>
				<img src="{$link->getImageLink($product->link_rewrite, $cover.id_image, 'large')}" {if $jqZoomEnabled}class="jqzoom" alt="{$link->getImageLink($product->link_rewrite, $cover.id_image, 'thickbox')}"{else} title="{$product->name|escape:'htmlall':'UTF-8'}" alt="{$product->name|escape:'htmlall':'UTF-8'}" {/if} id="bigpic"/>
			{/if}
			
			<div style="width:150px; height:150px;background:#f7f7f7;">
				<img src="http://maps.google.com/maps/api/staticmap?sensor=false&size=150x150&zoom=12&center={$product->vendor.latlng}&markers={$product->vendor.latlng}"/>
			</div>
			<h3>Her er vi</h3>
			
			{variablebox}
			{if $product->vendor.hours}
			<b>Åpningstider:</b><br/>
			{$product->vendor.hours|nl2br}<br/>
			<br/>
			{/if}
			
			<b>Adresse:</b><br/>
			{$product->vendor.address1}<br/>
			{$product->vendor.address2}<br/>
			{$product->vendor.postcode} {$product->vendor.city}<br/>
			<br/>
			<b>Telefon:</b> {$product->vendor.phone}<br/>
			<b>Mobil:</b> {$product->vendor.phone_mobile}<br/>
			{if $product->vendor.fax}
			<b>Faks:</b> {$product->vendor.fax}<br/>
			{/if}
			<br/>
			<b>E-mail:</b><br/>
			{$product->vendor.email}<br/>
			<br/>
			{if $product->vendor.blog_url}
			<b>Blogg:</b><br/>
			{$product->vendor.blog_url}<br/>
			<br/>
			{/if}
			{/variablebox}
		</div>
	</div>
{/variablebox}

{/if}


