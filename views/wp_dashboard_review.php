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
	$review_details = $wpdb->get_results
	(
		"SELECT "
			.wp_review_tbl.".id,"
			.wp_review_tbl.".heading,"
			.wp_review_tbl.".review_type,"
			.wp_review_tbl.".maximum_val,"
			.wp_review_tbl.".total,"
			.wp_review_tbl.".no_of_features 
			from " .wp_review_tbl
	);	
	?>
	<form id="ux_frm_review_dashboard" class="wpib-page-width">
		<div class="fluid-layout">
			<div class="layout-span12">
				<div class="widget-layout">
					<div class="widget-layout-title">
						<h4>
							<?php _e("Dashboard", review_bank); ?>
						</h4>
					</div>
					<div class="widget-layout-body">
						<div class="layout-control-group">
							<select id="ux_ddl_bulk_action" name="ux_ddl_bulk_action" class="layout-span2">
								<option value="0"><?php _e("Bulk Action", review_bank); ?></option>
								<option value="1"><?php _e("Delete", review_bank); ?></option>
							</select>
							<input type="button" id="ux_btn_action" onclick="bulk_delete();" name="ux_btn_action" class="btn btn-success" value="<?php _e("Apply", review_bank); ?>">
							<a class="btn btn-success" href="admin.php?page=review_bank" style="margin-top: 1px;"><?php _e("Add New Review", review_bank); ?></a>
						</div>
						<div class="separator-doubled"></div>
						<table class="widefat" style="background-color:#fff !important" id="data-table-dashboard">
							<thead>
								<tr>
									<th style="width: 10%"><input type="checkbox" id="selectall" name="selectall" style="margin: 0 0 0 0px;"></th>
									<th style="width: 15%"><?php _e("Review Title", review_bank); ?></th>
									<th style="width: 15%"><?php _e("Type", review_bank); ?></th>
									<th style="width: 8%"><?php _e("Total", review_bank); ?></th>
									<th style="width: 12%"><?php _e("No. of Features", review_bank); ?></th>
									<th style="width: 20%"><?php _e("Shortcode", review_bank); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
								for($flag = 0; $flag < count($review_details); $flag++)
								{
									$alternate = $flag % 2 == 0 ? "alternate" : "";
									?>
									<tr class="<?php echo $alternate; ?>">
										<td>
											<input type="checkbox" class="ux_chk_delete" name="review_title[]" value="<?php echo $review_details[$flag]->id; ?>" id="ux_chk_review_<?php echo $flag; ?>" >
										</td>
										<td>
											<?php echo stripcslashes(htmlspecialchars_decode($review_details[$flag]->heading));?>
											
											<br>
											<a  href="admin.php?page=review_bank&review_id=<?php echo $review_details[$flag]->id;?>">Edit</a> | 
											<a href="#"  onclick="delete_review(<?php echo $review_details[$flag]->id; ?>)"><?php _e("Delete", review_bank); ?></a>
										</td>
										<td>
											<?php echo $review_details[$flag]->review_type == "1" ? _e("Star", review_bank) : ($review_details[$flag]->review_type == "2" ? _e("Point", review_bank) : _e("Percentage", review_bank));?>
										</td>
										<td>
											<?php 
											$total = explode(".",$review_details[$flag]->total);
											$total_val = $total[1] == "00" ? $total[0] : $review_details[$flag]->total;
											echo $review_details[$flag]->review_type == "1" ? $total_val."/".$review_details[$flag]->maximum_val :
											 ($review_details[$flag]->review_type == "2" ? $total_val."/".$review_details[$flag]->maximum_val: $total_val."%");?>
										</td>
										<td>
											<?php echo $review_details[$flag]->no_of_features;?>
										</td>
										<td>
											[review_bank review_id=<?php echo $review_details[$flag]->id;?>]
										</td>
									</tr>
									<?php
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</form>
	<script type="text/javascript">
	jQuery(document).ready(function()
	{
		oTable = jQuery("#data-table-dashboard").dataTable
		({
			"bJQueryUI": false,
			"bAutoWidth": true,
			"sPaginationType": "full_numbers",
			"sDom": "<\"datatable-header\"fl>t<\"datatable-footer\"ip>",
			"oLanguage": {
			"sLengthMenu": "<span>Show entries:</span> _MENU_"
			},
			"aaSorting": [
				[ 0, "asc" ]
			],
			"aoColumnDefs": [
				{ "bSortable": false, "aTargets": [] }
			]
		});
		jQuery(".dataTables_wrapper").css("margin-top","20px");
		jQuery(".datatable-header").css("float","right");
		jQuery(".datatable-header").css("margin-bottom","8px");
	});
	jQuery("#selectall").click(function()
	{
		if(jQuery("#selectall").prop("checked") == true)
		{
			jQuery("input:checkbox[name=\"review_title[]\"]").attr("checked","checked");
		}
		else
		{
			jQuery("input:checkbox[name=\"review_title[]\"]").removeAttr("checked","checked");
		}
	});
	jQuery("input:checkbox[name=\"review_title[]\"]").click(function()
	{
		if(jQuery(this).prop("checked") == false)
		{
			jQuery("#selectall").removeAttr("checked","checked");
		}
	});
	function bulk_delete()
	{
		if(jQuery("#ux_ddl_bulk_action").val() == "1")
		{
			var confirm_delete =  confirm("<?php _e( "Are you sure, you want to delete these Reviews ?", review_bank ); ?>");
			 if(confirm_delete == true)
			 {
				jQuery.post(ajaxurl,jQuery("#ux_frm_review_dashboard").serialize()+"&param=delete_all_reviews&action=review_bank_library", function(data) 
				{
					window.location.reload();
				});
			}
		}
	}
	function delete_review(review_id)
	{
		var confirm_delete =  confirm("<?php _e( "Are you sure, you want to delete this Review ?", review_bank ); ?>");
		 if(confirm_delete == true)
		 {
			jQuery.post(ajaxurl,"review_id="+review_id+"&param=delete_review&action=review_bank_library", function(data) 
			{
				window.location.reload();
			});
		 }
	}
	</script>
<?php 
}
?>