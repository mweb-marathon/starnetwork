<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header('star_network'); ?>

    <div id="primary" class="content-area news-photo-wrapper">

        <div id="content" class="row site-content" role="main">
            <?php if (!wp_is_mobile()):  ?>
            <div id="right-sidebar" class="large-4 columns">
                <?php if (is_active_sidebar('left_sidebar')) : ?>
                    <?php dynamic_sidebar('left_sidebar'); ?>
                <?php endif; ?>
                <div style="clear:both"></div>
            </div>
            <?php endif; ?>
            <div class="large-8 columns">
                <div class="content-wrapper">
                    <?php if (!wp_is_mobile()): ?>
                    <div class="post-social-button hide-for-small" id="post-social-button">
                        <div class="post-social-button-wrapper">
                            <?php echo do_shortcode('[ssba]'); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php while (have_posts()) : the_post();
                        $story_meta = get_post_meta(get_the_ID());
                        ?>
                         <?php if (wp_is_mobile()): ?>
                        <div class="post-social-button-wrapper">
                            <?php echo do_shortcode('[ssba]'); ?>
                        </div>
                        <?php endif; ?>
                        <div class="extra-post-header">
                            <div class="post-info-additional">
                                <h1 class="headline"><?php echo get_the_title(); ?></h1>
                            </div>
                            <div class="post-thumb-news-photos">
                                <?php get_image_for_spot(); ?>
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
                                <?php
                                $content_post = get_the_content();
                                $gallery_short = array();

                                $gallery_regex = '/\[gallery.*id.*\]/';
                                preg_match($gallery_regex, $content_post, $gallery_short);
                                $gallery_short = $gallery_short[0];
                                if (!empty($gallery_short)) {
                                    $img_ids = array();
                                    preg_match_all('/\d{1,}/', $gallery_short, $img_ids);
                                    $img_ids = $img_ids[0];
                                    $gallery_html = '';
                                    if (!empty($img_ids) && count($img_ids)) {
                                        $gallery_html = '<ul id="photos-gallery" class="shneps-post-gallery">';
                                        foreach ($img_ids as $img_id) {
                                            $image_record = get_post($img_id);
                                            $attachmenturl = wp_get_attachment_url($image_record->ID);
                                            $attachmentimage = wp_get_attachment_image($image_record->ID);
                                            $gallery_html .= '<li><a href="'.$attachmenturl.'">' . $attachmentimage .'</a></li>';

                                        }
                                        $gallery_html .= '</ul>';
                                        $gallery_html .= '<label></label>';
                                        $content_post = preg_replace($gallery_regex, $gallery_html, $content_post);
                                    }
                                }
                                ?>
                                <?php echo html_entity_decode(wpautop($content_post)); ?>
                            </div>

                        </div>
                    <?php endwhile;
                    ?>
                </div>
                <div class="row post-footer-block news-photo-related-list" data-post-id="<?php the_ID(); ?>"></div>
            </div>
            <?php if (wp_is_mobile()):  ?>
            <div id="right-sidebar" class="large-4 columns">
                <?php if (is_active_sidebar('left_sidebar')) : ?>
                    <?php dynamic_sidebar('left_sidebar'); ?>
                <?php endif; ?>
                <div style="clear:both"></div>
            </div>
            <?php endif; ?>
        </div>

    </div>

<?php get_footer('star_network');