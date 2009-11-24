<p class="bold">{l s='Shipments'}</p>
<table class="std">
 <thead>
  <tr>
   <th class="first_item">{l s='Date'}</th>
   <th class="last_item">{l s='Tracking number'}</th>
  </tr>
 </thead>
 <tbody>
  {foreach from=$shipments item='shipment'}
   <tr>
    <td>{$shipment->date_add}</td>
    <td>{shiptrack carrier=$shipment->shiptrackcode tracking_number=$shipment->tracking_number linktext=$shipment->tracking_number}</td>
   </tr>
  {/foreach}
 </tbody>
</table>
