{debug}

{if isset($id_customization_field_schedule)}
 {foreach from=$customizationFields item='schedule' name='customizationFields'}
  {if $schedule.type == 2}
   {foreach from=$schedule.schedule key="venue" item="schedule"}
    {foreach from=$schedule item="item"}
     {if $item.id_customization_field_schedule|intval == $id_customization_field_schedule|intval}
      {assign var=edit_entry value=$item}
     {/if}
    {/foreach}
   {/foreach}
  {/if}
 {/foreach}
{/if}

<fieldset>
 <legend>
  {if isset($edit_entry)}
   Edit course
  {else}
   Add a new course
  {/if}
 </legend>
 <input type='hidden' name='schedule_entry_editor_id_customization_field' id='schedule_entry_editor_id_customization_field' value={$edit_entry.id_customization_field}>
 <input type='hidden' name='schedule_entry_editor_id_customization_field_schedule' id='schedule_entry_editor_id_customization_field_schedule' value={$edit_entry.id_customization_field_schedule}>
 <div>
  <label for='schedule_entry_editor_start_time'>Start</label>
  <script type='text/javascript'>$('#schedule_entry_editor_start_time').datepicker({ldelim}prevText:'', nextText:'', dateFormat:'yy-mm-dd'{rdelim});</script>
  <input type='text' name='schedule_entry_editor_start_time' id='schedule_entry_editor_start_time' value='{$edit_entry.start_time_value|date_format:"%Y-%m-%d %H:%M"}'>
 </div>
 <div>
  <label for='schedule_entry_editor_end_time'>End</label>
  <script type='text/javascript'>$('#schedule_entry_editor_end_time').datepicker({ldelim}prevText:'', nextText:'', dateFormat:'yy-mm-dd'{rdelim});</script>
  <input type='text' name='schedule_entry_editor_end_time' id='schedule_entry_editor_end_time' value='{$edit_entry.end_time_value|date_format:"%Y-%m-%d %H:%M"}'>
 </div>
 <div>
  <label for='schedule_entry_editor_venue'>Venue</label>
  <input type='text' name='schedule_entry_editor_venue' id='schedule_entry_editor_venue' value='{$edit_entry.venue}'>
 </div>
 <div>
  <label for='schedule_entry_editor_seats'>Seats</label>
  <input type='text' name='schedule_entry_editor_seats' id='schedule_entry_editor_seats' value='{$edit_entry.seats}'>
 </div>
 <div>
  <label for='schedule_entry_editor_teacher'>Teacher</label>
  <input type='text' name='schedule_entry_editor_teacher' id='schedule_entry_editor_teacher' value='{$edit_entry.teacher}'>
 </div>

 <div>
  <label for='schedule_entry_editor_name'>Name</label>
  <input type='text' name='schedule_entry_editor_name' id='schedule_entry_editor_name' value='{$edit_entry.name}'>
 </div>
 <div>
  <label for='schedule_entry_editor_description'>Description</label>
  <input type='text' name='schedule_entry_editor_description' id='schedule_entry_editor_description' value='{$edit_entry.description}'>
 </div>

 {if isset($edit_entry)}
  <input class="button" type="submit" onclick="this.form.action += '&addproduct&tabs=4';" value="Save" name="submitScheduleEditorAddEntry">
  <input class="button" type="submit" onclick="this.form.action += '&addproduct&tabs=4';" value="Delete" name="submitScheduleEditorDeleteEntry">
  <input class="button" type="submit" onclick="this.form.action += '&addproduct&tabs=4';" value="Cancel" name="submitScheduleEditorCancel">
 {else}
  <input class="button" type="submit" onclick="this.form.action += '&addproduct&tabs=4';" value="Add" name="submitScheduleEditorAddEntry">
 {/if}
</fieldset>

{assign var=schedule_entry_template value="$tpl_dir./customization_schedule_entry_admin.tpl"}
{counter start=0 assign='customizationField'}
{foreach from=$customizationFields item='schedule' name='customizationFields'}
 {if $schedule.type == 2}
  <div id="schedule_{$schedule.id_customization_field}">
   {if !empty($schedule.name)}<h2>{$schedule.name}</h2>{/if}
   {include file=$tpl_dir./customization_schedule.tpl'}
  </div>
 {/if}
{/foreach}
