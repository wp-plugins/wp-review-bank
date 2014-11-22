<?php
global $wpdb;
require_once(ABSPATH . "wp-admin/includes/upgrade.php");
if (count($wpdb->get_var("SHOW TABLES LIKE '" .wp_review_tbl."'")) == 0)
{
	create_table_review();
}
if(count($wpdb->get_var("SHOW TABLES LIKE '" .wp_review_tbl_features."'")) == 0)
{
	create_table_features();
}
function create_table_review()
{
	$sql= " CREATE TABLE ".wp_review_tbl." (
		id INTEGER(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		review_type VARCHAR(100) NOT NULL,
		heading VARCHAR(100) NOT NULL,
		description TEXT NOT NULL,
		review_color VARCHAR(50),
		font_color VARCHAR(50),
		heading_background_color VARCHAR(50),
		background_color VARCHAR(50),
		body_color VARCHAR(50),
		minimum_val INTEGER(11),
		maximum_val INTEGER(11),
		total DECIMAL(10,2) NOT NULL,
		no_of_features INTEGER(10) NOT NULL,
		PRIMARY KEY(id)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE utf8_general_ci";
		dbDelta($sql);
}
function create_table_features()
{
	$sql= " CREATE TABLE ".wp_review_tbl_features." (
		id INTEGER(10) UNSIGNED NOT NULL AUTO_INCREMENT,
		review_id INTEGER(10) NOT NULL,
		feature TEXT NOT NULL,
		points DECIMAL(10,2) NOT NULL,
		dynamic_id INTEGER(10) NOT NULL,
		feature_sorting INTEGER(10) NOT NULL,
		PRIMARY KEY(id)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE utf8_general_ci";
		dbDelta($sql);
}