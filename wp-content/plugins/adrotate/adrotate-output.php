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
 Name:      adrotate_ad

 Purpose:   Show requested ad
 Receive:   $banner_id, $individual, $group, $block (obsolete), $site
 Return:    $output
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_ad($banner_id, $individual = true, $group = 0, $block = 0, $site = 0) {
	global $wpdb, $adrotate_config, $adrotate_debug;

	$output = '';

	if($banner_id) {			
		$banner = $wpdb->get_row($wpdb->prepare(
			"SELECT 
				`id`, `bannercode`, `tracker`, `link`, `image`, `responsive`,
				`crate`, `irate`, `cbudget`, `ibudget` 
			FROM 
				`".$wpdb->prefix."adrotate` 
			WHERE 
				`id` = %d 
				AND (`type` = 'active' 
					OR `type` = '2days'
					OR `type` = '7days')
			;", $banner_id));

		if($banner) {
			if($adrotate_debug['general'] == true) {
				echo "<p><strong>[DEBUG][adrotate_ad()] Selected Ad ID</strong><pre>";
				print_r($banner->id); 
				echo "</pre></p>"; 
			}
			
			$selected = array($banner->id => 0);			
			$selected = adrotate_filter_schedule($selected, $banner);

			if($adrotate_config['enable_advertisers'] > 0 AND ($banner->crate > 0 OR $banner->irate > 0)) {
				$selected = adrotate_filter_budget($selected, $banner);
			}
		} else {
			$selected = false;
		}
		
		if($selected) {
			$image = str_replace('%folder%', $adrotate_config['banner_folder'], $banner->image);

			if($individual == true) $output .= '<div class="a-single a-'.$banner->id.'">';
			$output .= adrotate_ad_output($banner->id, 0, $banner->bannercode, $banner->tracker, $banner->link, $image, $banner->responsive);
			if($individual == true) $output .= '</div>';

			if($adrotate_config['enable_stats'] == 'Y'){
				adrotate_count_impression($banner->id, 0, 0);
			}
		} else {
			$output .= adrotate_error('ad_expired', array($banner_id));
		}
		unset($banner);
	} else {
		$output .= adrotate_error('ad_no_id');
	}

	return $output;
}

/*-------------------------------------------------------------
 Name:      adrotate_group

 Purpose:   Fetch ads in specified group(s) and show a random ad
 Receive:   $group_ids, $fallback, $weight, $site
 Return:    $output
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_group($group_ids, $fallback = 0, $weight = 0, $site = 0) {
	global $wpdb, $adrotate_config, $adrotate_debug;

	$output = $group_select = $weightoverride = '';
	if($group_ids) {
		$now = adrotate_now();

		(!is_array($group_ids)) ? $group_array = explode(",", $group_ids) : $group_array = $group_ids;

		foreach($group_array as $key => $value) {
			$group_select .= ' `'.$wpdb->prefix.'adrotate_linkmeta`.`group` = '.$value.' OR';
		}
		$group_select = rtrim($group_select, " OR");

		$group = $wpdb->get_row($wpdb->prepare("SELECT * FROM `".$wpdb->prefix."adrotate_groups` WHERE `name` != '' AND `id` = %d;", $group_array[0]));

		if($adrotate_debug['general'] == true) {
			echo "<p><strong>[DEBUG][adrotate_group] Selected group</strong><pre>"; 
			print_r($group);
			echo "</pre></p>";
		}

		if($group) {
			if($fallback == 0) $fallback = $group->fallback;
			if($weight > 0) $weightoverride = "	AND `".$wpdb->prefix."adrotate`.`weight` >= '$weight'";
	
			$ads = $wpdb->get_results(
				"SELECT 
					`".$wpdb->prefix."adrotate`.`id`, 
					`".$wpdb->prefix."adrotate`.`bannercode`, 
					`".$wpdb->prefix."adrotate`.`link`, 
					`".$wpdb->prefix."adrotate`.`image`, 
					`".$wpdb->prefix."adrotate`.`responsive`, 
					`".$wpdb->prefix."adrotate`.`tracker`, 
					`".$wpdb->prefix."adrotate_linkmeta`.`group`
				FROM 
					`".$wpdb->prefix."adrotate`, 
					`".$wpdb->prefix."adrotate_linkmeta` 
				WHERE 
					(".$group_select.") 
					AND `".$wpdb->prefix."adrotate_linkmeta`.`block` = 0 
					AND `".$wpdb->prefix."adrotate_linkmeta`.`user` = 0 
					AND `".$wpdb->prefix."adrotate`.`id` = `".$wpdb->prefix."adrotate_linkmeta`.`ad` 
					AND (`".$wpdb->prefix."adrotate`.`type` = 'active' 
						OR `".$wpdb->prefix."adrotate`.`type` = '2days'
						OR `".$wpdb->prefix."adrotate`.`type` = '7days')
					".$weightoverride."
				GROUP BY `".$wpdb->prefix."adrotate`.`id` 
				ORDER BY `".$wpdb->prefix."adrotate`.`id`;");
		
			if($ads) {
				if($adrotate_debug['general'] == true) {
					echo "<p><strong>[DEBUG][adrotate_group()] All ads in group</strong><pre>"; 
					print_r($ads); 
					echo "</pre></p>"; 
				}			

				foreach($ads as $ad) {
					$selected[$ad->id] = $ad;
					$selected = adrotate_filter_schedule($selected, $ad);
				}
				unset($ads);
				
				if($adrotate_debug['general'] == true) {
					echo "<p><strong>[DEBUG][adrotate_group] Reduced array based on schedule restrictions</strong><pre>"; 
					print_r($selected); 
					echo "</pre></p>"; 
				}			

				$array_count = count($selected);
				if($array_count > 0) {
					$before = $after = '';
					$before = str_replace('%id%', $group_array[0], stripslashes(html_entity_decode($group->wrapper_before, ENT_QUOTES)));
					$after = str_replace('%id%', $group_array[0], stripslashes(html_entity_decode($group->wrapper_after, ENT_QUOTES)));

					$output .= '<div class="g g-'.$group->id.'">';

					if($group->modus == 1) { // Dynamic ads
						$i = 1;

						// Determine the amount of ads to show for the group
						$amount = ($group->adspeed >= 10000) ? 5 : 10;
						$selected = adrotate_shuffle($selected, $amount);

						foreach($selected as $key => $banner) {
							$image = str_replace('%folder%', $adrotate_config['banner_folder'], $banner->image);

							$output .= '<div class="g-dyn a-'.$banner->id.' c-'.$i.'">';
							$output .= $before.adrotate_ad_output($banner->id, $group->id, $banner->bannercode, $banner->tracker, $banner->link, $image, $banner->responsive).$after;
							$output .= '</div>';
							$i++;
						}
					} else if($group->modus == 2) { // Block of ads
						$block_count = $group->gridcolumns * $group->gridrows;
						if($array_count < $block_count) $block_count = $array_count;
						$columns = 1;

						for($i=1;$i<=$block_count;$i++) {
							$banner_id = array_rand($selected, 1);

							$image = str_replace('%folder%', $adrotate_config['banner_folder'], $selected[$banner_id]->image);

							$output .= '<div class="g-col b-'.$group->id.' a-'.$selected[$banner_id]->id.'">';
							$output .= $before.adrotate_ad_output($selected[$banner_id]->id, $group->id, $selected[$banner_id]->bannercode, $selected[$banner_id]->tracker, $selected[$banner_id]->link, $image, $selected[$banner_id]->responsive).$after;
							$output .= '</div>';

							if($columns == $group->gridcolumns AND $i != $block_count) {
								$output .= '</div><div class="g g-'.$group->id.'">';
								$columns = 1;
							} else {
								$columns++;
							}

							if($adrotate_config['enable_stats'] == 'Y'){
								adrotate_count_impression($selected[$banner_id]->id, $group->id, 0);
							}

							unset($selected[$banner_id]);
						}
					} else { // Default (single ad)
						$banner_id = array_rand($selected, 1);

						$image = str_replace('%folder%', $adrotate_config['banner_folder'], $selected[$banner_id]->image);

						$output .= '<div class="g-single a-'.$selected[$banner_id]->id.'">';
						$output .= $before.adrotate_ad_output($selected[$banner_id]->id, $group->id, $selected[$banner_id]->bannercode, $selected[$banner_id]->tracker, $selected[$banner_id]->link, $image, $selected[$banner_id]->responsive).$after;
						$output .= '</div>';

						if($adrotate_config['enable_stats'] == 'Y'){
							adrotate_count_impression($selected[$banner_id]->id, $group->id, 0);
						}
					}

					$output .= '</div>';

					unset($selected);
				} else {
					$output .= adrotate_error('ad_expired');
				}
			} else { 
				$output .= adrotate_error('ad_unqualified');
			}
		} else {
			$output .= adrotate_error('group_not_found', array($group_array[0]));
		}
	} else {
		$output .= adrotate_error('group_no_id');
	}

	return $output;
}

/*-------------------------------------------------------------
 Name:      adrotate_inject_pages

 Purpose:   Add an advert to a single page
 Receive:   $post_content
 Return:    $post_content
 Added:		3.7
-------------------------------------------------------------*/
function adrotate_inject_pages($post_content) { 
	global $wpdb, $post, $adrotate_debug;
	
	if(is_page()) {
		// Inject ads into page
		$ids = $wpdb->get_results("SELECT `id`, `page`, `page_loc`, `page_par` FROM `".$wpdb->prefix."adrotate_groups` WHERE `page_loc` > 0;");
		
		$group_array = array();
		foreach($ids as $id) {
			$pages = explode(",", $id->page);
			// Build array of groups for pages
			if(in_array($post->ID, $pages)) {
				$group_array[] = array('group' => $id->id, 'location' => $id->page_loc, 'paragraph' => $id->page_par, 'pages' => $pages);
			}
		}
	
		if($adrotate_debug['general'] == true) {
			echo "<p><strong>[DEBUG][adrotate_inject_posts()] group_array</strong><pre>"; 
			print_r($group_array); 
			echo "</pre></p>"; 
		}			
		unset($ids, $pages);
	
		$group_count = count($group_array);
		if($group_count > 0) {
			if($group_count > 1) {
				// Try to prevent the same ad from showing when there are multiple ads to show
				$paragraph_count = substr_count($post_content, '<p>');
				if($paragraph_count == 0 OR $group_count < $paragraph_count) {
					$paragraph_count = $group_count;
				}
				$group_choice = array_rand($group_array, $paragraph_count);
				if(!is_array($group_choice)) $group_choice = array($page_choice);

				shuffle($group_choice);
			} else {
				$group_choice = array(0,0);
			}

			if($adrotate_debug['general'] == true) {
				echo "<p><strong>[DEBUG][adrotate_inject_posts()] Choices</strong><pre>"; 
				echo "Group count: ".$group_count."</br>";
				print_r($group_choice); 
				echo "</pre></p>"; 
			}

			$before = $after = 0;
			foreach($group_choice as $key => $group_id) {
				if(is_page($group_array[$group_id]['pages'])) {
					// Advert in front of content
					if(($group_array[$group_id]['location'] == 1 OR $group_array[$group_id]['location'] == 3) AND $before == 0) {
				   		$post_content = adrotate_group($group_array[$group_id]['group']).$post_content;
				   		$before = 1;
					}
		
					// Advert behind the content
					if(($group_array[$group_id]['location'] == 2 OR $group_array[$group_id]['location'] == 3) AND $after == 0) {
				   		$post_content = $post_content.adrotate_group($group_array[$group_id]['group']);
				   		$after = 1;
					}
	
					// Adverts inside the content
					if($group_array[$group_id]['location'] == 4) {
						$paragraphs = explode("</p>", $post_content);
						$par = 1;
						$post_content = '';
						foreach($paragraphs as $paragraph) {
							if($par == $group_array[$group_id]['paragraph']
							  OR ($par == 2 AND $group_array[$group_id]['paragraph'] == 20) 
							  OR ($par == 3 AND $group_array[$group_id]['paragraph'] == 30) 
							  OR ($par == 4 AND $group_array[$group_id]['paragraph'] == 40)) {
								$paragraph = $paragraph.adrotate_group($group_array[$group_id]['group']);
	
								if($group_array[$group_id]['paragraph'] > 1 AND $group_array[$group_id]['paragraph'] < 10) {
									$par = 1;
								} else {
									$par++;
								}
							} else {
								$par++;
							}
							$post_content .= $paragraph;
						}
					}
				}
			}
		}
		unset($group_choice, $group_count, $group_array, $paragraph, $paragraph_count, $before, $after);
	}
	return $post_content;
}

/*-------------------------------------------------------------
 Name:      adrotate_inject_posts

 Purpose:   Add an advert to a single post
 Receive:   $post_content
 Return:    $post_content
 Added:		3.7
-------------------------------------------------------------*/
function adrotate_inject_posts($post_content) { 
	global $wpdb, $post, $adrotate_debug;

	if(is_single()) {
		// Inject ads into posts in specified category
		$ids = $wpdb->get_results("SELECT `id`, `cat`, `cat_loc`, `cat_par` FROM `".$wpdb->prefix."adrotate_groups` WHERE `cat_loc` > 0;");
		$categories = get_terms('category', array('fields' => 'ids'));

		$category_array = array();
		foreach($ids as $id) {
			$cats = explode(",", $id->cat);
			// Build array of groups for categories
			foreach($categories as $category) {
				if(in_array($category, $cats)) {
					$category_array[] = array('group' => $id->id, 'location' => $id->cat_loc, 'paragraph' => $id->cat_par, 'categories' => $cats);
				}
			}
		}
	
		if($adrotate_debug['general'] == true) {
			echo "<p><strong>[DEBUG][adrotate_inject_posts()] category_array</strong><pre>"; 
			print_r($category_array); 
			echo "</pre></p>"; 
		}			
		unset($ids, $cats);
	
		$category_count = count($category_array);
		if($category_count > 0) {
			if($category_count > 1) {
				// Try to prevent the same ad from showing when there are multiple ads to show
				$paragraph_count = substr_count($post_content, '<p>');
				if($paragraph_count == 0 OR $category_count < $paragraph_count) {
					$paragraph_count = $category_count;
				}
				$category_choice = array_rand($category_array, $paragraph_count);
				if(!is_array($category_choice)) $category_choice = array($category_choice);

				shuffle($category_choice);
			} else {
				$category_choice = array(0,0);
			}
	
			if($adrotate_debug['general'] == true) {
				echo "<p><strong>[DEBUG][adrotate_inject_posts()] category_choice</strong><pre>"; 
				print_r($category_choice); 
				echo "</pre></p>"; 
			}
	
			$before = $after = 0;
			foreach($category_choice as $key => $group_id) {
				if(in_category($category_array[$group_id]['categories'])) {
					// Advert in front of content
					if(($category_array[$group_id]['location'] == 1 OR $category_array[$group_id]['location'] == 3) AND $before == 0) {
						$post_content = adrotate_group($category_array[$group_id]['group']).$post_content;
						$before = 1;
					}
					
					// Advert behind content
					if(($category_array[$group_id]['location'] == 2 OR $category_array[$group_id]['location'] == 3) AND $after == 0) {
				   		$post_content = $post_content.adrotate_group($category_array[$group_id]['group']);
				   		$after = 1;
					}
	
					// Adverts inside the content
					if($category_array[$group_id]['location'] == 4) {
						$paragraphs = explode("</p>", $post_content);
						$par = 1;
						$post_content = '';
						foreach($paragraphs as $paragraph) {
							if(($par == $category_array[$group_id]['paragraph']
							  OR ($par == 2 AND $category_array[$group_id]['paragraph'] == 20) 
							  OR ($par == 3 AND $category_array[$group_id]['paragraph'] == 30) 
							  OR ($par == 4 AND $category_array[$group_id]['paragraph'] == 40))) {
								$paragraph = $paragraph.adrotate_group($category_array[$group_id]['group']);
	
								if($category_array[$group_id]['paragraph'] > 1 AND $category_array[$group_id]['paragraph'] < 10) {
									$par = 1;
								} else {
									$par++;
								}
							} else {
								$par++;
							}
							$post_content .= $paragraph;
						}
					}
				}
			}
		}
		unset($category_choice, $category_count, $category_array, $paragraph, $paragraph_count, $before, $after);
	}

	return $post_content;
}

/*-------------------------------------------------------------
 Name:      adrotate_block (OBSOLETE)

 Purpose:   This function is no longer supported. Replace blocks with groups!
 Receive:   $block_id, $weight
 Return:    $output
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_block($block_id, $weight = 0) {
	global $wpdb;

	$output = '';
	if($block_id) {
		$groups = $wpdb->get_results($wpdb->prepare("SELECT `group` FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = 0 AND `block` = %d AND `user` = 0;", $block_id));
		if($groups) {
			foreach($groups as $group) {
				$group_ids[] = $group->group;
			}
			$output .= adrotate_group($group_ids);
		}			
	}

	return $output;
}

/*-------------------------------------------------------------
 Name:      adrotate_preview

 Purpose:   Show preview of selected ad (Dashboard)
 Receive:   $banner_id
 Return:    $output
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_preview($banner_id) {
	global $wpdb, $adrotate_debug;

	if($banner_id) {
		$now = adrotate_now();
		
		$banner = $wpdb->get_row($wpdb->prepare("SELECT * FROM `".$wpdb->prefix."adrotate` WHERE `id` = %d;", $banner_id));

		if($adrotate_debug['general'] == true) {
			echo "<p><strong>[DEBUG][adrotate_preview()] Ad information</strong><pre>"; 
			print_r($banner); 
			echo "</pre></p>"; 
		}			

		if($banner) {
			$image = str_replace('%folder%', '/wp-content/banners/', $banner->image);		
			$output = adrotate_ad_output($banner->id, 0, $banner->bannercode, $banner->tracker, $banner->link, $image, 'N');
		} else {
			$output = adrotate_error('ad_expired');
		}
	} else {
		$output = adrotate_error('ad_no_id');
	}

	return $output;
}

/*-------------------------------------------------------------
 Name:      adrotate_ad_output

 Purpose:   Prepare the output for viewing
 Receive:   $id, $group, $bannercode, $tracker, $link, $image, $responsive
 Return:    $banner_output
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_ad_output($id, $group = 0, $bannercode, $tracker, $link, $image, $responsive) {
	global $blog_id, $adrotate_debug, $adrotate_config;

	$banner_output = $bannercode;
	$banner_output = stripslashes(htmlspecialchars_decode($banner_output, ENT_QUOTES));
	$banner_output = str_replace('%link%', $link, $banner_output);

	if($adrotate_config['enable_stats'] == 'Y') {
		if(empty($blog_id) or $blog_id == '') {
			$blog_id = 0;
		}
		
		$banner_output = str_replace('<a ', '<a data-track="'.adrotate_hash($id, $group, $blog_id).'" ', $banner_output);

		if($tracker == "Y") {
			preg_match_all('/<a[^>](.*?)>/i', $banner_output, $matches, PREG_SET_ORDER);
			if(count($matches) > 0) {
				foreach($matches as &$value) {
					if(preg_match('/<a[^>]+class=\"(.+?)\"[^>]*>/i', $value[0], $regs)) {
					    $result = $regs[1]." gofollow";
						$str = str_replace('class="'.$regs[1].'"', 'class="'.$result.'"', $value[0]);	    
					} else {
						$str = str_replace('<a ', '<a class="gofollow" ', $value[0]);
					}
					$banner_output = str_replace($value[0], $str, $banner_output);
					unset($value, $regs, $result, $str);
				}
			}
			if($adrotate_debug['timers'] == true) {
				$banner_output = str_replace('<a ', '<a data-debug="1" ', $banner_output);
			}
			unset($matches);
		}
	}

	// Add Responsive classes
	preg_match_all('/<img[^>](.*?)>/i', $banner_output, $matches, PREG_SET_ORDER);
	if(count($matches) > 0) {
		foreach($matches as &$value) {
			if(preg_match('/<img[^>]+class=\"(.+?)\"[^>]*>/i', $value[0], $regs)) {
				$result = $regs[1];
				if($responsive == 'Y') $result .= " responsive";
				$result = trim($result);
				$str = str_replace('class="'.$regs[1].'"', 'class="'.$result.'"', $value[0]);	    
			} else {
				$result = '';
				if($responsive == 'Y') $result .= " responsive";
				$result = trim($result);
				if(strlen($result) > 0) {
					$str = str_replace('<img ', '<img class="'.$result.'" ', $value[0]);
				} else {
					$str = $value[0];
				}
			}
			$banner_output = str_replace($value[0], $str, $banner_output);
			unset($value, $regs, $result, $str);
		}
	}
	unset($matches);

	$banner_output = str_replace('%title%', get_the_title(), $banner_output);		
	$banner_output = str_replace('%random%', rand(100000,999999), $banner_output);
	$banner_output = str_replace('%image%', $image, $banner_output);
	$banner_output = str_replace('%id%', $id, $banner_output);
	$banner_output = do_shortcode($banner_output);

	return $banner_output;
}

/*-------------------------------------------------------------
 Name:      adrotate_custom_scripts

 Purpose:   Add required scripts to site head
 Receive:   -None-
 Return:	-None-
 Since:		3.6
-------------------------------------------------------------*/
function adrotate_custom_scripts() {
	global $adrotate_config;
	
	$in_footer = false;
	if($adrotate_config['jsfooter'] == "Y") {
		$in_footer = true;
	}
	
	if($adrotate_config['jquery'] == 'Y') wp_enqueue_script('jquery', false, false, false, $in_footer);
	if(get_option('adrotate_dynamic_required') > 0) wp_enqueue_script('jshowoff-adrotate', plugins_url('/library/jquery.adrotate.dyngroup.js', __FILE__), false, '0.7', $in_footer);
	if(get_option('adrotate_responsive_required') > 0) wp_enqueue_script('responsive-adrotate', plugins_url('/library/jquery.adrotate.responsive.js', __FILE__), false, '0.4', $in_footer);

	// Make clicktracking and impression tracking a possibility
	if($adrotate_config['enable_stats'] == 'Y'){
		wp_enqueue_script('clicktrack-adrotate', plugins_url('/library/jquery.adrotate.clicktracker.js', __FILE__), false, '0.7', $in_footer);
		wp_localize_script('jshowoff-adrotate', 'impression_object', array('ajax_url' => admin_url( 'admin-ajax.php')));
		wp_localize_script('clicktrack-adrotate', 'click_object', array('ajax_url' => admin_url('admin-ajax.php')));
	}

	if(!$in_footer) {
		add_action('wp_head', 'adrotate_custom_javascript');
	} else {
		add_action('wp_footer', 'adrotate_custom_javascript', 100);
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_custom_javascript

 Purpose:   Add required JavaScript to site
 Receive:   -None-
 Return:	-None-
 Since:		3.10.5
-------------------------------------------------------------*/
function adrotate_custom_javascript() {
	global $wpdb, $adrotate_config;

	$groups = $wpdb->get_results("SELECT `id`, `adspeed` FROM `".$wpdb->prefix."adrotate_groups` WHERE `name` != '' AND `modus` = 1 ORDER BY `id` ASC;");
	if($groups) {
		$output_js = "jQuery(document).ready(function(){\n";
		$output_js .= "if(jQuery.fn.gslider) {\n";
		foreach($groups as $group) {
			$output_js .= "\tjQuery('.g-".$group->id."').gslider({ groupid: ".$group->id.", speed: ".$group->adspeed." });\n";
		}
		$output_js .= "}\n";
		$output_js .= "});\n";
		unset($groups);
	}

	$output = "<!-- AdRotate JS -->\n";
	$output .= "<script type=\"text/javascript\">\n";

	if(($adrotate_config['adblock'] == 'Y' AND !is_user_logged_in()) OR ($adrotate_config['adblock'] == 'Y' AND $adrotate_config['adblock_loggedin'] == "Y" AND is_user_logged_in())) {
		$output .= "jQuery(document).ready(function() {\n";
		$output .= "\tjQuery('body').adblockdetect({time: ".$adrotate_config['adblock_timer'].", message: \"".$adrotate_config['adblock_message']."\"});\n";
		$output .= "});\n";
	}

	if(isset($output_js)) {
		$output .= $output_js;
		unset($output_js);
	}
	$output .= "</script>\n";
	$output .= "<!-- /AdRotate JS -->\n\n";

	echo $output;
}

/*-------------------------------------------------------------
 Name:      adrotate_custom_css

 Purpose:   Add required CSS to site head
 Receive:   -None-
 Return:	-None-
 Since:		3.8
-------------------------------------------------------------*/
function adrotate_custom_css() {
	global $wpdb, $adrotate_config;
	
	$output = "\n<!-- This site is using AdRotate v".ADROTATE_DISPLAY." to display their advertisements - https://ajdg.solutions/products/adrotate-for-wordpress/ -->\n";

	$groups = $wpdb->get_results("SELECT `id`, `modus`, `gridrows`, `gridcolumns`, `adwidth`, `adheight`, `admargin`, `align` FROM `".$wpdb->prefix."adrotate_groups` WHERE `name` != '' ORDER BY `id` ASC;");
	if($groups) {
		$output_css = "\t.g { margin:0px; padding:0px; overflow:hidden; line-height:1; zoom:1; }\n";
		$output_css .= "\t.g-col { position:relative; float:left; }\n";
		$output_css .= "\t.g-col:first-child { margin-left: 0; }\n";
		$output_css .= "\t.g-col:last-child { margin-right: 0; }\n";

		foreach($groups as $group) {
			if($group->align == 0) { // None
				$group_align = '';
			} else if($group->align == 1) { // Left
				$group_align = ' float:left; clear:left;';
			} else if($group->align == 2) { // Right
				$group_align = ' float:right; clear:right;';
			} else if($group->align == 3) { // Center
				$group_align = ' margin: 0 auto;';
			}

			if($group->modus == 0 AND ($group->admargin > 0 OR $group->align > 0)) { // Single ad group
				if($group->align < 3) {
					$output_css .= "\t.g-".$group->id." { margin:".$group->admargin."px;".$group_align." }\n";
				} else {
					$output_css .= "\t.g-".$group->id." { ".$group_align." }\n";	
				}
			}
	
			if($group->modus == 1) { // Dynamic group
				if($group->adwidth != 'auto') {
					$width = "width:100%; max-width:".$group->adwidth."px;";
				} else {
					$width = "width:auto;";
				}
				
				if($group->adheight != 'auto') {
					$height = "height:100%; max-height:".$group->adheight."px;";
				} else {
					$height = "height:auto;";
				}

				if($group->align < 3) {
					$output_css .= "\t.g-".$group->id." { margin:".$group->admargin."px;".$width." ".$height.$group_align." }\n";
				} else {
					$output_css .= "\t.g-".$group->id." { ".$width." ".$height.$group_align." }\n";	
				}

				unset($width_sum, $width, $height_sum, $height);
			}
	
			if($group->modus == 2) { // Block group
				if($group->adwidth != 'auto') {
					$width_sum = $group->gridcolumns * ($group->admargin + $group->adwidth + $group->admargin);
					$grid_width = "min-width:".$group->admargin."px; max-width:".$width_sum."px;";
				} else {
					$grid_width = "width:auto;";
				}
				
				$output_css .= "\t.g-".$group->id." { ".$grid_width.$group_align." }\n";
				$output_css .= "\t.b-".$group->id." { margin:".$group->admargin."px; }\n";
				unset($width_sum, $grid_width, $height_sum, $grid_height);
			}
		}
		$output_css .= "\t@media only screen and (max-width: 480px) {\n";
		$output_css .= "\t\t.g-col, .g-dyn, .g-single { width:100%; margin-left:0; margin-right:0; }\n";
		$output_css .= "\t}\n";
		unset($groups);
	}

	if(isset($output_css) OR $adrotate_config['widgetpadding'] == "Y") {
		$output .= "<!-- AdRotate CSS -->\n";
		$output .= "<style type=\"text/css\" media=\"screen\">\n";
		if(isset($output_css)) {
			$output .= $output_css;
			unset($output_css);
		}
		if($adrotate_config['widgetpadding'] == "Y") { 
			$output .= ".widget_adrotate_widgets { overflow:hidden; padding:0; }\n"; 
		}
		$output .= "</style>\n";
		$output .= "<!-- /AdRotate CSS -->\n\n";
	}

	echo $output;
}

/*-------------------------------------------------------------
 Name:      adrotate_nonce_error

 Purpose:   Display a formatted error if Nonce fails
 Receive:   -none-
 Return:    -none-
 Since:		3.7.4.2
-------------------------------------------------------------*/
function adrotate_nonce_error() {
	echo '	<h2 style="text-align: center;">'.__('Oh no! Something went wrong!', 'adrotate').'</h2>';
	echo '	<p style="text-align: center;">'.__('WordPress was unable to verify the authenticity of the url you have clicked. Verify if the url used is valid or log in via your browser.', 'adrotate').'</p>';
	echo '	<p style="text-align: center;">'.__('If you have received the url you want to visit via email, you are being tricked!', 'adrotate').'</p>';
	echo '	<p style="text-align: center;">'.__('Contact support if the issue persists:', 'adrotate').' <a href="https://ajdg.solutions/forums/forum/adrotate-for-wordpress/" title="AdRotate Support" target="_blank">AdRotate Support</a>.</p>';
}

/*-------------------------------------------------------------
 Name:      adrotate_error

 Purpose:   Show errors for problems in using AdRotate, should they occur
 Receive:   $action, $arg
 Return:    -none-
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_error($action, $arg = null) {
	global $adrotate_debug;

	switch($action) {
		// Ads
		case "ad_expired" :
			if($adrotate_debug['general'] == true) {
				$result = '<span style="font-weight: bold; color: #f00;">'.__('Error, Ad is not available at this time due to schedule/geolocation restrictions or does not exist!', 'adrotate').'</span>';
			} else {
				$result = '<!-- '.__('Error, Ad is not available at this time due to schedule/geolocation restrictions!', 'adrotate').' -->';
			}
			return $result;
		break;
		
		case "ad_unqualified" :
			if($adrotate_debug['general'] == true) {
				$result = '<span style="font-weight: bold; color: #f00;">'.__('Either there are no banners, they are disabled or none qualified for this location!', 'adrotate').'</span>';
			} else {
				$result = '<!-- '.__('Either there are no banners, they are disabled or none qualified for this location!', 'adrotate').' -->';
			}
			return $result;
		break;
		
		case "ad_no_id" :
			$result = '<span style="font-weight: bold; color: #f00;">'.__('Error, no Ad ID set! Check your syntax!', 'adrotate').'</span>';
			return $result;
		break;

		// Groups
		case "group_no_id" :
			$result = '<span style="font-weight: bold; color: #f00;">'.__('Error, no group ID set! Check your syntax!', 'adrotate').'</span>';
			return $result;
		break;

		case "group_not_found" :
			$result = '<span style="font-weight: bold; color: #f00;">'.__('Error, group does not exist! Check your syntax!', 'adrotate').' (ID: '.$arg[0].')</span>';
			return $result;
		break;

		// Database
		case "db_error" :
			$result = '<span style="font-weight: bold; color: #f00;">'.__('There was an error locating the database tables for AdRotate. Please deactivate and re-activate AdRotate from the plugin page!!', 'adrotate').'<br />'.__('If this does not solve the issue please seek support at', 'adrotate').' <a href="https://ajdg.solutions/forums/forum/adrotate-for-wordpress/">ajdg.solutions/forums/forum/adrotate-for-wordpress/</a></span>';
			return $result;
		break;

		// Misc
		default:
			$result = '<span style="font-weight: bold; color: #f00;">'.__('An unknown error occured.', 'adrotate').' (ID: '.$arg[0].')</span>';
			return $result;
		break;
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_notifications_dashboard

 Purpose:   Notify user of expired banners in the dashboard
 Receive:   -none-
 Return:    -none-
 Since:		3.0
-------------------------------------------------------------*/
function adrotate_notifications_dashboard() {
	global $adrotate_advert_status;
	if(current_user_can('adrotate_ad_manage')) {

		if(!is_array($adrotate_advert_status)) {
			$data = unserialize($adrotate_advert_status);
		} else {
			$data = $adrotate_advert_status;
		}

		if($data['total'] > 0) {
			if($data['expired'] > 0 AND $data['expiressoon'] == 0 AND $data['error'] == 0) {
				echo '<div class="error"><p>'.$data['expired'].' '.__('active ad(s) expired.', 'adrotate').' <a href="admin.php?page=adrotate-ads">'.__('Take action now', 'adrotate').'</a>!</p></div>';
			} else if($data['expired'] == 0 AND $data['expiressoon'] > 0 AND $data['error'] == 0) {
				echo '<div class="error"><p>'.$data['expiressoon'].' '.__('active ad(s) are about to expire.', 'adrotate').' <a href="admin.php?page=adrotate-ads">'.__('Check it out', 'adrotate').'</a>!</p></div>';
			} else if($data['expired'] == 0 AND $data['expiressoon'] == 0 AND $data['error'] > 0) {
				echo '<div class="error"><p>There are '.$data['error'].' '.__('active ad(s) with configuration errors.', 'adrotate').' <a href="admin.php?page=adrotate-ads">'.__('Solve this', 'adrotate').'</a>!</p></div>';
			} else {
				echo '<div class="error"><p>'.$data['expired'].' '.__('ad(s) expired.', 'adrotate').' '.$data['expiressoon'].' '.__('ad(s) are about to expire.', 'adrotate').' There are '.$data['error'].' '.__('ad(s) with configuration errors.', 'adrotate').' <a href="admin.php?page=adrotate-ads">'.__('Fix this as soon as possible', 'adrotate').'</a>!</p></div>';
			}
		}

		if(isset($_GET['hide'])) update_option('adrotate_hide_banner', 1);
		if(isset($_GET['page'])) { $page = $_GET['page']; } else { $page = ''; }

		$banner = get_option('adrotate_hide_banner');
		if($banner != 1 AND $banner < (adrotate_now() - 604800) AND strpos($page, 'adrotate') !== false) {
			echo '<div class="updated" style="padding: 0; margin: 0; border-left: none;">';
			echo '	<div class="adrotate_pro_banner">';
			echo '		<div class="button_div">';
			echo '			<a class="button" target="_blank" href="https://ajdg.solutions/products/adrotate-for-wordpress/?utm_source=adrotate_free&utm_medium=adrotate_free_banner&utm_campaign=upgrade_adrotatefree">'.__('Learn More', 'adrotate').'</a>';
			echo '		</div>';
			echo '		<div class="text">'.__("You've been using <strong>AdRotate</strong> for a while now. Why not upgrade to the <strong>PRO</strong> version", 'adrotate').'?<br />';
			echo '			<span>'.__('Get more features to even better run your advertising campaigns.', 'adrotate' ).' '.__('Thank you for your consideration!', 'adrotate' ).'</span>';
			echo '		</div>';
			echo '		<a class="close_banner" href="admin.php?page=adrotate-pro&hide=1"><small>dismiss</small></a>';
			echo '		<div class="icon">';
			echo '			<img  title="" src="'.plugins_url('images/adrotate-logo-60x60.png', __FILE__).'" alt=""/>';
			echo '		</div>';
			echo '	</div>';
			echo '</div>';
		}
	}
}

/*-------------------------------------------------------------
 Name:      adrotate_welcome_pointer

 Purpose:   Show dashboard pointers
 Receive:   -none-
 Return:    -none-
 Since:		3.9.14
-------------------------------------------------------------*/
function adrotate_welcome_pointer() {
    $pointer_content = '<h3>AdRotate '.ADROTATE_DISPLAY.'</h3>';
    $pointer_content .= '<p>'.__('Welcome, and thanks for using AdRotate Pro. Everything related to AdRotate Pro is in this menu. Check out the', 'adrotate').' <a href="http:\/\/ajdg.solutions\/manuals\/adrotate\/" target="_blank">'.__('manuals', 'adrotate').'</a> '.__('and', 'adrotate').' <a href="https:\/\/ajdg.solutions\/forums\/forum\/adrotate-for-wordpress\/" target="_blank">'.__('forums', 'adrotate').'</a>.</p>';
    $pointer_content .= '<p><strong>AdRotate Professional</strong><br />Did you know there is also a premium version of AdRotate? Learn how you can benefit from the <a href="admin.php?page=adrotate-pro" target="_blank">extra features</a>.</p>';
?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('#toplevel_page_adrotate').pointer({
				'content':'<?php echo $pointer_content; ?>',
				'position':{
					'edge':'left', // left, right, top, bottom
					'align':'middle' // top, bottom, left, right, middle
				},
				close: function() {
	                $.post(ajaxurl, {
	                    pointer:'adrotate_free_'+<?php echo ADROTATE_VERSION.ADROTATE_DB_VERSION; ?>,
	                    action:'dismiss-wp-pointer'
	                });
				}
			}).pointer("open");
		});
	</script>
<?php
}

/*-------------------------------------------------------------
 Name:      adrotate_help_info

 Purpose:   Help tab on all pages
 Receive:   -none-
 Return:    -none-
 Since:		3.10.17
-------------------------------------------------------------*/
function adrotate_help_info() {
    $screen = get_current_screen();

    $screen->add_help_tab(array(
        'id' => 'adrotate_useful_links',
        'title' => __('Useful Links'),
        'content' => '<h4>'.__('Useful links to learn more about AdRotate', 'adrotate').'</h4>'.
			'<ul>'.
			'<li><a href="https://ajdg.solutions/products/adrotate-for-wordpress/" target="_blank">'.__('AdRotate Page', 'adrotate').'</a>.</li>'.
			'<li><a href="https://ajdg.solutions/manuals/adrotate/getting-started-with-adrotate/" target="_blank">'.__('Getting Started With AdRotate', 'adrotate').'</a>.</li>'.
			'<li><a href="https://ajdg.solutions/manuals/adrotate/" target="_blank">'.__('AdRotate manuals', 'adrotate').'</a>.</li>'.
			'<li><a href="https://ajdg.solutions/forums/forum/adrotate-for-wordpress/" target="_blank">'.__('AdRotate Support Forum', 'adrotate').'</a>.</li>'.
			'<li><a href="http://wordpress.org/support/plugin/adrotate" target="_blank">'.__('WordPress.org Forum', 'adrotate').'</a>.</li>'.
			'</ul>'
		) 
    );
    $screen->add_help_tab(array(
        'id' => 'adrotate_thanks',
        'title' => 'Thank You',
        'content' => '<h4>Thank you for using AdRotate</h4><p>AdRotate is growing to be one of the most popular WordPress plugins for Advertising and is a household name for many companies around the world. AdRotate wouldn\'t be possible without your support and my life wouldn\'t be what it is today without your help.</p><p><em>- Arnan from AJdG Solutions</em></p>'.
        '<p><strong>Add me:</strong> <a href="http://twitter.com/ajdgsolutions/" target="_blank">Twitter</a>, <a href="https://www.facebook.com/adrotate" target="_blank">Facebook</a>. <strong>Business:</strong> <a href="https://ajdg.solutions/" target="_blank">ajdg.solutions</a> <strong>Blog:</strong> <a href="http://meandmymac.net/" target="_blank">meandmymac.net</a> and <strong>adventure:</strong> <a href="http://www.floatingcoconut.net/" target="_blank">floatingcoconut.net</a>.</p>'
		)
    );
}

/*-------------------------------------------------------------
 Name:      adrotate_credits

 Purpose:   Promotional stuff shown throughout the plugin
 Receive:   -none-
 Return:    -none-
 Since:		3.7
-------------------------------------------------------------*/
function adrotate_help_links() {
	echo '<h4>'.__('Useful links to learn more about AdRotate', 'adrotate').'</h4>';
	echo '<ul>';
	echo '<li><a href="https://ajdg.solutions/products/adrotate-for-wordpress/" target="_blank">'.__('AdRotate Website.', 'adrotate').'</a></li>';
	echo '<li><a href="https://ajdg.solutions/manuals/adrotate/getting-started-with-adrotate/" target="_blank">'.__('AdRotate Getting Started.', 'adrotate').'</a></li>';
	echo '<li><a href="https://ajdg.solutions/manuals/adrotate/" target="_blank">'.__('AdRotate Knoweledge base and manuals.', 'adrotate').'</a></li>';
	echo '<li><a href="https://ajdg.solutions/forums/" target="_blank">'.__('AdRotate Website Forum.', 'adrotate').'</a></li>';
	echo '<li><a href="http://wordpress.org/support/plugin/adrotate" target="_blank">'.__('WordPress.org Forum.', 'adrotate').'</a></li>';
	echo '</ul>';
}

/*-------------------------------------------------------------
 Name:      adrotate_credits

 Purpose:   Promotional stuff shown throughout the plugin
 Receive:   -none-
 Return:    -none-
 Since:		3.7
-------------------------------------------------------------*/
function adrotate_credits() {
	echo '<table class="widefat" style="margin-top: .5em">';

	echo '<thead>';
	echo '<tr valign="top">';
	echo '	<th colspan="2">'.__('Help AdRotate Grow', 'adrotate').'</th>';
	echo '	<th colspan="2" width="40%">'.__('Brought to you by', 'adrotate').'</th>';
	echo '</tr>';
	echo '</thead>';

	echo '<tbody>';
	echo '<tr>';
	echo '<td><center><a href="https://ajdg.solutions/products/adrotate-for-wordpress/?utm_source=adrotate_free&utm_medium=adrotate_credits&utm_campaign=adrotatefree" title="AdRotate plugin for WordPress"><img src="'.plugins_url('/images/adrotate-logo-60x60.png', __FILE__).'" alt="adrotate-logo-60x60" width="60" height="60" /></a></center></td>';
	echo '<td>'.__("A lot of users only think to review AdRotate when something goes wrong while thousands of people use AdRotate satisfactory. Don't let this go unnoticed.", 'adrotate').' <strong>'. __("If you find AdRotate useful please leave your honest", 'adrotate').' <a href="https://wordpress.org/support/view/plugin-reviews/adrotate?rate=5#postform" target="_blank">'.__('rating','adrotate').'</a> '.__('and','adrotate').' <a href="https://wordpress.org/support/view/plugin-reviews/adrotate" target="_blank">'.__('review','adrotate').'</a> '.__('on WordPress.org to help AdRotate grow in a positive way', 'adrotate').'!</strong></td>';

	echo '<td><center><a href="https://ajdg.solutions/" title="AJdG Solutions"><img src="'.plugins_url('/images/ajdg-logo-100x60.png', __FILE__).'" alt="ajdg-logo-100x60" width="100" height="60" /></a></center></td>';
	echo '<td><a href="https://ajdg.solutions/" title="AJdG Solutions">AJdG Solutions</a> - '.__('Your one stop for Webdevelopment, consultancy and anything WordPress! Find out more about what I can do for you!', 'adrotate').' '.__('Visit the', 'adrotate').' <a href="https://ajdg.solutions/" target="_blank">AJdG Solutions</a> '.__('website', 'adrotate').'</td>';
	echo '</tr>';
	echo '</tbody>';

	echo '</table>';
	echo adrotate_trademark();
}

/*-------------------------------------------------------------
 Name:      adrotate_trademark
 
 Purpose:   Trademark notice
 Receive:   -none-
 Return:    -none-
 Since:		3.9.14
-------------------------------------------------------------*/
function adrotate_trademark() {
	return '<center><small>AdRotate&reg; and the AdRotate Logo are owned by Arnan de Gans for AJdG Solutions.</small></center>';
}

/*-------------------------------------------------------------
 Name:      adrotate_pro_notice
 
 Purpose:   Credits shown on user statistics
 Receive:   $d
 Return:    -none-
 Since:		3.8
-------------------------------------------------------------*/
function adrotate_pro_notice($d = '') {

	if($d == "t") echo __('Available in AdRotate Pro', 'adrotate').'. <a href="admin.php?page=adrotate-pro">'.__('More information...', 'adrotate').'</a>';
	else echo __('This feature is available in AdRotate Pro', 'adrotate').'. <a href="admin.php?page=adrotate-pro">'.__('Learn more', 'adrotate').'</a>!';
}
?>