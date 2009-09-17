<?php /* Smarty version 2.6.20, created on 2009-08-25 17:11:14
         compiled from /srv/www/shp-dev/prestashop/modules/blockcompare/comparecart.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'l', '/srv/www/shp-dev/prestashop/modules/blockcompare/comparecart.tpl', 1, false),array('function', 't', '/srv/www/shp-dev/prestashop/modules/blockcompare/comparecart.tpl', 16, false),array('modifier', 'intval', '/srv/www/shp-dev/prestashop/modules/blockcompare/comparecart.tpl', 8, false),array('modifier', 'escape', '/srv/www/shp-dev/prestashop/modules/blockcompare/comparecart.tpl', 16, false),)), $this); ?>
<?php ob_start(); ?><?php echo smartyTranslate(array('s' => 'Compare products'), $this);?>
<?php $this->_smarty_vars['capture']['path'] = ob_get_contents(); ob_end_clean(); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['tpl_dir'])."./breadcrumb.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<h2><?php echo smartyTranslate(array('s' => 'Compare products'), $this);?>
</h2>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => ($this->_tpl_vars['tpl_dir'])."./errors.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $this->assign('id_lang', ((is_array($_tmp=$this->_tpl_vars['cookie']->id_lang)) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp))); ?>

<table>
 <tr>
  <th></th>
  <?php $_from = $this->_tpl_vars['products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['products'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['products']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['product']):
        $this->_foreach['products']['iteration']++;
?>
   <?php $this->assign('productId', $this->_tpl_vars['product']['id']); ?>
   <th>
    <a class="compare_block_product_name" href="<?php echo $this->_tpl_vars['link']->getProductLink($this->_tpl_vars['productId'],$this->_tpl_vars['product']['link_rewrite'],$this->_tpl_vars['product']['category']); ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['name'][$this->_tpl_vars['id_lang']])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
"><?php echo smartyTruncate(array('text' => $this->_tpl_vars['product']['name'][$this->_tpl_vars['id_lang']],'length' => '16','encode' => 'true'), $this);?>
</a>
   </th>
  <?php endforeach; endif; unset($_from); ?>
 </tr>

 <tr>
  <td></td>
  <?php $_from = $this->_tpl_vars['products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['products'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['products']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['product']):
        $this->_foreach['products']['iteration']++;
?>
   <td><?php echo $this->_tpl_vars['product']['description'][$this->_tpl_vars['id_lang']]; ?>
</td>
  <?php endforeach; endif; unset($_from); ?>
 </tr>

 <?php $_from = $this->_tpl_vars['features']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['feature_name'] => $this->_tpl_vars['feature']):
?>
  <tr>
   <td><?php echo ((is_array($_tmp=$this->_tpl_vars['feature_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
</td>
   <?php $_from = $this->_tpl_vars['products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['products'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['products']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['product']):
        $this->_foreach['products']['iteration']++;
?>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['feature'][$this->_tpl_vars['product']['id']])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
</td>
   <?php endforeach; endif; unset($_from); ?>
  </tr>
 <?php endforeach; endif; unset($_from); ?>
</table>