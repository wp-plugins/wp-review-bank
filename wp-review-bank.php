<?php 
/**
Plugin Name: Wp Review Bank
Plugin URI: http://tech-banker.com
Description: 
Author: Tech Banker
Version: 1.2
Author URI: http://tech-banker.com
 */

/////////////////////////////////////  Define  WP Review Bank  Constants  ////////////////////////////////////////

if (!defined("REVIEW_FRM_PLUGIN_DIR")) define("REVIEW_FRM_PLUGIN_DIR",  plugin_dir_path( __FILE__ ));
if (!defined("review_bank")) define("review_bank", "review_bank");
if (!defined("tech_bank")) define("tech_bank", "tech-banker");
if (!defined("REVIEW_FILE")) define("REVIEW_FILE","wp-review-bank/wp-review-bank.php");

/////////////////////////////////////  Call CSS & JS Scripts - Front End ////////////////////////////////////////

function frontend_plugin_css_styles_review_bank()
{

	wp_enqueue_style("wprb-forntend.css",  plugins_url("/assets/css/wprb-forntend.css",__FILE__));
	wp_enqueue_style("wprb-review.css", plugins_url("/assets/css/wprb-review.css",__FILE__));
}

/////////////////////////////////////  Call CSS & JS Scripts - Back End ////////////////////////////////////////
function backend_plugin_css_styles_review_bank()
{
	wp_enqueue_style("farbtastic");
	wp_enqueue_style("framework.css", plugins_url("/assets/css/framework.css",__FILE__));
	wp_enqueue_style("wprb-review.css", plugins_url("/assets/css/wprb-review.css",__FILE__));
}
function backend_plugin_js_review_bank()
{
	wp_enqueue_script("jquery");
	wp_enqueue_script("farbtastic");
	wp_enqueue_script("jquery-ui-sortable");
	wp_enqueue_script("jquery.dataTables.min", plugins_url("/assets/js/jquery.dataTables.min.js",__FILE__));
	wp_enqueue_script("jquery.validate.min", plugins_url("/assets/js/jquery.validate.min.js",__FILE__));
}

/////////////////////////////////////  Functions for Returing Table Names  ////////////////////////////////////////

if(!defined("wp_review_tbl")) define("wp_review_tbl","wp_review_bank");
if(!defined("wp_review_tbl_features")) define("wp_review_tbl_features","wp_review_features");

/////////////////////////////////////  Call Install Script on Plugin Activation ////////////////////////////////////////
if(!function_exists("plugin_install_script_for_review_bank"))
{
	function plugin_install_script_for_review_bank()
	{
		global $wpdb;
		if (is_multisite())
		{
			$blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
			foreach($blog_ids as $blog_id)
			{
				switch_to_blog($blog_id);
				if(file_exists(REVIEW_FRM_PLUGIN_DIR. "/lib/install-script.php"))
				{
					include REVIEW_FRM_PLUGIN_DIR. "/lib/install-script.php";
				}
				restore_current_blog();
			}
		}
		else
		{
			if(file_exists(REVIEW_FRM_PLUGIN_DIR. "/lib/install-script.php"))
			{
				include REVIEW_FRM_PLUGIN_DIR. "/lib/install-script.php";
			}
		}
	}
}

///////////////////////////////////  Call Shortcodes for Front End ////////////////////////////////////////

function review_bank_short_code($atts)
{
	extract(shortcode_atts(array(
	"review_id" => "",
	"show_title" => "",
	), $atts));
	return wprb_extract_short_code($review_id,$show_title);
}

/////////////////////////////////////  Extract Shortcodes called by Front End Function ////////////////////////////////////////

function wprb_extract_short_code($review_id,$show_title)
{
	ob_start();
	require REVIEW_FRM_PLUGIN_DIR."/views/review_bank_forms.php";
	$review_bank_output = ob_get_clean();
	wp_reset_query();
	return $review_bank_output;
}

/////////////////////////////////////  Include Menus on Dashboard ////////////////////////////////////////

function create_global_menus_for_review_bank()
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
	include REVIEW_FRM_PLUGIN_DIR . "/lib/wprb-include-menus.php";
}

///////////////////////////////////// Register Ajax Based Functions /////////////////////////////////////

