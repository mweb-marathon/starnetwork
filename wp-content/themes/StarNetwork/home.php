<?php
/*
Template Name: Star Network Home Page
*/
?>
<?php
/**
 * @author Maxim Bogdanov <sin666m4a1fox@gmail.com>
 * @copyright maxim 1/14/15 | 12:57 PM
 */


$star_network_homepage_carousel_option = get_option('star_network_theme_options_menu');
$star_network_homepage_statistic_publications_number = get_option('star-network-homepage-statistic-publications-number');
$star_network_homepage_statistic_annual_events_number = get_option('star-network-homepage-statistic-annual-events-number');
$star_network_homepage_statistic_unique_visitors_number = get_option('star-network-homepage-statistic-unique-visitors-number');
?>

<?php get_header(); ?>
    <div class="row widecolumn star-network-home-pages">
        <div class="extra-post-gallery">
            <ul class="bjqs">
                <?php foreach ($star_network_homepage_carousel_option['star_network_slider_images'] as $single_carousel_image):
                    $current_post = get_post($single_carousel_image['event_id']);
                    ?>
                    <li class="" style="">
                        <div class="bg-image page-header large-12">
                            <img src="<?php echo $single_carousel_image['url']; ?>" alt=""/>

                            <div class="page-header-text-wrapper">
                                <div class="page-header-text">
                                    <div class="title">
                                        <a href="<?php echo get_permalink($single_carousel_image['event_id']) ?>"><?php echo $current_post->post_title; ?></a>
                                    </div>
                                    <div class="text">
                                        <?php echo $current_post->post_excerpt; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="page-header-site-statistic large-12 columns">
            <ul>
                <li class="">
                    <span class="number"><?php echo $star_network_homepage_statistic_publications_number; ?></span>
                    publications
                </li>
                <li class="">
                    <span class="number"><?php echo $star_network_homepage_statistic_annual_events_number; ?></span>
                    annual events
                </li>
                <li class="">
                    <span class="number"><?php echo $star_network_homepage_statistic_unique_visitors_number; ?></span>
                    unique visitors
                </li>
            </ul>
        </div>
    </div>

    <div class="width100 star-network-homepage-content-wrapper">
        <div id="waitMe"></div>
        <div id="content" data-start-event-spots="1" class="row">
            <div class="arrow left hide">
                <a href="#" class="prev"></a>
            </div>
            <div class="star-network-homepage-content">
                <?php schneps_get_event_by_date('star_network'); ?>
            </div>
            <div class="arrow right">
                <a href="#" class="next"></a>
            </div>
        </div>


    </div>




<?php get_footer(); ?>