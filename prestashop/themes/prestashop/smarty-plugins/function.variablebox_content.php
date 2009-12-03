<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {variablebox_content} plugin
 *
 * Type:     function<br>
 * Name:     variablebox_content<br>
 * Purpose:  Creates divission in a {variablebox}
 * @param array
 * </pre>
 * @author Egil Möller <egil.moller@freecode.no>
 * @param any params (none!)
 * @param Smarty clever simulation of a method
 * @return string divider code 
 */

function find_tag(&$smarty, $tag)
{
    foreach (array_reverse($smarty->_tag_stack) as $stackentry)
        if ($stackentry[0] == $tag)
	    return $stackentry[1];
    return null;
}

function smarty_function_variablebox_content($params, &$smarty)
{
    $boxparams = array(
	    'border_left' => 'shown',
	    'border_right' => 'shown',
	    'border_top' => 'shown',
	    'border_bottom' => 'shown');
    $parentboxparams = find_tag($smarty, 'variablebox');
    if ($parentboxparams != null)
        $boxparams = array_merge($boxparams, $parentboxparams);
    $res = "</td>";
    if ($boxparams['border_right'] == 'shown')
        $res .= "<td class='variablebox_center_right'></td>";
    $res .= "</tr><tr>";
    if ($boxparams['border_left'] == 'shown')
        $res .= "<td class='variablebox_middle_left'></td>";
    $res .= "<td class='variablebox_middle_center'></td>";
    if ($boxparams['border_right'] == 'shown')
        $res .= "<td class='variablebox_middle_right'></td>";
    $res .= "</tr><tr>";
    if ($boxparams['border_left'] == 'shown')
        $res .= "<td class='variablebox_center_left'></td>";
    $res .= "<td class='variablebox_center_center'>";
    return $res;
}

/* vim: set expandtab: */

?>
