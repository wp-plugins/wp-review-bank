<?php
//--------------------------------------------------------------------------------------------------------------//
// CODE FOR CREATING MENUS
//---------------------------------------------------------------------------------------------------------------//
switch($wprb_role)
{
	case "administrator":
		add_menu_page("WP Review Bank", __("WP Review Bank", review_bank), "read", "dashboard_review","",plugins_url("/assets/images/icon.png" , dirname(__FILE__)));
		add_submenu_page("dashboard_review", "Dashboard", __("Dashboard", review_bank), "read", "dashboard_review","dashboard_review");
		add_submenu_page("dashboard_review", "Add New Review", __("Add New Review", review_bank), "read", "review_bank","review_bank");
		add_submenu_page("dashboard_review", "Plugin Updates", __("Plugin Updates", review_bank), "read", "review_plugin_update", "review_plugin_update");
		add_submenu_page("dashboard_review", "Short Codes", __("Short Codes", review_bank), "read", "short_code_review",  "short_code_review");
		add_submenu_page("dashboard_review", "Recommendations", __("Recommendations", review_bank), "read", "recommended_plugins_review", "recommended_plugins_review" );
		add_submenu_page("dashboard_review", "Our Other Services", __("Our Other Services", review_bank), "read", "other_services_review", "other_services_review" );
		add_submenu_page("dashboard_review", "System Status", __("System Status", review_bank), "read", "review_bank_system_status","review_bank_system_status");
	break;
	case "editor":
		add_menu_page("WP Review Bank", __("WP Review Bank", review_bank), "read", "dashboard_review","",plugins_url("/assets/images/icon.png" , dirname(__FILE__)));
		add_submenu_page("dashboard_review", "Dashboard", __("Dashboard", review_bank), "read", "dashboard_review","dashboard_review");
		add_submenu_page("dashboard_review", "Add New Review", __("Add New Review", review_bank), "read", "review_bank","review_bank");
		add_submenu_page("dashboard_review", "Plugin Updates", __("Plugin Updates", review_bank), "read", "review_plugin_update", "review_plugin_update");
		add_submenu_page("dashboard_review", "Short Codes", __("Short Codes", review_bank), "read", "short_code_review",  "short_code_review");
		add_submenu_page("dashboard_review", "Recommendations", __("Recommendations", review_bank), "read", "recommended_plugins_review", "recommended_plugins_review" );
		add_submenu_page("dashboard_review", "Our Other Services", __("Our Other Services", review_bank), "read", "other_services_review", "other_services_review" );
		add_submenu_page("dashboard_review", "System Status", __("System Status", review_bank), "read", "review_bank_system_status","review_bank_system_status");
	break;
	case "author":
		add_menu_page("WP Review Bank", __("WP Review Bank", review_bank), "read", "dashboard_review","",plugins_url("/assets/images/icon.png" , dirname(__FILE__)));
		add_submenu_page("dashboard_review", "Dashboard", __("Dashboard", review_bank), "read", "dashboard_review","dashboard_review");
		add_submenu_page("dashboard_review", "Add New Review", __("Add New Review", review_bank), "read", "review_bank","review_bank");
		add_submenu_page("dashboard_review", "Plugin Updates", __("Plugin Updates", review_bank), "read", "review_plugin_update", "review_plugin_update");
		add_submenu_page("dashboard_review", "Short Codes", __("Short Codes", review_bank), "read", "short_code_review",  "short_code_review");
		add_submenu_page("dashboard_review", "Recommendations", __("Recommendations", review_bank), "read", "recommended_plugins_review", "recommended_plugins_review" );
		add_submenu_page("dashboard_review", "Our Other Services", __("Our Other Services", review_bank), "read", "other_services_review", "other_services_review" );
		add_submenu_page("dashboard_review", "System Status", __("System Status", review_bank), "read", "review_bank_system_status","review_bank_system_status");
	break;
}

//--------------------------------------------------------------------------------------------------------------//
// CODE FOR CREATING PAGES
//---------------------------------------------------------------------------------------------------------------//

function dashboard_review()
{
	global $wp_admin_bar, $wpdb, $current_user;
	if(is_super_admin())
	{
		$wprb_role = "administrator";
	}
	else
	{
		$wprb_role = $wpdb->prefix . "capabilities";
		$current_user->role = array_keys($current_user->$wprb_role);
		$wprb_role = $current_user->role[0];
	}
	include_once REVIEW_FRM_PLUGIN_DIR ."/views/review_header.php";
	include_once REVIEW_FRM_PLUGIN_DIR ."/views/wp_dashboard_review.php";
}

