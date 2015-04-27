<?php
global $wpdb,$current_user,$user_role_permission;
$wprb_role = $wpdb->prefix . "capabilities";
$current_user->role = array_keys($current_user->$wprb_role);
$wprb_role = $current_user->role[0];

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
	class review_bank
	{
		function save_reviews($review_bank)
		{
			global $wpdb;
			$wpdb->insert(wp_review_tbl,$review_bank);
		}
		function update_reviews($review_bank,$where_review)
		{
			global $wpdb;
			$wpdb->update(wp_review_tbl,$review_bank,$where_review);
		}
		function save_review_features($features_review)
		{
			global $wpdb;
			$wpdb->insert(wp_review_tbl_features,$features_review);
		}
		function delete_old_reviews($where_review)
		{
			global $wpdb;
			$wpdb->delete(wp_review_tbl_features,$where_review);
		}
		
		function update_review_total($features_review_total,$where_review_total)
		{
			global $wpdb;
			$wpdb->update(wp_review_tbl,$features_review_total,$where_review_total);
		}
	}
	if(isset($_REQUEST["param"]))
	{
		if($_REQUEST["param"] == "review_bank")
		{
			$insert= new review_bank();
			$review_bank = array();
			$review_bank["id"]= intval($_REQUEST["review_id"]);
			$review_bank["review_type"]= esc_attr($_REQUEST["ux_ddl_review_type"]);
			$review_bank["heading"]= esc_html($_REQUEST["heading"]);
			$review_bank["description"]= esc_html($_REQUEST["ux_wprb_description"]);
			$review_bank["review_color"]= esc_attr($_REQUEST["ux_clr_text_color_input_field"]);
			$review_bank["font_color"]= esc_attr($_REQUEST["ux_txt_font_color"]);
			$review_bank["heading_background_color"]= esc_attr($_REQUEST["ux_txt_heading_font_color"]);
			$review_bank["background_color"]= esc_attr($_REQUEST["ux_clr_background_color_input_field"]);
			$review_bank["body_color"]= esc_attr($_REQUEST["ux_txt_body_color"]);
			$review_bank["minimum_val"]= esc_attr($_REQUEST["ux_txt_min_value"]);
			$review_bank["maximum_val"]= esc_attr($_REQUEST["ux_txt_max_value"]);
			
			$features_review=array();
			$features_dynamicid= json_decode($_REQUEST["features_dynamic_id"]);
			if($_REQUEST["sorting_id"] != "")
			{
				$sorting_str = $_REQUEST["sorting_id"];
				$sorting=explode(",","$sorting_str");
			}
			else
			{
				$sorting=json_decode($_REQUEST["features_dynamic_id"]);
			}
			$features_review_total=array();
			$features_review_total["total"]= $_REQUEST["ux_txt_total"];
			$features_review_total["no_of_features"]= count($features_dynamicid);
			$where_review_total = array();
			$where_review_total["id"] = intval($_REQUEST["review_id"]);
			if(esc_attr($_REQUEST["check"] == "add"))
			{
				$insert->save_reviews($review_bank);
				for($flag=0;$flag <count($features_dynamicid);$flag++)
				{
					$dynamicid= intval($sorting[$flag]);
					$features_review["review_id"]= intval($_REQUEST["review_id"]);
					$features_review["feature"]= esc_attr(htmlspecialchars($_REQUEST["ux_txt_list_features_".$dynamicid]));
					$features_review["points"]= ($_REQUEST["ux_txt_points_".$dynamicid]);
					$features_review["dynamic_id"]= $dynamicid;
					$features_review["feature_sorting"]= $flag;
					$insert->save_review_features($features_review);
					
				}
				$insert->update_review_total($features_review_total,$where_review_total);
			}
			else 
			{
				$where_review = array();
				$where_review["id"] = intval($_REQUEST["review_id"]);
				$insert->update_reviews($review_bank,$where_review);
				
				
				$where_review_features = array();
				$where_review_features["review_id"] =  intval($_REQUEST["review_id"]);
				$insert->delete_old_reviews($where_review_features);
				for($flag=0;$flag <count($features_dynamicid);$flag++)
				{
					$dynamicid= intval($sorting[$flag]);
					$features_review["review_id"]= intval($_REQUEST["review_id"]);
					$features_review["feature"]= esc_attr($_REQUEST["ux_txt_list_features_".$dynamicid]);
					$features_review["points"]= $_REQUEST["ux_txt_points_".$dynamicid];
					$features_review["dynamic_id"]= $dynamicid;
					$features_review["feature_sorting"]= $flag;
					$insert->save_review_features($features_review);
				}	
				$insert->update_review_total($features_review_total,$where_review_total);
			}
			die();
		}
		else if($_REQUEST["param"] == "delete_all_reviews")
		{
			$reviews = isset($_REQUEST["review_title"]) ? implode(",",$_REQUEST["review_title"]) : "0";
			$wpdb->query
			(
				"Delete from ".wp_review_tbl." where id in (".$reviews.")"
			);
			$wpdb->query
			(
				"Delete from ".wp_review_tbl_features." where review_id in (".$reviews.")"
			);
			die();
		}
		else if($_REQUEST["param"] == "delete_review")
		{
			$review_id = intval($_REQUEST["review_id"]);
			$wpdb->query
			(
				"Delete from ".wp_review_tbl." where id= " .$review_id
			);
			$wpdb->query
			(
				"Delete from ".wp_review_tbl_features." where review_id= " .$review_id
			);
			die();
		}
		else if($_REQUEST["param"] == "review_plugin_updates")
		{
			$review_updates = intval($_REQUEST["review_updates"]);
			update_option("review-bank-automatic-update",$review_updates);
			die();
		}
	}
}
?>