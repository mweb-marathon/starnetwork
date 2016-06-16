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

    register_sidebar( array(
        'name' => 'Left sidebar',
        'id' => 'left_sidebar',
        'before_widget' => '<div>',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="rounded">',
        'after_title' => '</h2>',
    ) );
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

function schneps_get_left_category_menu()
{
    return wp_nav_menu(schneps_get_menu('Left Category Menu', ''));
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

function count_paged_event_per_category($category_name)
{
    $not_sticky = array(
        'post_type' => array('event'),
        'posts_per_page' => 6,
        'order_by' => 'date',
        'order' => 'DESC',
        'taxonomy' => 'event-categories',
        'term' => $category_name,
        'post_status' => 'publish'
    );

    $the_query = new WP_Query($not_sticky);
    wp_reset_query();
    return $the_query->max_num_pages;
}


function count_event_per_category($category_name)
{
    $not_sticky = array(
        'post_type' => array('event'),

        'order_by' => 'date',
        'order' => 'DESC',
        'taxonomy' => 'event-categories',
        'term' => $category_name,
        'post_status' => 'publish'
    );

    $the_query = new WP_Query($not_sticky);
    wp_reset_query();
    return $the_query->found_posts;
}

/* Functionality for home page. End */

function schneps_get_event_by_date($template = false, $post_per_page = 6, $paged = 1, $category_name = false)
{
    $not_sticky = array(
        'post_type' => array('event'),
        'posts_per_page' => $post_per_page,
        'order_by' => 'term_order',
        'hide_empty' => false,
        'paged' => $paged,
        'post_status' => 'publish'
    );

    if ($category_name && $category_name !== 'all') {
        $not_sticky['taxonomy'] = 'event-categories';
        $not_sticky['term'] = $category_name;

    }
    $i = 0;

    $wp_query_not_sticky = new WP_Query($not_sticky);
    if ($wp_query_not_sticky->have_posts()) {
        while ($wp_query_not_sticky->have_posts()) {
            $i++;

            $wp_query_not_sticky->the_post();

            if ($template) {
                get_template_part('includes/event/' . $template . '-block');
            } else {
                get_template_part('includes/single-block');
            }
        }
        if ($category_name && $category_name !== 'all') {
            $amount_per_category = count_event_per_category($category_name);
            $paged_per_category = count_paged_event_per_category($category_name);
            echo '<span class="amount-event-per-category" data-amount-per-category="' . $amount_per_category . '" data-current-paged="' . $paged . '" data-all-paged="' . $paged_per_category . '" data-category-name="' . $category_name . '"></span>';
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

    $thumb = false;
    if(has_post_thumbnail(get_the_ID())) {
        $thumbnail = get_thumbnail($width, $height, $classtext, $titletext, $titletext);
        $thumb = $thumbnail["thumb"];
    }

    ?>
    <a href="<?php echo get_the_permalink(); ?>">
        <?php if ($thumb): ?>
            <?php print_thumbnail($thumb, true, $titletext, $width, $height, $classtext); ?>
        <?php else: ?>
            <div class="no-image">

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
    $thumb = false;
    if(has_post_thumbnail(get_the_ID())) {
        $thumbnail = get_thumbnail($width, $height, $classtext, $titletext, $titletext);
        $thumb = $thumbnail["thumb"];
    }



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

function rpHash($value)
{
    $hash = 5381;
    $value = strtoupper($value);
    for ($i = 0; $i < strlen($value); $i++) {
        $hash = (leftShift32($hash, 5) + $hash) + ord(substr($value, $i));
    }
    return $hash;
}

// Perform a 32bit left shift
function leftShift32($number, $steps)
{
    // convert to binary (string)
    $binary = decbin($number);
    // left-pad with 0's if necessary
    $binary = str_pad($binary, 32, "0", STR_PAD_LEFT);
    // left shift manually
    $binary = $binary . str_repeat("0", $steps);
    // get the last 32 bits
    $binary = substr($binary, strlen($binary) - 32);
    // if it's a positive number return it
    // otherwise return the 2's complement
    return ($binary{0} == "0" ? bindec($binary) :
        -(pow(2, 31) - bindec(substr($binary, 1))));
}


function star_network_send_form_email()
{
    $post = $_POST;

    if (rpHash($_POST['defaultReal']) == $_POST['defaultRealHash']) {
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
    } else {
        echo 'error';
        exit;
    }


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

function schneps_get_event_map_($event_id, $limit = 2)
{
    global $wpdb;
//    $results = $wpdb->get_results("SELECT * FROM `wp_em_locations` WHERE `post_id` = '" . ($event_id+1) . "' LIMIT 1", OBJECT);
    $results = $wpdb->get_results("SELECT p.*, t.* FROM wp_em_events p RIGHT JOIN wp_em_locations t ON p.location_id = t.location_id WHERE p.post_id = '" . ($event_id) . "' LIMIT 1", OBJECT);

    if (count($results) > 0) {
//        echo '<pre>'.print_r( $results , 1).'</pre>';
        $address = $results[0]->location_address;
        $loc_name = $results[0]->location_name;
        $map_link = $results[0]->location_latitude . ', ' . $results[0]->location_longitude;
        $venue_name = get_post_meta($event_id, 'event_post_event_venue', true);
        ?>

        <div id="map_canvas" style="width: 100%; height:400px;"></div>
<!--        <div class="event-map-info">
            <div class="location-name">
                <?php echo $results[0]->location_name; ?>
            </div>
            <div class="location-address">
                <?php echo $results[0]->location_address; ?>
            </div>
        </div>-->


        <script>
            function initialize() {
                var myLatlng = new google.maps.LatLng(<?php echo $map_link; ?>);
                var address = "<?php echo $address; ?>";
                var location_name = "<?php echo $loc_name;?>";
                var venue_name = "<?php echo $venue_name; ?>";
                var mapOptions = {
                    zoom: 14,
                    center: myLatlng,
                    streetViewControl: false,
                    zoomControl: false,
                    mapTypeControl: false,
                    overviewMapControl: false,
                    draggable: false,
                    disableDoubleClickZoom: true,
                    scrollwheel: false,
                    navigationControl: false,
                    scaleControl: false

                };

                var iconBase = location.origin + '/wp-content/themes/TheStyle-child/img/';

                var map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);

                var infowindow = new google.maps.InfoWindow({
                    content: '<p id="google-map-venue"><b>' + venue_name + '</b></p><p id="google-map-location">' + location_name + '</p><p id="google-map-address">' + address + '</p>'
                });

                var marker = new google.maps.Marker({
                    position: myLatlng,
                    map: map,
                    icon: iconBase + 'schneps-map-marker.png'
                });

                google.maps.event.addListener(marker, 'click', function () {
                    if (infowindow) {
                        infowindow.close();
                    }
                    window.open('http://maps.google.com/?q=<?php echo $map_link; ?>', '_blank');
                });

                google.maps.event.addListener(map, 'click', function (event) {

                    window.open('http://maps.google.com/?q=<?php echo $map_link; ?>', '_blank');
                });
                infowindow.open(map, marker);
            }
            google.maps.event.addDomListener(window, 'load', initialize);
        </script>

    <?php
    }
}

function get_em_location_record($id) {
    global $wpdb;
    $results = $wpdb->get_results("SELECT * FROM `wp_em_locations` WHERE `location_id` = '" . ($id) . "' LIMIT 1", OBJECT);
    return $results;
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
                    overviewMapControl: false,
                    scrollwheel: false,
                    navigationControl: false,
                    scaleControl: false,
                    draggable: false,
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


function get_single_event_additional_people_data($data)
{
    $people_role = array(
        'emcee' => 'Emcee',
        'special_honoree' => 'Special Honoree(s)',
        'keynote_speaker' => 'Keynote Speaker',
        'moderator' => 'Moderator',
        'honorees' => 'Honorees',
        'speakers' => 'Speakers'
    );
    $ids = array();
    $t = array();
    if ($data) {
        foreach ($data as $value) {
            $ids[] = $value['id'];
            $t[$value['id']] = $value['people_role'];
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
                $post_meta = get_post_meta(get_the_ID());
                $name = get_the_title();


                $company = $post_meta['schneps_people_company_or_organization'][0];

                if (!empty($post_meta['schneps_people_link'][0])) {
                    $name = '<a href="' . $post_meta['schneps_people_link'][0] . '">' . $name . '</a>';
                }

                if (!empty($post_meta['schneps_people_company_link'][0])) {
                    $company = '<a href="' . $post_meta['schneps_people_company_link'][0] . '">' . $company . '</a>';
                }
                ?>
                <li>
                    <div class="image circle-img-box">
                        <?php get_image_for_sponsor_people(); ?>
                        <div class="headshot">
                            <?php echo $people_role[$t[get_the_ID()]]; ?>
                        </div>
                    </div>
                    <div class="name">
                        <?php echo $name; ?>
                    </div>
                    <div class="company">
                        <?php echo $company; ?>
                    </div>
                </li>
            <?php
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
    $sponsor_meta = array();
    if ($data) {
        foreach ($data as $value) {
            $ids[] = $value['id'];
            $sort_data[$value['sponsor_role']][] = $value['id'];
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
                $sponsor_meta[get_the_ID()] = $post_meta;
            }
            if (!empty($sort_data['presenting'])) {
                echo '<div class="event-sponsor-title">Presenting Sponsors</div>';
                echo '<ul class="presenting-wrapper">';
                foreach ($sort_data['presenting'] as $val) {
                    echo '<li class="presenting"><a href="' . $sponsor_meta[$val]['schneps_sponsor_link'][0] . '">' . get_the_post_thumbnail($val) . '</a></li>';
                }
                echo '</ul>';
            }

            if (!empty($sort_data['gold'])) {
                echo '<div class="event-sponsor-title">Gold Sponsors</div>';
                echo '<ul class="gold-wrapper">';
                foreach ($sort_data['gold'] as $val) {
                    echo '<li class="gold"><a href="' . $sponsor_meta[$val]['schneps_sponsor_link'][0] . '">' . get_the_post_thumbnail($val) . '</a></li>';
                }
                echo '</ul>';
            }

            if (!empty($sort_data['regular'])) {
                echo '<div class="event-sponsor-title">Sponsors</div>';
                echo '<ul class="regular-wrapper">';
                foreach ($sort_data['regular'] as $val) {
                    echo '<li class="regular"><a href="' . $sponsor_meta[$val]['schneps_sponsor_link'][0] . '">' . get_the_post_thumbnail($val) . '</a></li>';
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

add_filter('gettext', 'custom_enter_title_for_people');

function custom_enter_title_for_people($input)
{
    global $post_type;

    if (is_admin() && 'Enter title here' == $input && 'people' == $post_type)
        return 'Enter name here';

    return $input;
}

function wp_get_attachment($attachment_id)
{

    $attachment = get_post($attachment_id);
    return array(
        'alt' => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
        'caption' => $attachment->post_excerpt ? : $attachment->post_title,
        'description' => $attachment->post_content,
        'href' => get_permalink($attachment->ID),
        'src' => $attachment->guid,
        'title' => $attachment->post_title
    );
}

function wp_schneps_get_related_stories_story_page_()
{

    $loopRelatedStory = 'loop_story_related';
    get_template_part($loopRelatedStory);
    wp_reset_query();
    exit;
}

add_action('wp_ajax_schneps_get_related_stories_story_page_', 'wp_schneps_get_related_stories_story_page_');
add_action('wp_ajax_nopriv_schneps_get_related_stories_story_page_', 'wp_schneps_get_related_stories_story_page_');
