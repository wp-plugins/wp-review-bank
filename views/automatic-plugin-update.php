<?php
switch($wprb_role)
{
	case "administrator":
		$cb_user_role_permission = "manage_options";
		break;
	case "editor":
		$cb_user_role_permission = "publish_pages";
		break;
	case "author":
		$cb_user_role_permission = "publish_posts";
		break;
	
}
if (!current_user_can($cb_user_role_permission))
{
	return;
}
else
{
	?>
	<form id="frm_auto_update" class="layout-form form_width">
		<div class="fluid-layout">
			<div class="layout-span12">
				<div class="widget-layout">
					<div class="widget-layout-title">
						<h4><?php _e("Plugin Updates", review_bank); ?></h4>
					</div>
					<div class="widget-layout-body">
						<div class="fluid-layout">
							<div class="layout-span12 responsive">
								<div class="layout-control-group">
									<label class="layout-control-label"><?php _e("Plugin Updates", review_bank); ?> :</label>
									<div class="layout-controls custom-layout-controls-review" style="margin-top: 8px;">
										<?php $review_updates = get_option("review-bank-automatic-update");?>
										<input type="radio" name="ux_contact_update" id="ux_enable_update" onclick="review_bank_autoupdate(this);" <?php echo $review_updates == "1" ? "checked=\"checked\"" : "";?> value="1"><label style="vertical-align: baseline;"><?php _e("Enable", review_bank); ?></label>
										<input type="radio" name="ux_contact_update" id="ux_disable_update" onclick="review_bank_autoupdate(this);" <?php echo $review_updates == "0" ? "checked=\"checked\"" : "";?> style="margin-left: 10px;" value="0"><label style="vertical-align: baseline;"><?php _e("Disable", review_bank); ?></label>
									</div>
								</div>
								<div class="layout-control-group" style="margin:10px 0 10px 0 ;">
									<strong><i>This feature allows the plugin to update itself automatically when a new version is available on WordPress Repository.<br/>This allows to stay updated to the latest features. If you would like to disable automatic updates, choose  the disable option above.</i></strong>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
	<script type="text/javascript">
		function review_bank_autoupdate(control)
		{
			var review_updates = jQuery(control).val();
			jQuery.post(ajaxurl, "review_updates="+review_updates+"&param=review_plugin_updates&action=review_bank_library", function(data)
			{
			});
		}
	</script>
<?php 
}
?>