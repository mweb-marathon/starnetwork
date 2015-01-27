<?php
/**
 * @author Maxim Bogdanov <sin666m4a1fox@gmail.com>
 * @copyright maxim 1/9/14 | 4:05 PM
 */

define('PAGE_NAME_SPONSOR', 'sponsor');
define('PAGE_NAME_PLURAL_SPONSOR', 'sponsor');
define('PAGE_NAME_UCFIRST_SPONSOR', ucfirst(PAGE_NAME_SPONSOR));
define('PAGE_PATH_SPONSOR', '/inc/extra/pages/' . PAGE_NAME_SPONSOR . '/');
define('PAGE_TEMPLATE_PATH_SPONSOR', get_stylesheet_directory() . PAGE_PATH_SPONSOR);
define('PAGE_URI_SPONSOR', get_stylesheet_directory_uri() . PAGE_PATH_SPONSOR);

/**
 * Include meta box code form implement additional fields
 */
include_once PAGE_TEMPLATE_PATH_SPONSOR . '/meta_box/meta_box.php';

function schneps_admin_css_file_sponsor()
{

    wp_register_script('schneps-admin-style-sponsor', PAGE_URI_SPONSOR . 'less/' . PAGE_NAME_SPONSOR . '.css');
    wp_enqueue_style('schneps-admin-style-sponsor', PAGE_URI_SPONSOR . 'less/' . PAGE_NAME_SPONSOR . '.css');
}

/**
 * Add js files.
 */
function schneps_admin_js_file_sponsor()
{
    wp_register_script('schneps-admin-js-' . PAGE_NAME_SPONSOR, PAGE_URI_SPONSOR . 'js/' . PAGE_NAME_SPONSOR . '.js');
    wp_enqueue_script('schneps-admin-js-' . PAGE_NAME_SPONSOR, PAGE_URI_SPONSOR . 'js/' . PAGE_NAME_SPONSOR . '.js');
}

add_action('admin_enqueue_scripts', 'schneps_admin_css_file_' . PAGE_NAME_SPONSOR);
add_action('admin_enqueue_scripts', 'schneps_admin_js_file_' . PAGE_NAME_SPONSOR);

function sponsor_setup_post_types()
{
    $sponsor_labels = apply_filters(PAGE_NAME_SPONSOR . '_labels', array(
        'name' => PAGE_NAME_UCFIRST_SPONSOR,
        'singular_name' => PAGE_NAME_SPONSOR,
        'add_new' => __('Add New', PAGE_NAME_SPONSOR),
        'add_new_item' => __('Add Sponsor', PAGE_NAME_SPONSOR),
        'edit_item' => __('Edit ' . PAGE_NAME_UCFIRST_SPONSOR, PAGE_NAME_SPONSOR),
        'new_item' => __('New ' . PAGE_NAME_UCFIRST_SPONSOR, PAGE_NAME_SPONSOR),
        'all_items' => __('All ' . PAGE_NAME_UCFIRST_SPONSOR, PAGE_NAME_SPONSOR),
        'view_item' => __('View ' . PAGE_NAME_UCFIRST_SPONSOR, PAGE_NAME_SPONSOR),
        'search_items' => __('Search ' . PAGE_NAME_UCFIRST_SPONSOR, PAGE_NAME_SPONSOR),
        'not_found' => __('No ' . PAGE_NAME_UCFIRST_SPONSOR . ' found', 'v'),
        'not_found_in_trash' => __('No ' . PAGE_NAME_UCFIRST_SPONSOR . ' found in Trash', PAGE_NAME_SPONSOR),
        'parent_item_colon' => '',
        'menu_name' => __(PAGE_NAME_UCFIRST_SPONSOR, PAGE_NAME_SPONSOR),
        'exclude_from_search' => true
    ));


    $sponsor_args = array(
        'labels' => $sponsor_labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'has_archive' => false,
        'hierarchical' => false,
        'menu_icon' => '',
        'supports' => array('title', 'thumbnail'),
    );
    register_post_type(PAGE_NAME_SPONSOR, apply_filters(PAGE_NAME_SPONSOR . '_post_type_args', $sponsor_args));

}


add_action('init', PAGE_NAME_SPONSOR . '_init');
function sponsor_init()
{
    sponsor_setup_post_types();
}