function review_bank()
{
	global $wp_admin_bar, $wpdb, $current_user;
	if(is_super_admin())
	{
		$wprb_role = "administrator";
	}
	else
	{
		$wprb_role = $wpdb->prefix . "capabilities";
		$current_user->role = array_keys($current_user->$wprb_role);
		$wprb_role = $current_user->role[0];
	}
	include_once REVIEW_FRM_PLUGIN_DIR ."/views/review_header.php";
	include_once REVIEW_FRM_PLUGIN_DIR ."/views/wprb-review.php";
}

function review_bank_system_status()
{
	global $wp_admin_bar, $wpdb, $current_user;
	if(is_super_admin())
	{
		$wprb_role = "administrator";
	}
	else
	{
		$wprb_role = $wpdb->prefix . "capabilities";
		$current_user->role = array_keys($current_user->$wprb_role);
		$wprb_role = $current_user->role[0];
	}
	include_once REVIEW_FRM_PLUGIN_DIR ."/views/review_header.php";
	include_once REVIEW_FRM_PLUGIN_DIR ."/views/wp_system-status.php";
}
if(!function_exists( "recommended_plugins_review" ))
{
	function recommended_plugins_review()
	{
		global $wpdb,$current_user,$user_role_permission,$wp_version;
		if(is_super_admin())
		{
			$wprb_role = "administrator";
		}
		else
		{
			$wprb_role = $wpdb->prefix . "capabilities";
			$current_user->role = array_keys($current_user->$wprb_role);
			$wprb_role = $current_user->role[0];
		}
		if(file_exists(REVIEW_FRM_PLUGIN_DIR."/views/review_header.php"))
		{
			include_once REVIEW_FRM_PLUGIN_DIR."/views/review_header.php";
		}
		if (file_exists(REVIEW_FRM_PLUGIN_DIR ."/views/recommended-plugins.php"))
		{
			include_once REVIEW_FRM_PLUGIN_DIR ."/views/recommended-plugins.php";
		}
	}
}
if(!function_exists("other_services_review"))
{
	function other_services_review()
	{
		global $wpdb,$current_user,$user_role_permission,$wp_version;
		if(is_super_admin())
		{
			$wprb_role = "administrator";
		}
		else
		{
			$wprb_role = $wpdb->prefix . "capabilities";
			$current_user->role = array_keys($current_user->$wprb_role);
			$wprb_role = $current_user->role[0];
		}
		if(file_exists(REVIEW_FRM_PLUGIN_DIR."/views/review_header.php"))
		{
			include_once REVIEW_FRM_PLUGIN_DIR."/views/review_header.php";
		}
		if (file_exists(REVIEW_FRM_PLUGIN_DIR ."/views/other-services.php"))
		{
			include_once REVIEW_FRM_PLUGIN_DIR ."/views/other-services.php";
		}
	}
}
if(!function_exists("short_code_review"))
{
	function short_code_review()
	{
		global $wpdb,$current_user,$user_role_permission,$wp_version;
		if(is_super_admin())
		{
			$wprb_role = "administrator";
		}
		else
		{
			$wprb_role = $wpdb->prefix . "capabilities";
			$current_user->role = array_keys($current_user->$wprb_role);
			$wprb_role = $current_user->role[0];
		}
		if(file_exists(REVIEW_FRM_PLUGIN_DIR."/views/review_header.php"))
		{
			include_once REVIEW_FRM_PLUGIN_DIR."/views/review_header.php";
		}
		if (file_exists(REVIEW_FRM_PLUGIN_DIR ."/views/short-code-tab.php"))
		{
			include_once REVIEW_FRM_PLUGIN_DIR ."/views/short-code-tab.php";
		}
	}
}
if(!function_exists("review_plugin_update"))
{
	function review_plugin_update()
	{
		global $wpdb,$current_user,$user_role_permission,$wp_version;
		if(is_super_admin())
		{
			$wprb_role = "administrator";
		}
		else
		{
			$wprb_role = $wpdb->prefix . "capabilities";
			$current_user->role = array_keys($current_user->$wprb_role);
			$wprb_role = $current_user->role[0];
		}
		if(file_exists(REVIEW_FRM_PLUGIN_DIR."/views/review_header.php"))
		{
			include_once REVIEW_FRM_PLUGIN_DIR."/views/review_header.php";
		}
		if (file_exists(REVIEW_FRM_PLUGIN_DIR ."/views/automatic-plugin-update.php"))
		{
			include_once REVIEW_FRM_PLUGIN_DIR ."/views/automatic-plugin-update.php";
		}
	}
}
?>