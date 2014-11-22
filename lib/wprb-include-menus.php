<?php
//--------------------------------------------------------------------------------------------------------------//
// CODE FOR CREATING MENUS
//---------------------------------------------------------------------------------------------------------------//

global $wpdb,$current_user;
$wprb_role = $wpdb->prefix . "capabilities";
$current_user->role = array_keys($current_user->$wprb_role);
$wprb_role = $current_user->role[0];

switch($wprb_role)
{
	case "administrator":
		add_menu_page("WP Review Bank", __("WP Review Bank", review_bank), "read", "dashboard_review","",plugins_url("/assets/images/icon.png" , dirname(__FILE__)));
		add_submenu_page("dashboard_review", "Dashboard", __("Dashboard", review_bank), "read", "dashboard_review","dashboard_review");
		add_submenu_page("dashboard_review", "Add New Review", __("Add New Review", review_bank), "read", "review_bank","review_bank");
		add_submenu_page("dashboard_review", "System Status", __("System Status", review_bank), "read", "review_bank_system_status","review_bank_system_status");
	break;
	case "editor":
		add_menu_page("WP Review Bank", __("WP Review Bank", review_bank), "read", "dashboard_review","",plugins_url("/assets/images/icon.png" , dirname(__FILE__)));
		add_submenu_page("dashboard_review", "Dashboard", __("Dashboard", review_bank), "read", "dashboard_review","dashboard_review");
		add_submenu_page("dashboard_review", "Add New Review", __("Add New Review", review_bank), "read", "review_bank","review_bank");
		add_submenu_page("dashboard_review", "System Status", __("System Status", review_bank), "read", "review_bank_system_status","review_bank_system_status");
	break;
	case "author":
		add_menu_page("WP Review Bank", __("WP Review Bank", review_bank), "read", "dashboard_review","",plugins_url("/assets/images/icon.png" , dirname(__FILE__)));
		add_submenu_page("dashboard_review", "Dashboard", __("Dashboard", review_bank), "read", "dashboard_review","dashboard_review");
		add_submenu_page("dashboard_review", "Add New Review", __("Add New Review", review_bank), "read", "review_bank","review_bank");
		add_submenu_page("dashboard_review", "System Status", __("System Status", review_bank), "read", "review_bank_system_status","review_bank_system_status");
	break;
}

//--------------------------------------------------------------------------------------------------------------//
// CODE FOR CREATING PAGES
//---------------------------------------------------------------------------------------------------------------//

function dashboard_review()
{
	global $wpdb,$current_user;
	$wprb_role = $wpdb->prefix . "capabilities";
	$current_user->role = array_keys($current_user->$wprb_role);
	$wprb_role = $current_user->role[0];
	include_once REVIEW_FRM_PLUGIN_DIR ."/views/review_header.php";
	include_once REVIEW_FRM_PLUGIN_DIR ."/views/wp_dashboard_review.php";
}


function review_bank()
{
	global $wpdb,$current_user;
	$wprb_role = $wpdb->prefix . "capabilities";
	$current_user->role = array_keys($current_user->$wprb_role);
	$wprb_role = $current_user->role[0];
	include_once REVIEW_FRM_PLUGIN_DIR ."/views/review_header.php";
	include_once REVIEW_FRM_PLUGIN_DIR ."/views/wprb-review.php";
}

function review_bank_system_status()
{
	global $wpdb,$current_user;
	$wprb_role = $wpdb->prefix . "capabilities";
	$current_user->role = array_keys($current_user->$wprb_role);
	$wprb_role = $current_user->role[0];
	include_once REVIEW_FRM_PLUGIN_DIR ."/views/review_header.php";
	include_once REVIEW_FRM_PLUGIN_DIR ."/views/wp_system-status.php";
}
?>