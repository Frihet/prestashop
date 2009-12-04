<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

require_once(dirname(__FILE__) . "/variablebox.php");

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

function smarty_function_variablebox_content($params, &$smarty)
{
    $boxparams = &smarty_find_tag($smarty, 'variablebox');
    if ($boxparams === null)
        throw new Exception('Unable to find parent variablebox for variablebox_content');

    $res = "</td>";
    if ($boxparams['border_right'] == 'shown')
        $res .= "<td class='variablebox_center_right'></td>";

    $boxparams['content_count'] += 1;
    $res .= "</tr><tr class='content_{$boxparams['content_count']}'>";
    if ($boxparams['border_left'] == 'shown')
        $res .= "<td class='variablebox_middle_left'></td>";
    $res .= "<td class='variablebox_middle_center'></td>";
    if ($boxparams['border_right'] == 'shown')
        $res .= "<td class='variablebox_middle_right'></td>";

    $boxparams['content_count'] += 1;
    $res .= "</tr><tr class='content_{$boxparams['content_count']}'>";
    if ($boxparams['border_left'] == 'shown')
        $res .= "<td class='variablebox_center_left'></td>";
    $res .= "<td class='variablebox_center_center'>";
    return $res;
}

/* vim: set expandtab: */

?>
