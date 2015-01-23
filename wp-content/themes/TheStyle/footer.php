		</div> <!-- end #container2 -->
	</div> <!-- end #container -->

	<div id="footer">
		<div id="footer-wrapper">
			<div id="footer-content">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Footer') ) : ?>
				<?php endif; ?>
			</div> <!-- end #footer-content -->
			<p id="copyright"><?php esc_html_e('Designed by ','TheStyle'); ?> <a href="http://www.elegantthemes.com" title="Elegant Themes">Elegant Themes</a> | <?php esc_html_e('Powered by ','TheStyle'); ?> <a href="http://www.wordpress.org">Wordpress</a></p>
		</div> <!-- end #footer-wrapper -->
	</div> <!-- end #footer -->

	<?php get_template_part('includes/scripts'); ?>

	<?php wp_footer(); ?>
</body>
</html>