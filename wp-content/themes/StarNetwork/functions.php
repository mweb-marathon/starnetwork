<?php

/**
 * @author Maxim Bogdanov <sin666m4a1fox@gmail.com>
 * @copyright maxim 7/24/14 | 12:00 PM
 */
$theme_directory = get_stylesheet_directory();
require_once($theme_directory . '/additional/star_networking_settings/star_networking_settings.php');
require_once($theme_directory . '/classes/change_submenu_ul_class.php');
require_once($theme_directory . '/additional/meta_box/meta_box.php');
require_once($theme_directory . '/inc/extra/extra_function.php');


function wp_gear_manager_admin_scripts()
{
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_enqueue_script('jquery');
}

function wp_gear_manager_admin_styles()
{
    wp_enqueue_style('thickbox');
}

add_action('init', 'add_excerpts_to_pages');
function add_excerpts_to_pages()
{
    add_post_type_support('page', 'excerpt');
}

/**
 * Register our sidebars and widgetized areas.
 *
 */
function _widgets_init()
{

    register_sidebar();
}


add_action('widgets_init', '_widgets_init');

add_action('admin_print_scripts', 'wp_gear_manager_admin_scripts');
add_action('admin_print_styles', 'wp_gear_manager_admin_styles');
/* Functionality for home page. Start */


function schneps_get_menu($name, $menu_class = '', $container = 'div', $echo = true, $after = '')
{
    $defaults = array(
        'theme_location' => '',
        'menu' => $name,
        'container' => $container,
        'container_class' => '',
        'container_id' => '',
        'menu_class' => $menu_class,
        'menu_id' => '',
        'echo' => $echo,
        'fallback_cb' => 'wp_page_menu',
        'before' => '',
        'after' => $after,
        'link_before' => '',
        'link_after' => '',
        'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
        'depth' => 0,
        'walker' => ''
    );

    return $defaults;
}

function schneps_get_header_social_menu()
{
    return wp_nav_menu(schneps_get_menu('Main Social', 'inline-list'));
}

function schneps_get_footer_top_menu()
{
    return wp_nav_menu(schneps_get_menu('Main Footer', 'inline-list', 'div', false));
}

function schneps_get_footer_second_menu()
{
    return wp_nav_menu(schneps_get_menu('Main Footer Left', 'inline-list default-red', 'div', false));
}

function schneps_get_star_network_footer_second_menu()
{
    return wp_nav_menu(schneps_get_menu('Star Network Menu Main Footer Left', 'inline-list default-red', 'div', false));
}


function schneps_get_header_right_menu()
{
    return wp_nav_menu(schneps_get_menu('Main Right Header Menu', '', 'div', false));
}

function schneps_get_header_main_menu()
{
    $menuClass = 'left primary-menu';
    $primaryNav = '';
    if (function_exists('wp_nav_menu')) {
        $primaryNav = wp_nav_menu(
            array(
                'theme_location' => 'primary-menu',
                'container' => '',
                'fallback_cb' => '',
                'menu_class' => $menuClass,
                'echo' => false,
                'walker' => new change_submenu_ul_class(),
            )
        );
    };
    return $primaryNav;
}

function schneps_get_header_main_star_network_menu()
{
    $menuClass = 'left primary-menu';
    $primaryNav = '';
    if (function_exists('wp_nav_menu')) {
        $primaryNav = wp_nav_menu(
            array(
                'theme_location' => 'primary-menu',
                'container' => '',
                'menu' => 'Star Network Menu',
                'fallback_cb' => '',
                'menu_class' => $menuClass,
                'echo' => false,
                'walker' => new change_submenu_ul_class(),
            )
        );
    };
    return $primaryNav;
}


/* Functionality for home page. End */

function schneps_get_event_by_date($template = false, $post_per_page = 6, $paged = 1, $category_name = false)
{
    $not_sticky = array(
        'post_type' => array('event'),
        'posts_per_page' => $post_per_page,
        'order_by' => 'date',
        'order' => 'DESC',
        'paged' => $paged,
        'post_status' => 'publish'
    );

    if ($category_name && $category_name !== 'all') {
        $not_sticky['taxonomy'] = 'event-categories';
        $not_sticky['term'] = $category_name;
    }

    $wp_query_not_sticky = new WP_Query($not_sticky);
    if ($wp_query_not_sticky->have_posts()) {
        while ($wp_query_not_sticky->have_posts()) {
            $wp_query_not_sticky->the_post();

            if ($template) {
                get_template_part('includes/event/' . $template . '-block');
            } else {
                get_template_part('includes/single-block');
            }
        }
    }
    wp_reset_query();
}


