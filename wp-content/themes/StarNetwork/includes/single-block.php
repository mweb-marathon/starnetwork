<div class="entry large-4 medium-4 columns small story">
    <div class="thumbnail">
        <?php get_image_for_spot(); ?>
    </div>
    <div class="entry-post-wrapper">
        <div class="entry-post-info">
            <a href="<?php the_permalink(); ?>">
                <?php echo crop_text(get_the_title(), 50);; ?>
            </a>
        </div>
        <div class="red-line Px10 relative"></div>
    </div>
</div>