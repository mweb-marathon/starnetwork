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
require_once($theme_directory . '/widget/widget.php');


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

function count_newsandphotos()
{
    $not_sticky = array(
        'post_type' => array('post'),
        'order_by' => 'date',
        'order' => 'DESC',
        'post_status' => 'publish'
    );

    $the_query = new WP_Query($not_sticky);
    wp_reset_query();
    return $the_query->found_posts;
}

/* Functionality for home page. End */
function schneps_get_event_by_date($template = false, $post_per_page = 6, $paged = 1, $category_name = false)
{
    global $post;
    $not_sticky = array(
        'post_type' => array('event'),
        'posts_per_page' => 1000, //$post_per_page,
        'order_by' => 'term_order',
        'hide_empty' => false,
//        'paged' => $paged,
        'post_status' => 'publish'
    );

    if ($category_name && $category_name !== 'all') {
        $not_sticky['taxonomy'] = 'event-categories';
        $not_sticky['term'] = $category_name;
    }

    $wp_query_not_sticky = new WP_Query($not_sticky);

    $today_events = $future_events = $past_events = array();
    foreach ($wp_query_not_sticky->posts as $post) {
        $meta = get_post_meta($post->ID);
        $event_start_date = !empty($meta['_event_start_date'][0]) ? $meta['_event_start_date'][0] : '';
        $event_start_time = !empty($meta['_event_start_time'][0]) ? $meta['_event_start_time'][0] : '';

        $event_record = array(
            'start_date' => $event_start_date,
            'start_time' => $event_start_time,
            'post_object' => $post
        );

        if ($event_start_date == date('Y-m-d')) {
            $today_events[] = $event_record;
        } else {
            if (strtotime($event_start_date) > time()) {
                $future_events[] = $event_record;
            } else {
                $past_events[] = $event_record;
            }
        }
    }

    array_multisort($future_events, SORT_ASC);
    array_multisort($past_events, SORT_DESC);
    array_multisort($today_events, SORT_DESC);

    $total_events = array_merge($today_events, $future_events, $past_events);
    
    $end_ind = $paged * $post_per_page;
    $start_ind = $end_ind - $post_per_page;

    if ($start_ind > count($total_events)) {
        return;
    }

    if ($end_ind > count($total_events)) {
        $end_ind = count($total_events);
    }
    
    $ad = get_option('select-adrotate-content-story');
    $ad_positions = array(2 => 1, 6 => 2, 14 => 3, 18 => 4);

    $events = array();

    $shift = 26;
    for ($i = $start_ind; $i < $end_ind; $i++) {
    
        if ($i > $shift) {
            $k = (int)floor($i / $shift);
            $ad_i = ($i-($k*$shift)) -1;
        } else {
            $ad_i = $i;
        }

        if (key_exists($ad_i, $ad_positions)) {
            $events[] = $ad[ $ad_positions[ $ad_i ] ];
        } else {
            $events[] = $total_events[$i]['post_object'];
        }
    }

    foreach ($events as $event) {

        if ($event instanceof  WP_Post) {
            $post = $event;
            if ($template) {
                get_template_part('includes/event/' . $template . '-block');
            } else {
                get_template_part('includes/single-block');
            }
            if ($category_name && $category_name !== 'all') {
                $amount_per_category = count_event_per_category($category_name);
                $paged_per_category = count_paged_event_per_category($category_name);
                echo '<span class="amount-event-per-category" data-amount-per-category="' . $amount_per_category . '" data-current-paged="' . $paged . '" data-all-paged="' . $paged_per_category . '" data-category-name="' . $category_name . '"></span>';
            }
        } else {            
            ?>
            <div class="large-4 single-event-wrapper columns ad-rotate-block-wrapper">
                <?php
                $html = schneps_get_adrotate_($event);
                echo empty($html) ? get_dummy_for_expired_adrotate() : $html;
                ?>
            </div>
                <?php
        }
    }
    wp_reset_query();
}

function adRotateForGreed($ad_record)
{
    if (!$ad_record) {
        echo get_dummy_for_expired_adrotate_with_wrapper(); 
        return;
    }
    ?>
    <div class="large-4 single-event-wrapper columns ad-rotate-block-wrapper">
        <?php $html = adrotate_ad($ad_record->id); ?>
        <?php echo $html == '<!-- Error, Ad is not available at this time due to schedule/geolocation restrictions! -->' ? get_dummy_for_expired_adrotate() : $html; ?>
    </div>
    <?php
}