function schneps_get_posts_by_date($template = false, $post_per_page = 6, $category_name = false)
{
    $not_sticky = array(
        'post_type' => array('post'),
        'posts_per_page' => $post_per_page,
        'order_by' => 'date',
        'order' => 'DESC',
        'post_status' => 'publish'
    );

    $wp_query_not_sticky = new WP_Query($not_sticky);
    if ($wp_query_not_sticky->have_posts()) {
        while ($wp_query_not_sticky->have_posts()) {
            $wp_query_not_sticky->the_post();

            if ($template) {
                get_template_part('includes/event/' . $template . '-block');
            } else {
                get_template_part('includes/single-block');
            }
        }
    }
    wp_reset_query();
}


function get_image_for_spot()
{
    $thumb = '';
    $width = 360;
    $height = 180;
    $classtext = '';
    $titletext = get_the_title();

    $thumbnail = get_thumbnail($width, $height, $classtext, $titletext, $titletext);
    $thumb = $thumbnail["thumb"];

    ?>
    <a href="<?php echo get_the_permalink(); ?>">
        <?php if ($thumb): ?>
            <?php print_thumbnail($thumb, true, $titletext, $width, $height, $classtext); ?>
        <?php else: ?>
            <div class="no-image">
                No Image
            </div>
        <?php endif; ?>
    </a>
<?php
}

function get_image_for_sponsor_people()
{
    $thumb = '';
    $width = 360;
    $height = 180;
    $classtext = '';
    $titletext = get_the_title();

    $thumbnail = get_thumbnail($width, $height, $classtext, $titletext, $titletext);
    $thumb = $thumbnail["thumb"];

    ?>

    <?php if ($thumb): ?>
    <?php print_thumbnail($thumb, true, $titletext, $width, $height, $classtext); ?>
<?php else: ?>
    <div class="no-image">
        No Image
    </div>
<?php endif; ?>

<?php
}

/* this function gets thumbnail from Post Thumbnail or Custom field or First post image */
if (!function_exists('get_thumbnail')) {
    function get_thumbnail($width = 100, $height = 100, $class = '', $alttext = '', $titletext = '', $fullpath = false, $custom_field = '', $post = '')
    {
        if ($post == '') global $post;
        global $shortname;

        $thumb_array['thumb'] = '';
        $thumb_array['use_timthumb'] = true;
        if ($fullpath) $thumb_array['fullpath'] = ''; //full image url for lightbox

        $new_method = true;

        if (has_post_thumbnail($post->ID)) {
            $thumb_array['use_timthumb'] = false;

            $et_fullpath = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
            $thumb_array['fullpath'] = $et_fullpath[0];
            $thumb_array['thumb'] = $thumb_array['fullpath'];
        }

        if ($thumb_array['thumb'] == '') {
            if ($custom_field == '') $thumb_array['thumb'] = esc_attr(get_post_meta($post->ID, 'Thumbnail', $single = true));
            else {
                $thumb_array['thumb'] = esc_attr(get_post_meta($post->ID, $custom_field, $single = true));
                if ($thumb_array['thumb'] == '') $thumb_array['thumb'] = esc_attr(get_post_meta($post->ID, 'Thumbnail', $single = true));
            }

            if (($thumb_array['thumb'] == '') && ((et_get_option($shortname . '_grab_image')) == 'on')) {
                $thumb_array['thumb'] = esc_attr(et_first_image());
                if ($fullpath) $thumb_array['fullpath'] = $thumb_array['thumb'];
            }

            #if custom field used for small pre-cropped image, open Thumbnail custom field image in lightbox
            if ($fullpath) {
                $thumb_array['fullpath'] = $thumb_array['thumb'];
                if ($custom_field == '') $thumb_array['fullpath'] = apply_filters('et_fullpath', et_path_reltoabs(esc_attr($thumb_array['thumb'])));
                elseif ($custom_field <> '' && get_post_meta($post->ID, 'Thumbnail', $single = true)) $thumb_array['fullpath'] = apply_filters('et_fullpath', et_path_reltoabs(esc_attr(get_post_meta($post->ID, 'Thumbnail', $single = true))));
            }
        }

        return $thumb_array;
    }
}

