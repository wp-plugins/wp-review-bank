<?php 
switch($wprb_role)
{
	case "administrator":
		$user_role_permission = "manage_options";
		break;
	case "editor":
		$user_role_permission = "publish_pages";
		break;
	case "author":
		$user_role_permission = "publish_posts";
		break;
}

if (!current_user_can($user_role_permission))
{
	return;
}
else
{
?>
	<div id="review-bank" style="display:none;">
		<div class="fluid-layout responsive">
			<div style="padding:20px 0 10px 15px;">
				<h3 class="label-shortcode"><?php _e("Insert Review Bank", review_bank); ?></h3>
					<span>
						<i><?php _e("Select a Review below to add it to your post or page.", review_bank); ?></i>
					</span>
			</div>
			<div class="layout-span12 responsive" style="padding:15px 15px 0 0;">
				<div class="layout-control-group">
					<label class="custom-layout-label" for="ux_form_name"><?php _e("Review Title", review_bank); ?> : </label>
					<select id="add_review_form_id" class="layout-span9">
						<option value="0"><?php _e("Select a Review", review_bank); ?>  </option>
						<?php
						global $wpdb;
						$forms = $wpdb->get_results
						(
							"SELECT * FROM " .wp_review_tbl
						);
						for($flag = 0;$flag<count($forms);$flag++)
						{
							?>
							<option value="<?php echo intval($forms[$flag]->id); ?>"><?php echo esc_html($forms[$flag]->heading) ?></option>
						<?php
						}
						?>
					</select>
				</div>
				
				<div class="layout-control-group">
					<label class="custom-layout-label"></label>
					<input type="button" class="button-primary" value="<?php _e("Insert Form", review_bank); ?>"
						onclick="Insert_review_Form();"/>&nbsp;&nbsp;&nbsp;
					<a class="button" style="color:#bbb;" href="#"
						onclick="tb_remove(); return false;"><?php _e("Cancel", review_bank); ?></a>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		function Insert_review_Form()
		{
			var review_id = jQuery("#add_review_form_id").val();
			
			if(review_id == 0)
			{
			    alert("<?php _e("Please choose a Review to insert into Shortcode", review_bank) ?>");
			    return;
			}
			window.send_to_editor("[review_bank review_id=" + review_id +"]");
		}
	</script>
<?php 
}
?>