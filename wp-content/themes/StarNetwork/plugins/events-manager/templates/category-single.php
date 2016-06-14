<?php
/*
 * This page displays a single event, called during the em_content() if this is an event page.
 * You can override the default display settings pages by copying this file to yourthemefolder/plugins/events-manager/templates/ and modifying it however you need.
 * You can display events however you wish, there are a few variables made available to you:
 *
 * $args - the args passed onto EM_Events::output()
 */
global $EM_Category;
/* @var $EM_Category EM_Category */
//echo $EM_Category->output_single();
?>
<?php
/**
 * @author Maxim Bogdanov <sin666m4a1fox@gmail.com>
 * @copyright maxim 1/14/15 | 12:57 PM
 */

?>



    <div id="content" class="row columns star-network-attend-pages single-event-category" data-single-event-category="<?php echo $EM_Category->name; ?>" data-parent-single-event-category="<?php echo $EM_Category->parent; ?>">
        <h1>Upcoming Events</h1>

        <div class="main-content">
            <div class="filter">
                <div class="large-2 columns filter-wrapper">
                    <span class="filter-text">
                        filter by
                    </span>

                    <div class="buttons">
                        <label for="radio1" class="disabled">
                            <input name="radio1" type="radio" id="radio1" checked>
                            <span class="custom radio checked"></span>
                            topic
                        </label>
                        <label for="radio2">
                            <input name="radio1" type="radio" id="radio2">
                            <span class="custom radio"></span>
                            location
                        </label>
                    </div>
                </div>
                <div class="large-10 columns categories">
                    <div id="waitMe"></div>
                    <div class="content">
                        <?php star_network_attend_event_category($EM_Category->parent); ?>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <div class="row main-content-data upcoming-event-page-wrapper">

        <div class="spots-wrapper columns" id="content" data-start-event-spots="1">
            <div id="waitMeSpot"></div>
            <div class="arrow left hide">
                <a href="#" class="prev"></a>
            </div>
            <div class="star-network-homepage-content">
                <?php schneps_get_event_by_date('star_network', 6, 1); ?>
            </div>
            <div class="arrow right">
                <a href="#" class="next"></a>
            </div>
        </div>
    </div>

