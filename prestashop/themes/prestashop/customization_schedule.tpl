{assign var=schedule_pixels_per_hour value=120}
{assign var=schedule_venue_width value=120}
{assign var=schedule_timestep_extra_w value=0}
{assign var=schedule_timestep_extra_h value=1}
{assign var=schedule_entry_extra_w value=14}
{assign var=schedule_entry_extra_h value=14}
{assign var=schedule_entry_empty_extra_w value=0}
{assign var=schedule_entry_empty_extra_h value=0}

<table class="schedule">
 <tr>
  <th></th>
  {foreach from=$schedule.schedule key="venue" item="xyzzy"}
   <th>
    {$venue}
   </th>
  {/foreach}
 </tr>

 <tr>
  <td>
   {section name=timestep start=$schedule.schedule_start_time_value loop=$schedule.schedule_end_time_value step=3600}
    {if $smarty.section.timestep.index == $schedule.schedule_start_time_value}
     <div style="height: {math equation="pph-eh" pph=$schedule_pixels_per_hour eh=$schedule_timestep_extra_h}px; width: {math equation="vw-ew" vw=$schedule_venue_width ew=$schedule_timestep_extra_w}px;" class="timestep first_timestep">{$smarty.section.timestep.index|date_format:"%Y-%m-%d %H:%M"}</div>
    {else}
     <div style="height: {math equation="pph-eh" pph=$schedule_pixels_per_hour eh=$schedule_timestep_extra_h}px; width: {math equation="vw-ew" vw=$schedule_venue_width ew=$schedule_timestep_extra_w}px;" class="timestep">{$smarty.section.timestep.index|date_format:"%H:%M"}</div>
    {/if}
   {/section}
  </td>
  {foreach from=$schedule.schedule key="venue" item="schedule_for_venue"}
   <td class="venue_col">
    {assign var=last_start_time value=$schedule.schedule_start_time_value|intval}
    {foreach from=$schedule_for_venue item="item"}
     {assign var=item_start value=$item.start_time_value|intval}

     {if $item_start > $last_start_time}
      {math assign=duration equation="(start-last)/60/60*pph" start=$item_start last=$last_start_time pph=$schedule_pixels_per_hour}
      <div style="height: {math equation="d-eh" d=$duration eh=$schedule_entry_empty_extra_h}px; width: {math equation="vw-ew" vw=$schedule_venue_width ew=$schedule_entry_empty_extra_w}px;" class="empty entry"></div>
     {/if}

     {assign var=scheduleFieldName value="scheduleFields_`$product->id`_`$schedule.id_customization_field`_`$item.id_customization_field_schedule`"}
     {math assign=duration equation="(end-start)/60/60*pph" end=$item.end_time_value|intval start=$item_start last=$last_start_time pph=$schedule_pixels_per_hour}
     {math assign=height equation="d-eh" d=$duration eh=$schedule_entry_extra_h}
     {math assign=width equation="vw-ew" vw=$schedule_venue_width ew=$schedule_entry_extra_w}

     {include file=$schedule_entry_template}
     {assign var=last_start_time value=$item.end_time_value|intval}

    {/foreach}
   </td>
  {/foreach}
 </tr>
</table>