if(isset($_REQUEST["action"]))
{
	switch($_REQUEST["action"])
	{
		case "review_bank_library":
			add_action("admin_init","review_bank_class");
			function review_bank_class()
			{
				include REVIEW_FRM_PLUGIN_DIR."/lib/save_review_bank_class.php";
			}
			break;
	}
}

///////////////////////////////////// Shortcodes Generator Functions /////////////////////////////////////


function add_review_shortcode_button($context) {
	add_thickbox();
	$context .= "<a href=\"#TB_inline?width=300&height=400&inlineId=review-bank\"  class=\"button thickbox\"  title=\"" . __("Add Review Bank Shortcode", review_bank) . "\">
    <span class=\"review_icon\"></span> Add review Bank Form</a>";
	return $context;
}

function add_review_mce_popup()
{
	add_thickbox();
	global $wpdb,$current_user,$user_role_permission;
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
	include REVIEW_FRM_PLUGIN_DIR."/lib/wprb-shortcode.php";
}
/////////////////////////////////////admin menu /////////////////////////////////////

function add_review_icon($meta = TRUE)
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
	switch ($wprb_role)
	{
		case "administrator":
			$wp_admin_bar->add_menu(array(
			"id" => "review_bank",
			"title" => __("<img src=\"" . plugins_url("/assets/images/icon.png",__FILE__)."\" width=\"25\"
			height=\"25\" style=\"vertical-align:text-top; margin-right:5px;\" />WP Review Bank"),
			"href" => __(site_url() . "/wp-admin/admin.php?page=dashboard_review"),
			));

			$wp_admin_bar->add_menu(array(
					"parent" => "review_bank",
					"id" => "Dashboard",
					"href" => site_url() . "/wp-admin/admin.php?page=dashboard_review",
					"title" => __("Dashboard", review_bank))
			);
			$wp_admin_bar->add_menu(array(
					"parent" => "review_bank",
					"id" => "Add New Review",
					"href" => site_url() . "/wp-admin/admin.php?page=review_bank",
					"title" => __("Add New Review", review_bank))
			);
			$wp_admin_bar->add_menu(array(
					"parent" => "review_bank",
					"id" => "short code review",
					"href" => site_url() . "/wp-admin/admin.php?page=short_code_review",
					"title" => __("Short Codes", review_bank))
			);
			$wp_admin_bar->add_menu(array(
					"parent" => "review_bank",
					"id" => "recommended_review",
					"href" => site_url() . "/wp-admin/admin.php?page=recommended_plugins_review",
					"title" => __("Recommendations", review_bank))
			);
			$wp_admin_bar->add_menu(array(
					"parent" => "review_bank",
					"id" => "other_services_review",
					"href" => site_url() . "/wp-admin/admin.php?page=other_services_review",
					"title" => __("Our Other Services", review_bank))
			);
			$wp_admin_bar->add_menu(array(
					"parent" => "review_bank",
					"id" => "review_bank_system_status",
					"href" => site_url() . "/wp-admin/admin.php?page=review_bank_system_status",
					"title" => __("System Status", review_bank))
			);
			break;
		case "editor":
			$wp_admin_bar->add_menu(array(
			"id" => "review_bank",
			"title" => __("<img src=\"" . plugins_url("/assets/images/icon.png",__FILE__)."\" width=\"25\"
			height=\"25\" style=\"vertical-align:text-top; margin-right:5px;\" />WP Review Bank"),
			"href" => __(site_url() . "/wp-admin/admin.php?page=dashboard_review"),
			));

			$wp_admin_bar->add_menu(array(
					"parent" => "review_bank",
					"id" => "Dashboard",
					"href" => site_url() . "/wp-admin/admin.php?page=dashboard_review",
					"title" => __("Dashboard", review_bank))
			);
			$wp_admin_bar->add_menu(array(
					"parent" => "review_bank",
					"id" => "Add New Review",
					"href" => site_url() . "/wp-admin/admin.php?page=review_bank",
					"title" => __("Add New Review", review_bank))
			);
			$wp_admin_bar->add_menu(array(
					"parent" => "review_bank",
					"id" => "short code review",
					"href" => site_url() . "/wp-admin/admin.php?page=short_code_review",
					"title" => __("Short Codes", review_bank))
			);
			$wp_admin_bar->add_menu(array(
					"parent" => "review_bank",
					"id" => "recommended_review",
					"href" => site_url() . "/wp-admin/admin.php?page=recommended_plugins_review",
					"title" => __("Recommendations", review_bank))
			);
			$wp_admin_bar->add_menu(array(
					"parent" => "review_bank",
					"id" => "other services_review",
					"href" => site_url() . "/wp-admin/admin.php?page=other_services_review",
					"title" => __("Our Other Services", review_bank))
			);
			$wp_admin_bar->add_menu(array(
					"parent" => "review_bank",
					"id" => "review_bank_system_status",
					"href" => site_url() . "/wp-admin/admin.php?page=review_bank_system_status",
					"title" => __("System Status", review_bank))
			);
			break;
		case "author":
			$wp_admin_bar->add_menu(array(
			"id" => "review_bank",
			"title" => __("<img src=\"" . plugins_url("/assets/images/icon.png",__FILE__)."\" width=\"25\"
			height=\"25\" style=\"vertical-align:text-top; margin-right:5px;\" />WP Review Bank"),
			"href" => __(site_url() . "/wp-admin/admin.php?page=dashboard_review"),
			));

			$wp_admin_bar->add_menu(array(
					"parent" => "review_bank",
					"id" => "Dashboard",
					"href" => site_url() . "/wp-admin/admin.php?page=dashboard_review",
					"title" => __("Dashboard", review_bank))
			);
			$wp_admin_bar->add_menu(array(
					"parent" => "review_bank",
					"id" => "Add New Review",
					"href" => site_url() . "/wp-admin/admin.php?page=review_bank",
					"title" => __("Add New Review", review_bank))
			);
			$wp_admin_bar->add_menu(array(
					"parent" => "review_bank",
					"id" => "short code review",
					"href" => site_url() . "/wp-admin/admin.php?page=short_code_review",
					"title" => __("Short Codes", review_bank))
			);
			$wp_admin_bar->add_menu(array(
					"parent" => "review_bank",
					"id" => "recommended_review",
					"href" => site_url() . "/wp-admin/admin.php?page=recommended_plugins_review",
					"title" => __("Recommendations", review_bank))
			);
			$wp_admin_bar->add_menu(array(
					"parent" => "review_bank",
					"id" => "other_services_review",
					"href" => site_url() . "/wp-admin/admin.php?page=other_services_review",
					"title" => __("Our Other Services", review_bank))
			);
			$wp_admin_bar->add_menu(array(
					"parent" => "review_bank",
					"id" => "review_bank_system_status",
					"href" => site_url() . "/wp-admin/admin.php?page=review_bank_system_status",
					"title" => __("System Status", review_bank))
			);
			break;
	}
}

