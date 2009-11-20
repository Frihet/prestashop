<?php

function normalize_date($date, $format = "%Y-%m-%d") {
	 return strftime($format, strtotime($date));
}