/* this function prints thumbnail from Post Thumbnail or Custom field or First post image */
if (!function_exists('print_thumbnail')) {
    function print_thumbnail($thumbnail = '', $use_timthumb = true, $alttext = '', $width = 100, $height = 100, $class = '', $echoout = true, $forstyle = false, $resize = true, $post = '', $et_post_id = '')
    {
        if (is_array($thumbnail)) {
            extract($thumbnail);
        }

        if ($post == '') global $post, $et_theme_image_sizes;

        $output = '';

        $et_post_id = '' != $et_post_id ? (int)$et_post_id : $post->ID;

        if (has_post_thumbnail($et_post_id)) {
            $thumb_array['use_timthumb'] = false;

            $image_size_name = $width . 'x' . $height;
            $et_size = isset($et_theme_image_sizes) && array_key_exists($image_size_name, $et_theme_image_sizes) ? $et_theme_image_sizes[$image_size_name] : array($width, $height);

            $et_attachment_image_attributes = wp_get_attachment_image_src(get_post_thumbnail_id($et_post_id), $et_size);
            $thumbnail = $et_attachment_image_attributes[0];
        } else {
            $thumbnail_orig = $thumbnail;

            $thumbnail = et_multisite_thumbnail($thumbnail);

            $cropPosition = '';

            $allow_new_thumb_method = false;

            $new_method = true;
            $new_method_thumb = '';
            $external_source = false;

            $allow_new_thumb_method = !$external_source && $new_method && $cropPosition == '';

            if ($allow_new_thumb_method && $thumbnail <> '') {
                $et_crop = get_post_meta($post->ID, 'et_nocrop', true) == '' ? true : false;
                $new_method_thumb = et_resize_image(et_path_reltoabs($thumbnail), $width, $height, $et_crop);
                if (is_wp_error($new_method_thumb)) $new_method_thumb = '';
            }

            $thumbnail = $new_method_thumb;
        }

        if (false === $forstyle) {
            $output = '<img src="' . esc_url($thumbnail) . '"';

            if ($class <> '') $output .= " class='" . esc_attr($class) . "' ";

            $dimensions = apply_filters('et_print_thumbnail_dimensions', " width='" . esc_attr($width) . "' height='" . esc_attr($height) . "'");

            $output .= " alt='" . esc_attr(strip_tags($alttext)) . "'{$dimensions} />";

            if (!$resize) $output = $thumbnail;
        } else {
            $output = $thumbnail;
        }

        if ($echoout) echo $output;
        else return $output;
    }
}


function date2string(array $params)
{
    // do something to $copyright
    $date = DateTime::createFromFormat("Y-m-d", $params['string']);

    return $date->format($params['format']);
}

add_filter('filter_date', 'date2string');

function wp_calendarevents()
{
    $post_per_page = !empty($_POST['post_per_page']) ? $_POST['post_per_page'] : 6;
    $page = !empty($_POST['page']) ? $_POST['page'] : 0;
    $category_name = !empty($_POST['post_category_name']) ? $_POST['post_category_name'] : 'all';


    schneps_get_event_by_date('star_network', $post_per_page, $page, $category_name);
    exit;
}

add_action('wp_ajax_calendar_events', 'wp_calendarevents'); // for logged in user
add_action('wp_ajax_nopriv_calendar_events', 'wp_calendarevents');

function star_network_calendar_filter()
{
    $taxonomy = get_term_by('name', $_POST['label'], 'event-categories');
    star_network_attend_event_category($taxonomy->term_id);
    exit;
}

add_action('wp_ajax_star_network_calendar_filter', 'star_network_calendar_filter'); // for logged in user
add_action('wp_ajax_nopriv_star_network_calendar_filter', 'star_network_calendar_filter');


function star_network_send_form_email()
{
    $post = $_POST;

    array_pop($post);
    array_shift($post);

    $str = '';

    foreach ($post as $key => $item) {

        if (is_array($item)) {
            $item = implode(', ', $item);
        }
        $str .= $key . ": " . $item . "\n";
    }

    $subject = "Web Form Submission From: Star Network";
    $headers = 'From: admin@starnetwork.org' . "\r\n" .
        'Reply-To: do-not-reply@starnetwork.org' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
    mail(get_option('star-network-form-email'), $subject, $str, $headers);

    echo get_option('star-network-form-email-send-success');

    exit;
}

