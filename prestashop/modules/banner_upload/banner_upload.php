<?php
class banner_upload extends Module 
{	
	public function __construct() 
	{
		$this->name = 'banner_upload';
		$this->tab = 'BlMod';
		$this->version = 1.0;

		parent::__construct();

		$this->page = basename(__FILE__, '.php');
        $this->displayName = $this->l('Upload banners');
        $this->description = $this->l('Displays banner in your shop');
		$this->confirmUninstall = $this->l('Are you sure you want to delete a module?');
	}

	public function install() 
	{
        if(!parent::install())
			return false;
			
		if(!$this->registerHook('header')
			or !$this->registerHook('rightColumn') 
			or !$this->registerHook('leftColumn')
			or !$this->registerHook('footer')
			or !$this->registerHook('home')
			)
			return false;
		
		$sql_blmod_banner =
			'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'blmod_upl_banner
			(
				`id` int(7) NOT NULL AUTO_INCREMENT,
				`recordListingID` int(3) NOT NULL,
				`position` varchar(10) NOT NULL,
				`image` varchar(100) NOT NULL,
				`type` varchar(4) DEFAULT NULL,
				`url` varchar(250) DEFAULT NULL,
				`new_window` tinyint(1) DEFAULT NULL,
				`alt` varchar(150) DEFAULT NULL,
				`resize` tinyint(1) DEFAULT NULL,
				`width` int(11) DEFAULT NULL,
				`height` int(11) DEFAULT NULL,
				`status` tinyint(1) DEFAULT NULL,
				`id_lang` int(10) unsigned DEFAULT NULL,
				PRIMARY KEY (`id`)
			)';
		$sql_blmod_banner_res = Db::getInstance()->Execute($sql_blmod_banner);
		
		if(!$sql_blmod_banner_res)
			return false;
			
		return true;		
    }
	
	public function uninstall()
	{
		Db::getInstance()->Execute('DROP TABLE '._DB_PREFIX_.'blmod_upl_banner');
		
		return parent::uninstall();
	}
	
	function getContent()
	{
		$this->_html = '<h2>'.$this->displayName.' - V'. $this->version .'</h2>';
		$full_address_no_t = 'http://' . $_SERVER['HTTP_HOST'] . __PS_BASE_URI__.substr($_SERVER['PHP_SELF'], strlen(__PS_BASE_URI__)).'?tab=AdminModules&configure='.Tools::getValue('configure');
		$token = '&token='.Tools::getValue('token');
		
		$this->_html .='		
		<script type="text/javascript" src="../modules/banner_upload/jquery-1.3.2.min.js"></script>
		<script type="text/javascript" src="../modules/banner_upload/jquery-ui-1.7.1.custom.min.js"></script>
		<script type="text/javascript" src="../modules/banner_upload/order_j.js"></script>
		<link rel="stylesheet" href="../modules/banner_upload/banner_uploader.css" type="text/css" />
		';	
		
		if(isset($_POST['btnDelete']) and isset($_POST['id']))
			$this->delete_image($_POST['id']);	
		
		$this->page_structure($full_address_no_t, $token);
		
		return $this->_html;
	}
	
	public function page_structure($full_address_no_t, $token)
	{
		$page = Tools::getValue('block');
		$this->_html .= '
		<div style="float: left; width: 230px;">';
			$this->categories($full_address_no_t, $token, $page);
		$this->_html .= '</div>
		<div style="float: left; margin-left: 20px; width: 677px;">';
		
		
		if(empty($page) or ($page != 'left' and $page != 'right' and $page != 'footer' and $page != 'home'))
			$page = 'header';
		
		if(!isset($_POST['btnUpdate']))
			$this->insert_form($id=null, $page);
		elseif(isset($_POST['id']))
			$this->insert_form($_POST['id'], $page);
		elseif(!empty($page))
			$this->insert_form(null, $page);
		
		if(!empty($page))
			$this->allBannerDisplayForm($page);
			
		$this->_html .= '
		</div>
		<div style = "clear: both; font-size: 0px;"></div>
		';
	}
	
	function change_to_friendly_name($title)
	{		
		$title = strtolower(trim($title));
		$title = iconv('UTF-8', 'LATIN1//TRANSLIT', $title);
		$title = preg_replace('/[^a-z0-9-.]/', '_', $title);
		$title = preg_replace('/-+/', "_", $title);
		$title = preg_replace('/"\'/', "", $title);

		return $title;
	}

