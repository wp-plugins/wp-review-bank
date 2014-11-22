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

	switch($_REQUEST["page"])
	{
		case "dashboard_review":
			$page = "Dashboard";
		break;
		case "review_bank":
			$page = "Add New Review";
		break;
		case "review_bank_system_status":
			$page = "System Status";
		break;
		default:
			$page = "Dashboard";
		break;
	}
	?>
	
<h2 class="nav-tab-wrapper">
	<a class="nav-tab " id="dashboard_review" href="admin.php?page=dashboard_review"><?php _e("Dashboard", review_bank);?></a>
	<a class="nav-tab " id="review_bank" href="admin.php?page=review_bank"><?php _e("Add New Review", review_bank);?></a>
	<a class="nav-tab " id="review_bank_system_status" href="admin.php?page=review_bank_system_status"><?php _e("System Status", review_bank);?></a>
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