add_action('wp_ajax_star_network_send_form_email', 'star_network_send_form_email'); // for logged in user
add_action('wp_ajax_nopriv_star_network_send_form_email', 'star_network_send_form_email');

function star_network_attend_event_category($child_of = 6)
{
    $categories = get_categories(array('taxonomy' => 'event-categories', 'hide_empty' => 0, 'child_of' => $child_of));
    ?>
    <ul>
        <li class="current-category" data-category-all="all">
            <a href="#">all</a>
        </li>
        <?php foreach ($categories as $cat): ?>
            <li class="">
                <a href="#" data-category-name="<?php echo $cat->name; ?>"><?php echo $cat->name; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php
}

function get_event_map_($event_id, $limit = 2)
{
    global $wpdb;
//    $results = $wpdb->get_results("SELECT * FROM `wp_em_locations` WHERE `post_id` = '" . ($event_id+1) . "' LIMIT 1", OBJECT);
    $results = $wpdb->get_results("SELECT p.*, t.* FROM wp_em_events p RIGHT JOIN wp_em_locations t ON p.location_id = t.location_id WHERE p.post_id = '" . ($event_id) . "' LIMIT 1", OBJECT);

    if (count($results) > 0) {
        $address = $results[0]->location_address;
        $map_link = $results[0]->location_latitude . ', ' . $results[0]->location_longitude;


        ?>

        <div id="map_canvas" style="width:150px;height:150px;"></div>
        <div class="event-map-info">
            <div class="location-name">
                <?php echo $results[0]->location_name; ?>
            </div>
            <div class="location-address">
                <?php echo $results[0]->location_address; ?>
            </div>
            <span class="location-n">n</span>
            <span class="location-q">q</span>
        </div>


        <script>
            function initialize() {
                var myLatlng = new google.maps.LatLng(<?php echo $map_link; ?>);
                var address = "<?php echo $address; ?>";
                var mapOptions = {
                    zoom: 14,
                    center: myLatlng,
                    streetViewControl: false,
                    zoomControl: false,
                    mapTypeControl: false,
                    overviewMapControl: false
                };

                var iconBase = location.origin + '/wp-content/themes/StarNetwork/img/';

                var map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);

                var infowindow = new google.maps.InfoWindow({
                    content: address
                });

                var marker = new google.maps.Marker({
                    position: myLatlng,
                    map: map,
                    title: 'Hello World!',
                    icon: iconBase + 'schneps-map-marker.png'
                });

                google.maps.event.addListener(marker, 'click', function () {
                    infowindow.open(map, marker);
                });
//            infowindow.open(map, marker);
            }
            google.maps.event.addDomListener(window, 'load', initialize);
        </script>

    <?php
    }
}


/* Adds a box to the main column on the Post and Page edit screens */