	public function categories($full_address_no_t, $token, $page)
	{
		$style = 'style="font-weight:bold;"';
		$style_f = '';
		$style_h = '';
		$style_l = '';
		$style_r = '';
		$style_ho = '';
		
		$all_block = Db::getInstance()->ExecuteS('
			SELECT COUNT(position) AS c, position
			FROM '._DB_PREFIX_.'blmod_upl_banner
			GROUP BY position
		');
		
		switch($page)
		{
			case "":
				$style_h = $style;
				break;
			case "footer":
				$style_f = $style;
				break;
			case "header":
				$style_h = $style;
				break;
			case "left":
				$style_l = $style;
				break;
			case "right":
				$style_r = $style;
				break;
			case "home":
				$style_ho = $style;
				break;
		}

		foreach($all_block as $count)
		{
			if($count['position'] == 'footer')
				$block_f = $count['c'];
			
			if($count['position'] == 'header')
				$block_h = $count['c'];
			
			if($count['position'] == 'left')
				$block_l = $count['c'];
			
			if($count['position'] == 'right')
				$block_r = $count['c'];
			
			if($count['position'] == 'home')
				$block_ho = $count['c'];	
		}

		$block_f = isset($block_f) ? $block_f : 0;
		$block_h = isset($block_h) ? $block_h : 0;
		$block_l = isset($block_l) ? $block_l : 0;
		$block_r = isset($block_r) ? $block_r : 0;
		$block_ho = isset($block_ho) ? $block_ho : 0;
		
		$this->_html .= '		
			<fieldset><legend><img src="../img/admin/summary.png" alt="'.$this->l('Blocks').'" title="'.$this->l('Blocks').'" />'.$this->l('Blocks').'</legend>
				<table border="0" width="100%" cellpadding="3" cellspacing="0">
					<tr>
						<td>
							<img src="../img/admin/tab-categories.gif" alt="" title="" /><a '.$style_ho.' href = "'.$full_address_no_t.'&block=home'.$token.'">'.$this->l('Home page').'</a> ('.$block_ho.')<br/>	
							<img src="../img/admin/tab-categories.gif" alt="" title="" /><a '.$style_h.' href = "'.$full_address_no_t.'&block=header'.$token.'">'.$this->l('Header').'</a> ('.$block_h.')<br/>
							<img src="../img/admin/tab-categories.gif" alt="" title="" /><a '.$style_l.' href = "'.$full_address_no_t.'&block=left'.$token.'">'.$this->l('Left column').'</a> ('.$block_l.')<br/>	
							<img src="../img/admin/tab-categories.gif" alt="" title="" /><a '.$style_r.' href = "'.$full_address_no_t.'&block=right'.$token.'">'.$this->l('Right column').'</a> ('.$block_r.')<br/>	
							<img src="../img/admin/tab-categories.gif" alt="" title="" /><a '.$style_f.' href = "'.$full_address_no_t.'&block=footer'.$token.'">'.$this->l('Footer').'</a> ('.$block_f.')<br/>														
						</td>
					</tr>
				</table>
		</fieldset><br/><br/>';
	}
	
	public function resize($folder)
	{
		$img_size = @getimagesize($folder);		
		$data = array();
		$resize = false;
		
		if(isset($img_size['mime']))
		{
			$width = $img_size[0];						
			$height = $img_size[1];			

			if($width > 240)
			{	
				$resize = true;
						
				$width_p = $width / 240;
				$width = 240;
				$height = $height / $width_p;
				$height = (int)$height;
			}
					
			if($height > 240)
			{
				$resize = true;
						
				$height_p = $height / 240;
				$height = 240;

				$width = $width / $height_p;
				$width = (int)$width;						
			}						
		}
		
		$data[] = isset($width) ? $width : 0;
		$data[] = isset($height) ? $height : 0;
		$data[] = $resize;
		
		return $data;
	}
	
	public function allBannerDisplayForm($page=false)
	{
		global $smarty;		

		$this->_html .=	'
		<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
			<div id="block_name" class="'.$page.'"></div>
			<div id = "content_images_dd" class = "content_images_table"><ul><div id="dd_message"></div>';			
			
			$all_banner_right = Db::getInstance()->ExecuteS('
				SELECT * FROM '._DB_PREFIX_.'blmod_upl_banner
				WHERE position = "'.$page.'"
				ORDER by recordListingID ASC
			');
			
			$i = 0;			
			
			if(!empty($all_banner_right))
			{
				foreach($all_banner_right as $banner_l)
				{
					$banner_l['status'] = isset($banner_l['status']) ? $banner_l['status'] : false;
					$i++;
					$bg = $i%2;
					
					if($bg == 0)
						$bg_table = 'line_dark';
					else
						$bg_table = '';
						
					$folder = '../banner_img/'.$banner_l['image'];
					$img_size = $this->resize($folder);
					
					if ($banner_l['id_lang'] == "")
                                                $banner_lang = "All languages";
					else {
						$banner_lang = Language::getLanguage($banner_l['id_lang']);
						$banner_lang = $banner_lang['isoc_code'] . ': ' . $banner_lang['name'];
					}

					$this->_html .='<li id="recordsArray_'.$banner_l['id'].'" class = "content_images_line '.$bg_table.'">
					
						<div class="banner_line_text">
						<img src="../img/admin/picture.gif" alt="" title="" /> <input type="radio" name="id" value="'.$banner_l['id'].'" /> <br/>
						<img src="../img/admin/home.gif" alt="" title="" /> <b>'.$this->l('Title:').'</b> <span>'.$banner_l['alt'].'</span><br/>
						<img src="../img/admin/home.gif" alt="" title="" /> <b>'.$this->l('Language:').'</b> <span>'.$banner_lang.'</span><br/>
						<img src="../img/admin/subdomain.gif" alt="" title="" /> <b>'.$this->l('Link:').'</b> <a href = "'.$banner_l['url'].'" target = "_blank">'.$banner_l['url'].'<a/><br/>
						<img src="../img/admin/access.png" /> <b>'.$this->l('Status:').'</b> <input type="checkbox" name="status"' . $this->status($banner_l['status'], true).'
						</div>
						<div style="float: left;">';
										
						if(strtolower($banner_l['type']) != 'x-sh')
							$this->_html .='<a href = "'.$folder.'" target = "_blank"><img width="'.$img_size[0].'" height="'.$img_size[1].'" src = "'.$folder.'" alt = "'.$this->l('Show real size').'" title = "'.$this->l('Show real size').'"/></a><input type = "hidden" value = "'.$banner_l['image'].'" name = "image" />';
						else
						{
							$this->_html .='
							<object id="FlashID" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'.$img_size[0].'" height="'.$img_size[1].'">
							<param name="movie" value= "'.$folder.'" /><param name="quality" value="high" />
							<param name="wmode" value="opaque" />
							<param name="swfversion" value="6.0.65.0" />
							<!-- This param tag prompts users with Flash Player 6.0 r65 and higher to download the latest version of Flash Player. Delete it if you don\'t want users to see the prompt. -->
							<param name="expressinstall" value="Scripts/expressInstall.swf" />
							<!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->
							<!--[if !IE]>-->
							<object type="application/x-shockwave-flash" data= "'.$folder.'" width="'.$img_size[0].'" height="'.$img_size[1].'">
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
							';
						}
						$this->_html .=	'</div><div style="clear: both; font-size: 0px;"></div>
					</li>';
				}
			}			

			$this->_html .='
			<br/></br>
			<center>
				<input type="submit" name="btnUpdate" class="button" value="'.$this->l('Edit').'">
				<input type="submit" name="btnDelete" class="button" value="'.$this->l('Delete').'">
			</center>
			</ul></div>
			
			
		</form>';
	}	
		
	public function delete_image($banner_id)
	{
		$img_name = Db::getInstance()->getRow('SELECT image FROM '._DB_PREFIX_.'blmod_upl_banner WHERE id = "'.$banner_id.'"');
		$folder_address = "../banner_img/" . $img_name['image'];
		if (file_exists($folder_address))
		{
			@unlink($folder_address);
		}
		Db::getInstance()->Execute('DELETE FROM '._DB_PREFIX_.'blmod_upl_banner WHERE id = "'.$banner_id.'"');
		$this->_html .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="'.$this->l('Confirmation').'" />'.$this->l('Delete successfully').'</div>'; 
	}
	
	public function insert_image_to_db($Link, $NewTab, $NewFile, $ImgAlt, $Position, $status_banner, $id_lang)
	{
		$user_file = $this->change_to_friendly_name($NewFile['name']);
		$file_temp = $NewFile['tmp_name'];
		$folder = "../banner_img/" . $user_file;
		$file_types = array('image/gif', 'image/jpeg', 'image/png', 'image/jpg', 'image/bmp', 'image/x-png', 'image/pjpeg', 'application/x-shockwave-flash');
		$type = explode('/', $NewFile['type']);
		
		$status = true;
		
		do
		{
			$check_value = Db::getInstance()->getRow('SELECT image FROM '._DB_PREFIX_.'blmod_upl_banner WHERE image = "'.$NewFile['name'].'"');
			
			if(isset($check_value['image']))
			{
				$user_file = rand(0, 9999).$user_file;
				$folder = "../banner_img/" . $user_file;
				$status = false;
				$NewFile['name'] = $user_file;
			}
			else			
				$status = true;			
		}
		while(!$status);

		if($check_value)		
			$this->_html .= '<div class="warning warn"><img src="../img/admin/warning.gif" alt="'.$this->l('Confirmation').'" />'.$this->l('This file exists').'</div>';
		else
		{		
			if(empty($NewFile['error']) and in_array($NewFile['type'], $file_types))
			{	  
				$uploaded_file = move_uploaded_file($file_temp, $folder);
				$img_size = @getimagesize($folder);
				$img_size[0] = isset($img_size[0]) ? $img_size[0] : 0;
				$img_size[1] = isset($img_size[1]) ? $img_size[1] : 0;
				
				Db::getInstance()->Execute('
					INSERT INTO '._DB_PREFIX_.'blmod_upl_banner 
					(position, image, type, url, new_window, alt, width, height, status, id_lang)
					VALUES 
					("'.$Position.'", "'.$user_file.'", "'.$type[1].'", "'.htmlspecialchars($Link, ENT_QUOTES).'", "'.$NewTab.'", "'.htmlspecialchars($ImgAlt, ENT_QUOTES).'", "'.$img_size[0].'", "'.$img_size[1].'", "'.$status_banner.'", '.$id_lang.')');
				$this->_html .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="'.$this->l('Confirmation').'" />'.$this->l('Save successfully').'</div>'; 
			} 
			else
			{
				$this->_html .= '<div class="warning warn"><img src="../img/admin/warning.gif" alt="'.$this->l('Confirmation').'" />'.$this->l('Bad image file, support: *.jpg, *.jpeg, *.png, *.gif, *.bmp, *.swf').'</div>';
			}			
		}
	}
	
	public function update_old_image($Link, $NewTab, $NewFile, $ImgAlt, $Position, $OldImageName, $OldImageId, $status_banner, $id_lang)
	{
		$check_value = Db::getInstance()->getRow('SELECT image FROM '._DB_PREFIX_.'blmod_upl_banner WHERE image = "'.$NewFile['name'].'"');

		if(!$check_value && !empty($NewFile['tmp_name']))
		{
			//Insert new image
			$folder_address = "../banner_img/" . $OldImageId;
			@unlink($folder_address);
			
			$user_file = $this->change_to_friendly_name($NewFile['name']);
			$file_temp = $NewFile['tmp_name'];
			$folder = "../banner_img/" . $user_file;
			$folder_old = "../banner_img/" . $OldImageName;
			$file_types = array('image/gif', 'image/jpeg', 'image/png', 'image/jpg', 'image/bmp', 'image/x-png', 'image/pjpeg', 'application/x-shockwave-flash');			
			$type = explode('/', $NewFile['type']);			
			
			$status = true;		
			do
			{
				$check_value = Db::getInstance()->getRow('SELECT image FROM '._DB_PREFIX_.'blmod_upl_banner WHERE image = "'.$NewFile['name'].'"');
				
				if(isset($check_value['image']))
				{
					$user_file = rand(0, 9999).$user_file;
					$folder = "../banner_img/" . $user_file;
					$status = false;
					$NewFile['name'] = $user_file;
				}
				else			
					$status = true;			
			}
			while(!$status);
			
			if(empty($NewFile['error']) and in_array($NewFile['type'], $file_types))
			{				
				$uploaded_file = move_uploaded_file($file_temp, $folder);
				$img_size = @getimagesize($folder);
				$img_size[0] = isset($img_size[0]) ? $img_size[0] : 0;
				$img_size[1] = isset($img_size[1]) ? $img_size[1] : 0;
				
				Db::getInstance()->Execute('UPDATE '._DB_PREFIX_.'blmod_upl_banner 
				SET position = "'.$Position.'", image = "'.$user_file.'", type = "'.$type[1].'", url = "'.htmlspecialchars($Link, ENT_QUOTES).'", 
				new_window = "'.$NewTab.'", alt = "'.htmlspecialchars($ImgAlt, ENT_QUOTES).'", width = "'.$img_size[0].'", height = "'.$img_size[1].'", status = "'.$status_banner.'", id_lang = '.$id_lang.'
				WHERE id = "'.$OldImageId.'"');
				
				//delete old image
				@unlink($folder_old);
				
				$this->_html .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="'.$this->l('Confirmation').'" />'.$this->l('Save successfully').'</div>'; 
			}
			else			
				$this->_html .= '<div class="warning warn"><img src="../img/admin/warning.gif" alt="'.$this->l('Confirmation').'" />'.$this->l('Bad image file, support: *.jpg, *.jpeg, *.png, *.gif, *.bmp, *.swf').'</div>';
		}
		elseif(empty($NewFile['tmp_name']))
		{
			//Leave old image
			Db::getInstance()->Execute('UPDATE '._DB_PREFIX_.'blmod_upl_banner 
			SET url = "'.htmlspecialchars($Link, ENT_QUOTES).'", new_window = "'.$NewTab.'", alt = "'.htmlspecialchars($ImgAlt, ENT_QUOTES).'", status = "'.$status_banner.'"
			WHERE id = "'.$OldImageId.'"');
			
			$this->_html .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="'.$this->l('Confirmation').'" />'.$this->l('Update successfully').'</div>';
		}
		elseif($check_value && !empty($NewFile['tmp_name']))
		{
			if($check_value)
			{
				$this->_html .= '<div class="warning warn"><img src="../img/admin/warning.gif" alt="'.$this->l('Confirmation').'" />'.$this->l('This file exists').'</div>';
			}
		}
	}
	
	public function status($status, $disabled = false)
	{
		if($disabled)
			$disabled = 'disabled';
		else
			$disabled = '';
			
		if(isset($status) and $status == 1)
			$status_text = ' value = "1" checked '.$disabled.' /> <img src="../img/admin/enabled.gif" alt = "'.$this->l('Enabled').'" />' . $this->l('Enabled');
		else
			$status_text = ' value = "1" '.$disabled.'/> <img src="../img/admin/disabled.gif" alt = "'.$this->l('Disabled').'" />' . $this->l('Disabled');
			
		return $status_text;
	}
	
	public function insert_form($id, $page)
	{
		global $cookie;
		
		$page = htmlspecialchars($page, ENT_QUOTES);
		
		switch($page)
		{
			case "footer":
				$block_name = $this->l('Footer');
				break;
			case "header":
				$block_name = $this->l('Header');
				break;
			case "left":
				$block_name = $this->l('Left column');
				break;
			case "right":
				$block_name = $this->l('Right column');
				break;
			case "home":
				$block_name = $this->l('Home page');
				break;
		}
		
		if($id)		
			$banner_info = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'blmod_upl_banner WHERE id = "'.$id.'"');			
		
		if(isset($_POST['InsertNewImage']))
		{
			$_POST['NewTab'] = (isset($_POST['NewTab']) ? $_POST['NewTab'] : '0');
			$_POST['ImgAlt'] = (isset($_POST['ImgAlt']) ? $_POST['ImgAlt'] : '');
			$_POST['status'] = (isset($_POST['status']) ? $_POST['status'] : '0');
			$_POST['Link'] = (isset($_POST['Link']) ? $_POST['Link'] : '0');
			$_POST['id_lang'] = (isset($_POST['id_lang']) ? $_POST['id_lang'] : null);

			$this->insert_image_to_db($_POST['Link'], $_POST['NewTab'], $_FILES['NewFile'], $_POST['ImgAlt'], $_POST['Position'], $_POST['status'], $_POST['id_lang']);
		}

		if(isset($_POST['UpdateOldImage']))
		{
			$_POST['NewTab'] = (isset($_POST['NewTab']) ? $_POST['NewTab'] : '0');
			$_POST['ImgAlt'] = (isset($_POST['ImgAlt']) ? $_POST['ImgAlt'] : '');
			$_POST['status'] = (isset($_POST['status']) ? $_POST['status'] : '0');
			$_POST['Link'] = (isset($_POST['Link']) ? $_POST['Link'] : '0');
			$_POST['id_lang'] = (isset($_POST['id_lang']) ? $_POST['id_lang'] : null);
			
			$this->update_old_image($_POST['Link'], $_POST['NewTab'], $_FILES['NewFile'], $_POST['ImgAlt'], $_POST['Position'], $_POST['OldImageName'], $_POST['OldImageId'], $_POST['status'], $_POST['id_lang']);
		}			
		
		$banner_info['url'] = isset($banner_info['url']) ? $banner_info['url'] : false;
		$banner_info['new_window'] = isset($banner_info['new_window']) ? $banner_info['new_window'] : false;
		$banner_info['alt'] = isset($banner_info['alt']) ? $banner_info['alt'] : false;
		$banner_info['position'] = isset($banner_info['position']) ? $banner_info['position'] : false;
		$banner_info['image'] = isset($banner_info['image']) ? $banner_info['image'] : false;
		$banner_info['type'] = isset($banner_info['type']) ? $banner_info['type'] : false;
		$banner_info['status'] = isset($banner_info['status']) ? $banner_info['status'] : false;
		
		$this->_html .= '
		<fieldset><legend><img src="../img/admin/tab-preferences.gif" alt="" title="" />'.$block_name.'</legend>
			<table border="0" width="100%" cellpadding="3" cellspacing="0">
				<tr>
					<td width="500">
						<form action="'.$_SERVER['REQUEST_URI'].'" method="post" enctype="multipart/form-data">
							<table border="0" width="100%" cellpadding="3" cellspacing="0">
								<tr>
									<td width="20"><img src="../img/admin/home.gif" alt="" title="" /></td>
									<td width="100"><b>'.$this->l('Title:').'</b></td>
									<td colspan = "5">
										<input type="text" name="ImgAlt"';
										if($banner_info['alt'])										
											$this->_html .= 'value="'.$banner_info['alt'].'"';										
										else										
											$this->_html .= 'value=""';										
										
										$this->_html .= 'size="50"/>
									</td>
								</tr>
								<tr><td>&nbsp;</td></tr>
								<tr>
									<td width="20"><img src="../img/admin/home.gif" alt="" title="" /></td>
									<td width="100"><b>'.$this->l('Language:').'</b></td>
									<td colspan = "5">
										<select name="id_lang">';
										         $current = '';
												 if ($banner_info['id_lang'] == "")
												         $current = ' selected="selected" ';
											 $this->_html .= '<option value="null"' . $current . '>All languages</option>';

                                                                                         $langs = Language::getLanguages();
											 
											 foreach ($langs as $lang) {
											         $current = '';
												 if ($lang['id_lang'] == $banner_info['id_lang'])
												         $current = ' selected="selected" ';
											         $this->_html .= '<option value="' . $lang['id_lang'] . '"' . $current . '>' . $lang['iso_code'] . ': ' . $lang['name'] . '</option>';
                                                                                         }
										
										$this->_html .= '</select>
									</td>
								</tr>
								<tr><td>&nbsp;</td></tr>
								<tr>
									<td width="20"><img src="../img/admin/subdomain.gif" alt="" title="" /></td>
									<td width="100"><b>'.$this->l('Link:').'</b></td>
									<td colspan = "5">
										<input type="text" name="Link"';
										if(!$id)										
											$this->_html .= 'value ="http://"';										
										elseif($banner_info['url'])										
											$this->_html .= 'value = "' . $banner_info['url'] . '"';
										
										$this->_html .= 'size="30"/>
										'.$this->l('in a new window:'). '
										<input type="checkbox" name="NewTab" value="1"';
										if($banner_info['new_window'])										
											$this->_html .= ' checked ';
										
										$this->_html .= '
										/>
									</td>
								</tr>
								<tr><td>&nbsp;</td></tr>
								<tr>
									<td width="20"><img src="../img/admin/access.png" /></td>
									<td width="100"><b>'.$this->l('Status:').'</b></td>
									<td colspan="4"><input type="checkbox" name="status"';
									
									if($id)									
										$this->_html .= $this->status($banner_info['status']);
									else
										$this->_html .= 'value = "1" checked/>';
									
									$this->_html .= '
									</td>
								</tr>
								<tr><td>&nbsp;</td></tr>
								<tr>
									<td width="20"><img src="../img/admin/picture.gif" /></td>
									<td width="100"><b>'.$this->l('Image:').'</b></td>
									<td colspan="4"><input type="file" name="NewFile" size = "49"/>
									</td>
								</tr>
								<tr><td>&nbsp;</td></tr>';
								if($banner_info['image'])
								{
									$this->_html .= '
									<tr>
										<td width="20"><img src="../img/admin/picture.gif" /></td>
										<td width="100"><b>'.$this->l('Old image').'</b></td>
										<td colspan="4">									
									';
									$folder = '../banner_img/'.$banner_info['image'];
									$img_size = $this->resize($folder);
								
									if(strtolower($banner_info['type']) != 'x-sh')
										$this->_html .='<img width="'.$img_size[0].'" height="'.$img_size[1].'" src = "'.$folder.'" /><input type = "hidden" value = "'.$banner_info['image'].'" name = "image" />';
									else
									{
										$this->_html .='
										<object id="FlashID" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'.$img_size[0].'" height="'.$img_size[1].'">
										<param name="movie" value= "'.$folder.'" /><param name="quality" value="high" />
										<param name="wmode" value="opaque" />
										<param name="swfversion" value="6.0.65.0" />
										<!-- This param tag prompts users with Flash Player 6.0 r65 and higher to download the latest version of Flash Player. Delete it if you don\'t want users to see the prompt. -->
										<param name="expressinstall" value="Scripts/expressInstall.swf" />
										<!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->
										<!--[if !IE]>-->
										<object type="application/x-shockwave-flash" data= "'.$folder.'" width="'.$img_size[0].'" height="'.$img_size[1].'">
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
										';
									}
									$this->_html .= '</td>
									</tr>
									<tr><td>&nbsp;</td></tr>';
								}
								$this->_html .='
								<input type = "hidden" name = "Position" value = "'.$page.'" />
							</table>							
							<table border="0" width="500" cellpadding="3" cellspacing="0">
								<tr>
									<td width="500" colspan="2">';
									if(!$banner_info['position'])
									{
										$this->_html .= '<center><input type="submit" name="InsertNewImage" value="'.$this->l('Insert').'" class="button" /></center>';
									}
									else
									{
										$this->_html .= '<input type="hidden" name="OldImageId" value = "';
										$this->_html .= $id . '"/>';	
										$this->_html .= '<input type="hidden" name="OldImageName" value = "';
										$this->_html .= $banner_info['image'];
										$this->_html .= '"/><center><input type="submit" name="UpdateOldImage" value="'.$this->l('Update').'" class="button" /></center>';
									}
									$this->_html .= '</td>
								</tr>
							</table>
						</form>
					</td>
					<td width="400" valign="top">';
						
					$this->_html .= '</td>
				</tr>
			</table>
		</fieldset>';
	}
	
	public function get_banner($block)
	{
	        global $cookie;

		$banner = Db::getInstance()->ExecuteS('
			SELECT image, type, url, new_window, width, height
			FROM '._DB_PREFIX_.'blmod_upl_banner
			WHERE status = "1" AND position = "'.$block.'" AND (id_lang IS NULL OR id_lang = "'.$cookie->id_lang.'")
			ORDER by recordListingID ASC
		');
		
		return $banner;
	}
	
	public function hookHome($params)
	{
		global $smarty;
		
		$banner = $this->get_banner('home');
		
		$smarty->assign('banner', $banner);
		return $this->display(__FILE__, 'horizontal_banner.tpl');
	}
	
	public function hookHeader($params)
	{
		global $smarty;
		
		$banner = $this->get_banner('header');
		
		$smarty->assign('banner', $banner);
		return $this->display(__FILE__, 'horizontal_banner.tpl');
	}
	
	public function hookLeftColumn($params)
	{
		global $smarty;
		
		$banner = $this->get_banner('left');
		
		$smarty->assign('banner', $banner);
		return $this->display(__FILE__, 'vertical_banner.tpl');
	}
	
	public function hookRightColumn($params)
	{
		global $smarty;
		
		$banner = $this->get_banner('right');
		
		$smarty->assign('banner', $banner);
		return $this->display(__FILE__, 'vertical_banner.tpl');
	}
	
	public function hookFooter($params)
	{
		global $smarty;
		
		$banner = $this->get_banner('footer');
		
		$smarty->assign('banner', $banner);
		return $this->display(__FILE__, 'horizontal_banner.tpl');
	}	
}
?>