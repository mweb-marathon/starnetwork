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
<?php if(!$group_edit_id) { 
	$action = "group_new";
	$edit_id = $wpdb->get_var("SELECT `id` FROM `".$wpdb->prefix."adrotate_groups` WHERE `name` = '' ORDER BY `id` DESC LIMIT 1;");
	if($edit_id == 0) {
		$wpdb->insert($wpdb->prefix.'adrotate_groups', array('name' => '', 'modus' => 0, 'fallback' => '0', 'sortorder' => 0, 'cat' => '', 'cat_loc' => 0, 'cat_par' => 0, 'page' => '', 'page_loc' => 0, 'page_par' => 0, 'geo' => 0, 'wrapper_before' => '', 'wrapper_after' => '', 'gridrows' => 2, 'gridcolumns' => 2, 'admargin' => 0, 'admargin_bottom' => 0, 'admargin_left' => 0, 'admargin_right' => 0, 'adwidth' => '125', 'adheight' => '125', 'adspeed' => 6000));
	    $edit_id = $wpdb->insert_id;
	}
	$group_edit_id = $edit_id;
	?>
<?php } else { 
	$action = "group_edit";
}

$edit_group = $wpdb->get_row("SELECT * FROM `".$wpdb->prefix."adrotate_groups` WHERE `id` = '$group_edit_id';");
$groups		= $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."adrotate_groups` WHERE `name` != '' ORDER BY `id` ASC;"); 
$ads = $wpdb->get_results("SELECT `id`, `title`, `tracker`, `weight`, `crate`, `cbudget`, `irate`, `ibudget`, `type` FROM `".$wpdb->prefix."adrotate` WHERE (`type` != 'empty' AND `type` != 'a_empty') ORDER BY `id` ASC;");
$linkmeta	= $wpdb->get_results("SELECT `ad` FROM `".$wpdb->prefix."adrotate_linkmeta` WHERE `group` = '$group_edit_id' AND `block` = 0 AND `user` = 0;");

$meta_array = '';
foreach($linkmeta as $meta) {
	$meta_array[] = $meta->ad;
}
if(!is_array($meta_array)) $meta_array = array();
?>

