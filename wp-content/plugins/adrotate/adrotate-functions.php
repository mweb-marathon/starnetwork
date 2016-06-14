<?php
/* ------------------------------------------------------------------------------------
*  COPYRIGHT AND TRADEMARK NOTICE
*  Copyright 2008-2015 AJdG Solutions (Arnan de Gans). All Rights Reserved.
*  ADROTATE is a trademark of Arnan de Gans.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from it's use.
------------------------------------------------------------------------------------ */

/*-------------------------------------------------------------
 Name:      adrotate_shortcode

 Purpose:   Prepare function requests for calls on shortcodes
 Receive:   $atts, $content
 Return:    Function()
 Since:		0.7
-------------------------------------------------------------*/
function adrotate_shortcode($atts, $content = null) {
	global $adrotate_config;

	$banner_id = $group_ids = $block_id = $fallback = $weight = $site = 0;
	if(!empty($atts['banner'])) $banner_id = trim($atts['banner'], "\r\t ");
	if(!empty($atts['group'])) $group_ids = trim($atts['group'], "\r\t ");
	if(!empty($atts['block'])) $block_id = trim($atts['block'], "\r\t ");
	if(!empty($atts['fallback'])) $fallback	= trim($atts['fallback'], "\r\t "); // Optional for groups (override)
	if(!empty($atts['weight']))	$weight	= trim($atts['weight'], "\r\t "); // Optional for groups (override)
	if(!empty($atts['site'])) $site = 0; // Not supported in free version

	$output = '';

	if($adrotate_config['w3caching'] == "Y") $output .= '<!-- mfunc '.W3TC_DYNAMIC_SECURITY.' -->';

	if($banner_id > 0 AND ($group_ids == 0 OR $group_ids > 0) AND $block_id == 0) { // Show one Ad
		if($adrotate_config['supercache'] == "Y") $output .= '<!--mfunc echo adrotate_ad('.$banner_id.', true, 0, 0) -->';
		$output .= adrotate_ad($banner_id, true, 0, 0);
		if($adrotate_config['supercache'] == "Y") $output .= '<!--/mfunc-->';
	}

	if($banner_id == 0 AND $group_ids > 0 AND $block_id == 0) { // Show group 
		if($adrotate_config['supercache'] == "Y") $output .= '<!--mfunc echo adrotate_group('.$group_ids.', '.$fallback.', '.$weight.') -->';
		$output .= adrotate_group($group_ids, $fallback, $weight);
		if($adrotate_config['supercache'] == "Y") $output .= '<!--/mfunc-->';
	}

	if($banner_id == 0 AND $group_ids == 0 AND $block_id > 0) { // Show block 
		if($adrotate_config['supercache'] == "Y") $output .= '<!--mfunc echo adrotate_block( $block_id, $weight ) -->';
		$output .= adrotate_block($block_id, $weight);
		if($adrotate_config['supercache'] == "Y") $output .= '<!--/mfunc-->';
	}

	if($adrotate_config['w3caching'] == "Y") $output .= '<!-- /mfunc -->';

	return $output;
}

/*-------------------------------------------------------------
 Name:      adrotate_is_networked

 Purpose:   Determine if AdRotate is network activated
 Receive:   -None-
 Return:    Boolean
 Since:		3.9.8
-------------------------------------------------------------*/
function adrotate_is_networked() {
	if(!function_exists( 'is_plugin_active_for_network')) require_once(ABSPATH.'/wp-admin/includes/plugin.php');
	 
	if(is_plugin_active_for_network(ADROTATE_FOLDER.'/adrotate.php')) {
		return true;
	}		
	return false;
}

/*-------------------------------------------------------------
 Name:      adrotate_is_bot

 Purpose:   Check if visitor is a bot
 Receive:   -None-
 Return:    Boolean
 Since:		3.11.10
-------------------------------------------------------------*/
function adrotate_is_bot() {
	global $adrotate_crawlers;

	if(is_array($adrotate_crawlers)) {
		$crawlers = $adrotate_crawlers;
	} else {
		$crawlers = array();
	}

	if(isset($_SERVER['HTTP_USER_AGENT'])) {
		$useragent = $_SERVER['HTTP_USER_AGENT'];
		$useragent = trim($useragent, ' \t\r\n\0\x0B');
	} else {
		$useragent = '';
	}

	if(strlen($useragent) > 0) {
		$nocrawler = true;
		foreach($crawlers as $crawler) {
			if(preg_match("/$crawler/i", $useragent)) $nocrawler = false;
		}
	} else {
		$nocrawler = false;
	}
	
	// Returns true if no bot.
	return $nocrawler;
}

