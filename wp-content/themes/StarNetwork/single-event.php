<?php
/**
 * @author Maxim Bogdanov <sin666m4a1fox@gmail.com>
 * @copyright maxim 12/11/14 | 11:55 AM
 */

$url = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));
$event_meta = get_post_meta(get_the_ID());

$people_sponsor = !empty($event_meta['event']) ? $event_meta['event'] : false;
$sponsor_sponsor = !empty($event_meta['event_sponsor']) ? $event_meta['event_sponsor'] : false;

$additional_data = array();

if (!empty($people_sponsor)) {
    $additional_data['sponsor_people'] = unserialize($people_sponsor[0]);
}

if (!empty($sponsor_sponsor)) {
    $additional_data['sponsor_sponsor'] = unserialize($sponsor_sponsor[0]);
}

$is_sponsored = !empty($event_meta['event_post_event_type'][0]) && $event_meta['event_post_event_type'][0] == 'sponsored' ? $event_meta['event_post_event_type'][0] : false;
$comment_turn_on = null;
$sponsored_by = null;
$event_date_start = $event_meta['_event_start_date'][0];
$event_location = em_get_location($event_meta['_event_id'][0]);
$event_date_end = $event_meta['_event_end_date'][0];
$event_time_start = $event_meta['_event_start_time'][0];
$event_time_end = $event_meta['_event_end_time'][0];
$event_type = !empty($event_meta['event_post_event_type']) ? $event_meta['event_post_event_type'][0] : '';
$star_network_people_title_on_event_page = get_option('star-network-people-title-on-event-page');

$address = '';
$map_link = '';
$social_link = array();

$sponsored_image = !empty($event_meta['event_post_sponsored_image']) ? $event_meta['event_post_sponsored_image'][0] : '';

if ($sponsored_image !== '') {
    $sponsored_image = wp_get_attachment_image_src($sponsored_image, 'original');
}
$thumb_id = get_post_thumbnail_id();
$thumb_url = wp_get_attachment_image_src($thumb_id, 'original');
?>

<?php get_header('star_network'); ?>
    <div class="star-main-content-wrapper single-event-page"
         style="background: url('<?php echo $sponsored_image[0]; ?>') no-repeat; min-height: 600px;">
        <div id="content" class="row widecolumn extra-pages">
            <div class="star-network-content">
                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <div class="star-network-additional-page-title">
                            <?php echo get_the_title(); ?>
                            <div class="event-type <?php echo $event_type; ?>"></div>
                            <div class="event-additional-info">
                                <ul>
                                    <li><?php echo $event_location->location_name; ?></li>
                                    <li>/</li>
                                    <li><?php echo apply_filters('filter_date', array('string' => $event_date_start, "format" => "l, F d")); ?></li>
                                    <li>/</li>
                                    <li><?php echo date("g:i A", strtotime($event_time_start)); ?>
                                        - <?php echo date("g:i A", strtotime($event_time_end)); ?></li>
                                </ul>
                            </div>
                        </div>

                        <div class="event-method">
                            <button class="red">Get Tickets</button>
                            <button class="yellow">Sponsor / Exhibit</button>
                        </div>

                        <?php if (get_the_content() !== ''): ?>
                            <div class="star-network-additional-page-content">
                                <div class="excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                                <div class="content">
                                    <?php the_content(); ?>
                                </div>
                            </div>
                            <div class="star-network-additional-event-honoree">
                                <div class="people-honoree-wrapper">
                                    <div class="people-honoree-title">
                                        <?php echo $star_network_people_title_on_event_page; ?>
                                    </div>
                                    <div class="people-honoree-data">
                                        <?php if (!empty($additional_data['sponsor_people'])): ?>
                                            <?php get_single_event_additional_people_data($additional_data['sponsor_people']); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="star-network-additional-event-info">
                                <div class="event-sponsor-wrapper">
                                    <div class="event-sponsor-data">
                                        <?php if (!empty($additional_data['sponsor_sponsor'])): ?>
                                            <?php get_single_event_additional_sponsor_data($additional_data['sponsor_sponsor']); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="event-method-after-content">
                                    <button class="red">Get Tickets</button>
                                    <button class="yellow">Sponsor / Exhibit</button>
                                </div>
                                <div class="event-map-wrapper">
                                    <div class="event-map-title">Event Location</div>
                                    <?php get_event_map_(get_the_ID()); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
        <div id="content-bottom-bg"></div>
    </div>
<?php get_footer('star_network'); ?>