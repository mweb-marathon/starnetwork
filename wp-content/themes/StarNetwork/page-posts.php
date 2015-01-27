<?php
/*
Template Name: Star Network Posts Page
*/
?>
<?php
/**
 * @author Maxim Bogdanov <sin666m4a1fox@gmail.com>
 * @copyright maxim 1/14/15 | 12:57 PM
 */

get_header('star_network');
?>
    <div class="row main-content-data">
        <div class="spots-wrapper columns" id="content">
            <?php schneps_get_posts_by_date(); ?>
        </div>
    </div>
<?php get_footer('star_network'); ?>