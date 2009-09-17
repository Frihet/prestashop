<?php /* Smarty version 2.6.20, created on 2009-08-25 15:43:07
         compiled from /srv/www/shp-dev/prestashop/modules/blockcompare/blockcompare.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'intval', '/srv/www/shp-dev/prestashop/modules/blockcompare/blockcompare.tpl', 12, false),array('modifier', 'escape', '/srv/www/shp-dev/prestashop/modules/blockcompare/blockcompare.tpl', 32, false),array('function', 'l', '/srv/www/shp-dev/prestashop/modules/blockcompare/blockcompare.tpl', 17, false),array('function', 't', '/srv/www/shp-dev/prestashop/modules/blockcompare/blockcompare.tpl', 32, false),)), $this); ?>

<?php if ($this->_tpl_vars['ajax_allowed']): ?>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['content_dir']; ?>
js/jquery/iutil.prestashop-modifications.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['content_dir']; ?>
js/jquery/ifxtransfer.js"></script>
<script type="text/javascript" src="<?php echo $this->_tpl_vars['content_dir']; ?>
modules/blockcompare/ajax-compare.js"></script>
<?php endif; ?>

<?php $this->assign('id_lang', ((is_array($_tmp=$this->_tpl_vars['cookie']->id_lang)) ? $this->_run_mod_handler('intval', true, $_tmp) : intval($_tmp))); ?>

<!-- MODULE Block compare -->
<div id="compare_block" class="block exclusive">
	<h4>
		<a href="<?php echo $this->_tpl_vars['base_dir_ssl']; ?>
modules/blockcompare/compare.php"><?php echo smartyTranslate(array('s' => 'Compare','mod' => 'blockcompare'), $this);?>
</a>
		<?php if ($this->_tpl_vars['ajax_allowed']): ?>
		<span id="block_compare_expand" <?php if ($this->_tpl_vars['colapseExpandStatus'] == 'expanded'): ?>class="hidden"<?php endif; ?>>&nbsp;</span>
		<span id="block_compare_collapse" <?php if ($this->_tpl_vars['colapseExpandStatus'] == 'collapsed' || ! isset ( $this->_tpl_vars['colapseExpandStatus'] )): ?>class="hidden"<?php endif; ?>>&nbsp;</span>
		<?php endif; ?>
	</h4>
	<div class="block_content">
		<!-- block list of products -->
		<div id="compare_block_list" class="<?php if (true || ! $this->_tpl_vars['ajax_allowed'] || $this->_tpl_vars['colapseExpandStatus'] == 'expanded'): ?>expanded<?php else: ?>collapsed<?php endif; ?>">
			<?php if ($this->_tpl_vars['products']): ?>
				<dl class="products">
				<?php $_from = $this->_tpl_vars['products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['myLoop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['myLoop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['product']):
        $this->_foreach['myLoop']['iteration']++;
?>
					<?php $this->assign('productId', $this->_tpl_vars['product']['id']); ?>

					<dt id="compare_block_product_<?php echo $this->_tpl_vars['productId']; ?>
" class="<?php if (($this->_foreach['myLoop']['iteration'] <= 1)): ?>first_item<?php elseif (($this->_foreach['myLoop']['iteration'] == $this->_foreach['myLoop']['total'])): ?>last_item<?php else: ?>item<?php endif; ?>">
						<a class="compare_block_product_name" href="<?php echo $this->_tpl_vars['link']->getProductLink($this->_tpl_vars['productId'],$this->_tpl_vars['product']['link_rewrite'],$this->_tpl_vars['product']['category']); ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['product']['name'][$this->_tpl_vars['id_lang']])) ? $this->_run_mod_handler('escape', true, $_tmp, 'htmlall', 'UTF-8') : smarty_modifier_escape($_tmp, 'htmlall', 'UTF-8')); ?>
"><?php echo smartyTruncate(array('text' => $this->_tpl_vars['product']['name'][$this->_tpl_vars['id_lang']],'length' => '16','encode' => 'true'), $this);?>
</a>
						<span class="remove_link"><a class="ajax_compare_block_remove_link" href="<?php echo $this->_tpl_vars['base_dir']; ?>
compare.php?delete&amp;id_product=<?php echo $this->_tpl_vars['productId']; ?>
&amp;token=<?php echo $this->_tpl_vars['static_token']; ?>
" title="<?php echo smartyTranslate(array('s' => 'remove this product from my compare','mod' => 'blockcompare'), $this);?>
">&nbsp;</a></span>
					</dt>

				<?php endforeach; endif; unset($_from); ?>
				</dl>
			<?php endif; ?>
			<p <?php if ($this->_tpl_vars['products']): ?>class="hidden"<?php endif; ?> id="compare_block_no_products"><?php echo smartyTranslate(array('s' => 'No products','mod' => 'blockcompare'), $this);?>
</p>

			<p id="compare-buttons">
				<a href="<?php echo $this->_tpl_vars['base_dir_ssl']; ?>
modules/blockcompare/comparecart.php" class="button_small" title="<?php echo smartyTranslate(array('s' => 'Compare','mod' => 'blockcompare'), $this);?>
"><?php echo smartyTranslate(array('s' => 'Compare','mod' => 'blockcompare'), $this);?>
</a>

			</p>
		</div>
	</div>
</div>
<!-- /MODULE Block compare -->