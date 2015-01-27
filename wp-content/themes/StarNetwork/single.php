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
<!--        <div class="post-social-button" id="post-social-button">-->
<!--            <div class="post-social-button-wrapper">-->
<!--                --><?php //echo do_shortcode('[ssba]'); ?>
<!--            </div>-->
<!--        </div>-->
        <div id="content" class="row site-content" role="main">
            <?php
            // Start the Loop.
            while (have_posts()) : the_post();
                ?>

                <div class="extra-post-header">
                    <div class="post-info-additional">
                        <h1 class="headline"><?php echo get_the_title(); ?></h1>
                    </div>
                    <div class="image text-center">
                        <?php echo get_the_post_thumbnail(); ?>
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
        <!-- #content -->
    </div><!-- #primary -->

<?php get_footer('star_network');