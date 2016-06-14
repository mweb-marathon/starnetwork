<?php
/* ------------------------------------------------------------------------------------
*  COPYRIGHT AND TRADEMARK NOTICE
*  Copyright 2008-2015 AJdG Solutions (Arnan de Gans). All Rights Reserved.
*  ADROTATE is a trademark of Arnan de Gans.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from it's use.
------------------------------------------------------------------------------------ */
?>
<style type="text/css" media="screen">
.postbox-adrotate {
	margin-bottom: 20px;
	padding: 0;
	position: relative;
	min-width: 255px;
	border: #dfdfdf 1px solid;
	background-color: #fff;
	-moz-box-shadow: inset 0 1px 0 #fff;
	-webkit-box-shadow: inset 0 1px 0 #fff;
	box-shadow: inset 0 1px 0 #fff;
	line-height: 1.5;
}

.postbox-adrotate h3 {
	margin: 0;
	padding: 7px 10px 7px 10px;
	box-shadow: #ddd 0px 1px 0px 0px;
	-moz-box-shadow: inset 0 1px 0 #ddd;
	-webkit-box-shadow: #ddd 0px 1px 0px 0px;
	display: block;
	line-height: 15px;
}

.postbox-adrotate .inside {
	margin: 10px 0px 0px 10px;
	padding: 0px 10px 10px 0px;
	min-height: 40px;
	position: relative;
	display: block;
	line-height: 16px;
}

.inside {
	padding: 6px 10px 12px;
	clear: both;
}
</style>

<?php
$banners = $groups = $queued = 0;
$banners = $wpdb->get_var("SELECT COUNT(*) FROM `".$wpdb->prefix."adrotate` WHERE `type` != 'empty' AND `type` != 'a_empty';");
$groups = $wpdb->get_var("SELECT COUNT(*) FROM `".$wpdb->prefix."adrotate_groups` WHERE `name` != '';");
$queued = $wpdb->get_var("SELECT COUNT(*) FROM `".$wpdb->prefix."adrotate` WHERE `type` = 'queue';");
$data = get_option("adrotate_advert_status");
?>

