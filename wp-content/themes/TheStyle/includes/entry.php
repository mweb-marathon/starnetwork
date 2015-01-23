<?php
	$i = 0;
	$show_sidebar = (get_option('thestyle_sidebar') == 'on') ? true : false;

	if ( is_home() ){
		$show_sidebar = (get_option('thestyle_sidebar_home') == 'on') ? true : false;
	}
?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<?php if (get_option('thestyle_blog_style') == 'false') { ?>

		<?php $biglayout = ( (bool) get_post_meta(get_the_ID(),'et_bigpost',true) ) ? true : false;

			$thumb = '';
			$width = $biglayout ? 466 : 222;
			$height = 180;
			$classtext = '';
			$titletext = get_the_title();

			$thumbnail = get_thumbnail($width,$height,$classtext,$titletext,$titletext);
			$thumb = $thumbnail["thumb"]; ?>

		<div class="entry <?php if ($biglayout) echo('big'); else echo('small');?>">
			<div class="thumbnail">
				<a href="<?php the_permalink(); ?>">
					<?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height, $classtext); ?>
					<span class="overlay"></span>
				</a>
				<div class="category"><?php the_category(); ?></div>
				<span class="month"><?php the_time('M'); ?><span class="date"><?php the_time('d'); ?></span></span>
			</div> <!-- end .thumbnail -->
			<h2 class="title"><a href="<?php the_permalink(); ?>"><?php if ($biglayout) truncate_title(50); else truncate_title(20); ?></a></h2>
			<p class="postinfo"><?php esc_html_e('posted by','TheStyle'); ?> <?php the_author_posts_link(); ?></p>
			<div class="entry-content">
				<div class="bottom-bg">
					<div class="excerpt">
						<p><?php if ($biglayout) truncate_post(650); else truncate_post(295); ?> </p>
						<div class="textright">
							<a href="<?php the_permalink(); ?>" class="readmore"><span>&raquo;</span>&raquo;</a>
						</div>
					</div><!-- end .excerpt -->
				</div><!-- end .bottom-bg -->
			</div><!-- end .entry-content -->
		</div><!-- end .entry -->

	<?php } else { ?>

		<div class="post">
			<div class="post-content clearfix">
				<div class="post-text">
					<h2 class="blog-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

					<?php if (get_option('thestyle_postinfo1') ) { ?>
						<p class="post-meta">
							<?php esc_html_e('Posted','TheStyle'); ?> <?php if (in_array('author', get_option('thestyle_postinfo1'))) { ?> <?php esc_html_e('by','TheStyle'); ?> <?php the_author_posts_link(); ?><?php }; ?><?php if (in_array('date', get_option('thestyle_postinfo1'))) { ?> <?php esc_html_e('on','TheStyle'); ?> <?php the_time(get_option('thestyle_date_format')) ?><?php }; ?><?php if (in_array('categories', get_option('thestyle_postinfo1'))) { ?> <?php esc_html_e('in','TheStyle'); ?> <?php the_category(', ') ?><?php }; ?><?php if (in_array('comments', get_option('thestyle_postinfo1'))) { ?> | <?php comments_popup_link(esc_html__('0 comments','TheStyle'), esc_html__('1 comment','TheStyle'), '% '.esc_html__('comments','TheStyle')); ?><?php }; ?>
						</p>
					<?php }; ?>

					<div class="hr"></div>

					<?php the_content(); ?>

					<?php wp_link_pages(array('before' => '<p><strong>'.esc_html__('Pages','TheStyle').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
					<?php edit_post_link(esc_html__('Edit this page','TheStyle')); ?>
				</div> <!-- .post-text -->

				<div class="info-panel">
					<?php get_template_part('includes/infopanel'); ?>
				</div> <!-- end .info-panel -->
			</div> <!-- .post-content -->
		</div><!-- end .post -->

	<?php } ?>

	<?php $i++; ?>
<?php endwhile; ?>
		</div> <!-- #boxes -->
		<?php if ($show_sidebar) get_sidebar(); ?>
	</div> <!-- #content -->
	<div id="controllers" class="clearfix">
		<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); }
		get_template_part('includes/navigation'); ?>
	</div> <!-- #controllers -->
<?php else : ?>
			<?php get_template_part('includes/no-results'); ?>
		</div> <!-- #boxes -->
	</div> <!-- #content -->
<?php endif; ?>