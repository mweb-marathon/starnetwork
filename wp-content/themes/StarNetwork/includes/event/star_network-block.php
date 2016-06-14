<?php
/**
 * @author Maxim Bogdanov <sin666m4a1fox@gmail.com>
 * @copyright maxim 10/3/14 | 6:08 PM
 */
$post_meta = get_post_meta(get_the_ID());
$post_type = get_post_type();
$is_sponsored = !empty($post_meta['event_post_event_type'][0]) ? $post_meta['event_post_event_type'][0] : false;
$start_event = $post_meta['_event_start_date'][0];
$ticket_link = !empty($post_meta['event_post_ticket_link'][0]) ? $post_meta['event_post_ticket_link'][0] : false;

$category = get_the_category();
?>
<div class="large-4 single-event-wrapper columns">
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
                <div class="rsvpt">rsvpt</div>
            <?php endif; ?>
        </div>
    </div>
    <div class="bg-image">
        <?php get_image_for_spot(); ?>
    </div>
    <div class="bg-color"></div>
    <div class="title">
        <a href="<?php the_permalink(); ?>">
            <?php echo get_the_ID(); ?>
            <?php echo get_the_title(); ?>
        </a>
    </div>
    <div class="footer qns">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/small-logo-<?php echo $is_sponsored; ?>.png" alt=""/>
        <span class="footer-text"> / Queens <?php if($ticket_link):?>/ <a href="<?php echo $ticket_link; ?>" target="_blank">Get Tickets</a><?php endif; ?></span>
    </div>
</div>