<div id="dashboard-widgets-wrap">
	<div id="dashboard-widgets" class="metabox-holder">

		<div id="postbox-container-1" class="postbox-container" style="width:50%;">
			<div id="normal-sortables" class="meta-box-sortables ui-sortable">
				
				<h3><?php _e('Currently', 'adrotate'); ?></h3>
				<div class="postbox-adrotate">
					<div class="inside">
						<table width="100%">
							<thead>
							<tr class="first">
								<td width="50%"><strong><?php _e('Your setup', 'adrotate'); ?></strong></td>
								<td width="50%"><strong><?php _e('Adverts that need you', 'adrotate'); ?></strong></td>
							</tr>
							</thead>
							
							<tbody>
							<tr class="first">
								<td class="first b"><a href="admin.php?page=adrotate-ads"><?php echo $banners; ?> <?php _e('Adverts', 'adrotate'); ?></a></td>
								<td class="b"><a href="admin.php?page=adrotate-ads"><?php echo $data['expiressoon']; ?> <?php _e('(Almost) Expired', 'adrotate'); ?></a></td>
							</tr>
							<tr>
								<td class="first b"><a href="admin.php?page=adrotate-groups"><?php echo $groups; ?> <?php _e('Groups', 'adrotate'); ?></a></td>
								<td class="b"><a href="admin.php?page=adrotate-ads"><?php echo $data['error']; ?> <?php _e('Have errors', 'adrotate'); ?></a></td>
							</tr>
							<tr>
								<td class="first b">&nbsp;</td>
								<td class="b"><a href="admin.php?page=adrotate-moderate"><?php echo $queued; ?> <?php _e('Queued', 'adrotate'); ?></a></td>
							</tr>
							</tbody>

							<thead>
							<tr class="first">
								<td colspan="2"><strong><?php _e('The last few days', 'adrotate'); ?></strong></td>
							</tr>
							</thead>

							<tbody>
							<tr class="first">
								<td colspan="2">
						        	<?php
						        	$adstats = $wpdb->get_results("SELECT `thetime`, SUM(`clicks`) as `clicks`, SUM(`impressions`) as `impressions` FROM `".$wpdb->prefix."adrotate_stats` GROUP BY `thetime` DESC LIMIT 5;");
						
									if($adstats) {
										$adstats = array_reverse($adstats);
										$dates = $clicks = $impressions = 0;
						
										foreach($adstats as $result) {
											if($result->clicks == null) $result->clicks = '0';
											if($result->impressions == null) $result->impressions = '0';
											
											$dates .= ',"'.date_i18n("d M", $result->thetime).'"';
											$clicks .= ','.$result->clicks;
											$impressions .= ','.$result->impressions;
										}
						
										$dates = trim($dates, ",");
										$clicks = trim($clicks, ",");
										$impressions = trim($impressions, ",");
										
										echo '<div id="chart-1" style="height:150px; width:100%;"></div>';
										adrotate_draw_graph(1, $dates, $clicks, $impressions);
									} else {
										_e('No data to show!', 'adrotate');
									} 
									?>
								</td>
							</tr>
							</tbody>
						</table>

						<div class="versions">
							<span id="adrotate-version-message"><?php _e('You are using', 'adrotate'); ?> <strong>AdRotate <?php echo ADROTATE_DISPLAY; ?></strong>.</span>
							<br class="clear">
						</div>
					</div>
				</div>
				
				<h3><?php _e('AdRotate News and Developer Blog', 'adrotate'); ?></h3>
				<div class="postbox-adrotate">
					<div class="inside">
						<?php wp_widget_rss_output(array(
							'url' => array('http://feeds.feedburner.com/meandmymacnet', 'http://ajdg.solutions/feed/'), 
							'title' => 'AdRotate Development News', 
							'items' => 4, 
							'show_summary' => 1, 
							'show_author' => 0, 
							'show_date' => 1)
							); ?>
					</div>
				</div>

			</div>
		</div>

		<div id="postbox-container-3" class="postbox-container" style="width:50%;">
			<div id="side-sortables" class="meta-box-sortables ui-sortable">
						
				<h3><?php _e('Buy AdRotate Professional', 'adrotate'); ?></h3>
				<div class="postbox-adrotate">
					<div class="inside">
						<a href="https://ajdg.solutions/products/adrotate-for-wordpress/?utm_source=adrotate_free&utm_medium=adrotate_info_page&utm_campaign=compare_license"><img src="<?php echo WP_CONTENT_URL; ?>/plugins/adrotate/images/adrotate-product.png" alt="adrotate-product" width="150" height="150" align="right" style="padding: 0 0 10px 10px;" /></a>
						<p><h4><?php _e('Singe License', 'adrotate'); ?> (&euro; 29.00)</h4><?php _e('For one WordPress installation.', 'adrotate'); ?> <a href="https://ajdg.solutions/cart/?add-to-cart=1124&utm_source=adrotate_free&utm_medium=adrotate_info_page&utm_campaign=buy_single" target="_blank"><?php _e('Buy now', 'adrotate'); ?> &raquo;</a></p>
						<p><h4><?php _e('Duo License', 'adrotate'); ?> (&euro; 39.00)</h4><?php _e('For two WordPress installations.', 'adrotate'); ?> <a href="https://ajdg.solutions/cart/?add-to-cart=1126&utm_source=adrotate_free&utm_medium=adrotate_info_page&utm_campaign=buy_duo" target="_blank"><?php _e('Buy now', 'adrotate'); ?> &raquo;</a></p>
						<p><h4><?php _e('Multi License', 'adrotate'); ?> (&euro; 99.00)</h4><?php _e(' For up to five WordPress installations.', 'adrotate'); ?> <a href="https://ajdg.solutions/cart/?add-to-cart=1128&utm_source=adrotate_free&utm_medium=adrotate_info_page&utm_campaign=buy_multi" target="_blank"><?php _e('Buy now', 'adrotate'); ?> &raquo;</a></p>
						<p><h4><?php _e('Developer License', 'adrotate'); ?> (&euro; 299.00)</h4><?php _e('Activate AdRotate on unlimited WordPress installations and/or networks.', 'adrotate'); ?> <a href="https://ajdg.solutions/cart/?add-to-cart=1130&utm_source=adrotate_free&utm_medium=adrotate_info_page&utm_campaign=buy_developer" target="_blank"><?php _e('Buy now', 'adrotate'); ?> &raquo;</a></p>
						<p><h4><?php _e('Network License', 'adrotate'); ?> (&euro; 199.00)</h4><?php _e('Set up your own advertising network on a WordPress Multisite.', 'adrotate'); ?> <a href="https://ajdg.solutions/cart/?add-to-cart=1132&utm_source=adrotate_free&utm_medium=adrotate_info_page&utm_campaign=buy_network" target="_blank"><?php _e('Buy now', 'adrotate'); ?> &raquo;</a></p>
						<p><h4><?php _e('Compare licenses', 'adrotate'); ?></h4> <?php _e("Not sure which license is for you? Compare them...", 'adrotate'); ?> <a href="https://ajdg.solutions/products/adrotate-for-wordpress/?utm_source=adrotate_free&utm_medium=adrotate_info_page&utm_campaign=compare_license" target="_blank"><?php _e('All Licenses', 'adrotate'); ?> &raquo;</a></p>
					</div>
				</div>

				<h3><?php _e('Get more features with AdRotate Pro', 'adrotate'); ?></h3>
				<div class="postbox-adrotate">
					<div class="inside">
						<p><a href="https://ajdg.solutions/products/adrotate-for-wordpress/?utm_source=adrotate_free&utm_medium=adrotate_info_page&utm_campaign=upgrade_adrotatefree" title="AdRotate plugin for WordPress"><img src="<?php echo WP_CONTENT_URL; ?>/plugins/adrotate/images/adrotate-logo-60x60.png" alt="adrotate-logo-60x60" width="60" height="60" align="left" style="padding: 0 10px 10px 0;" /></a><?php _e('Benefit from extra features to reinforce your income with advertising campaigns. Make the most of your website with the powerful tools AdRotate Pro offers on top of the trusted features included in the free version.', 'adrotate'); ?></p>
						<p><?php _e('Learn more about', 'adrotate'); ?> <a href="admin.php?page=adrotate-pro">AdRotate Pro</a> <?php _e('or go to the', 'adrotate'); ?> <a href="https://ajdg.solutions/products/adrotate-for-wordpress/?utm_source=adrotate_free&utm_medium=adrotate_info_page&utm_campaign=upgrade_adrotatefree" target="_blank">AdRotate <?php _e('website', 'adrotate'); ?> &raquo;</a></p>
					</div>
				</div>

				<h3><?php _e('Support AdRotate', 'adrotate'); ?></h3>
				<div class="postbox-adrotate">
					<div class="inside">
						<p><center><?php _e('Your gift will ensure the continued development of AdRotate!', 'adrotate'); ?></center></p>
						<p><center><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=paypal%40ajdg%2enet&item_name=AdRotate%20One-time%20Donation&item_number=000&no_shipping=0&no_note=0&tax=0&currency_code=EUR&bn=PP%2dDonationsBF&charset=UTF%2d8" target="_blank"><img src="http://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" /></a></center></p>
					</div>
				</div>

				<h3><?php _e('AdRotate is brought to you by', 'adrotate'); ?></h3>
				<div class="postbox-adrotate">
					<div class="inside">
						<p><a href="https://ajdg.solutions/" title="AJdG Solutions"><img src="<?php echo WP_CONTENT_URL; ?>/plugins/adrotate/images/ajdg-logo-100x60.png" alt="ajdg-logo-100x60" width="100" height="60" align="left" style="padding: 0 10px 10px 0;" /></a>
						<a href="https://ajdg.solutions/" title="AJdG Solutions">AJdG Solutions</a> - <?php _e('Your one stop for Webdevelopment, consultancy and anything WordPress! If you need a custom plugin. Theme customizations or have your site moved/migrated entirely. Visit my website for details!', 'adrotate'); ?> <a href="https://ajdg.solutions/" title="AJdG Solutions"><?php _e('Find out more', 'adrotate'); ?></a>!</p>

						<p><center><a href="https://twitter.com/AJdGSolutions" class="twitter-follow-button" data-show-count="false" data-size="large" data-dnt="true"><?php _e('Follow', 'adrotate'); ?> @AJdGSolutions</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></center></p>
					</div>
				</div>

			</div>	
		</div>

	</div>

	<div class="clear"></div>
	<p><?php echo adrotate_trademark(); ?></p>
</div>