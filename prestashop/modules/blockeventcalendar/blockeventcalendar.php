<?php

class BlockEventCalendar extends Module
{
	function __construct()
	{
		$this->name = 'blockeventcalendar';
		$this->tab = 'Blocks';
		$this->version = '0.1';

		parent::__construct();

		$this->displayName = $this->l('EventCalendar block');
		$this->description = $this->l('Displays an event calendar for your events and courses');
	}
	
	function install()
	{
			if
			(
				parent::install() == false
				OR $this->registerHook('header') == false
				OR $this->registerHook('rightColumn') == false
			)
			return false;
		return true;
	}


	function hookHeader($params)
	{
		$content_dir = _MODULE_DIR_ . "blockeventcalendar/";

		return "
		 <script type='text/javascript' src='{$content_dir}datepicker/js/datepicker.js'></script>
		 <link rel='stylesheet' type='text/css' href='{$content_dir}datepicker/css/datepicker.css'>
		";
	}

	function hookRightColumn($params)
	{
		global $smarty;

		$smarty->assign('event_dates', $this->getEventDates());
		$smarty->assign('mdl_uri', _MODULE_DIR_ . 'blockeventcalendar/');
		return $this->display(__FILE__, 'blockeventcalendar.tpl');
	}

	function getEventDates()
	{
		require_once(dirname(__file__)."/functions.php");

		$sql = "select distinct cast(date_format(start_time, '%Y-%m-%d') as date) as start_date, cast(date_format(end_time, '%Y-%m-%d') as date) as end_date from PREFIX_customization_field_schedule";
		$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
		$result = Db::getInstance()->ExecuteS($sql);
		$dates = array();
		if (!empty($result))
		        foreach($result as $row)
				for ($cur_date = normalize_date($row['start_date']); $cur_date != normalize_date("{$row['end_date']} + 1 day"); $cur_date = normalize_date("{$cur_date} + 1 day"))
				        if (!in_array($cur_date, $dates))
					        $dates[] = $cur_date;
		return $dates;
	}
	

}

?>
