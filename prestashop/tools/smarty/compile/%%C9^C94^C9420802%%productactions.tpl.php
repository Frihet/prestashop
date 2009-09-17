<?php /* Smarty version 2.6.20, created on 2009-08-25 14:00:29
         compiled from /srv/www/shp-dev/prestashop/modules/blockcompare/productactions.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'intval', '/srv/www/shp-dev/prestashop/modules/blockcompare/productactions.tpl', 2, false),array('function', 'l', '/srv/www/shp-dev/prestashop/modules/blockcompare/productactions.tpl', 2, false),)), $this); ?>
<p id="add_to_compare_cart" class="buttons_bottom_block">
 <a class="button_small ajax_add_to_compare_cart_button" rel="ajax_id_product_<?php echo ((is_array($_tmp=$this->_tpl_vars['id_product'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
" href="<?php echo $this->_tpl_vars['base_dir_ssl']; ?>
modules/blockcompare/compare.php?add&amp;id_product=<?php echo ((is_array($_tmp=$this->_tpl_vars['id_product'])) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp)); ?>
&amp;token=<?php echo $this->_tpl_vars['static_token']; ?>
" title="<?php echo smartyTranslate(array('s' => 'Compare','mod' => 'blockcompare'), $this);?>
"><?php echo smartyTranslate(array('s' => 'Compare','mod' => 'blockcompare'), $this);?>
</a>
</p>