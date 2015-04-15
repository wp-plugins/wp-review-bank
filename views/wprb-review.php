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
	if(isset($_REQUEST["review_id"]))
	{
		$review_id = $_REQUEST["review_id"];
	}
	else 
	{
		$last_form_id = $wpdb->get_var
		(
				"SELECT id FROM " .wp_review_tbl. " order by id desc limit 1"
		);
		$review_id = count($last_form_id) == 0 ? 1 : $last_form_id + 1;
	}
	if(isset($_REQUEST["review_id"]))
	{
		$edit_review_id = $_REQUEST["review_id"];
		$review_data = $wpdb->get_row
		(
			"SELECT * FROM " .wp_review_tbl. " where id =".$edit_review_id
		);
		$review_features = $wpdb->get_results
		(
			"SELECT * FROM " .wp_review_tbl_features. " where review_id =".$edit_review_id . " order by feature_sorting asc"
		);
		
	}
	?>
	<form id="ux_frm_review" class="layout-form wpib-page-width">
		<div class="fluid-layout">
			<div class="layout-span12">
				<div class="widget-layout ">
					<div class="widget-layout-title">
						<h4>
							<?php
							if(isset($_REQUEST["review_id"]))
							{
								_e("Update Review", review_bank);
							}
							else 
							{
								_e("Add New Review", review_bank);
								
							}
							 ?>
						</h4>
					</div>
					<div class="widget-layout-body">
						<a class="btn btn-success" href="admin.php?page=dashboard_review" style="margin-bottom:4px;"><?php _e("Back to Dashboard", review_bank);?></a>
						<input type="submit" class="btn btn-success" style="margin-bottom:4px; float:right" value="<?php _e("Save Changes", review_bank);?>" />
							<div class="separator-doubled" style="padding: 6px ;"></div>
							<div id=form_success_message class="message green" style="display: none; margin-bottom:8px;">
							<span >
								<strong><?php _e("Reviews Submitted. Kindly wait for the redirect.", review_bank); ?></strong>
							</span>
						</div>
						<div class="widget-layout">
							<div class="widget-layout-title">
							<h4><?php
								if(isset($_REQUEST["review_id"]))
								{
									_e("Update Review - WP Review Bank", review_bank);
								}
								else 
								{
									_e("Add New Review - WP Review Bank", review_bank);
									
								}
								 ?></h4>
							</div>
							<div class="widget-layout-body">
								<div class="layout-control-group">
									<label class="layout-control-label"><?php _e("Review Type", review_bank); ?> : <span class="error">*</span> </label>
									<div class="layout-controls">
										<select class="layout-span12" onchange="change_value_set();" name="ux_ddl_review_type" id="ux_ddl_review_type">
											<option value="1" <?php echo (isset($review_data) && $review_data->review_type == "1") ? "selected" : ""?>>
											<?php _e( "Star", review_bank ); ?></option>
											<option value="2" <?php echo (isset($review_data) && $review_data->review_type == "2") ? "selected" : ""?>>
											<?php _e( "Point", review_bank ); ?></option>
											<option value="3" <?php echo (isset($review_data) && $review_data->review_type == "3") ? "selected" : ""?>>
											<?php _e( "Percentage", review_bank ); ?></option>
										</select>
									</div>
								</div>
								<div class="layout-control-group">
									<div class="layout-span6">
										<label class="layout-control-label"><?php _e("Minimum", review_bank); ?> : <span class="error">*</span> </label>
										<div class="layout-controls">
											<input type="text" id="ux_txt_min_value" onkeypress="return OnlyNumbers(event)" 
												 onblur="change_points_value();" name="ux_txt_min_value" 
												class="layout-span12" placeholder="Enter Minimum Value" 
												value="<?php echo isset($review_data) ? $review_data->minimum_val : "";?>"/>
										</div>
									</div>
									<div class="layout-span6">
										<label class="layout-control-label"><?php _e("Maximum", review_bank); ?> : <span class="error">*</span> </label>
										<div class="layout-controls">
											<input type="text" id="ux_txt_max_value"
											 onkeypress="return OnlyNumbers(event)" onblur="change_points_value();" 
											 name="ux_txt_max_value" class="layout-span12" placeholder="Enter Maximum value" 
											 value="<?php echo isset($review_data) ? $review_data->maximum_val : "";?>"/>
										</div>
									</div>
								</div>
								<div class="layout-control-group">
									<label class="layout-control-label"><?php _e("Heading", review_bank); ?> : <span class="error">*</span> </label>
									<div class="layout-controls">
										<input type="text" id="ux_txt_heading" name="ux_txt_heading" class="layout-span12" placeholder="" 
										value="<?php echo isset($review_data) ? stripcslashes($review_data->heading) : "";?>"/>
									</div>
								</div>
								<div class="layout-control-group">
									<label class="layout-control-label"><?php _e("Description", review_bank); ?> : </label>
									<div class="layout-controls">
									<div class="layout-span12 wpib-margin-top-bottom">
									<?php
										$distribution = isset($review_data) ? stripcslashes(htmlspecialchars_decode($review_data->description, ENT_QUOTES)) : "";
										wp_editor( $distribution, $name ="ux_wprb_description" ,array("media_buttons" => false, 
										"textarea_rows" => 8, "tabindex" => 4,"tinymce" =>false )); 
									?>
									</div>
									</div>
								</div>
								<div class="layout-control-group">
									<div class="layout-span6">
										<label class="layout-control-label"><?php _e("Review Color", review_bank); ?> : </label>
										<div class="layout-controls">
											<input type="text" class="layout-span9" id="ux_clr_text_color_input_field" 
												name="ux_clr_text_color_input_field" onclick="ux_clr_font_color_input_settings();"
												 value="<?php echo isset($review_data) ? $review_data->review_color : "#e3e32b";?>"  
												 style="background-color:<?php echo isset($review_data) ? $review_data->review_color : "#e3e32b";?>;color:#fff;"/>
												 <img onclick="ux_clr_font_color_input_settings();" style="vertical-align: middle;margin-left: 5px;" src="<?php echo plugins_url("/assets/images/color.png" , dirname(__FILE__)); ?>" />
											<div id="clr_text_color"></div>
										</div>
									</div>
									<div class="layout-span6">
										<label class="layout-control-label"><?php _e("Font Color", review_bank); ?> : </label>
										<div class="layout-controls">
											<input type="text" class="layout-span9" name="ux_txt_font_color" id="ux_txt_font_color"  onclick="ux_clr_font_color();"
												value="<?php echo isset($review_data) ? $review_data->font_color : "#000000";?>"  
												style="background-color:<?php echo isset($review_data) ? $review_data->font_color : "#000000";?>;color:#fff;"/>
												<img onclick="ux_clr_font_color();" style="vertical-align: middle;margin-left: 5px;" src="<?php echo plugins_url("/assets/images/color.png" , dirname(__FILE__)); ?>" />
											<div id="clr_font_color"></div>
										</div>
									</div>
								</div>
								<div class="layout-control-group">
									<div class="layout-span6">
										<label class="layout-control-label"><?php _e("Heading Color", review_bank); ?> : </label>
										<div class="layout-controls">
											<input type="text" class="layout-span9" name="ux_txt_heading_font_color" 
											id="ux_txt_heading_font_color"  onclick="ux_clr_heading_color();" 
											value="<?php echo isset($review_data) ? $review_data->heading_background_color : "#fa0000";?>"  
											style="background-color:<?php echo isset($review_data) ? $review_data->heading_background_color : "#fa0000";?>;color:#fff;"/>
											<img onclick="ux_clr_heading_color();" style="vertical-align: middle;margin-left: 5px;" src="<?php echo plugins_url("/assets/images/color.png" , dirname(__FILE__)); ?>" />
											<div id="clr_heading_font_color"></div>
										</div>	
									</div>
									<div class="layout-span6">
										<label class="layout-control-label"><?php _e("Heading BG Color", review_bank); ?> : </label>
										<div class="layout-controls">
												<input type="text" class="layout-span9" id="ux_clr_background_color_input_field" 
												name="ux_clr_background_color_input_field" onclick="ux_clr_background_color_input_settings();" 
												value="<?php echo isset($review_data) ? $review_data->background_color : "#e5e5e5";?>"  
												style="background-color:<?php echo isset($review_data) ? $review_data->background_color : "#e5e5e5";?>;color:#ffffff;"/>
												<img onclick="ux_clr_background_color_input_settings();" style="vertical-align: middle;margin-left: 5px;" src="<?php echo plugins_url("/assets/images/color.png" , dirname(__FILE__)); ?>" />
												<div id="clr_background_color"></div>
										</div>
									</div>
								</div>
								<div class="layout-control-group">
									<div class="layout-span6">
										<label class="layout-control-label"><?php _e("Body Color", review_bank); ?> : </label>
										<div class="layout-controls">
											<input type="text" class="layout-span9" name="ux_txt_body_color" id="ux_txt_body_color"  
											onclick="ux_clr_body_color();" 
											value="<?php echo isset($review_data) ? $review_data->body_color : "#ffffff";?>"  
											style="background-color:<?php echo isset($review_data) ? $review_data->body_color : "#ffffff";?>;color:#000000;"/>
											<img onclick="ux_clr_body_color();" style="vertical-align: middle;margin-left: 5px;" src="<?php echo plugins_url("/assets/images/color.png" , dirname(__FILE__)); ?>" />
											<div id="clr_body_color"></div>
										</div>
									</div>
								</div>
								<div class="layout-control-group">
									<table class="widefat" style="width:100%;border:1px solid #e5e5e5; background-color:#fff !important">
										<thead>
											<tr style="padding:3px;">
												<td>
													<table style="width:100%;">
														<th style="width:70%;text-align:left;">List of Features</th>
														<th style="width:18%;text-align: left;" id="change_points">Points (0-10)</th>
														<th style="width:8%;padding:3px;"></th>
													</table>
												</td>
											</tr>
										</thead>
										<tbody id="add_another_item">
										<?php 
										if(isset($review_features))
										{
											for($flag=0;$flag<count($review_features);$flag++)
											{
												$dynamic_id = $flag;
												$alternate = $dynamic_id % 2 == 0 ? "alternate" : "";
												?>
												<tr id="<?php echo $dynamic_id;?>" class="<?php echo $alternate;?>" style="cursor:move;">
													<td style="padding:1px 15px;">
														<table style="width:100%;border:0.152em dashed #dddddd;margin:5px 0px;">
															<tr>
																<td style="width:70%;padding:9px 3px 1px 5px;">
																	<input type="text" style="margin-bottom: 7px;" class="layout-span12" 
																	id="ux_txt_list_features_<?php echo $dynamic_id;?>" name="ux_txt_list_features_<?php echo $dynamic_id;?>" 
																	value="<?php echo isset($review_features) ? esc_attr(stripcslashes(htmlspecialchars($review_features[$flag]->feature))) : "";?>"/>
																</td>
																<td style="width:18%;padding:9px 3px 1px 5px;">
																	<input type="text" class="layout-span12" style="margin-bottom: 7px;" 
																		onkeypress="return OnlyDigits(event);" onblur="OnlyNumbers_total(<?php echo $dynamic_id;?>);"
																	 id="ux_txt_points_<?php echo $dynamic_id;?>" name="ux_txt_points_<?php echo $dynamic_id;?>" 
																	 value="<?php echo isset($review_features) ? $review_features[$flag]->points : "";?>"/>
																</td>
																<td style="padding:7px 15px;width:8%;">
																	<input type="button" id="delete_item_<?php echo $dynamic_id;?>"  class="btn btn-success" onclick="delete_features(<?php echo $dynamic_id;?>);" name="delete_item_<?php echo $dynamic_id;?>" value="Delete"/>
																</td>
															</tr>
														</table>
													</td>
												</tr>
												<?php
											}
										}
										?>
										</tbody>
									</table>
								</div>
								<div class="layout-control-group">
									<div class="layout-span8">
										<input type="button" id="ux_add_another_item" name="ux_add_another_item " class="btn btn-success" onclick="add_another_item();" value="Add Another Item">
									</div>
									<div class="layout-span4" id="ux_total_points">
									<input type="text" style="border: 1px solid #dddddd;margin-left:8%;width: 53%;" 
										readonly="readonly" class="layout-span8" id="ux_txt_total"name="ux_txt_total"
										 value="<?php echo isset($review_data) ? $review_data->total : "0.00";?>">
									<label style="margin-left:25px;font-weight:bold;"><?php _e("Total", review_bank); ?></label>
									</div>
								</div>
							</div>
						</div>
						<div class="separator-doubled" style="padding: 3px 0px 6px 0px;"></div>
						<a class="btn btn-success" href="admin.php?page=dashboard_review" style="margin-bottom:4px;"><?php _e("Back to Dashboard", review_bank);?></a>
						<input type="submit" class="btn btn-success" style="margin-bottom:4px; float:right" value="<?php _e("Save Changes", review_bank);?>" />
					</div>
				</div>
			</div>
		</div>
	</form>
	<script type="text/javascript">
	var features_dynamic_id=[];
	var sort_id = 0;
	var total_rate=[];
	var order = "";
	jQuery(document).ready(function()
	{
		<?php 
		if(!((isset($review_features)) && (count($review_features) > 0)))
		{
			?>
			add_another_item();
			change_value_set();
			<?php
		}
		if(isset($review_features))
		{
			for($flag=0;$flag<count($review_features);$flag++)
			{
				?>
				features_dynamic_id.push(<?php echo $flag;?>);
				sort_id++;
				<?php
			}
		}
		?>
		//var review_type = jQuery("#ux_ddl_review_type").val();
		change_points_value();

		jQuery("#add_another_item").sortable
		({
			opacity: 0.6,
			cursor: "move",
			update: function()
			{
				order = jQuery("#add_another_item").sortable("toArray");
			}
		});
	});
	function ux_clr_font_color_input_settings()
	{
		jQuery("#clr_text_color").farbtastic("#ux_clr_text_color_input_field").slideDown();
		jQuery("#ux_clr_text_color_input_field").focus();
	}
	function ux_clr_font_color()
	{
		jQuery("#clr_font_color").farbtastic("#ux_txt_font_color").slideDown();
		jQuery("#ux_txt_font_color").focus();
	}
	function ux_clr_heading_color()
	{
		jQuery("#clr_heading_font_color").farbtastic("#ux_txt_heading_font_color").slideDown();
		jQuery("#ux_txt_heading_font_color").focus();
	}
	function ux_clr_background_color_input_settings()
	{
		jQuery("#clr_background_color").farbtastic("#ux_clr_background_color_input_field").slideDown();
		jQuery("#ux_clr_background_color_input_field").focus();
	}
	function ux_clr_body_color()
	{
		jQuery("#clr_body_color").farbtastic("#ux_txt_body_color").slideDown();
		jQuery("#ux_txt_body_color").focus();
	}
	function add_another_item()
	{
		var dynamicid= sort_id;
		alternate = dynamicid % 2 == 0 ? "alternate" : "";
		jQuery("#add_another_item").append("<tr class="+alternate+" style=\"cursor:move;\" id="+dynamicid+"><td style=\"padding:1px 15px;\"><table style=\"width:100%;border:0.152em dashed #dddddd;margin:5px 0px;\"><tr><td style=\"width:70%;padding:9px 3px 1px 5px;\"><input type=\"text\" style=\"margin-bottom: 7px;\" class=\"layout-span12\" id=\"ux_txt_list_features_"+dynamicid+"\" name=\"ux_txt_list_features_"+dynamicid+"\" value=\"\"/></td><td style=\"width:18%;padding:9px 3px 1px 5px;\"><input type=\"text\" class=\"layout-span12\" style=\"margin-bottom: 7px;\" onkeypress=\"return OnlyDigits(event);\" onblur=\"OnlyNumbers_total("+dynamicid+");\" id=\"ux_txt_points_"+dynamicid+"\" name=\"ux_txt_points_"+dynamicid+"\" value=\"\"/></td><td style=\"padding:7px 15px;width:8%;\"><input type=\"button\" id=\"delete_item_"+dynamicid+"\"  class=\"btn btn-success\" onclick=\"delete_features("+dynamicid+");\" name=\"delete_item_"+dynamicid+"\" value=\"Delete\"/></td></tr></table></td></tr>");
		features_dynamic_id.push(dynamicid);
		jQuery("#ux_total_points").css("display","block");
		sort_id++;
		calculate_total_rate();
	}
	function calculate_total_rate()
	{
		var total = 0;
		for (var flag = 0; flag < features_dynamic_id.length; flag++) 
		{
			var amoun_control = jQuery("#ux_txt_points_"+features_dynamic_id[flag]).val();
			total+=parseFloat(amoun_control) || 0;
		}
		var review_type = jQuery("#ux_ddl_review_type").val();
		if(review_type == 1 || review_type == 2)
		{
			var final_rate = total / features_dynamic_id.length;
			jQuery("#ux_txt_total").val(final_rate.toFixed(2));
		}
		else
		{
			var maximum_type = jQuery("#ux_txt_max_value").val();
			var final_rate = ((total / features_dynamic_id.length)/maximum_type) * 100;
			jQuery("#ux_txt_total").val(final_rate.toFixed(2));
		}
	}
	jQuery("#ux_clr_text_color_input_field").blur(function(){jQuery("#clr_text_color").slideUp()});
	jQuery("#ux_txt_font_color").blur(function(){jQuery("#clr_font_color").slideUp()});
	jQuery("#ux_txt_heading_font_color").blur(function(){jQuery("#clr_heading_font_color").slideUp()});
	jQuery("#ux_clr_background_color_input_field").blur(function(){jQuery("#clr_background_color").slideUp()});
	jQuery("#ux_txt_body_color").blur(function(){jQuery("#clr_body_color").slideUp()});
	jQuery("#ux_frm_review").validate
	({
		rules :
		{
			ux_ddl_review_type:
			{
				required:true
			},
			ux_txt_heading:
			{ 
		 		required:true 
			},
			ux_txt_min_value:
			{ 
		 		required:true
		 	},
			ux_txt_max_value:
			{ 
		 		required:true
			}
		},
		submitHandler: function(form)
		{
			var heading= encodeURIComponent(jQuery("#ux_txt_heading").val());
			jQuery("body").css("opacity",".5");
			var overlay = jQuery("<div class=\"processing_overlay\"></div>");
			jQuery("body").append(overlay);
			
			jQuery("#form_success_message").css("display","block");
			jQuery("body,html").animate({
			scrollTop: jQuery("body,html").position().top}, "slow");
			var review_id = "<?php echo $review_id;?>";
			var check = "<?php echo isset($_REQUEST["review_id"]) ? "edit" : "add";?>";
			jQuery.post(ajaxurl,jQuery(form).serialize()+"&features_dynamic_id="+JSON.stringify(features_dynamic_id)+"&review_id="+review_id+"&heading="+heading+"&check="+check+"&sorting_id="+order+"&param=review_bank&action=review_bank_library", function(data) 
			{
				setTimeout(function()
				{
					jQuery("#form_success_message").css("display","none");
					jQuery(".processing_overlay").remove();
					jQuery("body").css("opacity","1");
					window.location.href = "admin.php?page=dashboard_review";
				}, 2000);
	 		});
		}
	});
	function check_review_type()
	{
		var review_type = jQuery("#ux_ddl_review_type").val();
		var minimum_type = jQuery("#ux_txt_min_value").val();
		var maximum_type = jQuery("#ux_txt_max_value").val();
		if(review_type == 1)
		{
			if(minimum_type < 10)
			{
				if(maximum_type < minimum_type)
				{
					jQuery("#ux_txt_max_value").val("10");
				}
				else
				{
					if(jQuery("#ux_txt_max_value").val() > 10 )
					{
						jQuery("#ux_txt_max_value").val("10");
					}
				}
			}
			else
			{
				jQuery("#ux_txt_min_value").val("1");
			}
		}
		else if(review_type == 2 || review_type == 3)
		{
			
			if(minimum_type < 100)
			{
				if(maximum_type < minimum_type)
				{
					jQuery("#ux_txt_max_value").val("100");
				}
				else
				{
					if(jQuery("#ux_txt_max_value").val() > 100 )
					{
						jQuery("#ux_txt_max_value").val("100");
					}
				}
			}
			else
			{
				jQuery("#ux_txt_min_value").val("1");
			}
			
		}
	}
	function change_value_set()
	{
		var review_type = jQuery("#ux_ddl_review_type").val();
		if(review_type == 1)
		{
			jQuery("#ux_txt_min_value").val("1");
			jQuery("#ux_txt_max_value").val("5");
		}
		else if(review_type == 2)
		{
			jQuery("#ux_txt_min_value").val("1");
			jQuery("#ux_txt_max_value").val("10");
		}
		else
		{
			jQuery("#ux_txt_min_value").val("1");
			jQuery("#ux_txt_max_value").val("100");
		}
		change_points_value();
	}
	function change_points_value()
	{
		check_review_type();
		var minimum_type = jQuery("#ux_txt_min_value").val();
		var maximum_type = jQuery("#ux_txt_max_value").val();
		var review_type = jQuery("#ux_ddl_review_type").val();
		var review_type = jQuery("#ux_ddl_review_type").val();
		if(review_type == "1")
		{
			jQuery("#change_points").html("Star ("+minimum_type+" - "+maximum_type+")");
		}
		else if(review_type == "2")
		{
			jQuery("#change_points").html("Point ("+minimum_type+" - "+maximum_type+")");
		}
		else
		{
			jQuery("#change_points").html("Percentage ("+minimum_type+" - "+maximum_type+")");
		}
		//OnlyNumbers_total();
	}
	function delete_features(dynamicid)
	{
		var index = jQuery.inArray(dynamicid,features_dynamic_id) 
		if(index != -1)
		{
			features_dynamic_id.splice(index,1);
			jQuery("#"+dynamicid).remove();
		}
		if(features_dynamic_id.length != 0)
		{
			calculate_total_rate();
		}
		else
		{
			jQuery("#ux_txt_total").val("0.00");
			
			jQuery("#ux_total_points").css("display","none");
		}	
	}
	function OnlyDigits(e) ///////////////////////////////////allow only digits
	{
		var regex = new RegExp("^[0-9.\b]*$");
		var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
		if (regex.test(str))
		{
			return true;
		}
		e.preventDefault();
		return false;
	}
	function OnlyNumbers(e) ///////////////////////////////////allow only digits
	{
		var regex = new RegExp("^[0-9\b]*$");
		var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
		if (regex.test(str))
		{
			return true;
		}
		e.preventDefault();
		return false;
	}
	function OnlyNumbers_total(dynamicid) ///////////////////////////////////allow only digits
	{
		var minimum_type = jQuery("#ux_txt_min_value").val();
		var maximum_type = jQuery("#ux_txt_max_value").val();
		var total_rate_value = jQuery("#ux_txt_points_"+dynamicid).val();
		
		if(parseInt(total_rate_value) < minimum_type)
		{
			jQuery("#ux_txt_points_"+dynamicid).val(minimum_type);
		}
		else if(parseInt(total_rate_value) > maximum_type)
		{
			jQuery("#ux_txt_points_"+dynamicid).val(maximum_type);
		}
		calculate_total_rate();
	}
	</script>
<?php 
}
?>