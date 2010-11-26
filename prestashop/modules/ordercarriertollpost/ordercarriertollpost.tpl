{l s='Please select place of pickup:' mod='ordercarriertollpost'}

<select name="carrier_tollpost_pickup">
  {foreach from=$pickups item=title key=value}
    <option value="{$value}"
      {if $value == $carrier_tollpost_pickup}
        selected="selected"
      {/if}
    >{$title}</option>
  {/foreach}
</select>
