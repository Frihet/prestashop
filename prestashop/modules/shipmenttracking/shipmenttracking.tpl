<p class="bold">{l s='Shipments' mod='shipmenttracking'}</p>
<table class="std">
 <thead>
  <tr>
   <th class="first_item">{l s='Date' mod='shipmenttracking'}</th>
   <th class="last_item">{l s='Tracking number' mod='shipmenttracking'}</th>
  </tr>
 </thead>
 <tbody>
  {foreach from=$shipments item='shipment'}
   <tr>
    <td>{$shipment->date_add}</td>
    <td><a href="{$shipment->url}">{$shipment->tracking_number}</a></td>
   </tr>
  {/foreach}
 </tbody>
</table>
