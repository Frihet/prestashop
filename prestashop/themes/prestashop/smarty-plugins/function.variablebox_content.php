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

function smarty_function_variablebox_content($params, &$smarty)
{
    return "
      </td>
      <td class='variablebox_center_right'></td>
     </tr>
     <tr>
      <td class='variablebox_middle_left'></td>
      <td class='variablebox_middle_center'></td>
      <td class='variablebox_middle_right'></td>
     </tr>
     <tr>
      <td class='variablebox_center_left'></td>
      <td class='variablebox_center_center'>
    ";
}

/* vim: set expandtab: */

?>
