<?php
/**
 * @author Maxim Bogdanov <sin666m4a1fox@gmail.com>
 * @copyright maxim 10/3/14 | 6:08 PM
 */
$post_meta = get_post_meta(get_the_ID());


$em_location_name = empty($post_meta['_event_id'][0]) ? '' : EM_Events::getEventVenueNameByEventId($post_meta['_event_id'][0]);

$category_name = EM_Events::getEventOneCategoryNameByPostId(get_the_ID());

$post_type = get_post_type();
$is_sponsored = !empty($post_meta['event_post_event_type'][0]) ? $post_meta['event_post_event_type'][0] : false;
$start_event = $post_meta['_event_start_date'][0];
$ticket_link = !empty($post_meta['event_post_ticket_link'][0]) ? $post_meta['event_post_ticket_link'][0] : false;

?>
<div class="large-4 single-event-wrapper columns">
    <a href="<?php the_permalink(); ?>">
    <div class="event-start-date">
        <div class="date">
            <div class="month">
                <?php echo apply_filters('filter_date', array('string' => $start_event, "format" => "M")); ?>
            </div>
            <div class="day">
                <?php echo apply_filters('filter_date', array('string' => $start_event, "format" => "d")); ?>
            </div>
        </div>
        <div class="rsvp">
            <?php if ($is_sponsored == 'networking' || $is_sponsored == 'sponsored'): ?>
                <!--<div class="rsvpt">rsvpt</div>-->
            <?php endif; ?>
        </div>
    </div>
    </a>
    <a href="<?php the_permalink(); ?>">
        <div class="bg-image">
            <?php get_image_for_spot(); ?>
        </div>
    </a>
    <a href="<?php the_permalink(); ?>">
        <div class="bg-color"></div>
    </a>
    <a href="<?php the_permalink(); ?>">
    <div class="title">
        <?php echo get_the_title(); ?>
    </div>
    </a>
    <a href="<?php the_permalink(); ?>">
    <div class="footer qns">
        <img width="40" src="<?php echo scheps_get_event_category_image($category_name) ?>" alt=""/>
        <span class="footer-text"> <?php echo !empty($em_location_name) ? '/ '. $em_location_name : ''; ?>  <?php if($ticket_link):?>/ <a href="<?php echo $ticket_link; ?>" target="_blank">Get Tickets</a><?php endif; ?></span>
    </div>
    </a>
</div>