<?php
global $wpdb;
$post_id = $_POST['post_id'];

$story_meta = get_post_meta($post_id);
$story_type = get_post_type($post_id);

$story_type = $story_type == 'post' ? 'post' : '';


$related_stories = $story_meta['event_post_related_story'];

if (count($related_stories) > 0) {

    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'post__in' => $related_stories,
    );

    $related_post = null;
    $related_post = new WP_Query($args);
    if ($related_post->post_count > 0) {
        ?>
        <div class="related-post">
            <div class="related-post-title">
                Related Stories
            </div>
            <div class="related-post-block">
                <?php
                while ($related_post->have_posts()) : $related_post->the_post();
                    $post_meta = get_post_meta(get_the_ID());
                    $width = 222;
                    $height = 180;
                    $classtext = '';
                    $titletext = get_the_title();

                    $thumbnail = get_thumbnail($width, $height, $classtext, $titletext, $titletext);
                    $thumb = $thumbnail["thumb"];

                    ?>
                    <div class="related-post-wrapper">
                        <div class="related-post-thumbnail">
                            <?php echo $thumb ? print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, $classtext) : '<div class="no-image">No Image</div>'; ?>
                        </div>

                        <div class="related-post-headline">
                            <a href="<?php the_permalink() ?>" rel="bookmark"
                               title="Permanent Link to <?php the_title_attribute(); ?>">
                                <?php echo get_the_title(); ?>
                            </a>
                        </div>
                    </div>
                <?php
                endwhile;
                ?>
            </div>
        </div>
    <?php
    }
    wp_reset_query();
}
