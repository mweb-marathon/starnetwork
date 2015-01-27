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
    add_menu_page('Star Network Settings', 'Star Network Manager', 'administrator', __FILE__, 'star_networking_settings_page');

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
    }


}


function schneps_get_event_by_date_array()
{
    $data = array();
    $not_sticky = array(
        'post_type' => array('event', 'post'),
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

function schneps_get_people_for_event_array() {
    $data = array();
    $not_sticky = array(
        'post_type' => array('people'),
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

function schneps_get_sponsor_for_event_array() {
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

            })
        });
    </script>



    <h2 class="nav-tab-wrapper schneps-curation-tabs">
        <a href="#" class="nav-tab" data-page="home-page">Star Network Page</a>
        <a href="#" class="nav-tab nav-tab-active" data-page="home-page-carousel">Star Network Carousel</a>
    </h2>
    <div class="wrap schneps-settings general-settings">
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
                                                   value="<?php echo $val['event']; ?>" placeholder="Event"/>

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


            </div>
        </form>
    </div>

<?php } ?>