<?php
/*
Template Name: Star Network Attend Page
*/
?>
<?php
/**
 * @author Maxim Bogdanov <sin666m4a1fox@gmail.com>
 * @copyright maxim 1/14/15 | 12:57 PM
 */


?>

<?php get_header('star_network'); ?>

    <div id="content" class="row widecolumn star-network-attend-pages">
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
                        <?php star_network_attend_event_category();?>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <div class="row main-content-data">
        <div id="waitMeSpot"></div>
        <div class="spots-wrapper columns">
                <?php schneps_get_event_by_date('star_network', -1, 0);?>
        </div>
    </div>


<?php get_footer('star_network'); ?>