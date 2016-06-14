<?php global $shortname; ?>

	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.masonry.min.js"></script>
	<script type="text/javascript">
		jQuery(window).load(function(){
			<?php if (get_option('thestyle_blog_style') == 'false') { ?>
				jQuery('#content #boxes').masonry({ columnWidth: 122, animate: true });
			<?php } ?>
			jQuery('#footer-content').masonry({ columnWidth: 305, animate: true });

			var $fixed_sidebar_content = jQuery('.sidebar-fixedwidth');

			if ( $fixed_sidebar_content.length ) {
				var sidebarHeight = $fixed_sidebar_content.find('#sidebar').height(),
					contentHeight = $fixed_sidebar_content.height();
				if ( contentHeight < sidebarHeight ) $fixed_sidebar_content.css('height',sidebarHeight);
			}
		});
	</script>
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/superfish.js"></script>

	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.hoverIntent.minified.js"></script>

	<script type="text/javascript">
	//<![CDATA[
		jQuery.noConflict();
		jQuery(document).ready(function(){
			jQuery('ul.nav').superfish({
				delay:       300,                            // one second delay on mouseout
				animation:   {opacity:'show',height:'show'},  // fade-in and slide-down animation
				speed:       'fast',                          // faster animation speed
				autoArrows:  true,                           // disable generation of arrow mark-up
				dropShadows: false                            // disable drop shadows
			});

			jQuery('ul.nav > li > a.sf-with-ul').parent('li').addClass('sf-ul');

			jQuery(".entry").hoverIntent({
				over: makeTall,
				timeout: 100,
				out: makeShort
			});

			var $tabbed_area = jQuery('#tabbed'),
				$tab_content = jQuery('.tab-content'),
				$all_tabs = jQuery('#all_tabs');

			if ($tabbed_area.length) {
				$tabbed_area.tabs({ hide : true });
			};

			et_search_bar();

			function makeTall(){
				jQuery(this).addClass('active').css('z-index','7').find('.bottom-bg .excerpt').animate({"height":200},200);
				jQuery('.entry').not(this).animate({opacity:0.3},200);
			}
			function makeShort(){
				jQuery(this).css('z-index','1').find('.bottom-bg .excerpt').animate({"height":75},200);
				jQuery('.entry').removeClass('active').animate({opacity:1},200);
			}

//			Search Bar Improvements
			function et_search_bar(){
				var $searchform = jQuery('#header div#search-form'),
					$searchinput = $searchform.find("input#searchinput"),
					searchvalue = $searchinput.val();

				$searchinput.focus(function(){
					if (jQuery(this).val() === searchvalue) jQuery(this).val("");
				}).blur(function(){
					if (jQuery(this).val() === "") jQuery(this).val(searchvalue);
				});
			}

			<?php if (get_option($shortname.'_disable_toptier') == 'on') echo('jQuery("ul.nav > li > ul").prev("a").attr("href","#");'); ?>

			jQuery('.entry').click(function(){
				window.location = jQuery(this).find('.title a').attr('href');
			});
		});
	//]]>
	</script>