/* Prints the box content */
function dynamic_inner_sponsor_box($post)
{
    wp_nonce_field(plugin_basename(__FILE__), 'dynamic_sponsor_noncename');
    ?>
    <div id="meta_inner_sponsor">
        <?php
        $sponsor_sponsor = get_post_meta($post->ID, 'event_sponsor', true);
        $c = 0;
        if (!empty($sponsor_sponsor) && count($sponsor_sponsor) > 0) {
            foreach ($sponsor_sponsor as $value) {
                if (isset($value['sponsor'])) {
                    printf('<p> Sponsor<input type="text" name="event_sponsor[%1$s][sponsor]" value="%2$s"><input type="text" name="event_sponsor[%1$s][id]" value="%3$s"/><span class="remove-sponsor">%4$s</span></p>', $c, $value['sponsor'], $value['id'], __('Remove Sponsor'));
                    $c = $c + 1;
                }
            }
        }
        ?>
        <span id="sponsor-sponsor"></span>
        <span class="add-sponsor"><?php _e('Add Sponsor'); ?></span>
        <script>
            var $ = jQuery.noConflict();
            $(document).ready(function () {
                $(function () {
                    var availableSponsor = <?php echo schneps_get_sponsor_for_event_array();?>;

                    $(document).on("focus keyup", "input.sponsor", function (event) {
                        $(this).autocomplete({
                            source: availableSponsor,
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


                var count = <?php echo $c; ?>;
                $(".add-sponsor").click(function () {
                    count = count + 1;

                    $('#sponsor-sponsor').append('<p> Sponsor <input type="text"  class="sponsor" name="event_sponsor[' + count + '][sponsor]" value="" class="sponsor" /><input type="text" name="event_sponsor[' + count + '][id]"><span class="remove-sponsor">Remove Sponsor</span></p>');
                    return false;
                });
                $(".remove-sponsor").live('click', function () {
                    $(this).parent().remove();
                });
            });
        </script>
    </div>
<?php
}

/* When the post is saved, saves our custom data */
function dynamic_save_sponsor_data($post_id)
{
    // verify if this is an auto save routine.
    // If it is our form has not been submitted, so we dont want to do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if (!isset($_POST['dynamic_sponsor_noncename']))
        return;

    if (!wp_verify_nonce($_POST['dynamic_sponsor_noncename'], plugin_basename(__FILE__)))
        return;

    // OK, we're authenticated: we need to find and save the data

    $place = $_POST['event_sponsor'];

    update_post_meta($post_id, 'event_sponsor', $place);
}


function get_single_event_additional_people_data($data)
{
    $ids = array();
    if ($data) {
        foreach ($data as $value) {
            $ids[] = $value['id'];
        }
        $not_sticky = array(
            'post_type' => 'people',
            'order_by' => 'date',
            'order' => 'DESC',
            'post_status' => 'publish',
            'post__in' => $ids
        );

        $i = 0;
        $wp_query_not_sticky = new WP_Query($not_sticky);
        if ($wp_query_not_sticky->have_posts()) {
            echo '<div><ul>';
            while ($wp_query_not_sticky->have_posts()) {

                if ($i && $i % 8 == 0) {
                    echo '</ul></div><div><ul>';
                }
                $i++;
                $wp_query_not_sticky->the_post();
                get_template_part('includes/event/star_network_single_event-people');
            }
            echo '</ul></div>';
        }
        wp_reset_query();
    }
}

function get_single_event_additional_sponsor_data($data)
{
    $ids = array();
    $sort_data = array();
    if ($data) {
        foreach ($data as $value) {
            $ids[] = $value['id'];
        }
        $not_sticky = array(
            'post_type' => 'sponsor',
            'order_by' => 'date',
            'order' => 'DESC',
            'post_status' => 'publish',
            'post__in' => $ids
        );
        $wp_query_not_sticky = new WP_Query($not_sticky);
        if ($wp_query_not_sticky->have_posts()) {
            while ($wp_query_not_sticky->have_posts()) {
                $wp_query_not_sticky->the_post();
                $post_meta = get_post_meta(get_the_ID());
                $post_type = $post_meta['schneps_sponsor_sponsor_type'][0];

                $sort_data[$post_type][] = get_the_ID();
            }

            if (!empty($sort_data['presenting'])) {
                echo '<div class="event-sponsor-title">Presenting Sponsors</div>';
                echo '<ul class="presenting-wrapper">';
                foreach ($sort_data['presenting'] as $val) {
                    echo '<li class="presenting">' . get_the_post_thumbnail($val) . '</li>';
                }
                echo '</ul>';
            }

            if (!empty($sort_data['gold'])) {
                echo '<div class="event-sponsor-title">Gold Sponsors</div>';
                echo '<ul class="gold-wrapper">';
                foreach ($sort_data['gold'] as $val) {
                    echo '<li class="gold">' . get_the_post_thumbnail($val) . '</li>';
                }
                echo '</ul>';
            }

            if (!empty($sort_data['regular'])) {
                echo '<div class="event-sponsor-title">Sponsors</div>';
                echo '<ul class="regular-wrapper">';
                foreach ($sort_data['regular'] as $val) {
                    echo '<li class="regular">' . get_the_post_thumbnail($val) . '</li>';
                }
                echo '</ul>';
            }
        }
        wp_reset_query();
    }
}

function crop_text($text, $length = 30, $after = '...')
{
    if (strlen($text) > $length) {
        return substr($text, 0, $length) . ' ' . $after;
    }

    return $text;
}