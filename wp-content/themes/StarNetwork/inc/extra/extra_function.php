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
function dynamic_add_people_box()
{
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
                    ?>
                    <p>
                        People
                        <input type="text" name="event[<?php echo $c; ?>][people]"
                               value="<?php echo $value['people']; ?>"/>
                        <input type="hidden" name="event[<?php echo $c; ?>][id]" value="<?php echo $value['id']; ?>"/>
                        <select name="event[<?php echo $c; ?>][people_role]" id="people-role">
                            <option value="emcee" <?php echo $value['people_role'] == 'emcee' ? 'selected' : ''; ?>>
                                Emcee
                            </option>
                            <option
                                value="hall_of_fame" <?php echo $value['people_role'] == 'hall_of_fame' ? 'selected' : ''; ?>>
                                Hall of Fame
                            </option>
                            <option
                                value="keynote_speaker" <?php echo $value['people_role'] == 'keynote_speaker' ? 'selected' : ''; ?>>
                                Keynote Speaker
                            </option>
                            <option
                                value="moderator" <?php echo $value['people_role'] == 'moderator' ? 'selected' : ''; ?>>
                                Moderator
                            </option>
                            <option
                                value="honorees" <?php echo $value['people_role'] == 'honorees' ? 'selected' : ''; ?>>
                                Honorees
                            </option>
                            <option
                                value="speakers" <?php echo $value['people_role'] == 'speakers' ? 'selected' : ''; ?>>
                                Speakers
                            </option>
                        </select>
                        <span class="remove"><?php echo __('Remove People'); ?></span>
                    </p>
                    <?php $c = $c + 1;
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

                    $('#sponsor-people').append('' +
                        '<p> ' +
                        'People <input type="text"  class="people" name="event[' + count + '][people]" value="" class="people" />' +
                        '<input type="hidden" name="event[' + count + '][id]">' +
                        '<select name="event[' + count + '][people_role]" id="people-role">' +
                        '<option value="emcee">Emcee</option>' +
//                        '<option value="special_honoree">Special Honoree(s)</option> ' +
                        '<option value="hall_of_fame">Hall of Fame</option> ' +
                        '<option value="keynote_speaker">Keynote Speaker</option>' +
                        '<option value="moderator">Moderator</option>' +
                        '<option value="honorees">Honorees</option>' +
                        '<option value="speakers">Speakers</option>=' +
                        '</select>' +
                        '<span class="remove">Remove People</span>' +
                        '</p>');
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
                    $c = $c + 1;?>
                    <p>
                        Sponsor
                        <input type="text" name="event_sponsor[<?php echo $c; ?>][sponsor]" value="<?php echo $value['sponsor']; ?>"/>
                        <input type="hidden" name="event_sponsor[<?php echo $c; ?>][id]" value="<?php echo $value['id']; ?>"/>
                        <select name="event_sponsor[<?php echo $c; ?>][sponsor_role]" id="sponsor-role">
                            <option value="presenting" <?php echo $value['sponsor_role'] == 'presenting' ? 'selected' : ''; ?>>
                                Presenting Sponsor
                            </option>
                            <option
                                value="gold" <?php echo $value['sponsor_role'] == 'gold' ? 'selected' : ''; ?>>
                                Gold Sponsor
                            </option>
                            <option
                                value="regular" <?php echo $value['sponsor_role'] == 'regular' ? 'selected' : ''; ?>>
                                Sponsor
                            </option>
                        </select>
                        <span class="remove-sponsor"><?php echo __('Remove Sponsor'); ?></span>
                    </p>
                <?php
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

                    $('#sponsor-sponsor').append('' +
                        '<p> Sponsor ' +
                        '<input type="text"  class="sponsor" name="event_sponsor[' + count + '][sponsor]" value="" class="sponsor" />' +
                        '<input type="hidden" name="event_sponsor[' + count + '][id]">' +
                        '<select name="event_sponsor[' + count + '][sponsor_role]" id="sponsor-role">' +
                        '<option value="presenting">Presenting Sponsor</option>' +
                        '<option value="gold">Gold Sponsor</option> ' +
                        '<option value="regular">Sponsor</option>' +
                        '</select>' +
                        '<span class="remove-sponsor">Remove Sponsor</span>' +
                        '</p>');
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
