<?php
/**
 * @author Maxim Bogdanov <sin666m4a1fox@gmail.com>
 * @copyright maxim 12/11/14 | 11:55 AM
 */

$url = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));

$url_arr = explode('/', $_SERVER['REQUEST_URI']);
if (!empty($url_arr[1]) && $url_arr[1] == 'events') {
    $event_class = 'events-case';
}
?>

<?php get_header('star_network'); ?>
    <div class="star-main-content-wrapper" style="background: url('<?php echo $url; ?>') no-repeat; min-height: 600px;">
        <div id="content" class="row widecolumn extra-pages <?php echo $event_class ?>">
            <div class="star-network-content-wrapper-bg">
                <div class="star-network-content">
                    <?php if (have_posts()) : ?>
                        <?php while (have_posts()) : the_post(); ?>
                            <?php if (get_the_content() !== ''): ?>
<!--                                                            <div class="star-network-additional-page-title">
                                <?php echo get_the_title(); ?>
                                                            </div>-->


                                <div class="star-network-additional-page-content">
                                    <?php the_content(); ?>
                                </div>
                            <?php endif; ?>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>

            </div>
        </div>
        <div id="content-bottom-bg"></div>
    </div>
<?php get_footer('star_network'); ?>