{capture name=path}<a href="{$base_dir_ssl}my-account.php">{l s='My account' mod='serialnrmanager'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='Manage serial nr:s' mod='serialnrmanager'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

{variablebox class="universal"}
<h2>{l s='Manage serial nr:s' mod='serialnrmanager'}</h2>
{variablebox_content}

<form method="get">
 <label for="serial">{l s='Serial number' mod='serialnrmanager'}</label>
 <input type="text" name="serial" id="serial" value="{$serial}"></input>
 <input type="submit" class="button_small" value="{l s='Search' mod='serialnrmanager'}"></input>
</form>

{if $serial != ''}
 <table class="std">
  <thead>
   <tr>
    <th class="first_item">{l s='Serial number' mod='serialnrmanager'}</th>
    <th class="item">{l s='Model' mod='serialnrmanager'}</th>
    <th class="item">{l s='Date of sale' mod='serialnrmanager'}</th>
    <th class="item">{l s='Vendor' mod='serialnrmanager'}</th>
    <th class="item">{l s='Current owner' mod='serialnrmanager'}</th>
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
	<li><a href="{$base_dir_ssl}my-account.php"><img src="{$img_dir}icon/my-account.gif" alt="" class="icon" /></a><a href="{$base_dir_ssl}my-account.php">{l s='Back to Your Account' mod='serialnrmanager'}</a></li>
	<li><a href="{$base_dir}"><img src="{$img_dir}icon/home.gif" alt="" class="icon" /></a><a href="{$base_dir}">{l s='Home' mod='serialnrmanager'}</a></li>
</ul>
{/variablebox}
