<?php
/**
 * @author Maxim Bogdanov <sin666m4a1fox@gmail.com>
 * @copyright maxim 1/27/15 | 12:40 PM
 */
$post_meta = get_post_meta(get_the_ID());
$post_type = get_post_type();

$name = get_the_title();



$company = $post_meta['schneps_people_company_or_organization'][0];

if(!empty($post_meta['schneps_people_link'][0])) {
    $name = '<a href="' . $post_meta['schneps_people_link'][0] . '">' . $name . '</a>';
}

if(!empty($post_meta['schneps_people_company_link'][0])) {
    $company = '<a href="' . $post_meta['schneps_people_company_link'][0] . '">' . $company . '</a>';
}


?>
<li>
    <div class="image">
        <?php get_image_for_sponsor_people();?>
        <div class="headshot">
            <?php echo $post_meta['schneps_people_headshot'][0]; ?>
        </div>
    </div>
    <div class="name">
        <?php echo $name; ?>
    </div>
    <div class="title">
        <?php echo $post_meta['schneps_people_event_associated'][0]; ?>
    </div>
    <div class="company">
            <?php echo $company; ?>
    </div>
</li>