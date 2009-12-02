<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {variablebox}{/variablebox} block plugin
 *
 * Type:     block function<br>
 * Name:     variablebox<br>
 * Purpose:  Creates a stylable table box around content<br>
 * @param array
 * <pre>
 * Params:   class: string (class)
 * </pre>
 * @author Egil Möller <egil.moller@freecode.no>
 * @param string contents of the block
 * @param Smarty clever simulation of a method
 * @param repeat signal
 * @return string string $content re-formatted
 */
function smarty_block_variablebox($params, $content, &$smarty, &$repeat)
{
    if (is_null($content)) {
        return;
    }


    $boxparams = array_merge(
        array(
	    'class' => '',
	    'border_left' => 'shown',
	    'border_right' => 'shown',
	    'border_top' => 'shown',
	    'border_bottom' => 'shown',
	),
	$params);

    $res = "<table class='variablebox product_type_article centre_column_content {$boxparams['class']}'>";
    if ($boxparams['border_top'] == "shown") {
        $res .= "<tr>";
	if ($boxparams['border_left'] == "shown")
           $res .= "<td class='variablebox_top_left'></td>";
	$res .= "<td class='variablebox_top_center'></td>";
	if ($boxparams['border_right'] == "shown")
	   $res .= "<td class='variablebox_top_right'></td>";
        $res .= "</tr>";
    }
    $res .= "<tr>";
    if ($boxparams['border_left'] == "shown")
       $res .= "<td class='variablebox_center_left'></td>";
    $res .= "<td class='variablebox_center_center'>{$content}</td>";
    if ($boxparams['border_right'] == "shown")
       $res .= "<td class='variablebox_center_right'></td>";
    $res .= "</tr>";
    if ($boxparams['border_bottom'] == "shown") {
        $res .= "<tr>";
	if ($boxparams['border_left'] == "shown")
           $res .= "<td class='variablebox_bottom_left'></td>";
	$res .= "<td class='variablebox_bottom_center'></td>";
	if ($boxparams['border_right'] == "shown")
	   $res .= "<td class='variablebox_bottom_right'></td>";
        $res .= "</tr>";
    }
    $res .= "</table>";
    return $res;
}

/* vim: set expandtab: */

?>