/*-------------------------------------------------------------
 Name:      adrotate_count_impression

 Purpose:   Count Impressions where needed
 Receive:   $ad, $group
 Return:    -None-
 Since:		3.10.12
-------------------------------------------------------------*/
function adrotate_count_impression($ad, $group = 0, $blog_id = 0, $impression_timer = 0) { 
	global $wpdb, $adrotate_config, $adrotate_debug;

	if(($adrotate_config['enable_loggedin_impressions'] == 'Y' AND is_user_logged_in()) OR !is_user_logged_in()) {
		$now = adrotate_now();
		$today = adrotate_date_start('day');
		$remote_ip 	= adrotate_get_remote_ip();

		if($adrotate_debug['timers'] == true) {
			$impression_timer = $now;
		} else {
			$impression_timer = $now - $impression_timer;
		}

		$saved_timer = $wpdb->get_var($wpdb->prepare("SELECT `timer` FROM `".$wpdb->prefix."adrotate_tracker` WHERE `ipaddress` = '%s' AND `stat` = 'i' AND `bannerid` = %d ORDER BY `timer` DESC LIMIT 1;", $remote_ip, $ad));
		if($saved_timer < $impression_timer AND adrotate_is_bot()) {
			$stats = $wpdb->get_var($wpdb->prepare("SELECT `id` FROM `".$wpdb->prefix."adrotate_stats` WHERE `ad` = %d AND `group` = %d AND `thetime` = $today;", $ad, $group));
			if($stats > 0) {
				$wpdb->query("UPDATE `".$wpdb->prefix."adrotate_stats` SET `impressions` = `impressions` + 1 WHERE `id` = $stats;");
			} else {
				$wpdb->insert($wpdb->prefix.'adrotate_stats', array('ad' => $ad, 'group' => $group, 'block' => 0, 'thetime' => $today, 'clicks' => 0, 'impressions' => 1));
			}

			$wpdb->insert($wpdb->prefix."adrotate_tracker", array('ipaddress' => $remote_ip, 'timer' => $now, 'bannerid' => $ad, 'stat' => 'i', 'useragent' => '', 'country' => '', 'city' => ''));
		}
	}
} 

/*-------------------------------------------------------------
 Name:      adrotate_impression_callback

 Purpose:   Register a impression for dynamic groups
 Receive:   $_POST
 Return:    -None-
 Since:		3.10.14
-------------------------------------------------------------*/
function adrotate_impression_callback() {
	global $adrotate_debug;

	$meta = $_POST['track'];
	if($adrotate_debug['track'] != true) {
		$meta = base64_decode($meta);
	}

	$meta = esc_attr($meta);
	list($ad, $group, $blog_id, $impression_timer) = explode(",", $meta, 4);
	adrotate_count_impression($ad, $group, $blog_id, $impression_timer);

	die();
}


