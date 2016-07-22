<?php
/**
 * The template for displaying Search Results pages
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

    <div id="content" class="buddypress row search-page-wrapper">
        
        <div class="large-9 columns search-content-wrapper">
            <header class="page-header">
                <h1 class="page-title"><?php printf(__('Search Results for: %s'), get_search_query()); ?></h1>
            </header>
            <?php if (have_posts()) : ?>
                <?php if (is_search()) : ?>
                    <?php schneps_paging_nav(); ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <div class="search-content-single">
                            <div class="post-link">
                                <a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a>
                            </div>
                            <div class="post-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                            <div class="post-readmore">
                                <a href="<?php echo esc_url(get_permalink()); ?>">Read More...</a>
                            </div>
                        </div>
                        <hr>
                    <?php endwhile; ?>
                    <?php schneps_paging_nav(); ?>
                <?php endif; ?>
            <?php else: ?>
                <div class="error-page">
                    <h4>There are no matches for your search. Please try another search below:</h4>
                    <form action="/" method="get" id="searchform">
                        <input type="text" placeholder="Search..." name="s" class="s" id="s">
                        <input type="submit" value="Search" class="s-button" id="s-button">
                    </form>
                </div>

            <?php endif; ?>
        </div>
    </div>
<?php
get_footer();
