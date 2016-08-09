<?php
define('PAGE_NAME_STAR_NETWORK_SETTINGS', 'star_networking_settings');
define('PAGE_PATH_STAR_NETWORK_SETTINGS', '/additional/star_networking_settings/');
define('PAGE_TEMPLATE_PATH_STAR_NETWORK_SETTINGS', get_stylesheet_directory() . PAGE_PATH_STAR_NETWORK_SETTINGS);
define('PAGE_URI_PAGE_PATH_STAR_NETWORK_SETTINGS', get_stylesheet_directory_uri() . PAGE_PATH_STAR_NETWORK_SETTINGS);

$themename = "star_network";

// create custom plugin settings menu
add_action('admin_menu', 'star_networking_create_menu');

function star_networking_create_menu()
{

    //create new top-level menu
    add_menu_page('Star Network Settings', 'Homepage Manager', 'administrator', __FILE__, 'star_networking_settings_page');

    //call register settings function
    add_action('admin_init', 'star_networking_settings');
}

/**
 * Load media files needed for Uploader
 */
function load_wp_media_files()
{
    wp_enqueue_media();
}

add_action('admin_enqueue_scripts', 'load_wp_media_files');

/**
 * Add css files.
 */
function star_networking_admin_css_file_star_networking_settings()
{
    wp_register_script('star-networking-admin-style-' . PAGE_NAME_STAR_NETWORK_SETTINGS, PAGE_URI_PAGE_PATH_STAR_NETWORK_SETTINGS . 'css/' . PAGE_NAME_STAR_NETWORK_SETTINGS . '.css');
    wp_enqueue_style('star-networking-admin-style-' . PAGE_NAME_STAR_NETWORK_SETTINGS, PAGE_URI_PAGE_PATH_STAR_NETWORK_SETTINGS . 'css/' . PAGE_NAME_STAR_NETWORK_SETTINGS . '.css');
}

/**
 * Add js files.
 */
function star_networking_admin_js_file_star_networking_settings()
{
    wp_register_script('star-networking-admin-js-' . PAGE_NAME_STAR_NETWORK_SETTINGS, PAGE_URI_PAGE_PATH_STAR_NETWORK_SETTINGS . 'js/' . PAGE_NAME_STAR_NETWORK_SETTINGS . '.js');
    wp_enqueue_script('star-networking-admin-js-' . PAGE_NAME_STAR_NETWORK_SETTINGS, PAGE_URI_PAGE_PATH_STAR_NETWORK_SETTINGS . 'js/' . PAGE_NAME_STAR_NETWORK_SETTINGS . '.js');
}

add_action('admin_enqueue_scripts', 'star_networking_admin_css_file_' . PAGE_NAME_STAR_NETWORK_SETTINGS);
add_action('admin_enqueue_scripts', 'star_networking_admin_js_file_' . PAGE_NAME_STAR_NETWORK_SETTINGS);

