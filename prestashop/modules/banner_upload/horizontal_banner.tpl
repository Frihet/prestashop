<!-- MODULE Block banner upload-->
{if $banner}
<div style = "width:100%; text-align: center;">
			{foreach from=$banner item=ban}
				{if $ban.type != 'x-sh'}
					{if $ban.url != 'http://'}
						{if $ban.new_window == '1'}
							<a href = "{$ban.url}" target = "_blank">
						{else}
							<a href = "{$ban.url}" >
						{/if}
					{/if}
					<img src = "{$base_dir}banner_img/{$ban.image}" alt = '{l s="Ads" mod="banner_upload"}' title = '{l s="Ads" mod="banner_upload"}'/>
					{if $ban.url != 'http://'}
						</a>
					{/if}
				{else}
					<object id="FlashID" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="{$ban.width}" height="{$ban.height}">
					<param name="movie" value= "{$base_dir}banner_img/{$ban.image}" /><param name="quality" value="high" />
					<param name="wmode" value="opaque" />
					<param name="swfversion" value="6.0.65.0" />
					<!-- This param tag prompts users with Flash Player 6.0 r65 and higher to download the latest version of Flash Player. Delete it if you don\'t want users to see the prompt. -->
					<param name="expressinstall" value="Scripts/expressInstall.swf" />
					<!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->
					<!--[if !IE]>-->
					<object type="application/x-shockwave-flash" data= "{$base_dir}banner_img/{$ban.image}" width="{$ban.width}" height="{$ban.height}">
					<!--<![endif]-->
					<param name="quality" value="high" />
					<param name="wmode" value="opaque" />
					<param name="swfversion" value="6.0.65.0" />
					<param name="expressinstall" value="Scripts/expressInstall.swf" />
					<!-- The browser displays the following alternative content for users with Flash Player 6.0 and older. -->
					<div>
					<p>Content on this page requires a newer version of Adobe Flash Player.</p>
					<p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" width="112" height="33" /></a></p>
					</div>
					<!--[if !IE]>-->
					</object>
					<!--<![endif]-->
					</object>
				{/if}
			{/foreach}

</div>
{/if}
<!--  END MODULE Block banner upload-->