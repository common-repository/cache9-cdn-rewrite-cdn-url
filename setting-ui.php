	<script>
		var cache9url = "http://<?php echo @$arrCache9Options['cdn_id']; ?>.cache9.net";
		jQuery(document).ready(function($){
			$("#cache9_url_button").click(function(){
				$("#cache9_cdn_url").val(cache9url);
			});
			$(".button-primary").click(function(){
				$("#cache9_setting_form").submit();
			});
<?php
	if (@$arrCache9Options['cdn_free_status'] == 2)
	{
?>
			var b = "ckx";
			var d = $("#cache9_cdn_id").val();
			var f = $("#cache9_cdn_api_key").val();
			$.ajax({
				crossDomain:true,
				type: "GET",
				url: "http://api.cache9.com/",
				data: {a:b,c:d,e:f},
				dataType: "json",
				success: function(data){
					var cache9CDNNotice = "";
					if (data.status == 1)
					{
						$("#cache9_cdn_free_status").val(0);
					}
					else if (data.status == 2)
					{
						cache9CDNNotice = "<?php _e('API Key check failed.\nPlease, check your origin server information, and API Key on Cache9.com.', 'cache9-cdn-admin'); ?>";
					}
					else if (data.status == 3)
					{
						cache9CDNNotice = "<?php _e('Your 7 days free trial has been expired.\nPlease, visit Cache9.com to renew your CDN service.', 'cache9-cdn-admin'); ?>";
					}
					else
					{
						cache9CDNNotice = data.notice;
					}
					cache9Notice(cache9CDNNotice);
				}
			});
			function cache9Notice(notice)
			{
				if (str != "")
				{
					$("#cache9_notice_content").html(notice);
					$("#cache9_notice").show();
				}
			}
<?php
	}
?>
		});
	</script>
	<div class="wrap">
		<h2 style="background-color:#318FCF; border:1px #86C5FF solid; padding:20px; color:#FFFFFF;font-weight: bold;"><?php _e('Cache9 CDN', 'cache9-cdn-admin'); ?></h2>
		<form id="cache9_setting_form" method="post" action="options.php">
			<?php settings_fields('cache9_settings_group'); ?>

			<div style="background-color:#F5FAFF; border:1px #86C5FF solid; padding:20px; margin:10px 0;">

				<div style="background-color:#318FCF; border:1px #86C5FF solid; padding:20px; color:#FFFFFF;">
					<p>
						<input id="cache9_settings[cdn_activate]" type="checkbox" name="cache9_settings[cdn_activate]" value="1" <?php echo $cache9ActivateChecked; ?> /><label for="cache9_settings[cdn_activate]"><b style="font-weight: bold;"><?php _e('Activate rewriting URLs', 'cache9-cdn-admin'); ?></b></label><small></small>
						<input type="hidden" id="cache9_cdn_id" name="cache9_settings[cdn_id]" value="<?php echo @$arrCache9Options['cdn_id']; ?>" />
						<input type="hidden" name="cache9_settings[cdn_setting]" value="1" />
						<input type="hidden" id="cache9_cdn_free_status" name="cache9_settings[cdn_free_status]" value="<?php echo @$arrCache9Options['cdn_free_status']; ?>">
						<input type="hidden" id="cache9_cdn_free_exp" name="cache9_settings[cdn_free_exp]" value="<?php echo @$arrCache9Options['cdn_free_exp']; ?>">
						<input type="hidden" id="cache9_cdn_api_key" name="cache9_settings[cdn_api_key]" value="<?php echo @$arrCache9Options['cdn_api_key']; ?>">
					</p>
					<p id="cache9_notice" style="background-color:#FFFFFF; border:1px #EEEEEE solid; padding:10px; color:#FF0000; display:none;">
						<span id="cache9_notice_content"></span>
					</p>
					<p class="submit">
						<input type="submit" class="button-primary" value="<?php _e('Save Options', 'cache9-cdn-admin'); ?>" />
					</p>
				</div>
				<p>
					<h4 style="font-weight: bold;"><?php _e('Enter Cache9 CDN URL or any URL you want to replace with.', 'cache9-cdn-admin'); ?></h4>
				</p>
				<div style="background-color:#FFFFFF; border:1px #86C5FF solid; padding:20px;">
					<p>
						<div style="width: 140px;float: left;line-height: 26px;font-weight: bold;"><?php _e('CDN URL', 'cache9-cdn-admin'); ?></div><input id="cache9_cdn_url" type="textbox" name="cache9_settings[cdn_url]" value="<?php echo $cache9CDNURL; ?>" style="width:212px;" /> : <small style="margin-left:10px;"><span id="cache9_url_button"  style="background-color:#5F5FFF; border:1px #F2F2FF solid; padding:5px; cursor:pointer; color:#FFFFFF; font-weight: bold;"><?php _e('Use Cache9 CDN URL', 'cache9-cdn-admin'); ?></span><?php _e(' or enter the URL you want to replace with.', 'cache9-cdn-admin'); ?></small>
					</p>
					<p>
						<div style="width: 140px;float: left;font-weight: bold;"><?php _e('Cache9 CDN URL', 'cache9-cdn-admin'); ?></div>
						<div><b style="font-weight: bold;"> : <?php echo @$arrCache9Options['cdn_id']; ?>.cache9.net</b></div><small style="margin-left:10px;"></small>
					</p>
					<p>
						<div style="width: 140px;float: left;font-weight: bold;"><?php _e('Cache9 CDN ID', 'cache9-cdn-admin'); ?></div>
						<div style="float:left"><b style="font-weight: bold;"> : <?php echo @$arrCache9Options['cdn_id']; ?></b></div><small style="margin-left:10px;"> ( <?php _e('You can sign in Cache9.com with this ID.', 'cache9-cdn-admin'); ?>  ) </small>
					</p>
					<p>
						<div style="width: 140px;float: left;font-weight: bold;"><?php _e('Cache9 CDN API Key', 'cache9-cdn-admin'); ?></div>
						<div style="float:left"><b style="font-weight: bold;"> : <?php echo @$arrCache9Options['cdn_api_key']; ?></b></div><small style="margin-left:10px;">  ( <?php _e('You can sign in Cache9.com with this API Key as a password.', 'cache9-cdn-admin'); ?> ) </small>
					</p>
					<p class="submit">
						<input type="submit" class="button-primary" value="<?php _e('Save Options', 'cache9-cdn-admin'); ?>" />
					</p>
				</div>
				<p>
					<h4 style="font-weight: bold;"><?php _e('Select file types to be replaced.', 'cache9-cdn-admin'); ?></h4>
				</p>
				<div style="background-color:#FFFFFF; border:1px #86C5FF solid; padding:20px;">
					<p>
						<?php showExtentions(@$arrCache9Options['cdn_ext_set']); ?>
					</p>
					<p class="submit">
						<input type="submit" class="button-primary" value="<?php _e('Save Options', 'cache9-cdn-admin'); ?>" />
					</p>
				</div>
				<p>
					<h4 style="font-weight: bold;"><?php _e('Select wordpress paths to be replaced.', 'cache9-cdn-admin'); ?></h4>
				</p>
				<div style="background-color:#FFFFFF; border:1px #86C5FF solid; padding:20px;">
					<p>
						<?php showPaths($arrCache9Options); ?>
					</p>
					<p class="submit">
						<input type="submit" class="button-primary" value="<?php _e('Save Options', 'cache9-cdn-admin'); ?>" "/>
					</p>
				</div>
			</div>
		</form>
	</div>