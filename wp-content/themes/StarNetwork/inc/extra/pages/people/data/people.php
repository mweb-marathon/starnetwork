<?php
/**
 * @author Maxim Bogdanov <sin666m4a1fox@gmail.com>
 * @copyright maxim 1/9/14 | 4:05 PM
 */

define('PAGE_NAME_PEOPLE', 'people');
define('PAGE_NAME_PLURAL_PEOPLE', 'peoples');
define('PAGE_NAME_UCFIRST_PEOPLE', ucfirst(PAGE_NAME_PEOPLE));
define('PAGE_PATH_PEOPLE', '/inc/extra/pages/' . PAGE_NAME_PEOPLE . '/');
define('PAGE_TEMPLATE_PATH_PEOPLE', get_stylesheet_directory() . PAGE_PATH_PEOPLE);
define('PAGE_URI_PEOPLE', get_stylesheet_directory_uri() . PAGE_PATH_PEOPLE);

/**
 * Include meta box code form implement additional fields
 */
include_once PAGE_TEMPLATE_PATH_PEOPLE . '/meta_box/meta_box.php';

function schneps_admin_css_file_people()
{

    wp_register_script('schneps-admin-style-people', PAGE_URI_PEOPLE . 'less/' . PAGE_NAME_PEOPLE . '.css');
    wp_enqueue_style('schneps-admin-style-people', PAGE_URI_PEOPLE . 'less/' . PAGE_NAME_PEOPLE . '.css');
}

/**
 * Add js files.
 */
function schneps_admin_js_file_people()
{
    wp_register_script('schneps-admin-js-' . PAGE_NAME_PEOPLE, PAGE_URI_PEOPLE . 'js/' . PAGE_NAME_PEOPLE . '.js');
    wp_enqueue_script('schneps-admin-js-' . PAGE_NAME_PEOPLE, PAGE_URI_PEOPLE . 'js/' . PAGE_NAME_PEOPLE . '.js');
}

add_action('admin_enqueue_scripts', 'schneps_admin_css_file_' . PAGE_NAME_PEOPLE);
add_action('admin_enqueue_scripts', 'schneps_admin_js_file_' . PAGE_NAME_PEOPLE);

function people_setup_post_types()
{
    $people_labels = apply_filters(PAGE_NAME_PEOPLE . '_labels', array(
        'name' => PAGE_NAME_UCFIRST_PEOPLE,
        'singular_name' => PAGE_NAME_PEOPLE,
        'add_new' => __('Add New', PAGE_NAME_PEOPLE),
        'add_new_item' => __('Add People', PAGE_NAME_PEOPLE),
        'edit_item' => __('Edit ' . PAGE_NAME_UCFIRST_PEOPLE, PAGE_NAME_PEOPLE),
        'new_item' => __('New ' . PAGE_NAME_UCFIRST_PEOPLE, PAGE_NAME_PEOPLE),
        'all_items' => __('All ' . PAGE_NAME_UCFIRST_PEOPLE, PAGE_NAME_PEOPLE),
        'view_item' => __('View ' . PAGE_NAME_UCFIRST_PEOPLE, PAGE_NAME_PEOPLE),
        'search_items' => __('Search ' . PAGE_NAME_UCFIRST_PEOPLE, PAGE_NAME_PEOPLE),
        'not_found' => __('No ' . PAGE_NAME_UCFIRST_PEOPLE . ' found', 'v'),
        'not_found_in_trash' => __('No ' . PAGE_NAME_UCFIRST_PEOPLE . ' found in Trash', PAGE_NAME_PEOPLE),
        'parent_item_colon' => '',
        'menu_name' => __(PAGE_NAME_UCFIRST_PEOPLE, PAGE_NAME_PEOPLE),
        'exclude_from_search' => true
    ));


    $people_args = array(
        'labels' => $people_labels,
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
    register_post_type(PAGE_NAME_PEOPLE, apply_filters(PAGE_NAME_PEOPLE . '_post_type_args', $people_args));

}


add_action('init', PAGE_NAME_PEOPLE . '_init');
function people_init()
{
    people_setup_post_types();
}