function get_dummy_for_expired_adrotate() {
    $output = '';
    $output .= '<a href="http://schnepscommunications.com/advertise/">';
        $output .= '<img src="/wp-content/themes/StarNetwork/img/adverstise-on-qns.jpg" />';
    $output .= '</a>';

    return $output;
}

function get_dummy_for_expired_adrotate_with_wrapper() {
    $output = '';
    $output .= '<div class="entry large-4 medium-6 small-12 columns small ad-rotate-block-wrapper">';    
    $output .= '<a href="http://schnepscommunications.com/advertise/">';
        $output .= '<img src="/wp-content/themes/StarNetwork/img/adverstise-on-qns.jpg" />';
    $output .= '</a>';
    $output .= '</div>';

    return $output;
}

function schneps_get_posts_by_date($template = false, $post_per_page = 23, $paged = 1,  $category_name = false)
{
    $not_sticky = array(
        'post_type' => array('post'),
        'posts_per_page' => $post_per_page,
        'order_by' => 'date',
        'order' => 'DESC',
        'paged' => $paged,
        'post_status' => 'publish'
    );
    
    $ad = get_option('select-adrotate-content-story');
    $ad_positions = array(2 => 1, 5 => 2, 12 => 3, 15 => 4);

    $i = 0;
    $wp_query_not_sticky = new WP_Query($not_sticky);
    if ($wp_query_not_sticky->have_posts()) {
        while ($wp_query_not_sticky->have_posts()) {
            if (key_exists($i, $ad_positions)) {
                ?>
                <div class="large-4 single-event-wrapper columns ad-rotate-block-wrapper">
                <?php
                $html = schneps_get_adrotate_( $ad[ $ad_positions[$i] ] );
                echo empty($html) ? get_dummy_for_expired_adrotate() : $html;
                ?>
            </div>
                <?php
            }
            $i++;
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
//    $height = 180;
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

function get_image_for_sponsor_people_2($titletext, $the_ID, $post)
{
    $thumb = '';
    $width = 360;
//    $height = 180;
    $classtext = '';
    $thumb = false;
    if(has_post_thumbnail($the_ID)) {
        $thumbnail = get_thumbnail($width, $height, $classtext, $titletext, $titletext, false, '', $post);
        $thumb = $thumbnail["thumb"];
    }
    ?>

    <?php if ($thumb): ?>
    <?php print_thumbnail($thumb, true, $titletext, $width, $height, $classtext, true, false, true, $post); ?>
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
    $page = !empty($_POST['page']) ? $_POST['page'] : 1;
    $category_name = !empty($_POST['post_category_name']) ? $_POST['post_category_name'] : 'all';


    schneps_get_event_by_date('star_network', $post_per_page, $page, $category_name);
    exit;
}

function wp_newsandphotos() {
    $post_per_page = !empty($_POST['post_per_page']) ? $_POST['post_per_page'] : 23;
    $page = !empty($_POST['page']) ? $_POST['page'] : 1;
    $category_name = !empty($_POST['post_category_name']) ? $_POST['post_category_name'] : 'all';

    schneps_get_posts_by_date(false, $post_per_page, $page, $category_name);
    exit;
}

add_action('wp_ajax_calendar_events', 'wp_calendarevents'); // for logged in user
add_action('wp_ajax_nopriv_calendar_events', 'wp_calendarevents');

add_action('wp_ajax_news_and_photos', 'wp_newsandphotos'); // for logged in user
add_action('wp_ajax_nopriv_news_and_photos', 'wp_newsandphotos');


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


/**
 * Custom function which displayed the adrotate spot.
 *
 * @param $id
 * @param $ad_number
 */
function schneps_get_adrotate_($id)
{
    $ad_rotate_data = schneps_get_adrotate_ads_data($id, true);
    if ($ad_rotate_data['type'] == 'ad') { ?>
            <?php return adrotate_ad($ad_rotate_data['ad_id']); ?>
        <?php
    } elseif ($ad_rotate_data['type'] == 'group') { ?>
            <?php return adrotate_group($ad_rotate_data['group_id']); ?>

    <?php }
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

        $collector = array();
        foreach ($data as $value) {
            $people = explode(' ', $value['people'], 2);
            if (count($people) == 2) {
                $last_first_name = $people[1].' '.$people[0];
            } else {
                $last_first_name = $value['people'];
            }
            $collector[ $value['people_role'] ] [$last_first_name.' '.$value['id'] ]  = $value;
        }
        $collector_reordered = array();
        foreach ($people_role as $key => $role) {
            if (array_key_exists($key, $collector)) {
                ksort($collector[$key]);
                $collector_reordered[$key] = $collector[$key];
            }
        }
        $collector_merged = array();
        foreach ($collector_reordered as $role => $people){
            foreach ($people as $key => $val) {
                $collector_merged[] = $val;
            }
        }

        unset($collector, $collector_reordered);
        foreach ($data as $value) {
            $ids[] = $value['id'];
            $t[$value['id']] = $value['people_role'];
        }
        $not_sticky = array(
            'post_type' => 'people',
            'orderby' => 'post_title',
            'order' => 'ASC',
            'post_status' => 'publish',
            'post__in' => $ids,
            'posts_per_page' => 10000
        );

        $i = 0;
        $wp_query_not_sticky = new WP_Query($not_sticky);
        if ($wp_query_not_sticky->have_posts()) {
            $posts_records = array();

            while ($wp_query_not_sticky->have_posts()) {
                $wp_query_not_sticky->the_post();
                $posts_records[get_the_ID()] = array(
                    'post_meta' => get_post_meta(get_the_ID()),
                    'post_ID' => get_the_ID(),
                    'name' => get_the_title(),
                    'post' => get_post()
                );
            }

            echo '<div><ul>';
            foreach($collector_merged as $people) {

                if ($i && $i % 8 == 0) {
                    echo '</ul></div><div><ul>';
                }
                $i++;

//                $wp_query_not_sticky->the_post();
                $post_meta = $people['post_meta'];
                $name = $titletext = $people['people'];

                $company = $post_meta['schneps_people_company_or_organization'][0];

                if (!empty($post_meta['schneps_people_link'][0])) {
                    $name = '<a href="' . $post_meta['schneps_people_link'][0] . '">' . $name . '</a>';
                }

                if (!empty($post_meta['schneps_people_company_link'][0])) {
                    $company = '<a href="' . $post_meta['schneps_people_company_link'][0] . '">' . $company . '</a>';
                }
                $title = '';
                if (!empty($post_meta['schneps_people_title'][0])) {
                    $title = $post_meta['schneps_people_title'][0];
                }
                ?>
                <li>
                    <div class="image">
                        <div class="circle-img-box">
                            <?php echo !empty($post_meta['schneps_people_link'][0]) ? '<a href="'.$post_meta['schneps_people_link'][0].'">' : ''?>
                            
                                <?php get_image_for_sponsor_people_2($titletext, $people['id'], $posts_records[$people['id']]['post']); ?>
                            <?php echo !empty($post_meta['schneps_people_link'][0]) ? '</a>' : '' ?>
                        </div>
                        <div class="headshot">
                            <?php echo $people_role[$t[ $people['id'] ]]; ?>
                        </div>
                    </div>
                    <div class="name">
                        <?php echo $name; ?>
                    </div>
                    <div>
                        <?php echo $title; ?>
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

/**
 * Get pagination.
 */
function schneps_paging_nav()
{
    global $wp_query, $wp_rewrite;

    // Don't print empty markup if there's only one page.
    if ($wp_query->max_num_pages < 2) {
        return;
    }

    $paged = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
    $pagenum_link = html_entity_decode(get_pagenum_link());
    $query_args = array();
    $url_parts = explode('?', $pagenum_link);

    if (isset($url_parts[1])) {
        wp_parse_str($url_parts[1], $query_args);
    }

    $pagenum_link = remove_query_arg(array_keys($query_args), $pagenum_link);
    $pagenum_link = trailingslashit($pagenum_link) . '%_%';

    $format = $wp_rewrite->using_index_permalinks() && !strpos($pagenum_link, 'index.php') ? 'index.php/' : '';
    $format .= $wp_rewrite->using_permalinks() ? user_trailingslashit($wp_rewrite->pagination_base . '/%#%', 'paged') : '?paged=%#%';

    // Set up paginated links.
    $links = paginate_links(array(
        'base' => $pagenum_link,
        'format' => $format,
        'total' => $wp_query->max_num_pages,
        'current' => $paged,
        'mid_size' => 1,
//        'add_args' => array_map('urlencode', $query_args),
        'prev_text' => __('&larr; Previous', 'twentyfourteen'),
        'next_text' => __('Next &rarr;', 'twentyfourteen'),
    ));

    if ($links) : ?>
        <nav class="navigation paging-navigation" role="navigation">
            <div class="pagination loop-pagination">
                <?php echo $links; ?>
            </div>
        </nav>
        <?php
    endif;
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

function schneps_datetime_of_event($post_meta) {
    $date = array();
    $time = array();

    $result = '';
    if(!empty($post_meta['_event_start_date'][0])) {
        $date[] = date('m/d/Y', strtotime($post_meta['_event_start_date'][0]));
    };
    if (!empty($post_meta['_event_end_date'][0])) {
        $date[] = date('m/d/Y', strtotime($post_meta['_event_end_date'][0]));
    }

    if (!empty($post_meta['_event_start_time'][0])) {
        $time[] = date('h:i a', strtotime($post_meta['_event_start_time'][0]));
    }
    if (!empty($post_meta['_event_end_time'][0])) {
        $time[] = date('h:i a', strtotime($post_meta['_event_end_time'][0]));
    }

    $result .= implode(' - ', $date);
    if (count($time)) {
        $result .= ' @ '.implode(' - ', $time);
    }

    return $result;
}

function shnepsPopularStoryAdrotate($option_name) {
    $popular_story_id = get_option($option_name);
    $popular_story_record = schneps_get_adrotate_ads_data($popular_story_id);
    $explodeId = explode(' ', $popular_story_id);
    if(count($explodeId) > 1) {
        $ad_gr_object_key = strtolower($explodeId[0]).'_object'; // 'ad_object' or 'group_object'
    }

    if (!empty($popular_story_record[ $ad_gr_object_key ])):
    ?>
    <div class="popular-story-adrotate">
    <a href="<?php echo esc_url($popular_story_record[$ad_gr_object_key]->link); ?>">
        <img src="<?php echo esc_attr($popular_story_record[$ad_gr_object_key]->image); ?>"
             alt="<?php echo esc_attr(get_bloginfo('name')); ?>"/>
    </a>
    </div>
    <?php
    endif;
}

function scheps_get_event_category_image($slug) {
    $term_record = get_term_by('slug', $slug, 'event-categories');
    $em_category = new EM_Category($term_record);
    $category_image = $em_category->get_image_url();
    return $category_image;
}

/**
 * Get adrotate group.
 *
 * This is modification of plugin function.
 *
 * @param $group_ids
 * @param $how_display
 * @return string
 */
function schneps_adrotate_group($group_ids, $how_display)
{
    global $wpdb, $adrotate_config, $adrotate_debug;

    $output = $group_select = '';
    if ($group_ids) {
        $now = adrotate_now();
        $group_array = (!is_array($group_ids)) ? explode(",", $group_ids) : $group_ids;

        foreach ($group_array as $key => $value) {
            $group_select .= " `{$wpdb->prefix}adrotate_linkmeta`.`group` = {$value} OR";
        }
        $group_select = rtrim($group_select, " OR");

        $group = $wpdb->get_row($wpdb->prepare("SELECT * FROM `{$wpdb->prefix}adrotate_groups` WHERE `name` != '' AND `id` = %d;", $group_array[0]));

        if ($adrotate_debug['general'] == true) {
            echo "<p><strong>[DEBUG][adrotate_group] Selected group</strong><pre>";
            print_r($group);
            echo "</pre></p>";
        }

        if ($group) {
            // Get all ads in all selected groups
            $ads = $wpdb->get_results(
                "SELECT
					`{$wpdb->prefix}adrotate`.`id`,
					`{$wpdb->prefix}adrotate`.`title`,
					`{$wpdb->prefix}adrotate`.`bannercode`,
					`{$wpdb->prefix}adrotate`.`image`,
					`{$wpdb->prefix}adrotate`.`responsive`,
					`{$wpdb->prefix}adrotate`.`tracker`,
					`{$wpdb->prefix}adrotate_linkmeta`.`group`
				FROM
					`{$wpdb->prefix}adrotate`,
					`{$wpdb->prefix}adrotate_linkmeta`
				WHERE
					({$group_select})
					AND `{$wpdb->prefix}adrotate_linkmeta`.`user` = 0
					AND `{$wpdb->prefix}adrotate`.`id` = `{$wpdb->prefix}adrotate_linkmeta`.`ad`
					AND (`{$wpdb->prefix}adrotate`.`type` = 'active'
						OR `{$wpdb->prefix}adrotate`.`type` = '2days'
						OR `{$wpdb->prefix}adrotate`.`type` = '7days')
				GROUP BY `{$wpdb->prefix}adrotate`.`id`
				ORDER BY `{$wpdb->prefix}adrotate`.`id`;");

            if ($ads) {
                if ($adrotate_debug['general'] == true) {
                    echo "<p><strong>[DEBUG][adrotate_group()] All ads in group</strong><pre>";
                    print_r($ads);
                    echo "</pre></p>";
                }

                foreach ($ads as $ad) {
                    $selected[$ad->id] = $ad;
                    $selected = adrotate_filter_schedule($selected, $ad);
                }
                unset($ads);

                if ($adrotate_debug['general'] == true) {
                    echo "<p><strong>[DEBUG][adrotate_group] Reduced array based on schedule restrictions</strong><pre>";
                    print_r($selected);
                    echo "</pre></p>";
                }

                $array_count = count($selected);
                if ($array_count > 0) {
                    $before = $after = '';
                    $before = str_replace('%id%', $group_array[0], stripslashes(html_entity_decode($group->wrapper_before, ENT_QUOTES)));
                    $after = str_replace('%id%', $group_array[0], stripslashes(html_entity_decode($group->wrapper_after, ENT_QUOTES)));

                    $output .= '<div class="g g-' . $group->id . '">';

                    if ($group->modus == 1) { // Dynamic ads
                        $i = 1;

                        // Limit group to save resources
                        $amount = ($group->adspeed >= 10000) ? 10 : 20;

                        // Randomize and trim output
                        $selected = adrotate_shuffle($selected);
                        foreach ($selected as $key => $banner) {
                            if ($i <= $amount) {
                                $image = str_replace('%folder%', $adrotate_config['banner_folder'], $banner->image);


                                $output .= '<div class="g-dyn a-' . $banner->id . ' c-' . $i . '">';
                                if (!$how_display) {
                                    $output .= '<div class="thumbnail">';
                                }
                                $output .= $before . adrotate_ad_output($banner->id, $group->id, $banner->title, $banner->bannercode, $banner->tracker, $image, $banner->responsive) . $after;
                                if (!$how_display) {
                                    $output .= '</div>';
                                }
                                if (!$how_display) {
                                    $output .= '<div class="entry-post-wrapper">';
                                    $output .= '<div class="entry-post-info a-single a-' . $banner->id . '">';
                                    $output .= '<a href="' . $banner->link . '" class="gofollow" target="_blank"  data-track="' . adrotate_hash($banner->id, 0, 0) . '">';
                                    $output .= $banner->title;
                                    $output .= '</a>';
                                    $output .= '</div>';
                                    $output .= '<div class="entry-post-additional-info"></div>';
                                    $output .= '<div class="red-line Px10 relative">';
                                    $output .= '<span class="is-sponsored">sponsored</span>';
                                    $output .= '</div>';
                                    $output .= '</div>';
                                }
                                $output .= '</div>';


                                $i++;
                            }
                        }
                    } else if ($group->modus == 2) { // Block of ads
                        $block_count = $group->gridcolumns * $group->gridrows;
                        if ($array_count < $block_count) $block_count = $array_count;
                        $columns = 1;

                        for ($i = 1; $i <= $block_count; $i++) {
                            $banner_id = array_rand($selected, 1);

                            $image = str_replace('%folder%', $adrotate_config['banner_folder'], $selected[$banner_id]->image);
                            if (!$how_display) {
                                $output = '<div class="thumbnail">';
                            }

                            $output .= '<div class="g-col b-' . $group->id . ' a-' . $selected[$banner_id]->id . '">';
                            $output .= $before . adrotate_ad_output($selected[$banner_id]->id, $group->id, $selected[$banner_id]->title, $selected[$banner_id]->bannercode, $selected[$banner_id]->tracker, $image, $selected[$banner_id]->responsive) . $after;
                            $output .= '</div>';
                            if (!$how_display) {
                                $output .= '</div>';
                            }

                            if ($columns == $group->gridcolumns AND $i != $block_count) {
                                $output .= '</div><div class="g g-' . $group->id . '">';
                                $columns = 1;
                            } else {
                                $columns++;
                            }

                            if ($adrotate_config['stats'] == 1) {
                                adrotate_count_impression($selected[$banner_id]->id, $group->id, 0, $adrotate_config['impression_timer']);
                            }

                            if (!$how_display) {
                                $output .= '<div class="entry-post-wrapper">';
                                $output .= '<div class="entry-post-info a-single a-' . $selected[$banner_id]->id . '">';
                                $output .= '<a href="' . $selected[$banner_id]->link . '" class="gofollow" target="_blank"  data-track="' . adrotate_hash($selected[$banner_id]->id, 0, 0) . '">';
                                $output .= $selected[$banner_id]->title;
                                $output .= '</a>';
                                $output .= '</div>';
                                $output .= '<div class="entry-post-additional-info"></div>';
                                $output .= '<div class="red-line Px10 relative">';
                                $output .= '<span class="is-sponsored">sponsored</span>';
                                $output .= '</div>';
                                $output .= '</div>';
                            }

                            unset($selected[$banner_id]);
                        }
                    } else { // Default (single ad)
                        $banner_id = array_rand($selected, 1);

                        $image = str_replace('%folder%', $adrotate_config['banner_folder'], $selected[$banner_id]->image);


                        $output .= '<div class="g-single a-' . $selected[$banner_id]->id . '">';
                        if (!$how_display) {
                            $output = '<div class="thumbnail">';
                        }
                        $output .= $before . adrotate_ad_output($selected[$banner_id]->id, $group->id, $selected[$banner_id]->title, $selected[$banner_id]->bannercode, $selected[$banner_id]->tracker, $image, $selected[$banner_id]->responsive) . $after;
                        if (!$how_display) {
                            $output .= '</div>';
                        }
                        if (!$how_display) {
                            $output .= '<div class="entry-post-wrapper">';
                            $output .= '<div class="entry-post-info a-single a-' . $selected[$banner_id]->id . '">';
                            $output .= '<a href="' . $selected[$banner_id]->link . '" class="gofollow"  target="_blank"  data-track="' . adrotate_hash($selected[$banner_id]->id, 0, 0) . '">';
                            $output .= $selected[$banner_id]->title;
                            $output .= '</a>';
                            $output .= '</div>';
                            $output .= '<div class="entry-post-additional-info"></div>';
                            $output .= '<div class="red-line Px10 relative">';
                            $output .= '<span class="is-sponsored">sponsored</span>';
                            $output .= '</div>';
//                            $output .= '</div>';
                        }
                        if ($how_display) {
                            $output .= '</div>';
                        }


                        if ($adrotate_config['stats'] == 1) {
                            adrotate_count_impression($selected[$banner_id]->id, $group->id, 0, $adrotate_config['impression_timer']);
                        }

                    }

                    $output .= '</div>';
                    unset($selected);
                } else {
                    $output .= get_dummy_for_expired_adrotate();
//                    $output .= adrotate_error('ad_expired');
                }
            } else {
                $output .= get_dummy_for_expired_adrotate();
//                $output .= adrotate_error('ad_unqualified');
            }
        } else {
            $output .= get_dummy_for_expired_adrotate();
//            $output .= adrotate_error('group_not_found', array($group_array[0]));
        }
    } else {
        $output .= get_dummy_for_expired_adrotate();
//        $output .= adrotate_error('group_no_id');
    }


    return $output;
}


add_action('wp_ajax_schneps_get_related_stories_story_page_', 'wp_schneps_get_related_stories_story_page_');
add_action('wp_ajax_nopriv_schneps_get_related_stories_story_page_', 'wp_schneps_get_related_stories_story_page_');
