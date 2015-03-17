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
	<h2 class="nav-tab-wrapper">
		<a class="nav-tab " id="dashboard_review" href="admin.php?page=dashboard_review"><?php _e("Dashboard", review_bank);?></a>
		<a class="nav-tab " id="review_bank" href="admin.php?page=review_bank"><?php _e("Add New Review", review_bank);?></a>
		<a class="nav-tab " id="review_plugin_update" href="admin.php?page=review_plugin_update"><?php _e("Plugin Updates", review_bank);?></a>
		<a class="nav-tab " id="short_code_review" href="admin.php?page=short_code_review"><?php _e("Short Code", review_bank);?></a>
		<a class="nav-tab" id="recommended_plugins_review" href="admin.php?page=recommended_plugins_review"><?php _e("Recommendations", review_bank);?></a>
		<a class="nav-tab" id="other_services_review" href="admin.php?page=other_services_review"><?php _e("Our Other Services", review_bank);?></a>
	</h2>
	<script>
	jQuery(document).ready(function()
	{
		jQuery(".nav-tab-wrapper > a#<?php echo $_REQUEST["page"];?>").addClass("nav-tab-active");
	});
	</script>
	<?php 
}
?>