/*-------------------------------------------------------------
 Name:      adrotate_click_callback

 Purpose:   Register clicks for clicktracking
 Receive:   $_POST
 Return:    -None-
 Since:		3.10.14
-------------------------------------------------------------*/
function adrotate_click_callback() {
	global $wpdb, $adrotate_config, $adrotate_debug;

	$meta = $_POST['track'];

	if($adrotate_debug['track'] != true) {
		$meta = base64_decode($meta);
	}
	
	$meta = esc_attr($meta);
	list($ad, $group, $blog_id, $impression_timer) = explode(",", $meta, 4);

	if(is_numeric($ad) AND is_numeric($group) AND is_numeric($blog_id)) {
		if(($adrotate_config['enable_loggedin_clicks'] == 'Y' AND is_user_logged_in()) OR !is_user_logged_in()) {	
			$remote_ip = adrotate_get_remote_ip();

			if(adrotate_is_bot() AND $remote_ip != "unknown" AND !empty($remote_ip)) {
				$now = adrotate_now();
				$today = adrotate_date_start('day');

				if($adrotate_debug['timers'] == true) {
					$click_timer = $now;
				} else {
					$click_timer = $now - $adrotate_config['click_timer'];
				}
	
				$saved_timer = $wpdb->get_var($wpdb->prepare("SELECT `timer` FROM `".$wpdb->prefix."adrotate_tracker` WHERE `ipaddress` = '%s' AND `stat` = 'c' AND `bannerid` = %d ORDER BY `timer` DESC LIMIT 1;", $remote_ip, $ad));
				if($saved_timer < $click_timer) {
					$stats = $wpdb->get_var($wpdb->prepare("SELECT `id` FROM `".$wpdb->prefix."adrotate_stats` WHERE `ad` = %d AND `group` = %d AND `thetime` = $today;", $ad, $group));
					if($stats > 0) {
						$wpdb->query("UPDATE `".$wpdb->prefix."adrotate_stats` SET `clicks` = `clicks` + 1 WHERE `id` = $stats;");
					} else {
						$wpdb->insert($wpdb->prefix.'adrotate_stats', array('ad' => $ad, 'group' => $group, 'block' => 0, 'thetime' => $today, 'clicks' => 1, 'impressions' => 1));
					}

					$wpdb->insert($wpdb->prefix.'adrotate_tracker', array('ipaddress' => $remote_ip, 'timer' => $now, 'bannerid' => $ad, 'stat' => 'c', 'useragent' => $useragent, 'country' => '', 'city' => ''));
				}
			}
		}

		unset($remote_ip, $track, $meta, $ad, $group, $remote, $banner);
	}

	die();
}
/*-------------------------------------------------------------
 Name:      adrotate_filter_schedule

 Purpose:   Weed out ads that are over the limit of their schedule
 Receive:   $selected, $banner
 Return:    $selected
 Since:		3.6.11
-------------------------------------------------------------*/
function adrotate_filter_schedule($selected, $banner) { 
	global $wpdb, $adrotate_config, $adrotate_debug;

	$now = adrotate_now();

	if($adrotate_debug['general'] == true) {
		echo "<p><strong>[DEBUG][adrotate_filter_schedule()] Filtering banner</strong><pre>";
		print_r($banner->id); 
		echo "</pre></p>"; 
	}
	
	// Get schedules for advert
	$schedules = $wpdb->get_results("SELECT `".$wpdb->prefix."adrotate_schedule`.`id`, `starttime`, `stoptime`, `maxclicks`, `maximpressions` FROM `".$wpdb->prefix."adrotate_schedule`, `".$wpdb->prefix."adrotate_linkmeta` WHERE `schedule` = `".$wpdb->prefix."adrotate_schedule`.`id` AND `ad` = '".$banner->id."' ORDER BY `starttime` ASC LIMIT 1;");

	$schedule = $schedules[0];
	
	$current = array();
	if($schedule->starttime > $now OR $schedule->stoptime < $now) {
		$current[] = 0;
	} else {
		$current[] = 1;
		if($adrotate_config['enable_stats'] == 'Y') {
			$stat = adrotate_stats($banner->id, $schedule->starttime, $schedule->stoptime);

			if($adrotate_debug['general'] == true) {
				echo "<p><strong>[DEBUG][adrotate_filter_schedule] Ad ".$banner->id." - Has schedule (id: ".$schedule->id.")</strong><pre>";
				echo "<br />Start: ".$schedule->starttime." (".date("F j, Y, g:i a", $schedule->starttime).")";
				echo "<br />End: ".$schedule->stoptime." (".date("F j, Y, g:i a", $schedule->stoptime).")";
				echo "<br />Clicks this period: ".$stat['clicks'];
				echo "<br />Impressions this period: ".$stat['impressions'];
				echo "</pre></p>";
			}

			if($stat['clicks'] >= $schedule->maxclicks AND $schedule->maxclicks > 0 AND $banner->tracker == "Y") {
				unset($selected[$banner->id]);
			}

			if($stat['impressions'] >= $schedule->maximpressions AND $schedule->maximpressions > 0) {
				unset($selected[$banner->id]);
			}
		}
	}
	
	// Remove advert from array if all schedules are false (0)
	if(!in_array(1, $current)) {
		unset($selected[$banner->id]);
	}
	unset($current, $schedules);
	
	return $selected;
} 

