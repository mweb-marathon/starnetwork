<?php
/*
Template Name: Sitemap Page
*/
?>
<?php
$et_ptemplate_settings = array();
$et_ptemplate_settings = maybe_unserialize( get_post_meta(get_the_ID(),'et_ptemplate_settings',true) );

$fullwidth = isset( $et_ptemplate_settings['et_fullwidthpage'] ) ? (bool) $et_ptemplate_settings['et_fullwidthpage'] : false;
?>

<?php get_header(); ?>

<?php get_template_part('includes/breadcrumbs'); ?>

<div id="content" class="clearfix<?php if($fullwidth) echo(' fullwidth');?>">
	<?php if (get_option('thestyle_integration_single_top') <> '' && get_option('thestyle_integrate_singletop_enable') == 'on') echo(get_option('thestyle_integration_single_top')); ?>
	<div id="left-area">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<div id="post" class="post">
			<div class="post-content clearfix">

				<?php if (!$fullwidth) { ?>
					<div class="info-panel">
						<?php get_template_part('includes/infopanel'); ?>
					</div> <!-- end .info-panel -->
				<?php } ?>

				<div class="post-text">
					<h1 class="title"><?php the_title(); ?></h1>

					<div class="hr"></div>

					<?php the_content(); ?>

					<?php wp_link_pages(array('before' => '<p><strong>'.esc_html__('Pages','TheStyle').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

					<div id="sitemap">
						<div class="sitemap-col">
							<h2><?php esc_html_e('Pages','TheStyle'); ?></h2>
							<ul id="sitemap-pages"><?php wp_list_pages('title_li='); ?></ul>
						</div> <!-- end .sitemap-col -->

						<div class="sitemap-col">
							<h2><?php esc_html_e('Categories','TheStyle'); ?></h2>
							<ul id="sitemap-categories"><?php wp_list_categories('title_li='); ?></ul>
						</div> <!-- end .sitemap-col -->

						<div class="sitemap-col<?php if (!$fullwidth) echo ' last'; ?>">
							<h2><?php esc_html_e('Tags','TheStyle'); ?></h2>
							<ul id="sitemap-tags">
								<?php $tags = get_tags();
								if ($tags) {
									foreach ($tags as $tag) {
										echo '<li><a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '">' . esc_html( $tag->name ) . '</a></li> ';
									}
								} ?>
							</ul>
						</div> <!-- end .sitemap-col -->

						<?php if (!$fullwidth) { ?>
							<div class="clear"></div>
						<?php } ?>

						<div class="sitemap-col<?php if ($fullwidth) echo ' last'; ?>">
							<h2><?php esc_html_e('Authors','TheStyle'); ?></h2>
							<ul id="sitemap-authors" ><?php wp_list_authors('show_fullname=1&optioncount=1&exclude_admin=0'); ?></ul>
						</div> <!-- end .sitemap-col -->
					</div> <!-- end #sitemap -->

					<div class="clear"></div>

					<?php edit_post_link(esc_html__('Edit this page','TheStyle')); ?>

					<?php if (get_option('thestyle_integration_single_bottom') <> '' && get_option('thestyle_integrate_singlebottom_enable') == 'on') echo(get_option('thestyle_integration_single_bottom')); ?>

					<?php if (get_option('thestyle_468_enable') == 'on') { ?>
						<?php if(get_option('thestyle_468_adsense') <> '') echo(get_option('thestyle_468_adsense'));
						else { ?>
							<a href="<?php echo esc_url(get_option('thestyle_468_url')); ?>"><img src="<?php echo esc_url(get_option('thestyle_468_image')); ?>" alt="468 ad" class="foursixeight" /></a>
						<?php } ?>
					<?php } ?>
				</div> <!-- .post-text -->
			</div> <!-- .post-content -->
		</div> <!-- #post -->
	<?php endwhile; endif; ?>
	</div> <!-- #left-area -->
	<?php get_sidebar(); ?>
</div> <!-- #content -->

<div id="content-bottom-bg"></div>

<?php get_footer(); ?>