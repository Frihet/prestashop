{variablebox}
	<h2 class="category_title">
		{l s='Map'}
		<span></span>
	</h2>
	{variablebox_content}
	<div id="map_canvas" style="float:left;width:70%;height:500px;"></div>
	<div style="float:left;width:30%;height:500px;">
		<div style="width:100%;height:300px;">
			<h2 style="margin-bottom: 15px;">{l s='Dealer'}</h2>
			<div style="padding: 0 10px 0 10px;" id="dealer_info">
				<p>{l s='Navigate the map to find dealers in your area.'}</p>
				<p>{l s='Click the markers to view more information about the dealer.'}</p>
				<!--
				<p>Navigér i kartet til venstre for å finne forhandlere i ditt nærområde.</p>
				<p>Klikk på en av markørene om du vil se mer informasjon om forhandleren.</p>
				-->
			</div>
		</div>
		<div id="map_controls">
			<h2 style="margin-bottom: 15px;">{l s='Find dealers near you'}</h2>
			<div style="padding: 0 10px 0 10px;">
				<select name="country" onchange="changeMap(this.value, 'country')">
					<option value="0">[ {l s='CHOOSE COUNTRY'} ]</option>
					{foreach from=$areas key="country" item="area"}
					<option value="{if is_array($area)}{$country}{else}{$area}{/if}">{l s=`$country`}</option>
					{/foreach}
				</select>
				{foreach from=$areas key="country" item="area"}
				{if count($area) gt 0}
				<select name="region" class="select_region country_{$country}" style="display:none;" onchange="changeMap(this.value, 'region')">
					<option value="0">[ {l s='CHOOSE REGION'} ]</option>
					{foreach from=$area key="region" item="latlng"}
					<option value="{$latlng}">{l s=`$region`}</option>
					{/foreach}
				</select>
				{/if}
				{/foreach}
			</div>
		</div>
		
		{*<!-- 
		{variablebox}
		<h2>Våre forhandlere</h2>
		{variablebox_content}
		<p>Navigér i kartet til venstre for å finne forhandlere i ditt nærområde.</p>
		<p>Klikk på en av markørene om du vil se mer informasjon om forhandleren.</p>
		{/variablebox}
		 -->*}
	</div>
{/variablebox}



<script type="text/javascript">
var id_lang = {$id_lang};
var id_category = {$category->id};
var map;
var initialZoom = 9;
var norway = new google.maps.LatLng(60.472024,8.4689);
var sweden = new google.maps.LatLng(60.128161,18.64350);
var denmark = new google.maps.LatLng(56.26392,9.5017850);
var iceland = new google.maps.LatLng(64.963051,-19.02083);
var faroe_islands = new google.maps.LatLng(61.892635,-6.911806);

var lang2latlng = [];
lang2latlng[1] = norway;
lang2latlng[2] = norway;
lang2latlng[3] = norway;
lang2latlng[4] = sweden;
lang2latlng[5] = denmark;

var initialLocation = lang2latlng[id_lang];
var browserSupportFlag = new Boolean();
var dealers = [];