/*-------------------------------------------------------------
 Name:      adrotate_array_unique

 Purpose:   Filter out duplicate records in multidimensional arrays
 Receive:   $array
 Return:    $array|$return
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_array_unique($array) {
	if(count($array) > 0) {
		if(is_array($array[0])) {
			$return = array();
			// multidimensional
			foreach($array as $row) {
				if(!in_array($row, $return)) {
					$return[] = $row;
				}
			}
			return $return;
		} else {
			// not multidimensional
			return array_unique($array);
		}
	} else {
		return $array;
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_rand

 Purpose:   Generate a random string
 Receive:   $length
 Return:    $result
 Since:		3.8
-------------------------------------------------------------*/
function adrotate_rand($length = 8) {
	$available_chars = "abcdefghijklmnopqrstuvwxyz";	

	$result = '';
	$size = strlen($available_chars);
	for($i = 0; $i < $length; $i++) {
		$result .= $available_chars[rand(0, $size - 1)];
	}

	return $result;
}

/*-------------------------------------------------------------
 Name:      adrotate_shuffle

 Purpose:   Randomize and slice an array but keep keys intact
 Receive:   $array, $amount
 Return:    $shuffle
 Since:		3.8.8.3
-------------------------------------------------------------*/
function adrotate_shuffle($array, $amount = 0) { 
	if(!is_array($array)) return $array; 
	$keys = array_keys($array); 
	shuffle($keys);
	
	$count = count($keys);
	if($amount == 0 OR $amount > $count) $amount = $count;
	$keys = array_slice($keys, 0, $amount, 1);
	
	$shuffle = array(); 
	foreach($keys as $key) {
		$shuffle[$key] = $array[$key];
	}
	return $shuffle; 
}

