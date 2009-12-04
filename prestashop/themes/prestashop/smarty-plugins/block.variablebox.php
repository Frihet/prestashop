<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

require_once(dirname(__FILE__) . "/variablebox.php");

function smarty_init_variablebox($params, &$smarty) {
    return array_merge(
        array(
	    'content_count' => 1, // See the hard-coded two starting-lines (content_0 and content_1) in smarty_block_variablebox
	    'class' => '',
	    'border_left' => 'shown',
	    'border_right' => 'shown',
	    'border_top' => 'shown',
	    'border_bottom' => 'shown',
	),
	$params);
}

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

    $boxparams = &smarty_find_tag($smarty, 'variablebox');

    $res = "<table class='variablebox product_type_article centre_column_content {$boxparams['class']}'>";
    if ($boxparams['border_top'] == "shown") {
        $res .= "<tr class='content_0'>";
	if ($boxparams['border_left'] == "shown")
           $res .= "<td class='variablebox_top_left'></td>";
	$res .= "<td class='variablebox_top_center'></td>";
	if ($boxparams['border_right'] == "shown")
	   $res .= "<td class='variablebox_top_right'></td>";
        $res .= "</tr>";
    }
    $res .= "<tr class='content_1'>";
    if ($boxparams['border_left'] == "shown")
       $res .= "<td class='variablebox_center_left'></td>";
    $res .= "<td class='variablebox_center_center'>{$content}</td>";
    if ($boxparams['border_right'] == "shown")
       $res .= "<td class='variablebox_center_right'></td>";
    $res .= "</tr>";

    $boxparams['content_count'] += 1;
    if ($boxparams['border_bottom'] == "shown") {
        $res .= "<tr class='content_{$boxparams['content_count']}'>";
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
