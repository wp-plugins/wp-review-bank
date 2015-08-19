<?php 
global $wpdb;
$last_form_id = $wpdb->get_var
(
	$wpdb->prepare
	(
		"SELECT count(id) FROM " .wp_review_tbl. " where id = %d",
		$review_id
	)
);
if($last_form_id > 0)
{
	$review = $wpdb->get_row
	(
			$wpdb->prepare
			(
					"SELECT * from " .wp_review_tbl. " where id = %d",
					$review_id
			)
	);
	
	$review_details = $wpdb->get_results
	(
			$wpdb->prepare
			(
					"SELECT * from " .wp_review_tbl_features. " where review_id = %d order by id asc",
					$review_id
			)
	);
	?>
	<style>
	.font_clr {color: <?php echo $review->font_color;?>}
	.heading_bg_clr {color: <?php echo $review->heading_background_color;?>}
	.bg_clr {background-color: <?php echo $review->background_color;?>}
	.body_clr {background-color: <?php echo $review->body_color;?>;border: 1px solid #e5e5e5;}
	</style>
	
	<div id="review " class="wp-review-bank-wrapper body_clr">
	<h5 class="wp-review-bank-title heading_bg_clr bg_clr"><?php echo stripcslashes(htmlspecialchars_decode($review->heading));?></h5>
	<div class="wp-review-bank-desc font_clr" style="float:left;">
	<p class="wp-review-bank-summary-title">
		<strong class="font_clr">Summary</strong>
		<p class="font_clr"><?php echo stripslashes(htmlspecialchars_decode($review->description, ENT_QUOTES));?></p>
	</p>
	</div>
	<div class="wp-review-bank-desc_total" style="float:right;">
	<?php 
		if(PHP_VERSION < 5.4)
		{
			if($review->review_type == 1)
			{
				?>
				<span class="wp-review-bank-total-box " itemprop="review"><?php echo round( $review->total, 2);?>/<?php echo $review->maximum_val;?></span>
			<?php
			}
			else if($review->review_type == 2)
			{
			?>
				<span class="wp-review-bank-total-box " itemprop="review"><?php echo round( $review->total, 2);?>/<?php echo $review->maximum_val;?></span>
			<?php
			}
			else
			{
				$total = explode(".",$review->total);
				$total_val = $total[1] == "00" ? $total[0] :$review->total;
			?>
				<span class="wp-review-bank-total-box" itemprop="review"><?php echo round($total_val, 2);?>%</span>
			<?php
			}
		}
		else
		{
			if($review->review_type == 1)
			{
				?>
				<span class="wp-review-bank-total-box " itemprop="review"><?php echo round( $review->total, 1, PHP_ROUND_HALF_UP);?>/<?php echo $review->maximum_val;?></span>
			<?php
			}
			else if($review->review_type == 2)
			{
			?>
				<span class="wp-review-bank-total-box " itemprop="review"><?php echo round( $review->total, 1, PHP_ROUND_HALF_UP);?>/<?php echo $review->maximum_val;?></span>
			<?php
			}
			else
			{
				$total = explode(".",$review->total);
				$total_val = $total[1] == "00" ? $total[0] :$review->total;
			?>
				<span class="wp-review-bank-total-box" itemprop="review"><?php echo round($total_val, 1, PHP_ROUND_HALF_UP);?>%</span>
			<?php
			}
		}
			
		?>
		</div>
	<?php 
		for($flag = 0; $flag < count($review_details);$flag++)
		{
			if($review->review_type == 1)
			{
				?>
				<style>
				.wp-review-bank-result-wrapper .wp-review-bank-result i { color: <?php echo $review->review_color;?>; opacity: 1; filter: alpha(opacity=100); }
				.wp-review-bank-result-wrapper i{ color: <?php echo $review->review_color;?>; opacity: 0.50; filter: alpha(opacity=50); }
				span {color: <?php echo $review->font_color;?>};
				</style>
				<div id="review" class="wp-review-bank-wrapper">
					<ul class="wp-review-bank-list">
						<li>
							<span><?php echo esc_attr(stripcslashes(htmlspecialchars($review_details[$flag]->feature)));?></span>
							<div class="wp-review-bank-star">
								<div class="wp-review-bank-result-wrapper">
									<i class="mts-icon-star"></i>
									<i class="mts-icon-star"></i>
									<i class="mts-icon-star"></i>
									<i class="mts-icon-star"></i>
									<i class="mts-icon-star"></i>
									<div class="wp-review-bank-result" style="color:<?php echo $review->review_color;?>; width:<?php echo $review_details[$flag]->points * 100 / $review->maximum_val;?>%;">
										<i class="mts-icon-star"></i>
										<i class="mts-icon-star"></i>
										<i class="mts-icon-star"></i>
										<i class="mts-icon-star"></i>
										<i class="mts-icon-star"></i>
									</div>
								</div>
							</div>
						</li>
					</ul>
				</div>
				<?php 
				
			}
			else if($review->review_type == 2)
			{
				?>
				<style>
				.bar-point .wp-review-bank-result { background-color: <?php echo $review->review_color;?>; }
				
				span {color: <?php echo $review->font_color;?>};
				</style>
				<div id="review" class="wp-review-bank-wrapper  bar-point ">
					<ul class="wp-review-bank-list">
					<?php 
					if(PHP_VERSION < 5.4)
					{	
					?>
						<li><span><?php echo esc_attr(stripcslashes(htmlspecialchars($review_details[$flag]->feature)));?> (<?php echo round($review_details[$flag]->points, 2) ."/". $review->maximum_val;?>)</span>
							<div class="wp-review-bank-star">
								<div class="wp-review-bank-result-wrapper">
									<div class="wp-review-bank-result" style="color:<?php echo $review->review_color;?>; width:<?php echo $review_details[$flag]->points * 100 / $review->maximum_val ."%";?>;"></div>
								</div>
							</div>
						</li>
					<?php 
					}
					else
					{
						?>
						<li><span><?php echo esc_attr(stripcslashes(htmlspecialchars($review_details[$flag]->feature)));?> (<?php echo round($review_details[$flag]->points, 1, PHP_ROUND_HALF_UP) ."/". $review->maximum_val;?>)</span>
							<div class="wp-review-bank-star">
								<div class="wp-review-bank-result-wrapper">
									<div class="wp-review-bank-result" style="color:<?php echo $review->review_color;?>; width:<?php echo $review_details[$flag]->points * 100 / $review->maximum_val ."%";?>;"></div>
								</div>
							</div>
						</li>
					<?php 
					}
					?>
					</ul>
				</div>
				
				
				<?php 
			}
			else if($review->review_type == 3)
			{
				?>
				<style>
				.bar-point .wp-review-bank-result { background-color: <?php echo $review->review_color;?>; }
				
				span {color: <?php echo $review->font_color;?>};
				</style>
				<div id="review" class="wp-review-bank-wrapper bar-point">
					<ul class="wp-review-bank-list">
					<?php 
					if(PHP_VERSION < 5.4)
					{	
					?>
						<li><span><?php echo esc_attr(stripcslashes(htmlspecialchars($review_details[$flag]->feature)));?> (<?php echo round($review_details[$flag]->points,2) ."%";?>)</span>
							<div class="wp-review-bank-star">
								<div class="wp-review-bank-result-wrapper">
									<div class="wp-review-bank-result" style="color:<?php echo $review->review_color;?>; width:<?php echo $review_details[$flag]->points * 100 / $review->maximum_val ."%";?>;"></div>
								</div>
							</div>
						</li>
						<?php 
					}
					else
					{
						?>
						<li><span><?php echo esc_attr(stripcslashes(htmlspecialchars($review_details[$flag]->feature)));?> (<?php echo round($review_details[$flag]->points, 1, PHP_ROUND_HALF_UP) ."%";?>)</span>
							<div class="wp-review-bank-star">
								<div class="wp-review-bank-result-wrapper">
									<div class="wp-review-bank-result" style="color:<?php echo $review->review_color;?>; width:<?php echo $review_details[$flag]->points * 100 / $review->maximum_val ."%";?>;"></div>
								</div>
							</div>
						</li>
						<?php 
					}	
					?>
					</ul>
				</div>
				<?php 
			}
		}
		?>
		<div id="review" class="wp-review-bank-wrapper bar-point">
			<div itemscope="itemscope" itemtype="http://data-vocabulary.org/Review"></div>
		</div>
	</div>
		<?php 
}
?>