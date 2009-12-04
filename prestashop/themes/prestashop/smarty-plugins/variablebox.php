<?php

function &smarty_find_tag(&$smarty, $tag)
{
    for ($pos = count($smarty->_tag_stack) - 1;  $pos >= 0; $pos--)
        if ($smarty->_tag_stack[$pos][0] == $tag) {
	    if (count($smarty->_tag_stack[$pos]) < 3) {
	        $initfunc = "smarty_init_{$tag}";
		$smarty->_tag_stack[$pos][2] = $initfunc($smarty->_tag_stack[$pos][1], $smarty);
	    }
	    return $smarty->_tag_stack[$pos][2];
	}
    return null;
}

