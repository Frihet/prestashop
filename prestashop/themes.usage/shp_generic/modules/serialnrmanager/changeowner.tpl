{capture name=path}<a href="{$base_dir_ssl}my-account.php">{l s='My account'}</a><span class="navigation-pipe">{$navigationPipe}</span><a href="manage.php?serial={$product_instance.serial}">{l s='Manage serial nr:s'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='Change owner of product'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

{variablebox class="universal"}
<h2>{l s='Change owner of product'}</h2>
{variablebox_content}

<table class="std">
 <tbody>
  <tr>
   <td>{l s='Serial number'}</td>
   <td>{$product_instance.serial}</td>
  </tr>
  <tr>
   <td>{l s='Model'}</td>
   <td>{$product_instance.product_name}</td>
  </tr>
  <tr>
   <td>{l s='Date of sale'}</td>
   <td>{$product_instance.order_invoice_date}</td>
  </tr>
  <tr>
   <td>{l s='Vendor'}</td>
   <td>{$product_instance.vendor_title}</td>
  </tr>
  <tr>
   <td>{l s='Current owner'}</td>
   <td>{$product_instance.current_owner_firstname} {$product_instance.current_owner_lastname} &lt;{$product_instance.current_owner_email}&gt;</td>
  </tr>
 </tbody>
</table>

{include file=$tpl_dir./errors.tpl}

<form method="post">
 <label for="new_owner_email">{l s='E-mail address of new owner'}</label>
 <input type="text" name="new_owner_email" id="new_owner_email" value="{$product_instance.current_owner_email}"></input>
 <input type="submit" class="button" value="{l s='Change owner'}"></input>
</form>

<ul class="footer_links">
	<li><a href="{$base_dir_ssl}my-account.php"><img src="{$img_dir}icon/my-account.gif" alt="" class="icon" /></a><a href="{$base_dir_ssl}my-account.php">{l s='Back to Your Account'}</a></li>
	<li><a href="{$base_dir}"><img src="{$img_dir}icon/home.gif" alt="" class="icon" /></a><a href="{$base_dir}">{l s='Home'}</a></li>
</ul>
{/variablebox}
