	<script>
		var alphalow = 'abcdefghijklmnopqrstuvwxyz';
		var phone = '1234567890-';
		var gStatus = 0;
		jQuery(document).ready(function($){
			$("#cache9_cdn_register").click(function(){
				if (gStatus != 1)
				{
					if ( Cache9Checksum($("#cache9_cdn_id"),"<?php _e('Cache9 CDN ID', 'cache9-cdn-admin'); ?>",alphalow+phone,6,20)) return false ;
					if ( Cache9Checksum($("#cache9_cdn_api_key"),"<?php _e('Cache9 CDN API Key', 'cache9-cdn-admin'); ?>",alphalow+phone,6,20)) return false ;
					gStatus = 1;
					var b = "ckr";
					var d = $("#cache9_cdn_id").val();
					var f = $("#cache9_cdn_api_key").val();
					$.ajax({
						crossDomain:true,
						type: "GET",
						url: "http://api.cache9.com/",
						data: {a:b,c:d,e:f},
						dataType: "json",
						success: function(data){
							if (data.status == 0)
							{
								alert("<?php _e('The ID you entered does not exist.\nPlease, enter correct ID to start.', 'cache9-cdn-admin'); ?>");
								gStatus = 0;
							}
							else if (data.status == 1)
							{
								$("#cache9_start_form").submit();
							}
							else if (data.status == 2)
							{
								alert("<?php _e('It seems the ID you entered is not yours.\nPlease, enter correct ID or change origin server information on Cache9.com.', 'cache9-cdn-admin'); ?>");
								gStatus = 0;
							}
							else if (data.status == 3)
							{
								alert("<?php _e('Your 7 days free trial has been expired.\nPlease, visit Cache9.com to renew your CDN service.', 'cache9-cdn-admin'); ?>");
								gStatus = 0;
							}
						}
					});
				}
			});
			$("#cache9_cdn_start").click(function(){
				if (gStatus != 1)
				{
					if ( Cache9Checksum($("#cache9_cdn_id"),"<?php _e('Cache9 CDN ID', 'cache9-cdn-admin'); ?>",alphalow+phone,6,20)) return false ;
					gStatus = 1;
					var b = "ckf";
					var d = $("#cache9_cdn_id").val();
					var f = "wpk";
					$.ajax({
						crossDomain:true,
						type: "GET",
						url: "http://api.cache9.com/",
						data: {a:b,c:d,e:f},
						dataType: "json",
						success: function(data){
							if (data.status == 0)
							{
								alert("<?php _e("Cache9 ID's failed to register. Please, try it later again.", 'cache9-cdn-admin'); ?>");
								gStatus = 0;
							}
							else if (data.result == 1)
							{
								$("#cache9_cdn_free_status").val(1);
								$("#cache9_cdn_free_exp").val(data.exp);
								$("#cache9_cdn_api_key").val(data.api);
								alert("<?php _e("Your Cache9 ID's registered and CDN will be set up in 1~2 minutes.", 'cache9-cdn-admin'); ?>");
								$("#cache9_start_form").submit();
							}
							else if (data.result == 2)
							{
								alert("<?php _e('The ID you entered is aready in use. Please, choose another ID to start.', 'cache9-cdn-admin'); ?>");
								gStatus = 0;
							}
							else if (data.result == 3)
							{
								alert("<?php _e('You already have a Cache9 ID.\nIt will be set for your Cache9 ID.', 'cache9-cdn-admin'); ?>");
								$("#cache9_cdn_id").val(data.c);
								gStatus = 0;
							}
						}
					});
				}
			});
		});
	</script>
	<div class="wrap">
		<h2 style="background-color:#318FCF; border:1px #86C5FF solid; padding:20px; color:#FFFFFF;font-weight: bold;"><?php _e('Cache9 CDN', 'cache9-cdn-admin'); ?></h2>
		<div style="background-color:#F5FAFF; border:1px #86C5FF solid; padding:20px; margin:10px 0;">
			<h4 style="font-weight: bold;"><?php _e('Enter your Cache9 ID or new one (Cache9 provides 7 days free trial for new comers).', 'cache9-cdn-admin'); ?></h4>

			<div style="background-color:#FFFFFF; border:1px #86C5FF solid; padding:20px;">
			<form id="cache9_start_form" method="post" action="options.php">
				<?php settings_fields('cache9_settings_group'); ?>
				<p>
					<label for="cache9_cdn_id"><?php _e('Cache9 CDN ID', 'cache9-cdn-admin'); ?></label>
					<span> : http://</sapn><input id="cache9_cdn_id" type="textbox" name="cache9_settings[cdn_id]" value="<?php echo $arrCache9Options['cdn_id']; ?>" /><b style="font-weight: bold;">.cache9.net</b><small style="margin-left:10px;"><?php _e('Enter the ID you want to register(The length between 6 and 12, including lowercase of english and numbers).', 'cache9-cdn-admin'); ?></small>
				<p>
				</p>
					<label for="cache9_cdn_id"><?php _e('Cache9 CDN API Key', 'cache9-cdn-admin'); ?></label> : <input type="textbox" id="cache9_cdn_api_key" name="cache9_settings[cdn_api_key]" value=""><small style="margin-left:10px;"><?php _e('Enter the API Key to verify your existing CDN ID. Optional for the user has Cache9 CDN ID.', 'cache9-cdn-admin'); ?></small>
					<input type="hidden" id="cache9_cdn_free_status" name="cache9_settings[cdn_free_status]" value="">
					<input type="hidden" id="cache9_cdn_free_exp" name="cache9_settings[cdn_free_exp]" value="">
				</p>
			</form>
			<p>
				<span id="cache9_cdn_start"  style="background-color:#00BD0F; border:1px #F2F2FF solid; padding:5px; cursor:pointer; color:#FFFFFF; font-weight: bold;"><?php _e('Genenrate Cache9 ID & Start CDN', 'cache9-cdn-admin'); ?></span> <span id="cache9_cdn_register"  style="background-color:#5F5FFF; border:1px #F2F2FF solid; padding:5px; cursor:pointer; color:#FFFFFF; font-weight: bold;"><?php _e('I already have my Cache9 ID', 'cache9-cdn-admin'); ?></span>
			</p>
			</div>

		</div>
	</div>
	<script>
		function Cache9Checksum(target, cmt, astr, lmin, lmax)
		{
			var i;
			var t = target.val();

			if (t.length == 0 ) {
				//alert('Enter the ' + cmt);
				//alert(cmt + '을(를) 입력해주세요.');
				alert(<?php _e("'Enter the ' + cmt", 'cache9-cdn-admin'); ?>);
				target.focus();
				return true ;
			}
			if (lmax != 0 && t.length > lmax) {
				//alert(cmt + ' allows ' + lmax + ' letters maximum');
				//alert(cmt + '은(는) ' + lmax + '자 이하로 입력해주세요.');
				alert(<?php _e("cmt + ' allows ' + lmax + ' letters maximum'", 'cache9-cdn-admin'); ?>);
				target.focus();
				return true;
			}
			if (lmin != 0 && t.length < lmin) {
				//alert(cmt + ' is too short');
				//alert(cmt + '은(는) ' + lmin + '자 이상 입력해주세요.');
				alert(<?php _e("cmt + ' is too short'", 'cache9-cdn-admin'); ?>);
				target.focus();
				return true;
			}
			if (astr.length >= 1) {
					for (i=0; i<t.length; i++)
							if(astr.indexOf(t.substring(i,i+1))<0) {
						//alert(cmt + ' contains inappropriate character(s)');
						//alert(cmt + '에 허용되지 않은 문자가 포함되어 있습니다.');
						alert(<?php _e("cmt + ' contains inappropriate character(s)'", 'cache9-cdn-admin'); ?>);
						target.focus();
						return true;
					}
			}
			return false;
		}
	</script>