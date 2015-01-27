<?php
/**
 * @author Maxim Bogdanov <sin666m4a1fox@gmail.com>
 * @copyright maxim 7/23/14 | 5:27 PM
 */

/**
 * Registering meta boxes
 *
 * All the definitions of meta boxes are listed below with comments.
 * Please read them CAREFULLY.
 *
 * You also should read the changelog to know what has been changed before updating.
 *
 * For more information, please visit:
 * @link http://www.deluxeblogtips.com/meta-box/
 */


add_filter( 'rwmb_meta_boxes', 'schneps_people_register_meta_boxes' );

/**
 * Register meta boxes
 *
 * Remember to change "your_prefix" to actual prefix in your project
 *
 * @return void
 */
function schneps_people_register_meta_boxes( $meta_boxes )
{
    /**
     * prefix of meta keys (optional)
     * Use underscore (_) at the beginning to make keys hidden
     * Alt.: You also can make prefix empty to disable it
     */
    // Better has an underscore as last sign
    $prefix = 'schneps_people_';

    // 1st meta box
    $meta_boxes[] = array(
        // Meta box id, UNIQUE per meta box. Optional since 4.1.5
        'id' => 'standard',

        // Meta box title - Will appear at the drag and drop handle bar. Required.
        'title' => __( 'Standard Fields', 'rwmb' ),

        // Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
        'pages' => array('people',),

        // Where the meta box appear: normal (default), advanced, side. Optional.
        'context' => 'normal',

        // Order of meta box: high (default), low. Optional.
        'priority' => 'high',

        // Auto save: true, false (default). Optional.
        'autosave' => true,

        // List of meta fields
        'fields' => array(

            array(
                'name'     => __( 'Role', 'rwmb' ),
                'id'       => "{$prefix}event_associated",
                'type'     => 'select',
                // Array of 'value' => 'Label' pairs for select box
                'options'  => array(
                    'emcee' => __( 'Emcee', 'rwmb' ),
                    'special_honoree' => __( 'Special Honoree(s)', 'rwmb' ),
                    'keynote_speaker' => __( 'Keynote Speaker', 'rwmb' ),
                    'moderators' => __( 'Moderators', 'rwmb' ),
                    'honorees' => __( 'Honorees', 'rwmb' ),
                    'speakers' => __( 'Speakers', 'rwmb' ),
                ),
                'placeholder' => __( 'Select an Associated Event with', 'rwmb' ),
            ),

            array(
                // Field name - Will be used as label
                'name'  => __( 'Company or Organization', 'rwmb' ),
                // Field ID, i.e. the meta key
                'id'    => "{$prefix}company_or_organization",
                'type'  => 'text'
            ),

            array(
                // Field name - Will be used as label
                'name'  => __( 'Link', 'rwmb' ),
                // Field ID, i.e. the meta key
                'id'    => "{$prefix}link",
                'type'  => 'text'
            ),

            array(
                // Field name - Will be used as label
                'name'  => __( 'Company Link', 'rwmb' ),
                // Field ID, i.e. the meta key
                'id'    => "{$prefix}company_link",
                'type'  => 'text'
            ),

        ),
        'validation' => array(
            'rules' => array(
                "{$prefix}event_associated" => array(
                    'required'  => true
                ),
                "{$prefix}headshot" => array(
                    'required'  => true
                ),
                "{$prefix}first_name" => array(
                    'required'  => true
                ),
                "{$prefix}last_name" => array(
                    'required'  => true
                ),
                "{$prefix}company_or_organization" => array(
                    'required'  => true
                ),
            ),
            // optional override of default jquery.validate messages
            'messages' => array(
                "{$prefix}event_associated" => array(
                    'required'  => __( 'Select one is required', 'rwmb' )
                ),
                "{$prefix}company_or_organization" => array(
                    'required'  => __( 'Company or Organization is required', 'rwmb' )
                ),
            )
        )
    );

    // 2nd meta box
//    $meta_boxes[] = array(
//        'title' => __( 'Advanced Fields', 'rwmb' ),
//
//        'pages' => array( 'story', 'page' ),
//
//        'fields' => array(
//            // HEADING
//            array(
//                'type' => 'heading',
//                'name' => __( 'Heading', 'rwmb' ),
//                'id'   => 'fake_id', // Not used but needed for plugin
//            ),
//            // SLIDER
//            array(
//                'name' => __( 'Slider', 'rwmb' ),
//                'id'   => "{$prefix}slider",
//                'type' => 'slider',
//
//                // Text labels displayed before and after value
//                'prefix' => __( '$', 'rwmb' ),
//                'suffix' => __( ' USD', 'rwmb' ),
//
//                // jQuery UI slider options. See here http://api.jqueryui.com/slider/
//                'js_options' => array(
//                    'min'   => 10,
//                    'max'   => 255,
//                    'step'  => 5,
//                ),
//            ),
//            // NUMBER
//            array(
//                'name' => __( 'Number', 'rwmb' ),
//                'id'   => "{$prefix}number",
//                'type' => 'number',
//
//                'min'  => 0,
//                'step' => 5,
//            ),
//            // DATE
//            array(
//                'name' => __( 'Date picker', 'rwmb' ),
//                'id'   => "{$prefix}date",
//                'type' => 'date',
//
//                // jQuery date picker options. See here http://api.jqueryui.com/datepicker
//                'js_options' => array(
//                    'appendText'      => __( '(yyyy-mm-dd)', 'rwmb' ),
//                    'dateFormat'      => __( 'yy-mm-dd', 'rwmb' ),
//                    'changeMonth'     => true,
//                    'changeYear'      => true,
//                    'showButtonPanel' => true,
//                ),
//            ),
//            // DATETIME
//            array(
//                'name' => __( 'Datetime picker', 'rwmb' ),
//                'id'   => $prefix . 'datetime',
//                'type' => 'datetime',
//
//                // jQuery datetime picker options.
//                // For date options, see here http://api.jqueryui.com/datepicker
//                // For time options, see here http://trentrichardson.com/examples/timepicker/
//                'js_options' => array(
//                    'stepMinute'     => 15,
//                    'showTimepicker' => true,
//                ),
//            ),
//            // TIME
//            array(
//                'name' => __( 'Time picker', 'rwmb' ),
//                'id'   => $prefix . 'time',
//                'type' => 'time',
//
//                // jQuery datetime picker options.
//                // For date options, see here http://api.jqueryui.com/datepicker
//                // For time options, see here http://trentrichardson.com/examples/timepicker/
//                'js_options' => array(
//                    'stepMinute' => 5,
//                    'showSecond' => true,
//                    'stepSecond' => 10,
//                ),
//            ),
//            // COLOR
//            array(
//                'name' => __( 'Color picker', 'rwmb' ),
//                'id'   => "{$prefix}color",
//                'type' => 'color',
//            ),
//            // CHECKBOX LIST
//            array(
//                'name' => __( 'Checkbox list', 'rwmb' ),
//                'id'   => "{$prefix}checkbox_list",
//                'type' => 'checkbox_list',
//                // Options of checkboxes, in format 'value' => 'Label'
//                'options' => array(
//                    'value1' => __( 'Label1', 'rwmb' ),
//                    'value2' => __( 'Label2', 'rwmb' ),
//                ),
//            ),
//            // EMAIL
//            array(
//                'name'  => __( 'Email', 'rwmb' ),
//                'id'    => "{$prefix}email",
//                'desc'  => __( 'Email description', 'rwmb' ),
//                'type'  => 'email',
//                'std'   => 'name@email.com',
//            ),
//            // RANGE
//            array(
//                'name'  => __( 'Range', 'rwmb' ),
//                'id'    => "{$prefix}range",
//                'desc'  => __( 'Range description', 'rwmb' ),
//                'type'  => 'range',
//                'min'   => 0,
//                'max'   => 100,
//                'step'  => 5,
//                'std'   => 0,
//            ),
//            // URL
//            array(
//                'name'  => __( 'URL', 'rwmb' ),
//                'id'    => "{$prefix}url",
//                'desc'  => __( 'URL description', 'rwmb' ),
//                'type'  => 'url',
//                'std'   => 'http://google.com',
//            ),
//            // OEMBED
//            array(
//                'name'  => __( 'oEmbed', 'rwmb' ),
//                'id'    => "{$prefix}oembed",
//                'desc'  => __( 'oEmbed description', 'rwmb' ),
//                'type'  => 'oembed',
//            ),
//            // SELECT ADVANCED BOX
//            array(
//                'name'     => __( 'Select', 'rwmb' ),
//                'id'       => "{$prefix}select_advanced",
//                'type'     => 'select_advanced',
//                // Array of 'value' => 'Label' pairs for select box
//                'options'  => array(
//                    'value1' => __( 'Label1', 'rwmb' ),
//                    'value2' => __( 'Label2', 'rwmb' ),
//                ),
//                // Select multiple values, optional. Default is false.
//                'multiple'    => false,
//                // 'std'         => 'value2', // Default value, optional
//                'placeholder' => __( 'Select an Item', 'rwmb' ),
//            ),
//            // TAXONOMY
//            array(
//                'name'    => __( 'Taxonomy', 'rwmb' ),
//                'id'      => "{$prefix}taxonomy",
//                'type'    => 'taxonomy',
//                'options' => array(
//                    // Taxonomy name
//                    'taxonomy' => 'category',
//                    // How to show taxonomy: 'checkbox_list' (default) or 'checkbox_tree', 'select_tree', select_advanced or 'select'. Optional
//                    'type' => 'checkbox_list',
//                    // Additional arguments for get_terms() function. Optional
//                    'args' => array()
//                ),
//            ),
//            // POST
//            array(
//                'name'    => __( 'Posts (Pages)', 'rwmb' ),
//                'id'      => "{$prefix}pages",
//                'type'    => 'post',
//
//                // Post type
//                'post_type' => 'page',
//                // Field type, either 'select' or 'select_advanced' (default)
//                'field_type' => 'select_advanced',
//                // Query arguments (optional). No settings means get all published posts
//                'query_args' => array(
//                    'post_status'    => 'publish',
//                    'posts_per_page' => - 1,
//                )
//            ),
//            // WYSIWYG/RICH TEXT EDITOR
//            array(
//                'name' => __( 'WYSIWYG / Rich Text Editor', 'rwmb' ),
//                'id'   => "{$prefix}wysiwyg",
//                'type' => 'wysiwyg',
//                // Set the 'raw' parameter to TRUE to prevent data being passed through wpautop() on save
//                'raw'  => false,
//                'std'  => __( 'WYSIWYG default value', 'rwmb' ),
//
//                // Editor settings, see wp_editor() function: look4wp.com/wp_editor
//                'options' => array(
//                    'textarea_rows' => 4,
//                    'teeny'         => true,
//                    'media_buttons' => false,
//                ),
//            ),
//            // DIVIDER
//            array(
//                'type' => 'divider',
//                'id'   => 'fake_divider_id', // Not used, but needed
//            ),
//            // FILE UPLOAD
//            array(
//                'name' => __( 'File Upload', 'rwmb' ),
//                'id'   => "{$prefix}file",
//                'type' => 'file',
//            ),
//            // FILE ADVANCED (WP 3.5+)
//            array(
//                'name' => __( 'File Advanced Upload', 'rwmb' ),
//                'id'   => "{$prefix}file_advanced",
//                'type' => 'file_advanced',
//                'max_file_uploads' => 4,
//                'mime_type' => 'application,audio,video', // Leave blank for all file types
//            ),
//            // IMAGE UPLOAD
//            array(
//                'name' => __( 'Image Upload', 'rwmb' ),
//                'id'   => "{$prefix}image",
//                'type' => 'image',
//            ),
//            // THICKBOX IMAGE UPLOAD (WP 3.3+)
//            array(
//                'name' => __( 'Thickbox Image Upload', 'rwmb' ),
//                'id'   => "{$prefix}thickbox",
//                'type' => 'thickbox_image',
//            ),
//            // PLUPLOAD IMAGE UPLOAD (WP 3.3+)
//            array(
//                'name'             => __( 'Plupload Image Upload', 'rwmb' ),
//                'id'               => "{$prefix}plupload",
//                'type'             => 'plupload_image',
//                'max_file_uploads' => 4,
//            ),
//            // IMAGE ADVANCED (WP 3.5+)
//            array(
//                'name'             => __( 'Image Advanced Upload', 'rwmb' ),
//                'id'               => "{$prefix}imgadv",
//                'type'             => 'image_advanced',
//                'max_file_uploads' => 4,
//            ),
//            // BUTTON
//            array(
//                'id'   => "{$prefix}button",
//                'type' => 'button',
//                'name' => ' ', // Empty name will "align" the button to all field inputs
//            ),
//
//        )
//    );

    return $meta_boxes;
}

