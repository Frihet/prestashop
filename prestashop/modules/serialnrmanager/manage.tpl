{capture name=path}<a href="{$base_dir_ssl}my-account.php">{l s='My account'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='Manage serial nr:s'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

<h2>{l s='Manage serial nr:s'}</h2>

<form method="get">
 <label for="serial">{l s='Serial number'}</label>
 <input type="text" name="serial" id="serial" value="{$serial}"></input>
 <input type="submit" class="button_small" value="{l s='Search'}"></input>
</form>

{if $serial != ''}
 <table class="std">
  <thead>
   <tr>
    <th class="first_item">{l s='Serial number'}</th>
    <th class="item">{l s='Model'}</th>
    <th class="item">{l s='Date of sale'}</th>
    <th class="item">{l s='Vendor'}</th>
    <th class="item">{l s='Current owner'}</th>
    <th class="last_item"></th>
   </tr>
  </thead>
  <tbody>
   {foreach from=$product_instances item='instance'}
    <tr>
     <td>{$instance.serial}</td>
     <td>{$instance.product_name}</td>
     <td>{$instance.order_invoice_date}</td>
     <td>{$instance.vendor_title}</td>
     <td>{$instance.current_owner_firstname} {$instance.current_owner_lastname} &lt;{$instance.current_owner_email}&gt;</td>
     <td>{if $current_vendor || $instance.id_current_owner == $current_customer}<a href="changeowner.php?serial={$instance.serial}">Change owner</a>{/if}</td>
    </tr>
   {/foreach}
  </tbody>
 </table>
{else}
 <div>Please enter a serial number or part of a serial number.</div>
{/if}

<ul class="footer_links">
	<li><a href="{$base_dir_ssl}my-account.php"><img src="{$img_dir}icon/my-account.gif" alt="" class="icon"></a><a href="{$base_dir_ssl}my-account.php">{l s='Back to Your Account'}</a></li>
	<li><a href="{$base_dir}"><img src="{$img_dir}icon/home.gif" alt="" class="icon"></a><a href="{$base_dir}">{l s='Home'}</a></li>
</ul>
