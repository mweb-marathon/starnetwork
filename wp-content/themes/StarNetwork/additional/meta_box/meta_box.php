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


add_filter( 'rwmb_meta_boxes', 'additional_register_meta_boxes' );

/**
 * Register meta boxes
 *
 * Remember to change "your_prefix" to actual prefix in your project
 *
 * @return void
 */
function additional_register_meta_boxes( $meta_boxes )
{


//    echo '<pre>'.print_r( array('value1' => __( 'Label1', 'rwmb' ),'value2' => __( 'Label2', 'rwmb' ),) , 1).'</pre>';
//    echo '<pre>'.print_r( $story_goto_array , 1).'</pre>';

    /**
     * prefix of meta keys (optional)
     * Use underscore (_) at the beginning to make keys hidden
     * Alt.: You also can make prefix empty to disable it
     */
    // Better has an underscore as last sign

    $event_prefix = 'event_post_';



    // 1st meta box
    $meta_boxes[] = array(
        // Meta box id, UNIQUE per meta box. Optional since 4.1.5
        'id' => 'event_type',

        // Meta box title - Will appear at the drag and drop handle bar. Required.
        'title' => __( 'Event Type', 'rwmb' ),

        // Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
        'pages' => array('event'),

        // Where the meta box appear: normal (default), advanced, side. Optional.
        'context' => 'normal',

        // Order of meta box: high (default), low. Optional.
        'priority' => 'high',

        // Auto save: true, false (default). Optional.
        'autosave' => true,

        // List of meta fields
        'fields' => array(
            // CHECKBOX LIST
            array(
                'name' => __( 'Post Type', 'rwmb' ),
                'id'   => "{$event_prefix}event_type",
                'type' => 'select',
                // Options of checkboxes, in format 'value' => 'Label'
                'options' => array(
                    'kings' => __( 'Kings', 'rwmb' ),
                    'top-women' => __( 'Top Women', 'rwmb' ),
                    'latino-stars' => __( 'Latino Stars', 'rwmb' ),
                    'real-estate' => __( 'Real Estate', 'rwmb' ),
                    'health-fitness' => __( 'Health & Fitness', 'rwmb' ),
                    'kids-family' => __( 'Kids & Family', 'rwmb' ),
                    'networking' => __( 'Networking', 'rwmb' ),
                    'catchall' => __( '"Catchall"', 'rwmb' ),

                ),
            ),
            array(
                'name'             => __( 'Add background image', 'rwmb' ),
                'id'               => "{$event_prefix}sponsored_image",
                'type'             => 'image_advanced',
                'max_file_uploads' => 1,
            ),
        ),
    );

    $meta_boxes[] = array(
        // Meta box id, UNIQUE per meta box. Optional since 4.1.5
        'id' => 'additional_post_gallery',

        // Meta box title - Will appear at the drag and drop handle bar. Required.
        'title' => __( 'Custom Fields', 'rwmb' ),

        // Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
        'pages' => array('post'),

        // Where the meta box appear: normal (default), advanced, side. Optional.
        'context' => 'normal',

        // Order of meta box: high (default), low. Optional.
        'priority' => 'high',

        // Auto save: true, false (default). Optional.
        'autosave' => true,

        // List of meta fields
        'fields' => array(
            array(
                'name'      => __( 'Related Stories', 'rwmb' ),
                'id'        => "{$event_prefix}related_story",
                'type'    => 'post',
                'field_type' => 'select_advanced',
                'post_type' => array('post'),
                'multiple'  => true,
                'std'       => '',
                'query_args' => array(
                    'post_status'    => 'publish',
                    'posts_per_page' => - 1,
                ),
                'placeholder' => __( 'Select a Related Stories', 'rwmb' ),

            ),
            // IMAGE ADVANCED (WP 3.5+)
            array(
                'name'             => __( 'Image Advanced Upload', 'rwmb' ),
                'id'               => "{$event_prefix}imgadv",
                'type'             => 'image_advanced',
                'max_file_uploads' => 4,
            )
        ),
    );

    return $meta_boxes;
}

