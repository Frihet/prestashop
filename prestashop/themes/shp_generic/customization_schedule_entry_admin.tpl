<div style="height: {$height}px; width: {$width}px;" class="entry available">
 <a href="{$base_url}&schedule_editor_select={$item.id_customization_field_schedule}">
  <div class="duration">{$item.start_time_value|date_format:"%H:%M"}-{$item.end_time_value|date_format:"%H:%M"}</div>
  <div class="name">{$item.name}</div>
  <div class="description">{$item.description}</div>
  <div class="teacher">Teacher: {$item.teacher}</div>
  <div class="searts">Seats left: {$item.seats}</div>
  <input type="hidden" name="{$scheduleFieldName}" value="{$scheduleFields[$scheduleFieldName]}">
  <span style="display:none" class="start_time_value">{$item.start_time_value}</span>
  <span style="display:none" class="end_time_value">{$item.end_time_value}</span>
 </a>
</div>