<form name="editgroup" id="post" method="post" action="admin.php?page=adrotate-groups">
	<?php wp_nonce_field('adrotate_save_group','adrotate_nonce'); ?>
	<input type="hidden" name="adrotate_id" value="<?php echo $edit_group->id;?>" />
	<input type="hidden" name="adrotate_action" value="<?php echo $action;?>" />

	<?php if($edit_group->name == '') { ?>
		<h3><?php _e('New Group', 'adrotate'); ?></h3>
	<?php } else { ?> 
		<h3><?php _e('Edit Group', 'adrotate'); ?></h3>
	<?php } ?>

	<p><em><?php _e('These are required.', 'adrotate'); ?></em></p>
   	<table class="widefat" style="margin-top: .5em">
		<tbody>
	    <tr>
			<th width="15%"><?php _e('ID:', 'adrotate'); ?></th>
			<td colspan="2"><?php echo $edit_group->id; ?></td>
		</tr>
	    <tr>
			<th width="15%"><?php _e('Name:', 'adrotate'); ?></th>
			<td colspan="2">
				<label for="adrotate_groupname"><input tabindex="1" name="adrotate_groupname" type="text" class="search-input" size="80" value="<?php echo $edit_group->name; ?>" autocomplete="off" /></label>
			</td>
		</tr>
	    <tr>
			<th valign="top"><?php _e('Mode', 'adrotate'); ?></strong></th>
			<td width="35%">
		       	<select tabindex="2" name="adrotate_modus">
		        	<option value="0" <?php if($edit_group->modus == 0) { echo 'selected'; } ?>><?php _e('Default - Show one ad at a time', 'adrotate'); ?></option>
		        	<option value="1" <?php if($edit_group->modus == 1) { echo 'selected'; } ?>><?php _e('Dynamic Mode - Show a different ad every few seconds', 'adrotate'); ?></option>
		        	<option value="2" <?php if($edit_group->modus == 2) { echo 'selected'; } ?>><?php _e('Block Mode - Show a block of ads', 'adrotate'); ?></option>
		        </select> 
			</td>
			<td>
		        <p><em><?php _e('Dynamic mode requires jQuery. You can enable this in AdRotate Settings.', 'adrotate'); ?></em></p>
			</td>
		</tr>
		</tbody>
	</table>
	<center><?php _e('Get more features with AdRotate Pro.', 'adrotate'); ?> <a href="admin.php?page=adrotate-pro"><?php _e('More information', 'adrotate'); ?></a>.</center>

	<h3><?php _e('Dynamic and Block Mode', 'adrotate'); ?></h3>
	<p><em><?php _e('Only required if your group is in Dynamic or Block mode.', 'adrotate'); ?></em></p>
   	<table class="widefat" style="margin-top: .5em">
		<tbody>
	    <tr>
			<th width="15%"><?php _e('Block shape and border', 'adrotate'); ?></strong></th>
			<td width="35%">
		       	<label for="adrotate_gridrows"><select tabindex="3" name="adrotate_gridrows">
		        	<option value="1" <?php if($edit_group->gridrows == 1) { echo 'selected'; } ?>>1</option>
		        	<option value="2" <?php if($edit_group->gridrows == 2) { echo 'selected'; } ?>>2</option>
		        	<option value="3" <?php if($edit_group->gridrows == 3) { echo 'selected'; } ?>>3</option>
		        	<option value="4" <?php if($edit_group->gridrows == 4) { echo 'selected'; } ?>>4</option>
		        	<option value="5" <?php if($edit_group->gridrows == 5) { echo 'selected'; } ?>>5</option>
		        	<option value="6" <?php if($edit_group->gridrows == 6) { echo 'selected'; } ?>>6</option>
		        	<option value="7" <?php if($edit_group->gridrows == 7) { echo 'selected'; } ?>>7</option>
		        	<option value="8" <?php if($edit_group->gridrows == 8) { echo 'selected'; } ?>>8</option>
		        	<option value="9" <?php if($edit_group->gridrows == 9) { echo 'selected'; } ?>>9</option>
		        	<option value="10" <?php if($edit_group->gridrows == 10) { echo 'selected'; } ?>>10</option>
		        	<option value="11" <?php if($edit_group->gridrows == 11) { echo 'selected'; } ?>>11</option>
		        	<option value="12" <?php if($edit_group->gridrows == 12) { echo 'selected'; } ?>>12</option>
		        	<option value="13" <?php if($edit_group->gridrows == 13) { echo 'selected'; } ?>>13</option>
		        	<option value="14" <?php if($edit_group->gridrows == 14) { echo 'selected'; } ?>>14</option>
		        	<option value="15" <?php if($edit_group->gridrows == 15) { echo 'selected'; } ?>>15</option>
		        	<option value="16" <?php if($edit_group->gridrows == 16) { echo 'selected'; } ?>>16</option>
		        	<option value="17" <?php if($edit_group->gridrows == 17) { echo 'selected'; } ?>>17</option>
		        	<option value="18" <?php if($edit_group->gridrows == 18) { echo 'selected'; } ?>>18</option>
		        	<option value="19" <?php if($edit_group->gridrows == 19) { echo 'selected'; } ?>>19</option>
		        	<option value="20" <?php if($edit_group->gridrows == 20) { echo 'selected'; } ?>>20</option>
		        	<option value="21" <?php if($edit_group->gridrows == 21) { echo 'selected'; } ?>>21</option>
		        	<option value="22" <?php if($edit_group->gridrows == 22) { echo 'selected'; } ?>>22</option>
		        	<option value="23" <?php if($edit_group->gridrows == 23) { echo 'selected'; } ?>>23</option>
		        	<option value="24" <?php if($edit_group->gridrows == 24) { echo 'selected'; } ?>>24</option>
		        </select> <?php _e('rows', 'adrotate'); ?>,</label> <label for="adrotate_gridcolumns"><select tabindex="4" name="adrotate_gridcolumns">
		        	<option value="1" <?php if($edit_group->gridcolumns == 1) { echo 'selected'; } ?>>1</option>
		        	<option value="2" <?php if($edit_group->gridcolumns == 2) { echo 'selected'; } ?>>2</option>
		        	<option value="3" <?php if($edit_group->gridcolumns == 3) { echo 'selected'; } ?>>3</option>
		        	<option value="4" <?php if($edit_group->gridcolumns == 4) { echo 'selected'; } ?>>4</option>
		        	<option value="5" <?php if($edit_group->gridcolumns == 5) { echo 'selected'; } ?>>5</option>
		        	<option value="6" <?php if($edit_group->gridcolumns == 6) { echo 'selected'; } ?>>6</option>
		        	<option value="7" <?php if($edit_group->gridcolumns == 7) { echo 'selected'; } ?>>7</option>
		        	<option value="8" <?php if($edit_group->gridcolumns == 8) { echo 'selected'; } ?>>8</option>
		        	<option value="9" <?php if($edit_group->gridcolumns == 9) { echo 'selected'; } ?>>9</option>
		        	<option value="10" <?php if($edit_group->gridcolumns == 10) { echo 'selected'; } ?>>10</option>
		        	<option value="11" <?php if($edit_group->gridcolumns == 11) { echo 'selected'; } ?>>11</option>
		        	<option value="12" <?php if($edit_group->gridcolumns == 12) { echo 'selected'; } ?>>12</option>
		        </select> <?php _e('columns', 'adrotate'); ?>.</label>
			</td>
			<td colspan="2">
		        <p><em><?php _e('Block Mode', 'adrotate'); ?> - <?php _e('Make a grid for your ads. Selecting 3 and 2 makes a grid with 2 columns showing a maximum of 6 ads. Default: 2x2.', 'adrotate'); ?></em></p>
			</td>
		</tr>
	    <tr>
			<th valign="top"><?php _e('Advert Width and Height', 'adrotate'); ?></strong></th>
			<td>
				<label for="adrotate_adwidth"><input tabindex="5" name="adrotate_adwidth" type="text" class="search-input" size="3" value="<?php echo $edit_group->adwidth; ?>" autocomplete="off" /> <?php _e('pixel(s) wide', 'adrotate'); ?>,</label> <label for="adrotate_adheight"><input tabindex="6" name="adrotate_adheight" type="text" class="search-input" size="3" value="<?php echo $edit_group->adheight; ?>" autocomplete="off" /> <?php _e('pixel(s) high.', 'adrotate'); ?></label>
			</td>
			<td colspan="2">
		        <p><em><?php _e('Dynamic and Block Mode', 'adrotate'); ?> - <?php _e('Define the maximum size of the ads in pixels. Size can be \'auto\' (Not recommended). Default: 125/125.', 'adrotate'); ?></em></p>
			</td>
		</tr>
	    <tr>
			<th valign="top"><?php _e('Automated refresh', 'adrotate'); ?></strong></th>
			<td>
		       	<label for="adrotate_adwidth"><select tabindex="7" name="adrotate_adspeed">
		        	<option value="3000" <?php if($edit_group->adspeed == 3000) { echo 'selected'; } ?>>3</option>
		        	<option value="4000" <?php if($edit_group->adspeed == 4000) { echo 'selected'; } ?>>4</option>
		        	<option value="5000" <?php if($edit_group->adspeed == 5000) { echo 'selected'; } ?>>5</option>
		        	<option value="6000" <?php if($edit_group->adspeed == 6000) { echo 'selected'; } ?>>6</option>
		        	<option value="7000" <?php if($edit_group->adspeed == 7000) { echo 'selected'; } ?>>7</option>
		        	<option value="8000" <?php if($edit_group->adspeed == 8000) { echo 'selected'; } ?>>8</option>
		        	<option value="9000" <?php if($edit_group->adspeed == 9000) { echo 'selected'; } ?>>9</option>
		        	<option value="10000" <?php if($edit_group->adspeed == 10000) { echo 'selected'; } ?>>10</option>
		        	<option value="12000" <?php if($edit_group->adspeed == 12000) { echo 'selected'; } ?>>12</option>
		        	<option value="15000" <?php if($edit_group->adspeed == 15000) { echo 'selected'; } ?>>15</option>
		        	<option value="20000" <?php if($edit_group->adspeed == 20000) { echo 'selected'; } ?>>20</option>
		        	<option value="25000" <?php if($edit_group->adspeed == 25000) { echo 'selected'; } ?>>25</option>
		        	<option value="35000" <?php if($edit_group->adspeed == 35000) { echo 'selected'; } ?>>35</option>
		        	<option value="45000" <?php if($edit_group->adspeed == 45000) { echo 'selected'; } ?>>45</option>
		        	<option value="60000" <?php if($edit_group->adspeed == 60000) { echo 'selected'; } ?>>60</option>
		        	<option value="90000" <?php if($edit_group->adspeed == 90000) { echo 'selected'; } ?>>90</option>
		        </select> <?php _e('seconds.', 'adrotate'); ?></label>
			</td>
			<td colspan="2">
		        <p><em><?php _e('Dynamic Mode', 'adrotate'); ?> - <?php _e('Load a new advert in this interval without reloading the page. Default: 6.', 'adrotate'); ?></em></p>
			</td>
		</tr>
		</tbody>
	</table>

	<h3><?php _e('Usage', 'adrotate'); ?></h3>
	<p><em><?php _e('Copy the shortcode in a post or page. The PHP code goes in a theme file where you want the advert to show up.', 'adrotate'); ?></em></p>
   	<table class="widefat" style="margin-top: .5em">
		<tbody>
      	<tr>
	        <th width="15%"><?php _e('In a post or page:', 'adrotate'); ?></th>
	        <td width="35%">[adrotate group="<?php echo $edit_group->id; ?>"]</td>
	        <th width="15%"><?php _e('Directly in a theme:', 'adrotate'); ?></th>
	        <td width="35%">&lt;?php echo adrotate_group(<?php echo $edit_group->id; ?>); ?&gt;</td>
      	</tr>
      	</tbody>
	</table>

	<p class="submit">
		<input tabindex="8" type="submit" name="adrotate_group_submit" class="button-primary" value="<?php _e('Save Group', 'adrotate'); ?>" />
		<a href="admin.php?page=adrotate-groups&view=manage" class="button"><?php _e('Cancel', 'adrotate'); ?></a>
	</p>

	<h3><?php _e('Advanced', 'adrotate'); ?></h3>
   	<table class="widefat" style="margin-top: .5em">
		<tbody>
	    <tr>
			<th valign="top"><?php _e('Advert Margin', 'adrotate'); ?></strong></th>
			<td>
				<label for="adrotate_admargin"><input tabindex="9" name="adrotate_admargin" type="text" class="search-input" size="5" value="<?php echo $edit_group->admargin; ?>" autocomplete="off" /> <?php _e('pixel(s)', 'adrotate'); ?>.</label>
				</td>
			<td colspan="2">
		        <p><em><?php _e('A transparent area outside the advert in pixels. Default: 0.', 'adrotate'); ?> <?php _e('Set to 0 to disable.', 'adrotate'); ?></em></p>
			</td>
		</tr>
	    <tr>
			<th valign="top"><?php _e('Align the group', 'adrotate'); ?></strong></th>
			<td>
		       	<label for="adrotate_align"><select tabindex="10" name="adrotate_align">
		        	<option value="0" <?php if($edit_group->align == 0) { echo 'selected'; } ?>><?php _e('None (Default)', 'adrotate'); ?></option>
		        	<option value="1" <?php if($edit_group->align == 1) { echo 'selected'; } ?>><?php _e('Left', 'adrotate'); ?></option>
		        	<option value="2" <?php if($edit_group->align == 2) { echo 'selected'; } ?>><?php _e('Right', 'adrotate'); ?></option>
		        	<option value="3" <?php if($edit_group->align == 3) { echo 'selected'; } ?>><?php _e('Center', 'adrotate'); ?></option>
		        </select></label>
				</td>
			<td colspan="2">
		        <p><em><?php _e('Align the group in your post or page. Using \'center\' may affect your margin setting. Not every theme supports this feature.', 'adrotate'); ?></em></p>
			</td>
		</tr>
      	<tr>
	        <th valign="top"><?php _e('Sortorder:', 'adrotate'); ?></th>
	        <td><label for="adrotate_sortorder"><input tabindex="12" name="adrotate_sortorder" type="text" size="5" class="search-input" autocomplete="off" value="<?php echo $edit_group->sortorder;?>" /></label></td>
	        <td><em><?php _e('For administrative purposes set a sortorder.', 'adrotate'); ?> <?php _e('Leave empty or 0 to skip this. Will default to group id.', 'adrotate'); ?></em></td>
      	</tr>
		</tbody>
	</table>
	<center><?php _e('Set up a fallback group and use Geo Location in AdRotate Pro', 'adrotate'); ?> <a href="admin.php?page=adrotate-pro"><?php _e('Upgrade today', 'adrotate'); ?></a>.</center>
	
   	<h3><?php _e('Post Injection', 'adrotate'); ?></h3>
   	<p><em><?php _e('Insert ads to the begin or end of a post/page.', 'adrotate'); ?></em></p>
   	<table class="widefat" style="margin-top: .5em">
      	<tr>
	        <th width="15%"><?php _e('Include ads in categories?', 'adrotate'); ?></th>
	        <td>
	        <label for="adrotate_cat_location">
		        <select tabindex="13" name="adrotate_cat_location">
		        	<option value="0" <?php if($edit_group->cat_loc == 0) { echo 'selected'; } ?>><?php _e('Disabled', 'adrotate'); ?></option>
		        	<option value="1" <?php if($edit_group->cat_loc == 1) { echo 'selected'; } ?>><?php _e('Before content', 'adrotate'); ?></option>
		        	<option value="2" <?php if($edit_group->cat_loc == 2) { echo 'selected'; } ?>><?php _e('After content', 'adrotate'); ?></option>
		        	<option value="3" <?php if($edit_group->cat_loc == 3) { echo 'selected'; } ?>><?php _e('Before and after content', 'adrotate'); ?></option>
		        	<option value="4" <?php if($edit_group->cat_loc == 4) { echo 'selected'; } ?>><?php _e('After...', 'adrotate'); ?></option>
		        </select>
			</label>
	        <label for="adrotate_cat_paragraph">
		        <select tabindex="14" name="adrotate_cat_paragraph">
		        	<option value="0" <?php if($edit_group->cat_par == 0) { echo 'selected'; } ?>>...</option>
		        	<option value="1" <?php if($edit_group->cat_par == 1) { echo 'selected'; } ?>><?php _e('the first paragraph', 'adrotate'); ?></option>
		        	<option value="20" <?php if($edit_group->cat_par == 20) { echo 'selected'; } ?>><?php _e('the 2nd paragraph', 'adrotate'); ?></option>
		        	<option value="30" <?php if($edit_group->cat_par == 30) { echo 'selected'; } ?>><?php _e('the 3rd paragraph', 'adrotate'); ?></option>
		        	<option value="40" <?php if($edit_group->cat_par == 40) { echo 'selected'; } ?>><?php _e('the 4th paragraph', 'adrotate'); ?></option>
		        	<option value="0" disabled>---</option>
		        	<option value="2" <?php if($edit_group->cat_par == 2) { echo 'selected'; } ?>><?php _e('every 2nd paragraph', 'adrotate'); ?></option>
		        	<option value="3" <?php if($edit_group->cat_par == 3) { echo 'selected'; } ?>><?php _e('every 3rd paragraph', 'adrotate'); ?></option>
		        	<option value="4" <?php if($edit_group->cat_par == 4) { echo 'selected'; } ?>><?php _e('every 4th paragraph', 'adrotate'); ?></option>
		        	<option value="5" <?php if($edit_group->cat_par == 5) { echo 'selected'; } ?>><?php _e('every 5th paragraph', 'adrotate'); ?></option>
		        	<option value="6" <?php if($edit_group->cat_par == 6) { echo 'selected'; } ?>><?php _e('every 6th paragraph', 'adrotate'); ?></option>
		        	<option value="7" <?php if($edit_group->cat_par == 7) { echo 'selected'; } ?>><?php _e('every 7th paragraph', 'adrotate'); ?></option>
		        	<option value="8" <?php if($edit_group->cat_par == 8) { echo 'selected'; } ?>><?php _e('every 8th paragraph', 'adrotate'); ?></option>
		        </select>
			</label>
	        </td>
      	</tr>
      	<tr>
	        <th valign="top"><?php _e('Which categories?', 'adrotate'); ?></th>
	        <td>
	        <label for="adrotate_categories">
				<div class="adrotate-select">
		        <?php echo adrotate_select_categories($edit_group->cat, 0, 0, 0); ?>
				</div><em><?php _e('Click the categories posts you want the adverts to show in.', 'adrotate'); ?></em>
	        </label>
	        </td>
      	</tr>
      	<tr>
	        <th valign="top"><?php _e('Include ads in pages?', 'adrotate'); ?></th>
	        <td>
	        <label for="adrotate_page_location">
		        <select tabindex="15" name="adrotate_page_location">
		        	<option value="0" <?php if($edit_group->page_loc == 0) { echo 'selected'; } ?>><?php _e('Disabled', 'adrotate'); ?></option>
		        	<option value="1" <?php if($edit_group->page_loc == 1) { echo 'selected'; } ?>><?php _e('Before content', 'adrotate'); ?></option>
		        	<option value="2" <?php if($edit_group->page_loc == 2) { echo 'selected'; } ?>><?php _e('After content', 'adrotate'); ?></option>
		        	<option value="3" <?php if($edit_group->page_loc == 3) { echo 'selected'; } ?>><?php _e('Before and after content', 'adrotate'); ?></option>
		        	<option value="4" <?php if($edit_group->page_loc == 4) { echo 'selected'; } ?>><?php _e('After...', 'adrotate'); ?></option>
		        </select>
			</label>
	        <label for="adrotate_page_paragraph">
		        <select tabindex="16" name="adrotate_page_paragraph">
		        	<option value="0" <?php if($edit_group->page_par == 0) { echo 'selected'; } ?>>...</option>
		        	<option value="1" <?php if($edit_group->page_par == 1) { echo 'selected'; } ?>><?php _e('the first paragraph', 'adrotate'); ?></option>
		        	<option value="20" <?php if($edit_group->page_par == 20) { echo 'selected'; } ?>><?php _e('the 2nd paragraph', 'adrotate'); ?></option>
		        	<option value="30" <?php if($edit_group->page_par == 30) { echo 'selected'; } ?>><?php _e('the 3rd paragraph', 'adrotate'); ?></option>
		        	<option value="40" <?php if($edit_group->page_par == 40) { echo 'selected'; } ?>><?php _e('the 4th paragraph', 'adrotate'); ?></option>
		        	<option value="0" disabled>---</option>
		        	<option value="2" <?php if($edit_group->page_par == 2) { echo 'selected'; } ?>><?php _e('every 2nd paragraph', 'adrotate'); ?></option>
		        	<option value="3" <?php if($edit_group->page_par == 3) { echo 'selected'; } ?>><?php _e('every 3rd paragraph', 'adrotate'); ?></option>
		        	<option value="4" <?php if($edit_group->page_par == 4) { echo 'selected'; } ?>><?php _e('every 4th paragraph', 'adrotate'); ?></option>
		        	<option value="5" <?php if($edit_group->page_par == 5) { echo 'selected'; } ?>><?php _e('every 5th paragraph', 'adrotate'); ?></option>
		        	<option value="6" <?php if($edit_group->page_par == 6) { echo 'selected'; } ?>><?php _e('every 6th paragraph', 'adrotate'); ?></option>
		        	<option value="7" <?php if($edit_group->page_par == 7) { echo 'selected'; } ?>><?php _e('every 7th paragraph', 'adrotate'); ?></option>
		        	<option value="8" <?php if($edit_group->page_par == 8) { echo 'selected'; } ?>><?php _e('every 8th paragraph', 'adrotate'); ?></option>
		        </select>
			</label>
	        </td>
      	</tr>
      	<tr>
	        <th valign="top"><?php _e('Which pages?', 'adrotate'); ?></th>
	        <td>
	        <label for="adrotate_pages">
		        <div class="adrotate-select">
		        <?php echo adrotate_select_pages($edit_group->page, 0, 0, 0); ?>
				</div><em><?php _e('Click the pages you want the adverts to show in.', 'adrotate'); ?></em>
	        </label>
	        </td>
      	</tr>
		</tbody>
	</table>
	
	<h3><?php _e('Usage', 'adrotate'); ?></h3>
	<p><em><?php _e('Copy the shortcode in a post or page. The PHP code goes in a theme file where you want the advert to show up.', 'adrotate'); ?></em></p>
   	<table class="widefat" style="margin-top: .5em">
		<tbody>
      	<tr>
	        <th width="15%"><?php _e('In a post or page:', 'adrotate'); ?></th>
	        <td width="35%">[adrotate group="<?php echo $edit_group->id; ?>"]</td>
	        <th width="15%"><?php _e('Directly in a theme:', 'adrotate'); ?></th>
	        <td width="35%">&lt;?php echo adrotate_group(<?php echo $edit_group->id; ?>); ?&gt;</td>
      	</tr>
      	</tbody>
	</table>

	<p class="submit">
		<input tabindex="17" type="submit" name="adrotate_group_submit" class="button-primary" value="<?php _e('Save Group', 'adrotate'); ?>" />
		<a href="admin.php?page=adrotate-groups&view=manage" class="button"><?php _e('Cancel', 'adrotate'); ?></a>
	</p>

   	<h3><?php _e('Wrapper code', 'adrotate'); ?></h3>
   	<p><em><?php _e('Wraps around each ad.', 'adrotate'); ?></em></p>
   	<table class="widefat" style="margin-top: .5em">
		<tbody>
	    <tr>
			<th width="15%" valign="top"><?php _e('Before ad', 'adrotate'); ?></strong></th>
			<td colspan="2"><textarea tabindex="18" name="adrotate_wrapper_before" cols="65" rows="3"><?php echo stripslashes($edit_group->wrapper_before); ?></textarea></td>
			<td width="35%">
		        <p><strong><?php _e('Example:', 'adrotate'); ?></strong> <em>&lt;span style="background-color:#aaa;"&gt;</em></p>
		        <p><strong><?php _e('Options:', 'adrotate'); ?></strong> <em>%id%</em></p>
				<p><em><?php _e('HTML/JavaScript allowed, use with care!', 'adrotate'); ?></em></p>
			</td>
		</tr>
	    <tr>
			<th valign="top"><?php _e('After ad', 'adrotate'); ?></strong></th>
			<td colspan="2"><textarea tabindex="19" name="adrotate_wrapper_after" cols="65" rows="3"><?php echo stripslashes($edit_group->wrapper_after); ?></textarea></td>
			<td>
				<p><strong><?php _e('Example:', 'adrotate'); ?></strong> <em>&lt;/span&gt;</em></p>
			</td>
		</tr>
		</tbody>
	</table>
	
	<h3><?php _e('Select Ads', 'adrotate'); ?></h3>
   	<table class="widefat" style="margin-top: .5em">
		<thead>
		<tr>
			<th scope="col" class="manage-column column-cb check-column"><input type="checkbox" /></th>
			<th>&nbsp;</th>
			<th width="5%"><center><?php _e('Impressions', 'adrotate'); ?></center></th>
			<th width="5%"><center><?php _e('Clicks', 'adrotate'); ?></center></th>
			<th width="5%"><center><?php _e('Weight', 'adrotate'); ?></center></th>
			<th width="15%"><?php _e('Visible until', 'adrotate'); ?></th>
		</tr>
		</thead>

		<tbody>
		<?php if($ads) {
			$class = '';
			foreach($ads as $ad) {
				$stoptime = $wpdb->get_var("SELECT `stoptime` FROM `".$wpdb->prefix."adrotate_schedule`, `".$wpdb->prefix."adrotate_linkmeta` WHERE `ad` = '".$ad->id."' AND `schedule` = `".$wpdb->prefix."adrotate_schedule`.`id` ORDER BY `stoptime` DESC LIMIT 1;");

				$stats = adrotate_stats($ad->id);

				$errorclass = '';
				if($ad->type == 'error') $errorclass = ' row_error'; 
				if($ad->type == 'expired') $errorclass = ' row_inactive';
				if($ad->type == '2days') $errorclass = ' row_urgent';

				$class = ('alternate' != $class) ? 'alternate' : '';
				$class = ($errorclass != '') ? $errorclass : $class;
				?>
			    <tr class='<?php echo $class; ?>'>
					<th class="check-column" width="2%"><input type="checkbox" name="adselect[]" value="<?php echo $ad->id; ?>" <?php if(in_array($ad->id, $meta_array)) echo "checked"; ?> /></th>
					<td><?php echo $ad->id; ?> - <strong><?php echo stripslashes(html_entity_decode($ad->title)); ?></strong></td>
					<td><center><?php echo $stats['impressions']; ?></center></td>
					<td><center><?php if($ad->tracker == 'Y') { echo $stats['clicks']; } else { ?>--<?php } ?></center></td>
					<td><center><?php echo $ad->weight; ?></center></td>
					<td><span style="color: <?php echo adrotate_prepare_color($stoptime);?>;"><?php echo date_i18n("F d, Y", $stoptime); ?></span></td>
				</tr>
			<?php unset($stats);?>
 			<?php } ?>
		<?php } else { ?>
		<tr>
			<th class="check-column">&nbsp;</th>
			<td colspan="5"><em><?php _e('No ads created!', 'adrotate'); ?></em></td>
		</tr>
		<?php } ?>
		</tbody>					
	</table>

	<p><center>
		<span style="border: 1px solid #e6db55; height: 12px; width: 12px; background-color: #ffffe0">&nbsp;&nbsp;&nbsp;&nbsp;</span> <?php _e("Configuration errors.", "adrotate"); ?>
		&nbsp;&nbsp;&nbsp;&nbsp;<span style="border: 1px solid #c00; height: 12px; width: 12px; background-color: #ffebe8">&nbsp;&nbsp;&nbsp;&nbsp;</span> <?php _e("Expires soon.", "adrotate"); ?>
		&nbsp;&nbsp;&nbsp;&nbsp;<span style="border: 1px solid #466f82; height: 12px; width: 12px; background-color: #8dcede">&nbsp;&nbsp;&nbsp;&nbsp;</span> <?php _e("Has expired.", "adrotate"); ?>
	</center></p>

	<p class="submit">
		<input tabindex="20" type="submit" name="adrotate_group_submit" class="button-primary" value="<?php _e('Save Group', 'adrotate'); ?>" />
		<a href="admin.php?page=adrotate-groups&view=manage" class="button"><?php _e('Cancel', 'adrotate'); ?></a>
	</p>
</form>