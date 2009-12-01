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

    return "
     <table class='variablebox product_type_article centre_column_content {$params['class']}'>
      <tr>
       <td class='variablebox_top_left'></td>
       <td class='variablebox_top_center'></td>
       <td class='variablebox_top_right'></td>
      </tr>
      <tr>
       <td class='variablebox_center_left'></td>
       <td class='variablebox_center_center'>{$content}</td>
       <td class='variablebox_center_right'></td>
      </tr>
      <tr>
       <td class='variablebox_bottom_left'></td>
       <td class='variablebox_bottom_center'></td>
       <td class='variablebox_bottom_right'></td>
      </tr>
     </table>
    ";
}

/* vim: set expandtab: */

?>