function review_bank_plugin_update_message($args)
{
	$response = wp_remote_get( 'http://plugins.svn.wordpress.org/wp-review-bank/trunk/readme.txt' );
	if ( ! is_wp_error( $response ) && ! empty( $response['body'] ) )
	{
		// Output Upgrade Notice
		$matches        = null;
		$regexp         = '~==\s*Changelog\s*==\s*=\s*[0-9.]+\s*=(.*)(=\s*' . preg_quote($args['Version']) . '\s*=|$)~Uis';
		$upgrade_notice = '';
		if ( preg_match( $regexp, $response['body'], $matches ) ) {
			$changelog = (array) preg_split('~[\r\n]+~', trim($matches[1]));
			$upgrade_notice .= '<div class="framework_plugin_message">';
			foreach ( $changelog as $index => $line ) {
				$upgrade_notice .= "<p>".$line."</p>";
			}
			$upgrade_notice .= '</div> ';
			echo $upgrade_notice;
		}
	}
}
///////////////////////////////////  Call Hooks   /////////////////////////////////////////////////////

register_activation_hook(__FILE__,"plugin_install_script_for_review_bank");
add_action("network_admin_menu", "create_global_menus_for_review_bank" );
add_action("admin_bar_menu", "add_review_icon",100);
add_shortcode("review_bank", "review_bank_short_code");
add_action("wp_head","frontend_plugin_css_styles_review_bank");
add_action("admin_init","backend_plugin_js_review_bank");
add_action("admin_init","backend_plugin_css_styles_review_bank");
add_action("admin_menu","create_global_menus_for_review_bank");
add_action( "media_buttons_context", "add_review_shortcode_button", 1);
add_action("admin_footer","add_review_mce_popup");
add_action("in_plugin_update_message-".REVIEW_FILE,"review_bank_plugin_update_message" );
