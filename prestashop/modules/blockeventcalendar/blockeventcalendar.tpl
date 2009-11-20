<div id="compare_block" class="block">
 <h4>{l s='Upcoming events' mod='blockeventcalendar'}</h4>
 <div class="block_content">
  <div id="eventcalendar"></div>

  <script type="text/javascript">
   var now = new Date();
   $('#eventcalendar').DatePicker({ldelim}
	   flat: true,
	   date: false,
	   current: now,
	   format: 'Y-m-d',
	   calendars: 1,
	   mode: 'single',
	   onRender: function(date) {ldelim}
		   return {ldelim}
			   disabled:
			   !(false
			     {foreach from=$event_dates item=event_date}
			      || date.valueOf() == (new Date('{$event_date|date_format:"%Y/%m/%d"}')).valueOf()
                             {/foreach}
                            ),
			   className: false
		   {rdelim}
	   {rdelim},
	   onChange: function(formated, dates) {ldelim}
		     window.location = "{$mdl_uri}eventsfordate.php?event_date=" + formated;
	   {rdelim},
	   starts: 0
   {rdelim});
  </script>

 </div>
</div>
