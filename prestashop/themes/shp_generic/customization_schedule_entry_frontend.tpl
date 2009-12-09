<div style="height: {$height}px; width: {$width}px;" class="entry {if isset($scheduleFields[$scheduleFieldName])}selected{/if} {if $item.seats|intval > 0}available{else}full{/if}" onclick="scheduleSelect(event)">
 <div class="duration">{$item.start_time_value|date_format:"%H:%M"}-{$item.end_time_value|date_format:"%H:%M"}</div>
 <div class="name">{$item.name}</div>
 <div class="description">{$item.description}</div>
 <div class="teacher">Teacher: {$item.teacher}</div>
 <div class="searts">Seats left: {$item.seats}</div>
 <input type="hidden" name="{$scheduleFieldName}" value="{$scheduleFields[$scheduleFieldName]}">
 <span style="display:none" class="start_time_value">{$item.start_time_value}</span>
 <span style="display:none" class="end_time_value">{$item.end_time_value}</span>
</div>
