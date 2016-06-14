<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title><?php elegant_titles(); ?></title>
<?php elegant_description(); ?>
<?php elegant_keywords(); ?>
<?php elegant_canonical(); ?>

<link href='http://fonts.googleapis.com/css?family=Droid+Sans:regular,bold' rel='stylesheet' type='text/css' />

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<!--[if lt IE 7]>
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/ie6style.css" />
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/DD_belatedPNG_0.0.8a-min.js"></script>
	<script type="text/javascript">DD_belatedPNG.fix('img#logo, #search-form, .thumbnail .overlay, .big .thumbnail .overlay, .entry-content, .bottom-bg, #controllers span#left-arrow, #controllers span#right-arrow, #content-bottom-bg, .post, #comment-wrap, .post-content, .single-thumb .overlay, .post ul.related-posts li, .hr, ul.nav ul li a, ul.nav ul li a:hover, #comment-wrap #comment-bottom-bg, ol.commentlist, .comment-icon, #commentform textarea#comment, .avatar span.overlay, li.comment, #footer .widget ul a, #footer .widget ul a:hover, #sidebar .widget, #sidebar h3.widgettitle, #sidebar .widgetcontent ul li, #tabbed-area, #tabbed-area li a, #tabbed .tab ul li');</script>
<![endif]-->
<!--[if IE 7]>
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/ie7style.css" />
<![endif]-->
<!--[if IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/ie8style.css" />
<![endif]-->

<script type="text/javascript">
	document.documentElement.className = 'js';
</script>

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>
	<div id="container">
		<div id="container2">
			<div id="header">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php $logo = get_option('thestyle_logo') <> '' ? get_option('thestyle_logo') : get_template_directory_uri() . '/images/logo.png'; ?>
					<img src="<?php echo esc_attr( $logo ); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" id="logo"/>
				</a>
				<div id="header-bottom" class="clearfix">
					<?php $menuClass = 'nav';
					$menuID = 'primary';
					$primaryNav = '';
					if (function_exists('wp_nav_menu')) {
						$primaryNav = wp_nav_menu( array( 'theme_location' => 'primary-menu', 'container' => '', 'fallback_cb' => '', 'menu_class' => $menuClass, 'menu_id' => $menuID, 'echo' => false ) );
					};
					if ($primaryNav == '') { ?>
						<ul id="<?php echo esc_attr( $menuID ); ?>" class="<?php echo esc_attr( $menuClass ); ?>">
							<?php if (get_option('thestyle_home_link') == 'on') { ?>
								<li <?php if (is_home()) echo('class="current_page_item"') ?>><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e('Home','TheStyle') ?></a></li>
							<?php } ?>

							<?php show_page_menu($menuClass,false,false); ?>

							<?php show_categories_menu($menuClass,false); ?>
						</ul> <!-- end ul#nav -->
					<?php }
					else echo($primaryNav); ?>

					<div id="search-form">
						<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
							<input type="text" value="<?php esc_attr_e('Search this website...','TheStyle'); ?>" name="s" id="searchinput" />

							<input type="image" src="<?php echo get_template_directory_uri(); ?>/images/<?php if (get_option('thestyle_color_scheme') == 'Gray') echo 'gray/'; ?>search-btn.png" id="searchsubmit" />
						</form>
					</div> <!-- end #search-form -->

				</div> <!-- end #header-bottom -->
			</div> <!-- end #header -->