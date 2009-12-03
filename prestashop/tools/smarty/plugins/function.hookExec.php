<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {hookExec} function plugin
 *
 * Type:     function<br>
 * Name:     hookExec<br>
 * Purpose:  Execute a prestashop hook
 * @author Egil Möller <egil.moller@freecode.no>
 * <pre>
 *  params
 *   hook name of hook to execute
 *   Set any of the following global variables:
 *    product
 *    cart
 * </pre>
 * @param array parameters
 * @param Smarty
 * @return string|null
 */

function smarty_function_hookExec($params, &$smarty)
{
 global $product, $cart;

 $old_product = $product;
 $old_cart = $cart;

 if (isset($params['product']))
     $product = $params['product'];
 if (isset($params['cart']))
     $cart = $params['cart'];

 $res = Module::hookExec($params['hook']);

 $cart = $old_cart;
 $product = $old_product;

 return $res;
}
