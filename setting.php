<?php
	$arrCache9Options = array();
	$arrExtentions = array(
		"doc"=>array("js","css")
		, "img"=>array("gif","jpg","jpeg","png","bmp","tiff","jpc","jp2","jpf","jb2","aiff","wbmp","xbm")
		, "font"=>array("eot","ttf","otf","woff","svg")
		, "mp"=>array("mp3","mp4")
	);
	if (!is_admin())
	{
		add_action('wp_loaded', "cache9_cdn", 0);
	}
	else
	{
		load_plugin_textdomain("cache9-cdn-admin", false, dirname(plugin_basename(__FILE__)).'/languages');
		add_action('admin_menu', 'cache9_add_options_link');
		add_action('network_admin_menu', 'cache9_network_pages');
		add_action('admin_init', 'cache9_register_settings');
	}
?>