/*-------------------------------------------------------------
 Name:      adrotate_select_categories

 Purpose:   Create scrolling menu of all categories.
 Receive:   $savedcats, $count, $child_of, $parent
 Return:    $output
 Since:		3.8.4
-------------------------------------------------------------*/
function adrotate_select_categories($savedcats, $count = 2, $child_of = 0, $parent = 0) {
	if(!is_array($savedcats)) $savedcats = explode(',', $savedcats);
	$categories = get_categories(array('child_of' => $parent, 'parent' => $parent,  'orderby' => 'id', 'order' => 'asc', 'hide_empty' => 0));

	if(!empty($categories)) {
		$output = '';
		if($parent == 0) {
			$output = '<table width="100%">';
			if(count($categories) > 5) {
				$output .= '<thead><tr><td scope="col" class="manage-column check-column" style="padding: 0px;"><input type="checkbox" /></td><td style="padding: 0px;">Select All</td></tr></thead>';
			}
			$output .= '<tbody>';
		}
		foreach($categories as $category) {
			if($category->parent > 0) {
				if($category->parent != $child_of) {
					$count = $count + 1;
				}
				$indent = '&nbsp;'.str_repeat('-', $count * 2).'&nbsp;';
			} else {
				$indent = '';
			}
			$output .= '<tr>';
			$output .= '<td class="check-column" style="padding: 0px;"><input type="checkbox" name="adrotate_categories[]" value="'.$category->cat_ID.'"';
			if(in_array($category->cat_ID, $savedcats)) {
				$output .= ' checked';
			}
			$output .= '></td><td style="padding: 0px;">'.$indent.$category->name.' ('.$category->category_count.')</td>';
			$output .= '</tr>';
			$output .= adrotate_select_categories($savedcats, $count, $category->parent, $category->cat_ID);
			$child_of = $parent;
		}
		if($parent == 0) {
			$output .= '</tbody></table>';
		}
		return $output;
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_select_pages

 Purpose:   Create scrolling menu of all pages.
 Receive:   $savedpages, $count, $child_of, $parent
 Return:    $output
 Since:		3.8.4
-------------------------------------------------------------*/
function adrotate_select_pages($savedpages, $count = 2, $child_of = 0, $parent = 0) {
	if(!is_array($savedpages)) $savedpages = explode(',', $savedpages);
	$pages = get_pages(array('child_of' => $parent, 'parent' => $parent, 'sort_column' => 'ID', 'sort_order' => 'asc'));

	if(!empty($pages)) {
		$output = '';
		if($parent == 0) {
			$output = '<table width="100%">';
			if(count($pages) > 5) {
				$output .= '<thead><tr><td scope="col" class="manage-column check-column" style="padding: 0px;"><input type="checkbox" /></td><td style="padding: 0px;">Select All</td></tr></thead>';
			}
			$output .= '<tbody>';
		}
		foreach($pages as $page) {
			if($page->post_parent > 0) {
				if($page->post_parent != $child_of) {
					$count = $count + 1;
				}
				$indent = '&nbsp;'.str_repeat('-', $count * 2).'&nbsp;';
			} else {
				$indent = '';
			}
			$output .= '<tr>';
			$output .= '<td class="check-column" style="padding: 0px;"><input type="checkbox" name="adrotate_pages[]" value="'.$page->ID.'"';
			if(in_array($page->ID, $savedpages)) {
				$output .= ' checked';
			}
			$output .= '></td><td style="padding: 0px;">'.$indent.$page->post_title.'</td>';
			$output .= '</tr>';
			$output .= adrotate_select_pages($savedpages, $count, $page->post_parent, $page->ID);
			$child_of = $parent;
		}
		if($parent == 0) {
			$output .= '</tbody></table>';
		}
		return $output;
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_prepare_evaluate_ads

 Purpose:   Initiate evaluations for errors and determine the ad status
 Receive:   -None-
 Return:    -None-
 Since:		3.6.5
-------------------------------------------------------------*/
function adrotate_prepare_evaluate_ads($return = true) {
	global $wpdb;
	
	// Fetch ads
	$ads = $wpdb->get_results("SELECT `id`, `type` FROM `".$wpdb->prefix."adrotate` WHERE `type` != 'disabled' AND `type` != 'empty' ORDER BY `id` ASC;");

	// Determine error states
	$error = $expired = $expiressoon = $normal = $unknown = 0;
	foreach($ads as $ad) {
		$result = adrotate_evaluate_ad($ad->id);
		if($result == 'error') {
			$error++;
			$wpdb->query("UPDATE `".$wpdb->prefix."adrotate` SET `type` = 'error' WHERE `id` = '".$ad->id."';");
		} 

		if($result == 'expired') {
			$expired++;
			$wpdb->query("UPDATE `".$wpdb->prefix."adrotate` SET `type` = 'expired' WHERE `id` = '".$ad->id."';");
		} 
		
		if($result == '2days') {
			$expiressoon++;
			$wpdb->query("UPDATE `".$wpdb->prefix."adrotate` SET `type` = '2days' WHERE `id` = '".$ad->id."';");
		}
		
		if($result == '7days') {
			$normal++;
			$wpdb->query("UPDATE `".$wpdb->prefix."adrotate` SET `type` = '7days' WHERE `id` = '".$ad->id."';");
		}
		
		if($result == 'active') {
			$normal++;
			$wpdb->query("UPDATE `".$wpdb->prefix."adrotate` SET `type` = 'active' WHERE `id` = '".$ad->id."';");
		}
		
		if($result == 'unknown') {
			$unknown++;
		}
	}

	$count = $expired + $expiressoon + $error;
	$result = array('error' => $error,
					'expired' => $expired,
					'expiressoon' => $expiressoon,
					'normal' => $normal,
					'total' => $count,
					'unknown' => $unknown
					);

	update_option('adrotate_advert_status', $result);
	if($return) adrotate_return('db_evaluated');
}

/*-------------------------------------------------------------
 Name:      adrotate_evaluate_ads

 Purpose:   Initiate automated evaluations for errors and determine the ad status
 Receive:   -None-
 Return:    -None-
 Since:		3.8.5.1
-------------------------------------------------------------*/
function adrotate_evaluate_ads() {
	adrotate_prepare_evaluate_ads(false);
}

/*-------------------------------------------------------------
 Name:      adrotate_evaluate_ad

 Purpose:   Evaluates ads for errors
 Receive:   $ad_id
 Return:    boolean
 Since:		3.6.5
-------------------------------------------------------------*/
function adrotate_evaluate_ad($ad_id) {
	global $wpdb, $adrotate_config;
	
	$now = adrotate_now();
	$in2days = $now + 172800;
	$in7days = $now + 604800;

	// Fetch ad
	$ad = $wpdb->get_row($wpdb->prepare("SELECT `id`, `bannercode`, `tracker`, `link`, `imagetype`, `image`, `cbudget`, `ibudget`, `crate`, `irate` FROM `".$wpdb->prefix."adrotate` WHERE `id` = %d;", $ad_id));
	$advertiser = $wpdb->get_var("SELECT `user` FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = '".$ad->id."' AND `group` = 0 AND `block` = 0 AND `user` > 0 AND `schedule` = 0;");
	$stoptime = $wpdb->get_var("SELECT `stoptime` FROM `".$wpdb->prefix."adrotate_schedule`, `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = '".$ad->id."' AND `schedule` = `".$wpdb->prefix."adrotate_schedule`.`id` ORDER BY `stoptime` DESC LIMIT 1;");

	$bannercode = stripslashes(htmlspecialchars_decode($ad->bannercode, ENT_QUOTES));
	// Determine error states
	if(
		strlen($bannercode) < 1 // AdCode empty
		OR ($ad->tracker == 'N' AND $advertiser > 0) // Didn't enable click-tracking, didn't provide a link, DID set a advertiser
		OR (!preg_match_all('/<a[^>](.*?)>/i', $bannercode, $things) AND $ad->tracker == 'Y') // Clicktracking active but no valid link present
		OR (!preg_match("/%image%/i", $bannercode) AND $ad->image != '' AND $ad->imagetype != '') // Didn't use %image% but selected an image
		OR (preg_match("/%image%/i", $bannercode) AND $ad->image == '' AND $ad->imagetype == '') // Did use %image% but didn't select an image
		OR ($ad->image == '' AND $ad->imagetype != '') // Image and Imagetype mismatch
	) {
		return 'error';
	} else if(
		$stoptime <= $now // Past the enddate
	){
		return 'expired';
	} else if(
		$stoptime <= $in2days AND $stoptime >= $now // Expires in 2 days
	){
		return '2days';
	} else if(
		$stoptime <= $in7days AND $stoptime >= $now	// Expires in 7 days
	){
		return '7days';
	} else {
		return 'active';
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_prepare_color

 Purpose:   Check if ads are expired and set a color for its end date
 Receive:   $banner_id
 Return:    $result
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_prepare_color($enddate) {
	$now = adrotate_now();
	$in2days = $now + 172800;
	$in7days = $now + 604800;
	
	if($enddate <= $now) {
		return '#CC2900'; // red
	} else if($enddate <= $in2days AND $enddate >= $now) {
		return '#F90'; // orange
	} else if($enddate <= $in7days AND $enddate >= $now) {
		return '#E6B800'; // yellow
	} else {
		return '#009900'; // green
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_ad_is_in_groups

 Purpose:   Build list of groups the ad is in (overview)
 Receive:   $id
 Return:    $output
 Since:		3.8
-------------------------------------------------------------*/
function adrotate_ad_is_in_groups($id) {
	global $wpdb;

	$output = '';
	$groups	= $wpdb->get_results("
		SELECT 
			`".$wpdb->prefix."adrotate_groups`.`name` 
		FROM 
			`".$wpdb->prefix."adrotate_groups`, 
			`".$wpdb->prefix."adrotate_linkmeta` 
		WHERE 
			`".$wpdb->prefix."adrotate_linkmeta`.`ad` = '".$id."'
			AND `".$wpdb->prefix."adrotate_linkmeta`.`group` = `".$wpdb->prefix."adrotate_groups`.`id`
			AND `".$wpdb->prefix."adrotate_linkmeta`.`block` = 0
			AND `".$wpdb->prefix."adrotate_linkmeta`.`user` = 0
		;");
	if($groups) {
		foreach($groups as $group) {
			$output .= $group->name.", ";
		}
	}
	$output = rtrim($output, ", ");
	
	return $output;
}

/*-------------------------------------------------------------
 Name:      adrotate_hash

 Purpose:   Generate the adverts clicktracking hash
 Receive:   $ad, $group, $remote, $blog_id
 Return:    $result
 Since:		3.9.12
-------------------------------------------------------------*/
function adrotate_hash($ad, $group = 0, $blog_id = 0) {
	global $adrotate_debug, $adrotate_config;
	
	if($adrotate_debug['timers'] == true) {
		$timer = 0;
	} else {
		$timer = $adrotate_config['impression_timer'];
	}
		
	if($adrotate_debug['track'] == true) {
		return "$ad,$group,$blog_id,$timer";
	} else {
		return base64_encode("$ad,$group,$blog_id,$timer");
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_get_remote_ip

 Purpose:   Get the remote IP from the visitor
 Receive:   -None-
 Return:    $buffer[0]
 Since:		3.6.2
-------------------------------------------------------------*/
function adrotate_get_remote_ip(){
	if(empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
		$remote_ip = $_SERVER["REMOTE_ADDR"];
	} else {
		$remote_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	}
	$buffer = explode(',', $remote_ip, 2);

	return $buffer[0];
}

/*-------------------------------------------------------------
 Name:      adrotate_get_sorted_roles

 Purpose:   Returns all roles and capabilities, sorted by user level. Lowest to highest.
 Receive:   -none-
 Return:    $sorted
 Since:		3.2
-------------------------------------------------------------*/
function adrotate_get_sorted_roles() {	
	global $wp_roles;

	$editable_roles = apply_filters('editable_roles', $wp_roles->roles);
	$sorted = array();
	
	foreach($editable_roles as $role => $details) {
		$sorted[$details['name']] = get_role($role);
	}

	$sorted = array_reverse($sorted);

	return $sorted;
}

/*-------------------------------------------------------------
 Name:      adrotate_set_capability

 Purpose:   Grant or revoke capabilities to a role and all higher roles
 Receive:   $lowest_role, $capability
 Return:    -None-
 Since:		3.2
-------------------------------------------------------------*/
function adrotate_set_capability($lowest_role, $capability){
	$check_order = adrotate_get_sorted_roles();
	$add_capability = false;
	
	foreach($check_order as $role) {
		if($lowest_role == $role->name) $add_capability = true;
		if(empty($role)) continue;
		$add_capability ? $role->add_cap($capability) : $role->remove_cap($capability) ;
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_remove_capability

 Purpose:   Remove the $capability from the all roles
 Receive:   $capability
 Return:    -None-
 Since:		3.2
-------------------------------------------------------------*/
function adrotate_remove_capability($capability){
	$check_order = adrotate_get_sorted_roles();

	foreach($check_order as $role) {
		$role = get_role($role->name);
		$role->remove_cap($capability);
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_dashboard_scripts

 Purpose:   Load file uploaded popup
 Receive:   -None-
 Return:	-None-
 Since:		3.6
-------------------------------------------------------------*/
function adrotate_dashboard_scripts() {
	wp_enqueue_script('jquery');
	wp_enqueue_script('raphael', plugins_url('/library/raphael-min.js', __FILE__), array('jquery'));
	wp_enqueue_script('elycharts', plugins_url('/library/elycharts.min.js', __FILE__), array('jquery', 'raphael'));
	wp_enqueue_script('textatcursor', plugins_url('/library/textatcursor.js', __FILE__));

	// WP Pointers
	$seen_it = explode(',', get_user_meta(get_current_user_id(), 'dismissed_wp_pointers', true));
	$do_add_script = false;
	if(!in_array('adrotate_free_'.ADROTATE_VERSION.ADROTATE_DB_VERSION, $seen_it)) {
		$do_add_script = true;
		add_action('admin_print_footer_scripts', 'adrotate_welcome_pointer');
	}

	if($do_add_script) {
		wp_enqueue_script('wp-pointer');
		wp_enqueue_style('wp-pointer');
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_dashboard_styles

 Purpose:   Load file uploaded popup
 Receive:   -None-
 Return:	-None-
 Since:		3.6
-------------------------------------------------------------*/
function adrotate_dashboard_styles() {
	wp_enqueue_style( 'adrotate-admin-stylesheet', plugins_url( 'library/dashboard.css', __FILE__ ) );
}

/*-------------------------------------------------------------
 Name:      adrotate_folder_contents

 Purpose:   List folder contents of /wp-content/banners and /wp-content/uploads
 Receive:   $current
 Return:	$output
 Since:		0.4
-------------------------------------------------------------*/
function adrotate_folder_contents($current) {
	global $wpdb, $adrotate_config;

	$output = '';
	$siteurl = get_option('siteurl');

	// Read Banner folder
	$files = array();
	$i = 0;
	if($handle = opendir(ABSPATH.$adrotate_config['banner_folder'])) {
	    while (false !== ($file = readdir($handle))) {
	        if ($file != "." AND $file != ".." AND $file != "index.php") {
	            $files[] = $file;
	        	$i++;
	        }
	    }
	    closedir($handle);

	    if($i > 0) {
			sort($files);
			foreach($files as $file) {
				$fileinfo = pathinfo($file);
		
				if((strtolower($fileinfo['extension']) == "jpg" OR strtolower($fileinfo['extension']) == "gif" OR strtolower($fileinfo['extension']) == "png" 
				OR strtolower($fileinfo['extension']) == "jpeg" OR strtolower($fileinfo['extension']) == "swf" OR strtolower($fileinfo['extension']) == "flv")) {
				    $output .= "<option value='".$file."'";
				    if(($current == $siteurl.'/wp-content/banners/'.$file) OR ($current == $siteurl."/%folder%".$file)) { $output .= "selected"; }
				    $output .= ">".$file."</option>";
				}
			}
		} else {
	    	$output .= "<option disabled>&nbsp;&nbsp;&nbsp;".__('No files found', 'adrotate')."</option>";
		}
	} else {
    	$output .= "<option disabled>&nbsp;&nbsp;&nbsp;".__('Folder not found or not accessible', 'adrotate')."</option>";
	}
	
	return $output;
}

/*-------------------------------------------------------------
 Name:      adrotate_return

 Purpose:   Internal redirects
 Receive:   $action, $arg (array)
 Return:    -none-
 Since:		0.2
 Usage:		array('wp_page', 'message', 'arguments', 'id')
-------------------------------------------------------------*/
function adrotate_return($action, $arg = null) {

	switch($action) {
		// Manage Ads
		case "new" :
			wp_redirect('admin.php?page=adrotate-ads&message=new');
		break;

		case "update" :
			wp_redirect('admin.php?page=adrotate-ads&view=edit&message=updated&ad='.$arg[0]);
		break;

		case "update_manage" :
			wp_redirect('admin.php?page=adrotate-ads&message=updated');
		break;

		case "delete" :
			wp_redirect('admin.php?page=adrotate-ads&message=deleted');
		break;

		case "reset" :
			wp_redirect('admin.php?page=adrotate-ads&message=reset');
		break;

		case "renew" :
			wp_redirect('admin.php?page=adrotate-ads&message=renew');
		break;

		case "deactivate" :
			wp_redirect('admin.php?page=adrotate-ads&message=deactivate');
		break;

		case "activate" :
			wp_redirect('admin.php?page=adrotate-ads&message=activate');
		break;

		case "exported" :
			wp_redirect('admin.php?page=adrotate-ads&message=exported&file='.$arg[0]);
		break;

		case "field_error" :
			wp_redirect('admin.php?page=adrotate-ads&message=field_error');
		break;

		// Groups
		case "group_new" :
			wp_redirect('admin.php?page=adrotate-groups&message=created');
		break;

		case "group_edit" :
			wp_redirect('admin.php?page=adrotate-groups&view=edit&message=updated&group='.$arg[0]);
		break;

		case "group_delete" :
			wp_redirect('admin.php?page=adrotate-groups&message=deleted');
		break;

		case "group_delete_banners" :
			wp_redirect('admin.php?page=adrotate-groups&message=deleted_banners');
		break;

		// Settings
		case "settings_saved" :
			wp_redirect('admin.php?page=adrotate-settings&message=updated');
		break;

		// Maintenance
		case "db_optimized" :
			wp_redirect('admin.php?page=adrotate-settings&message=db_optimized');
		break;

		case "db_evaluated" :
			wp_redirect('admin.php?page=adrotate-settings&message=db_evaluated');
		break;

		case "db_repaired" :
			wp_redirect('admin.php?page=adrotate-settings&message=db_optimized');
		break;

		case "db_cleaned" :
			wp_redirect('admin.php?page=adrotate-settings&message=db_cleaned');
		break;

		case "db_timer" :
			wp_redirect('admin.php?page=adrotate-settings&message=db_timer');
		break;

		// Misc plugin events
		case "mail_sent" :
			wp_redirect('admin.php?page=adrotate-advertiser&message=mail_sent');
		break;

		case "beta_mail_sent" :
			wp_redirect('admin.php?page=adrotate-beta&message=sent');
		break;

		case "beta_mail_empty" :
			wp_redirect('admin.php?page=adrotate-beta&message=empty');
		break;

		case "no_access" :
			wp_redirect('admin.php?page=adrotate&message=no_access');
		break;

		case "error" :
			wp_redirect('admin.php?page=adrotate&message=error');
		break;

		default:
			wp_redirect('admin.php?page=adrotate');
		break;

	}
}
?>