function star_networking_settings()
{

    global $themename;

    if (isset($_POST[$themename . '_menu_submit'])) {

        $new_values = array(

            $themename . '_slider_images' => $_POST[$themename . '_images'],
        );
        update_option($themename . '_theme_options_menu', $new_values);

        register_setting('star_networking-home-page-group', 'star_networking-home-page-bg-image');
        register_setting('star_networking-home-page-group', 'star-network-homepage-bg-text-title');
        register_setting('star_networking-home-page-group', 'star-network-homepage-bg-text-text');
        register_setting('star_networking-home-page-group', 'star-network-homepage-statistic-publications-number');
        register_setting('star_networking-home-page-group', 'star-network-homepage-statistic-annual-events-number');
        register_setting('star_networking-home-page-group', 'star-network-homepage-statistic-unique-visitors-number');
        register_setting('star_networking-home-page-group', 'star-network-form-email');
        register_setting('star_networking-home-page-group', 'star-network-form-email-send-success');
        register_setting('star_networking-home-page-group', 'star-network-people-title-on-event-page');
        register_setting('star_networking-home-page-group', 'star-network-footer-info-telephone-number');
        register_setting('star_networking-home-page-group', 'star-network-footer-info-copyright-info');
        register_setting('star_networking-home-page-group', 'star-network-event-single-adrotate');
        register_setting('star_networking-home-page-group', 'star-network-sidebar-choice-5-ads-or-long-pic');
        register_setting('star_networking-home-page-group', 'star-network-listing-single-adrotate-sponsored-story-long-pic');
        register_setting('star_networking-home-page-group', 'star-network-listing-single-adrotate-sponsored-story-1');
        register_setting('star_networking-home-page-group', 'star-network-listing-single-adrotate-sponsored-story-2');
        register_setting('star_networking-home-page-group', 'star-network-listing-single-adrotate-sponsored-story-3');
        register_setting('star_networking-home-page-group', 'star-network-listing-single-adrotate-sponsored-story-4');
        register_setting('star_networking-home-page-group', 'star-network-listing-single-adrotate-sponsored-story-5');
        register_setting('star_networking-home-page-group', 'select-leaderboard');
        register_setting('star_networking-home-page-group', 'popular-story-section-1');
        register_setting('star_networking-home-page-group', 'popular-story-section-2');
        register_setting('star_networking-home-page-group', 'select-adrotate-content-story');
    }


}

function schneps_get_adrotate_ads_data($id, $is_object = false)
{
    global $wpdb;
    $explodeId = explode(' ', $id);

    if(count($explodeId) > 1) {

        $id = $explodeId[1];

        if($explodeId[0] == 'Ad') {
            $select_description = $wpdb->get_row("SELECT * FROM `wp_adrotate` WHERE `id` = '" . $id . "' AND `type` != 'expired' AND `type` != 'error'");
            return array('ad_object' => $select_description, 'ad_id' => $id, 'type' => 'ad');
        } else {
            return schneps_get_adrotate_groups_ads_data($explodeId[1]);
        }
    } else {
        $id = $explodeId[0];
    }


    $select_description = $wpdb->get_row("SELECT * FROM `wp_adrotate` WHERE `id` = '" . $id . "' AND `type` != 'expired' AND `type` != 'error'");

    if (!$select_description) {
        $select_description = $wpdb->get_row("SELECT * FROM `wp_adrotate_groups` WHERE `id` = '" . $id . "'");

        if ($select_description) {
            return array('group_object' => $select_description, 'group_id' => $id, 'type' => 'group');
        }

        return false;
    }

    if (!$is_object) {
        return $select_description;
    }

    return array('ad_object' => $select_description, 'ad_id' => $id, 'type' => 'ad');
}

function schneps_get_adrotate_groups_ads_data($id)
{
    global $wpdb;

    $select_description = $wpdb->get_row("SELECT * FROM `wp_adrotate_groups` WHERE `id` = '" . $id . "'");

    if ($select_description) {
        return array('group_object' => $select_description, 'group_id' => $id, 'type' => 'group');
    }

    return false;
}

function schneps_get_event_by_date_array()
{
    $data = array();
    $not_sticky = array(
        'post_type' => array('event', 'post'),
        'order_by' => 'date',
        'order' => 'DESC',
        'posts_per_page' => 10000
    );

    $wp_query = new WP_Query($not_sticky);
    if ($wp_query->have_posts()) {
        while ($wp_query->have_posts()) {
            $wp_query->the_post();
            $data[] = array('value' => get_the_ID(), 'label' => get_the_title());

        }
    }
    wp_reset_query();

    return json_encode($data);
}

function schneps_get_people_for_event_array()
{
    $data = array();
    $not_sticky = array(
        'post_type' => array('people'),
        'orderby' => 'post_title',
        'order' => 'ASC',
        'posts_per_page' => 10000
    );

    $wp_query = new WP_Query($not_sticky);
    if ($wp_query->have_posts()) {
        while ($wp_query->have_posts()) {
            $wp_query->the_post();
            $data[] = array('value' => get_the_ID(), 'label' => get_the_title());

        }
    }
    wp_reset_query();

    return json_encode($data);
}

