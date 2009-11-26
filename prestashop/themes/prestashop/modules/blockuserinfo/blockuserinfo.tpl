<!-- Block user information module HEADER -->
<div id="header_user">
	<p id="header_user_info">
		{if $logged}
			<a href="{$base_dir}index.php?mylogout" class="button_login">{l s='Log out' mod='blockuserinfo'}</a>
		{else}
			<a href="{$base_dir_ssl}my-account.php" class="button_login">{l s='Log in' mod='blockuserinfo'}</a>
		{/if}
	</p>
</div>
<!-- /Block user information module HEADER -->
