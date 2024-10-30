<?php
	function cache9_setting_page() {
		global $arrCache9Options, $arrExtentions, $wpdb, $_GET;
		$arrCache9Options = get_option('cache9_settings');
		$cache9ActivateChecked = "";
		$cache9SyncContentChecked = "";
		$cache9SyncIncludesChecked = "";
		$cache9DebugChecked = "";

		if (@$arrCache9Options['cdn_setting'] == 1)
		{
			if (@$arrCache9Options['cdn_activate'] == 1) $cache9ActivateChecked = "checked";
			else $arrCache9Options['cdn_activate'] = 0;
			if (@$arrCache9Options['cdn_content'] == 1) $cache9SyncContentChecked = "checked";
			else $arrCache9Options['cdn_content'] = 0;
			if (@$arrCache9Options['cdn_includes'] == 1) $cache9SyncIncludesChecked = "checked";
			else $arrCache9Options['cdn_includes'] = 0;
		}
		else
		{
			$arrCache9Options['cdn_activate'] == 0;
			$arrCache9Options['cdn_ext_set'] = getDefaultExtentions($arrExtentions);
			$arrCache9Options['cdn_content'] = 1;
			$arrCache9Options['cdn_includes'] = 1;
		}
		ob_start();
		if ( (@$arrCache9Options['cdn_id'] == "") )
		{
			include_once("start-ui.php");
		}
		else
		{
			if (@$arrCache9Options['cdn_free_status'] == 1)
			{
				date_default_timezone_set('UTC');
				if (@$arrCache9Options['cdn_free_exp'] < date("Y-m-d H:i:s", mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))) ) $arrCache9Options['cdn_free_status']=2;
			}
			if (@$arrCache9Options['cdn_url'] == "") $cache9CDNURL = "http://".$arrCache9Options['cdn_id'].".cache9.net";
			else $cache9CDNURL = $arrCache9Options['cdn_url'];
			include_once("setting-ui.php");
		}
		echo ob_get_clean();

		$cache9TablePrefix = lstrstr($wpdb->prefix, "_")."_";
		$cache9Table = $cache9TablePrefix . 'cache9_cdn';

		if (@$_GET["settings-updated"] == "true")
		{
			if ($wpdb->get_var("SELECT COUNT(*) FROM ".$cache9Table))
			{
				// update
				$wpdb->get_results(
					" update ".$cache9Table."
					SET
						cdn_activate=".$arrCache9Options['cdn_activate']."
						, cdn_id='".$arrCache9Options['cdn_id']."'
						, cdn_api_key='".$arrCache9Options['cdn_api_key']."'
						, cdn_url='".$arrCache9Options['cdn_url']."'
						, cdn_content=".$arrCache9Options['cdn_content']."
						, cdn_includes=".$arrCache9Options['cdn_includes']."
						, cdn_ext='".json_encode($arrCache9Options['cdn_ext_set'])."'
						, mname='".rstrstr(get_option("blogname"),"-")."'
						, cdn_free_status='".$arrCache9Options['cdn_free_status']."'
						, cdn_free_exp='".$arrCache9Options['cdn_free_exp']."'
					"
				);
			}
			else
			{
				// insert
				$wpdb->get_results(
					" insert into ".$cache9Table."
					SET
						cdn_activate=".$arrCache9Options['cdn_activate']."
						, cdn_id='".$arrCache9Options['cdn_id']."'
						, cdn_api_key='".$arrCache9Options['cdn_api_key']."'
						, cdn_url='".$arrCache9Options['cdn_url']."'
						, cdn_content=".$arrCache9Options['cdn_content']."
						, cdn_includes=".$arrCache9Options['cdn_includes']."
						, cdn_ext='".json_encode($arrCache9Options['cdn_ext_set'])."'
						, mname='".rstrstr(get_option("blogname"),"-")."'
						, cdn_free_status='".$arrCache9Options['cdn_free_status']."'
						, cdn_free_exp='".$arrCache9Options['cdn_free_exp']."'
					"
				);
			}
		}
		else
		{
			if ($wpdb->get_var( "SHOW TABLES LIKE '{$cache9Table}'") != $cache9Table) {
				cache9_create_table($cache9Table);
			}
		}
	}

	function showExtentions($arrExtentionsSelected)
	{
		global $arrExtentions;
		if (is_array($arrExtentions) && sizeof($arrExtentions)>0)
		{
			foreach ($arrExtentions as $extType_ => $arrExt_)
			{
				echo '<div style="margin-left:10px; float:left; width:100px;">';
				switch ($extType_)
				{
					case "doc" : _e('Document', 'cache9-cdn-admin'); break;
					case "img" : _e('Image', 'cache9-cdn-admin'); break;
					case "font" : _e('Font', 'cache9-cdn-admin'); break;
					case "mp" : _e('Audio/Movie', 'cache9-cdn-admin'); break;
				}
				echo ' : </div><div>';
				foreach ($arrExt_ as $k_ => $supportedExt_)
				{
					$extentionsChecked = "";
					if (in_array($supportedExt_, $arrExtentionsSelected)) $extentionsChecked = " checked";
					echo '<input type="checkbox" id="ext_sel_'.trim($supportedExt_).'" name="cache9_settings[cdn_ext_set][]" value="'.trim($supportedExt_).'"'.$extentionsChecked.'><label class="description" for="ext_sel_'.trim($supportedExt_).'" style="margin:0 10px 0 5px;">'.trim($supportedExt_).'</label>';
				}
				echo '</div>';
			}
		}
	}

	function showPaths($arrCache9Options)
	{
		$pathContentChecked = "";
		$pathIncludesChecked = "";
		echo '<div>';
		if ($arrCache9Options["cdn_content"]) $pathContentChecked = " checked";
		echo '<input type="checkbox" id="cache9_path_content" name="cache9_settings[cdn_content]" value="1"'.$pathContentChecked.'><label class="description" for="cache9_path_content" style="margin:0 10px 0 5px;">wp-content</label>';
		echo '</div>';
		echo '<div>';
		if ($arrCache9Options["cdn_includes"]) $pathIncludesChecked = " checked";
		echo '<input type="checkbox" id="cache9_path_includes" name="cache9_settings[cdn_includes]" value="1"'.$pathIncludesChecked.'><label class="description" for="cache9_path_includes" style="margin:0 10px 0 5px;">wp-includes</label>';
		echo '</div>';
	}

	function getDefaultExtentions($arrExtentions)
	{
		$arr=array();
		if (is_array($arrExtentions) && sizeof($arrExtentions)>0)
		{
			foreach ($arrExtentions as $extType_ => $arrExt_)
			{
				foreach ($arrExt_ as $k_ => $supportedExt_)
				{
					$arr[] = $supportedExt_;
				}
			}
		}
		return $arr;
	}

	function cache9_create_table($cache9Table) {
		global $wpdb;
		if (!empty ($wpdb->charset))
			$charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
		if (!empty ($wpdb->collate))
			$charset_collate .= " COLLATE {$wpdb->collate}";

			$sql = "CREATE TABLE IF NOT EXISTS {$cache9Table} (
				site_id bigint(20) NOT NULL AUTO_INCREMENT,
				cdn_activate tinyint(1) NOT NULL default 0,
				cdn_url varchar(255) NOT NULL,
				cdn_id varchar(20) NULL,
				cdn_api_key varchar(20) NULL,
				cdn_content tinyint(1) NOT NULL default 0,
				cdn_includes tinyint(1) NOT NULL default 0,
				cdn_ext varchar(255) NOT NULL,
				ftp_addr varchar(255) NULL,
				ftp_id varchar(20) NULL,
				ftp_pw varchar(20) NULL,
				ftp_sync tinyint(1) NOT NULL default 0,
				ftp_debug tinyint(1) NOT NULL default 0,
				mname varchar(10) NOT NULL,
				cdn_free_status tinyint(1) NULL,
				cdn_free_exp datetime NULL,

				UNIQUE KEY site_id (site_id)
			) {$charset_collate};";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	function cache9_cdn()
	{
		$arrCache9Options = get_option('cache9_settings');
		if (@$arrCache9Options['cdn_activate'] == 1) ob_start("cache9_replace_buffer");
	}

	function cache9_replace_buffer($buffer)
	{
		$arrCache9Options = cache9_get_options();
		$arrTag = array("script", "link", "img", "source");
		foreach ($arrTag as $k => $tag_)
		{
			$buffer = cache9_replace_tag($buffer, $tag_, $arrCache9Options);
		}
		return $buffer;
	}

	function cache9_replace_tag($buffer, $tag, $arrCache9Options)
	{
		$originURL = home_url('/');
		$tagS = "<".$tag;
		$tagE = ">";
		$buffer_ = $buffer__ = "";
		$loop = 0;

		$arrExt = json_decode($arrCache9Options["cdn_ext"], true);

		if ( ($arrCache9Options["cdn_activate"] == 1) && ( ($arrCache9Options['cdn_url'] != "") || ($arrCache9Options['cdn_id'] != "") ) )
		{
			$cache9URL = $arrCache9Options['cdn_url'];
			if ($cache9URL == "") $cache9URL = $arrCache9Options['cdn_id'].".cache9.net";

			$arrTags = explode($tagS, $buffer);
			foreach ($arrTags as $k => $tag_)
			{
				if ($loop > 0)
				{
					$doReplacePath = 0;
					$doReplaceExt = 0;
					$buffer__ = $tagS.$tag_;
					$tag__= lstrstr($tag_, $tagE);
					if ( ($arrCache9Options["cdn_content"]==1) && preg_match("/".WPCONT."/", $tag__) ) $doReplacePath = 1;
					else if ( ($arrCache9Options["cdn_includes"]==1) && preg_match("/".WPINC."/", $tag__) ) $doReplacePath = 1;
					switch ($tag)
					{
						case "link" :
							if ( in_array("css", $arrExt) && preg_match("/\.css/", $tag__) ) $doReplaceExt = 1;
						break;
						case "source" :
							if ( in_array("mp3", $arrExt) && preg_match("/\.mp3/", $tag__) ) $doReplaceExt = 1;
							if ( in_array("mp4", $arrExt) && preg_match("/\.mp4/", $tag__) ) $doReplaceExt = 1;
						break;
						default :
							foreach($arrExt as $k_ => $ext_)
							{
								if ( in_array($ext_, $arrExt) && preg_match("/\.".$ext_."/", $tag__) ) $doReplaceExt = 1;
							}
					}
					if ( ($doReplacePath == 1) && ($doReplaceExt == 1) ) $buffer__ = str_replace($originURL, $cache9URL."/", $tagS.$tag__.$tagE).rstrstr($tag_, $tagE);
				}
				else
				{
					$buffer__ = $tag_;
					$loop++;
				}
				$buffer_ .= $buffer__;
			}
		}
		else
		{
			$buffer_ = $buffer;
		}
		return $buffer_;
	}

	function cache9_get_options()
	{
		global $arrCache9Options, $wpdb;
		if (sizeof($arrCache9Options) == 0)
		{
			$cache9TablePrefix = lstrstr($wpdb->prefix, "_")."_";
			$cache9Table = $cache9TablePrefix . 'cache9_cdn';
			$arrCache9Options = $wpdb->get_row(
			" SELECT * FROM ".$cache9Table,ARRAY_A
			);
		}
		return $arrCache9Options;
	}

	function cache9_add_options_link() {
		add_options_page('Cache9 CDN', 'Cache9 CDN', 'manage_options', 'cache9-setting', 'cache9_setting_page');
	}

	function cache9_network_pages() {
		add_menu_page('Cache9 CDN', 'Cache9 CDN', 'manage_options', 'cache9-setting', 'cache9_setting_page');
	}

	function cache9_register_settings() {
		register_setting('cache9_settings_group', 'cache9_settings');
	}

	function lstrstr($haystack,$needle, $null="", $start=0)
	{
		return substr($haystack, $start,strpos($haystack, $needle));
	}

	function rstrstr($haystack,$needle, $null="", $start=0)
	{
		return substr($haystack, strpos($haystack, $needle)+strlen($needle));
	}
?>