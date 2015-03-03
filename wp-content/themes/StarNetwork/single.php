<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header('star_network'); ?>

    <div id="primary" class="content-area">
        <div class="post-social-button" id="post-social-button">
            <div class="post-social-button-wrapper">
                <?php echo do_shortcode('[ssba]'); ?>
            </div>
        </div>
        <div id="content" class="row site-content" role="main">
            <?php while (have_posts()) : the_post();
                $story_meta = get_post_meta(get_the_ID());
                ?>
                <div class="extra-post-header">
                    <div class="post-info-additional">
                        <h1 class="headline"><?php echo get_the_title(); ?></h1>
                    </div>
                    <div class="image">
                        <?php if (isset($story_meta['event_post_imgadv']) && count($story_meta['event_post_imgadv']) > 0): ?>
                            <div class="post-gallery">
                                <ul class="bjqs">
                                    <?php foreach ($story_meta['event_post_imgadv'] as $key => $val):
                                        $attachment_data = wp_get_attachment($val);
                                        ?>
                                        <li>
                                            <a title="<?php echo $attachment_data['caption']; ?>" rel="gallery1"
                                               href="/slideshow/?type=slideshow&post=<?php echo get_the_ID() ?>&start=<?php echo $key ?>">
                                                <?php echo wp_get_attachment_image($val, 'full'); ?>
                                            </a>

                                            <div
                                                class="slider-image-info-wrapper <?php echo strlen($attachment_data['description']) > 200 ? 'expandable' : ''; ?>">
                                                <div class="slider-image-info-caption">
                                                    <?php echo $attachment_data['caption']; ?>
                                                </div>
                                                <div class="slider-image-info-description">
                                                    <?php echo $attachment_data['description']; ?>
                                                </div>
                                                <?php if (strlen($attachment_data['description']) > 200): ?>
                                                    <h4 class="s-byline"></h4>
                                                <?php endif; ?>
                                            </div>

                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>


                </div>
                <div class="under-image-wrapper">
                    <ul class="date-time-info">
                        <li>
                            By <?php echo get_the_author(); ?>
                        </li>
                        <li>/</li>
                        <li>
                            <?php echo get_the_time('l, F jS, Y'); ?>
                        </li>
                        <li>/</li>
                        <li>
                            <?php echo get_the_time('h:i A T'); ?>
                        </li>
                    </ul>
                    <div class="extra-post-content">
                        <?php echo html_entity_decode(wpautop(get_the_content())); ?>
                    </div>
                </div>
            <?php endwhile;
            ?>
        </div>
    </div>
<?php get_footer('star_network');