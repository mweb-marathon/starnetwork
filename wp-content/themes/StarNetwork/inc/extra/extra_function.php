<?php
/**
 * @author Maxim Bogdanov <sin666m4a1fox@gmail.com>
 * @copyright maxim 7/23/14 | 10:30 AM
 */
include 'pages/people/data/people.php';
include 'pages/sponsor/data/sponsor.php';

add_action('add_meta_boxes', 'dynamic_add_people_box');


/* Do something with the data entered */
add_action('save_post', 'dynamic_save_people_data');
add_action('save_post', 'dynamic_save_sponsor_data');

/* Adds a box to the main column on the Post and Page edit screens */
function dynamic_add_people_box() {
    add_meta_box('dynamic_sectionid', __('People', 'myplugin_text_domain'), 'dynamic_inner_people_box', 'event');
    add_meta_box('dynamic_section_sponsor', __('Sponsor', 'myplugin_textdomain'), 'dynamic_inner_sponsor_box', 'event');
}

/* Prints the box content */
function dynamic_inner_people_box()
{
    global $post;

    wp_nonce_field(plugin_basename(__FILE__), 'dynamic_people_noncename');
    ?>
    <div id="meta_inner">
        <?php
        $sponsor_people = get_post_meta($post->ID, 'event', true);
        $c = 0;
        if (!empty($sponsor_people) && count($sponsor_people) > 0) {
            foreach ($sponsor_people as $value) {
                if (isset($value['people'])) {
                    printf('<p> People<input type="text" name="event[%1$s][people]" value="%2$s"><input type="text" name="event[%1$s][id]" value="%3$s"/><span class="remove">%4$s</span></p>', $c, $value['people'], $value['id'], __('Remove People'));
                    $c = $c + 1;
                }
            }
        }
        ?>
        <span id="sponsor-people"></span>
        <span class="add"><?php _e('Add People'); ?></span>
        <script>
            var $ = jQuery.noConflict();
            $(document).ready(function () {
                $(function () {
                    var availablePeople = <?php echo schneps_get_people_for_event_array();?>;

                    $(document).on("focus keyup", "input.people", function (event) {
                        $(this).autocomplete({
                            source: availablePeople,
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
                $(".add").click(function () {
                    count = count + 1;

                    $('#sponsor-people').append('<p> People <input type="text"  class="people" name="event[' + count + '][people]" value="" class="people" /><input type="text" name="event[' + count + '][id]"><span class="remove">Remove People</span></p>');
                    return false;
                });
                $(".remove").live('click', function () {
                    $(this).parent().remove();
                });


            });
        </script>
    </div>
<?php
}

/* When the post is saved, saves our custom data */
function dynamic_save_people_data($post_id)
{

//    echo '<pre>'.print_r( $_POST , 1).'</pre>';

    // verify if this is an auto save routine.
    // If it is our form has not been submitted, so we dont want to do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if (!isset($_POST['dynamic_people_noncename']))
        return;

    if (!wp_verify_nonce($_POST['dynamic_people_noncename'], plugin_basename(__FILE__)))
        return;

    // OK, we're authenticated: we need to find and save the data

    $place = $_POST['event'];

    update_post_meta($post_id, 'event', $place);
}