function schneps_get_adrotate_groups()
{
    global $wpdb;
    $select_description = $wpdb->get_results("SELECT * FROM `wp_adrotate_groups`");

    return $select_description;
}

function schneps_get_adrotate_ads()
{
    global $wpdb;
    $select_description = $wpdb->get_results("SELECT * FROM `wp_adrotate` WHERE `type` != 'expired' AND `type` != 'error'");

    return $select_description;
}

function schneps_get_sponsor_for_event_array()
{
    $data = array();
    $not_sticky = array(
        'post_type' => array('sponsor'),
        'order_by' => 'date',
        'order' => 'DESC',
    );

    $wp_query = new WP_Query($not_sticky);
    if ($wp_query->have_posts()) {
        while ($wp_query->have_posts()) {
            $wp_query->the_post();
            $data[] = array('value' => get_the_ID(), 'label' => get_the_title());

        }
    }
    wp_reset_query();

    return json_encode($data);
}


function star_networking_settings_page()
{
    global $themename;

    if (get_option($themename . '_theme_options_menu')) {
        $theme_options_menu = get_option($themename . '_theme_options_menu');
    } else {
        add_option($themename . '_theme_options_menu', array());
        $theme_options_menu = get_option($themename . '_theme_options_menu');
    }

    $star_network_homepage_statistic_publications_number = get_option('star-network-homepage-statistic-publications-number');
    $star_network_homepage_statistic_annual_events_number = get_option('star-network-homepage-statistic-annual-events-number');
    $star_network_homepage_statistic_unique_visitors_number = get_option('star-network-homepage-statistic-unique-visitors-number');
    $star_network_form_email = get_option('star-network-form-email');
    $star_network_form_email_send_success = get_option('star-network-form-email-send-success');
    $star_network_people_title_on_event_page = get_option('star-network-people-title-on-event-page');
    $star_network_footer_info_telephone_number = get_option('star-network-footer-info-telephone-number');
    $star_network_footer_info_copyright_info = get_option('star-network-footer-info-copyright-info');

    $schneps_settings_single_listing_adrotate = get_option('star-network-listing-single-adrotate');
    $schneps_settings_star_network_sidebar_choice_5_ads_or_long_pic = get_option('star-network-sidebar-choice-5-ads-or-long-pic');
    $schneps_settings_single_listing_adrotate_sponsored_story_long_pic = get_option('star-network-listing-single-adrotate-sponsored-story-long-pic');
    $schneps_settings_single_listing_adrotate_sponsored_story_1 = get_option('star-network-listing-single-adrotate-sponsored-story-1');
    $schneps_settings_single_listing_adrotate_sponsored_story_2 = get_option('star-network-listing-single-adrotate-sponsored-story-2');
    $schneps_settings_single_listing_adrotate_sponsored_story_3 = get_option('star-network-listing-single-adrotate-sponsored-story-3');
    $schneps_settings_single_listing_adrotate_sponsored_story_4 = get_option('star-network-listing-single-adrotate-sponsored-story-4');
    $schneps_settings_single_listing_adrotate_sponsored_story_5 = get_option('star-network-listing-single-adrotate-sponsored-story-5');
    $popular_story_section_1 = get_option('popular-story-section-1');
    $popular_story_section_2 = get_option('popular-story-section-2');

    $schneps_settings_star_network_sidebar_choice_5_ads_or_long_pic  = (empty($schneps_settings_star_network_sidebar_choice_5_ads_or_long_pic) ? '5-pic' : $schneps_settings_star_network_sidebar_choice_5_ads_or_long_pic);
    ?>
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    <script>
        $(function () {
            var availableTags = <?php echo schneps_get_event_by_date_array();?>;
            $(document).on("focus keyup", "input.event", function (event) {
                $(this).autocomplete({
                    source: availableTags,
                    select: function (event, ui) {
                        event.preventDefault();
                        this.value = ui.item.label;
                        $(this).next().val(ui.item.value);
                    },
                    focus: function (event, ui) {
                        event.preventDefault();
                        this.value = ui.item.label;
                        $(this).next().val(ui.item.value);
                    }
                });
            });

            toggleSkyscraper5ads($('[name=star-network-sidebar-choice-5-ads-or-long-pic]:checked'));
            $('[name=star-network-sidebar-choice-5-ads-or-long-pic]').on('change', function(){
                toggleSkyscraper5ads($(this));
            });
            function toggleSkyscraper5ads(el) {
                var lsb = {'long-pic' : '5-pic', '5-pic' : 'long-pic'};
                $('.'+ lsb[$(el).val()]).hide();
                $('.'+ $(el).val()).show();
            }
        });
    </script>

    <h2 class="nav-tab-wrapper schneps-curation-tabs">
        <a href="#" class="nav-tab" data-page="home-page">Star Network Page</a>
        <a href="#" class="nav-tab nav-tab-active" data-page="home-page-carousel">Star Network Carousel</a>
        <a href="#" class="nav-tab" data-page="category-adrotate">Adrotate Settings</a>
    </h2>
    <div class="wrap schneps-settings general-settings">
        <?php
        $adrotate_ads = schneps_get_adrotate_ads();
        $adrotate_groups = schneps_get_adrotate_groups();
        $adrotate_merged = array_merge($adrotate_ads, $adrotate_groups);
        ?>

        <form method="post" class="" action="options.php">
            <p class="submit">
                <input type="submit" class="button-primary" name="<?php echo $themename . '_menu_submit' ?>"
                       value="<?php _e('Save Changes') ?>"/>
            </p>

            <div class="js-star-network slideshow">
                <div class="hide home-page divider-settings">
                    <h2>Star Network Homepage Manager</h2>
                    <?php settings_fields('star_networking-home-page-group'); ?>

                        <div class="star-network-form-email">
                            <div class="email">
                                <label for="form-email">
                                    Form Email:
                                </label>
                                <input type="text" class="form-email" id="form-email"
                                       name="star-network-form-email"
                                       value="<?php echo $star_network_form_email; ?>"/>
                            </div>

                            <div class="Success">
                                <label for="form-email-success">
                                    Success:
                                </label>
                                <input type="text" class="form-email-success" id="form-email-success"
                                       name="star-network-form-email-send-success"
                                       value="<?php echo $star_network_form_email_send_success; ?>"/>
                            </div>

                            <div class="people-title-on-event-page">
                                <label for="people-title-on-event-page">
                                    People on event page:
                                </label>
                                <input type="text" class="form-email-success" id="people-title-on-event-page"
                                       name="star-network-people-title-on-event-page"
                                       value="<?php echo $star_network_people_title_on_event_page; ?>"/>
                            </div>

                        </div>

                        <div class="star-network-homepage-statistic">
                            <div class="publications-wrapper">
                                <label for="publications-number">
                                    Publications:
                                </label>
                                <input type="text" class="publications-number" id="publications-number"
                                       name="star-network-homepage-statistic-publications-number"
                                       value="<?php echo $star_network_homepage_statistic_publications_number; ?>"/>
                            </div>
                            <div class="annual-events-wrapper">
                                <label for="annual-events-number">
                                    Annual Events:
                                </label>
                                <input type="text" class="annual-events-number" id="annual-events-number"
                                       name="star-network-homepage-statistic-annual-events-number"
                                       value="<?php echo $star_network_homepage_statistic_annual_events_number; ?>"/>
                            </div>
                            <div class="unique-visitors-wrapper">
                                <label for="unique-visitors-number">
                                    Unique Visitors:
                                </label>
                                <input type="text" class="unique-visitors-number" id="unique-visitors-number"
                                       name="star-network-homepage-statistic-unique-visitors-number"
                                       value="<?php echo $star_network_homepage_statistic_unique_visitors_number; ?>"/>
                            </div>
                        </div>
                        <br /><br />
                        <div class="adrotate-map-admin">
                            <div>
                                <?php
                                $adrotate_link = get_option('select-adrotate-content-story');
                                $adrotate_show_as = get_option('show-adrotate-content-story');

                                for ($i = 1; $i <= 4; $i++): ?>
                                    <div>
                                        <label>
                                            <?php echo $i; ?>
                                        </label>
                                        <select name="select-adrotate-content-story[<?php echo $i; ?>]" id="">
                                            <option value="">no selection</option>
                                            <?php foreach ($adrotate_merged as $adrotate_ad): ?>
                                                <?php if ($adrotate_ad->title && $adrotate_ad->title !== ''): ?>
                                                    <option
                                                        value="Ad <?php echo $adrotate_ad->id; ?>" <?php echo $adrotate_link[$i] == 'Ad ' . $adrotate_ad->id ? 'selected' : ''; ?> >
                                                        Ad <?php echo $adrotate_ad->id; ?></option>
                                                <?php else: ?>
                                                    <option
                                                        value="Group <?php echo $adrotate_ad->id; ?>" <?php echo $adrotate_link[$i] == 'Group ' . $adrotate_ad->id ? 'selected' : ''; ?> >
                                                        Group <?php echo $adrotate_ad->name; ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <br/>
                                <?php endfor; ?>
                            </div>
                            <img src="<?php echo get_template_directory_uri(); ?>/img/adrotatemap.png">
                            <div class="clear"></div>
                        </div>
                        <div class="clear"></div>
                        <hr/>
                        <h2>Star Network Footer Info</h2>
                        <div class="star-network-footer-info">
                            <div class="telephone-wrapper">
                                <label for="telephone-number">
                                    Telephone:
                                </label>
                                <input type="text" class="telephone-number" id="telephone-number"
                                       name="star-network-footer-info-telephone-number"
                                       value="<?php echo $star_network_footer_info_telephone_number; ?>"/>
                            </div>
                            <div class="copyright-wrapper">
                                <label for="copyright-info">
                                    Copyright:
                                </label>
                                <input type="text" class="copyright-info" id="copyright-info"
                                       name="star-network-footer-info-copyright-info"
                                       value="<?php echo $star_network_footer_info_copyright_info; ?>"/>
                            </div>
                        </div>
                    </div>


                    <!-- This is carousel wrapper in admin area for homepage. Start -->

                    <div class="divider-settings home-page-carousel">
                        <div id="div_inputs">
                            <?php if ($theme_options_menu[$themename . '_slider_images']): ?>
                                <?php foreach ($theme_options_menu[$themename . '_slider_images'] as $key => $val): ?>
                                    <div data-id="<?php echo $key; ?>" class="js-image-parent-block image-parent-block">
                                        <span class="js-delete-button delete-button"></span>

                                        <div class="media-block">
                                            <div class="gallery-text text">Picture</div>
                                            <div class="gallery-input input">
                                                <a class="button choose-from-library-link" href="#"
                                                   data-update-link="<?php echo $key; ?>">Open Media Library</a>

                                                <div class="select-image-description">
                                                    Choose your image, then click "Select" to apply it.
                                                </div>
                                                <?php $style = $val['url'] ? 'inline-block' : 'none'; ?>
                                                <input class="imgUrl" style="display: <?php echo $style; ?>;"
                                                       id="inp<?php echo $key; ?>" type="text"
                                                       name="<?php echo $themename; ?>_images[<?php echo $key; ?>][url]"
                                                       value="<?php echo $val['url']; ?>"/>
                                            </div>
                                        </div>
                                        <div class="link-block hide">
                                            <div class="link-text text">Link</div>
                                            <div class="link-input input">
                                                <input class="link" id="inp<?php echo $key; ?>" type="hidden"
                                                       name="<?php echo $themename; ?>_images[<?php echo $key; ?>][link]"
                                                       value="<?php echo $val['link']; ?>" placeholder="http://"/></div>
                                        </div>


                                        <!--                                    <div class="ui-widget">-->
                                        <!--                                        <label for="tags">Tags: </label>-->
                                        <!--                                        <input id="tags">-->
                                        <!--                                    </div>-->


                                        <div class="event-block">
                                            <div class="link-text text">Event:</div>
                                            <div class="event-input input">
                                                <input class="event" id="inp<?php echo $key; ?>" type="text"
                                                       name="<?php echo $themename; ?>_images[<?php echo $key; ?>][event]"
                                                       value="<?php echo $val['event']; ?>" placeholder="Event or Post"/>

                                                <input class="event_id" id="inp<?php echo $key; ?>" type="hidden"
                                                       name="<?php echo $themename; ?>_images[<?php echo $key; ?>][event_id]"
                                                       value="<?php echo $val['event_id']; ?>"/>
                                            </div>
                                        </div>


                                        <div class="sortable-handler"></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <span id="add-new-slide" class="round-button">Add New Slide</span>
                    </div>

                    <!-- This is carousel wrapper in admin area for homepage. End -->

                    <div class="hide category-adrotate divider-settings">
                    <h1>Adrotate Setting Page</h1>
                    
                    <?php
                    $leaderboard_selected = get_option('select-leaderboard');
                    ?>
                    <table class="form-table">
                        <tr>
                            <td>
                                <label>
                                    Leaderboard
                                </label>
                                <select name="select-leaderboard" id="">
                                    <option value="">no selection</option>
                                    <?php foreach ($adrotate_merged as $adrotate_ad): ?>
                                        <?php if ($adrotate_ad->title && $adrotate_ad->title !== ''): ?>
                                            <option
                                                value="Ad <?php echo $adrotate_ad->id; ?>" <?php echo $leaderboard_selected == 'Ad '.$adrotate_ad->id ? 'selected' : ''; ?> >
                                                Ad <?php echo $adrotate_ad->id; ?></option>
                                        <?php else: ?>
                                            <option
                                                value="Group <?php echo $adrotate_ad->id; ?>" <?php echo $leaderboard_selected == 'Group ' . $adrotate_ad->id ? 'selected' : ''; ?> >
                                                Group <?php echo $adrotate_ad->name; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>
                                    Popular story section 1
                                </label>
                                <select name="popular-story-section-1" id="">
                                    <option value="">no selection</option>
                                    <?php foreach ($adrotate_merged as $adrotate_ad): ?>
                                        <?php if ($adrotate_ad->title && $adrotate_ad->title !== ''): ?>
                                            <option
                                                value="Ad <?php echo $adrotate_ad->id; ?>" <?php echo $popular_story_section_1 == 'Ad ' . $adrotate_ad->id ? 'selected' : ''; ?> >
                                                Ad <?php echo $adrotate_ad->id; ?></option>
                                        <?php else: ?>
                                            <option
                                                value="Group <?php echo $adrotate_ad->id; ?>" <?php echo $popular_story_section_1 == 'Group ' . $adrotate_ad->id ? 'selected' : ''; ?> >
                                                Group <?php echo $adrotate_ad->name; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label>
                                    Popular story section 2
                                </label>
                                <select name="popular-story-section-2" id="">
                                    <option value="">no selection</option>
                                    <?php foreach ($adrotate_merged as $adrotate_ad): ?>
                                        <?php if ($adrotate_ad->title && $adrotate_ad->title !== ''): ?>
                                            <option
                                                value="Ad <?php echo $adrotate_ad->id; ?>" <?php echo $popular_story_section_2 == 'Ad ' . $adrotate_ad->id ? 'selected' : ''; ?> >
                                                Ad <?php echo $adrotate_ad->id; ?></option>
                                        <?php else: ?>
                                            <option
                                                value="Group <?php echo $adrotate_ad->id; ?>" <?php echo $popular_story_section_2 == 'Group ' . $adrotate_ad->id ? 'selected' : ''; ?> >
                                                Group <?php echo $adrotate_ad->name; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                    <br />
                    <br />

                    <fieldset class="adrotate-settings-fset">
                        <legend>Left Sidebar</legend>

                        <table class="form-table">
                            <tr>
                                <td colspan="3">
                                    <input type="radio" name="star-network-sidebar-choice-5-ads-or-long-pic" id="star-network-sidebar-choice-5-ads-or-long-pic-5-pic" value="5-pic" <?php echo $schneps_settings_star_network_sidebar_choice_5_ads_or_long_pic == '5-pic' ? 'checked="checked"': ''?> />
                                    <label for="star-network-sidebar-choice-5-ads-or-long-pic-5-pic">Use 5 pictures</label>
                                    &nbsp;&nbsp;
                                    <input type="radio" name="star-network-sidebar-choice-5-ads-or-long-pic" id="star-network-sidebar-choice-5-ads-or-long-pic-long-pic" value="long-pic" <?php echo $schneps_settings_star_network_sidebar_choice_5_ads_or_long_pic == 'long-pic' ? 'checked="checked"': ''?>/>
                                    <label for="star-network-sidebar-choice-5-ads-or-long-pic-long-pic">Use Long Picture</label>
                                </td>
                                <td>
                                </td>
                            </tr>
                            <tr class="long-pic">
                                <td colspan="3">
                                    <h2>Sponsored Story for left sidebar skyscraper</h2>
                                </td>
                                <td>
                                    <select name="star-network-listing-single-adrotate-sponsored-story-long-pic" id="">
                                        <option value="0">no selection</option>
                                        <?php foreach ($adrotate_merged as $adrotate_ad): ?>
                                            <?php if ($adrotate_ad->title && $adrotate_ad->title !== ''): ?>
                                                <option
                                                    value="Ad <?php echo $adrotate_ad->id; ?>" <?php echo $schneps_settings_single_listing_adrotate_sponsored_story_long_pic == 'Ad ' . $adrotate_ad->id ? 'selected' : ''; ?> >
                                                    Ad <?php echo $adrotate_ad->id; ?></option>
                                            <?php else: ?>
                                                <option
                                                    value="Group <?php echo $adrotate_ad->id; ?>" <?php echo $schneps_settings_single_listing_adrotate_sponsored_story_long_pic == 'Group ' . $adrotate_ad->id ? 'selected' : ''; ?> >
                                                    Group <?php echo $adrotate_ad->name; ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>

                                </td>
                            </tr>

                            <tr class="5-pic">
                                <td colspan="3">
                                    <h2>Sponsored Story for left sidebar 1</h2>
                                </td>
                                <td>
                                    <select name="star-network-listing-single-adrotate-sponsored-story-1" id="">
                                        <option value="0">no selection</option>
                                        <?php foreach ($adrotate_merged as $adrotate_ad): ?>
                                            <?php if ($adrotate_ad->title && $adrotate_ad->title !== ''): ?>
                                                <option
                                                    value="Ad <?php echo $adrotate_ad->id; ?>" <?php echo $schneps_settings_single_listing_adrotate_sponsored_story_1 == 'Ad ' . $adrotate_ad->id ? 'selected' : ''; ?> >
                                                    Ad <?php echo $adrotate_ad->id; ?></option>
                                            <?php else: ?>
                                                <option
                                                    value="Group <?php echo $adrotate_ad->id; ?>" <?php echo $schneps_settings_single_listing_adrotate_sponsored_story_1 == 'Group ' . $adrotate_ad->id ? 'selected' : ''; ?> >
                                                    Group <?php echo $adrotate_ad->name; ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>

                                </td>
                            </tr>

                            <tr class="5-pic">
                                <td colspan="3">
                                    <h2>Sponsored Story for left sidebar 2</h2>
                                </td>
                                <td>
                                    <select name="star-network-listing-single-adrotate-sponsored-story-2" id="">
                                        <option value="0">no selection</option>
                                        <?php foreach ($adrotate_merged as $adrotate_ad): ?>
                                            <?php if ($adrotate_ad->title && $adrotate_ad->title !== ''): ?>
                                                <option
                                                    value="Ad <?php echo $adrotate_ad->id; ?>" <?php echo $schneps_settings_single_listing_adrotate_sponsored_story_2 == 'Ad ' . $adrotate_ad->id ? 'selected' : ''; ?> >
                                                    Ad <?php echo $adrotate_ad->id; ?></option>
                                            <?php else: ?>
                                                <option
                                                    value="Group <?php echo $adrotate_ad->id; ?>" <?php echo $schneps_settings_single_listing_adrotate_sponsored_story_2 == 'Group ' . $adrotate_ad->id ? 'selected' : ''; ?> >
                                                    Group <?php echo $adrotate_ad->name; ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>

                                </td>
                            </tr>

                            <tr class="5-pic">
                                <td colspan="3">
                                    <h2>Sponsored Story for left sidebar 3</h2>
                                </td>
                                <td>
                                    <select name="star-network-listing-single-adrotate-sponsored-story-3" id="">
                                        <option value="0">no selection</option>
                                        <?php foreach ($adrotate_merged as $adrotate_ad): ?>
                                            <?php if ($adrotate_ad->title && $adrotate_ad->title !== ''): ?>
                                                <option
                                                    value="Ad <?php echo $adrotate_ad->id; ?>" <?php echo $schneps_settings_single_listing_adrotate_sponsored_story_3 == 'Ad ' . $adrotate_ad->id ? 'selected' : ''; ?> >
                                                    Ad <?php echo $adrotate_ad->id; ?></option>
                                            <?php else: ?>
                                                <option
                                                    value="Group <?php echo $adrotate_ad->id; ?>" <?php echo $schneps_settings_single_listing_adrotate_sponsored_story_3 == 'Group ' . $adrotate_ad->id ? 'selected' : ''; ?> >
                                                    Group <?php echo $adrotate_ad->name; ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>

                                </td>
                            </tr>

                            <tr class="5-pic">
                                <td colspan="3">
                                    <h2>Sponsored Story for left sidebar 4</h2>
                                </td>
                                <td>
                                    <select name="star-network-listing-single-adrotate-sponsored-story-4" id="">
                                        <option value="0">no selection</option>
                                        <?php foreach ($adrotate_merged as $adrotate_ad): ?>
                                            <?php if ($adrotate_ad->title && $adrotate_ad->title !== ''): ?>
                                                <option
                                                    value="Ad <?php echo $adrotate_ad->id; ?>" <?php echo $schneps_settings_single_listing_adrotate_sponsored_story_4 == 'Ad ' . $adrotate_ad->id ? 'selected' : ''; ?> >
                                                    Ad <?php echo $adrotate_ad->id; ?></option>
                                            <?php else: ?>
                                                <option
                                                    value="Group <?php echo $adrotate_ad->id; ?>" <?php echo $schneps_settings_single_listing_adrotate_sponsored_story_4 == 'Group ' . $adrotate_ad->id ? 'selected' : ''; ?> >
                                                    Group <?php echo $adrotate_ad->name; ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>

                                </td>
                            </tr>

                            <tr class="5-pic">
                                <td colspan="3">
                                    <h2>Sponsored Story for left sidebar 5</h2>
                                </td>
                                <td>
                                    <select name="star-network-listing-single-adrotate-sponsored-story-5" id="">
                                        <option value="0">no selection</option>
                                        <?php foreach ($adrotate_merged as $adrotate_ad): ?>
                                            <?php if ($adrotate_ad->title && $adrotate_ad->title !== ''): ?>
                                                <option
                                                    value="Ad <?php echo $adrotate_ad->id; ?>" <?php echo $schneps_settings_single_listing_adrotate_sponsored_story_5 == 'Ad ' . $adrotate_ad->id ? 'selected' : ''; ?> >
                                                    Ad <?php echo $adrotate_ad->id; ?></option>
                                            <?php else: ?>
                                                <option
                                                    value="Group <?php echo $adrotate_ad->id; ?>" <?php echo $schneps_settings_single_listing_adrotate_sponsored_story_5 == 'Group ' . $adrotate_ad->id ? 'selected' : ''; ?> >
                                                    Group <?php echo $adrotate_ad->name; ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>

                                </td>
                            </tr>
                        </table>
                    </fieldset>
                </div>
            </div>
        </form>
    </div>

<?php } ?>