{literal}
var initMap = function() {
	var mapOptions = {
		zoom: initialZoom,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	$('.category_title span').html('{/literal}{l s='Loading map'}{literal}...');
	
	map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
	
	initGeolocation();
	
	loadDealers();
}

var showDealer = function(dealer) {

	var html = '';
	html += '<h3><a href="product.php?id_product=' + dealer.id + '&amp;id_category=' + dealer.id_top_category + '" title="" style="font-size:14px;">' + dealer.name + '</a></h3>';
	html += '<table style="width:100%;font-size:13px;">';
	html += '	<tr>';
	html += '		<th valign="top">{/literal}{l s='Address'}{literal}</th>';
	html += '		<td>' + dealer.data.address1 + '<br/>';
	if (dealer.data.address2) {
		html += dealer.data.address2 + '<br/>';
	}
	html += dealer.data.postcode + ' ' + dealer.data.city + '</td>';
	html += '</tr>';
	html += '	<tr>';
	html += '		<th>{/literal}{l s='Phone'}{literal}</th>';
	html += '		<td>' + dealer.data.phone + '</td>';
	html += '	</tr>';
	if (dealer.data.fax) {
		html += '	<tr>';
		html += '		<th>{/literal}{l s='Fax'}{literal}</th>';
		html += '		<td>' + dealer.data.fax + '</td>';
		html += '	</tr>';
	}
	html += '	<tr>';
	html += '		<th>{/literal}{l s='E-mail'}{literal}</th>';
	html += '		<td>' + dealer.data.email + '</td>';
	html += '	</tr>';
	
	if (dealer.data.hours) {
		html += '	<tr>';
		html += '		<th valign="top">{/literal}{l s='Opening hours'}{literal}</th>';
		html += '		<td>' + dealer.data.hours + '</td>';
		html += '	</tr>';
	}
	if (dealer.description_short) {
		html += '<tr><td>&nbsp;</td><td>' + dealer.description_short + '</td></tr>';
	}
	
	html += '<tr>';
	html += '<td colspan="2">';
	html += '<ul class="dealer-classifications">';
	html += '<li' + (dealer.data.classifications.indexOf('broderi') !== -1 ? ' class="checked"' : '') + '>Ekspert på broderisymaskiner og programvare</li>';
	html += '<li' + (dealer.data.classifications.indexOf('service') !== -1 ? ' class="checked"' : '') + '>Eget serviceverksted</li>';
	html += '<li' + (dealer.data.classifications.indexOf('kurs')    !== -1 ? ' class="checked"' : '') + '>Driver kursvirksomhet</li>';
	html += '</ul>';
	html += '</td>';
	html += '</tr>';
	
	html += '</table>';
	$('#dealer_info').html(html);
}

var loadDealers = function() {
	$.ajax({
		url: 'dealers-ajax.php?id_category=' + id_category,
		dataType: 'json',
		success: function(data) {
			$.each(data.dealers, function(){
				addDealer(this);
			});
		}
	});
}


var addDealer = function(dealer) {
	if (!map)
		return;
	var location = dealer.latlng.split(",");
	var latlng = new google.maps.LatLng(location[0], location[1]);
	var marker = new google.maps.Marker({
		position: latlng,
		title: dealer.name
	});
	marker.setMap(map);
	marker.dealer = dealer;
	google.maps.event.addListener(marker, 'click', function() {
		showDealer(marker.dealer);
	});
	
	dealers.push(dealer);
}

var initGeolocation = function() {
	map.setCenter(initialLocation);
	map.setZoom(6);
	$('.category_title span').html('{/literal}{l s='Waiting for position'}{literal}...');
	if (navigator.geolocation) {
		browserSupportFlag = true;
		navigator.geolocation.getCurrentPosition(function(position) {
			initialLocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
			map.setCenter(initialLocation);
			map.setZoom(9);
			$('.category_title span').html('');
		}, function() {
			//handleNoGeolocation(browserSupportFlag);
		});
	// Try Google Gears Geolocation
	} else if (google.gears) {
		browserSupportFlag = true;
		var geo = google.gears.factory.create('beta.geolocation');
		geo.getCurrentPosition(function(position) {
			initialLocation = new google.maps.LatLng(position.latitude,position.longitude);
			map.setCenter(initialLocation);
			map.setZoom(9);
			$('.category_title span').html('');
		}, function() {
			//handleNoGeoLocation(browserSupportFlag);
		});
	// Browser doesn't support Geolocation
	} else {
		browserSupportFlag = false;
		$('.category_title span').html('');
		//handleNoGeolocation(browserSupportFlag);
	}
}

var changeMap = function(val, context) {
	if (context == 'country') {
		$('.select_region').hide();
		var selectbox = $('.country_' + val);
		if (selectbox.length > 0) {
			selectbox.show();
			selectbox.get(0).selectedIndex = 0;
		}
	}
	var arr = val.split('@');
	if (arr.length == 2) {
		var zoom = parseInt(arr[1]);
		arr = arr[0].split(',');
		var latlng = new google.maps.LatLng(arr[0], arr[1]);
		map.setCenter(latlng);
		map.setZoom(zoom);
	}
}


$(document).ready(function(){
	initMap();
	
});

</script>
{/literal}
