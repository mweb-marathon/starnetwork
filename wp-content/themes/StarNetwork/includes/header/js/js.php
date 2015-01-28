<?php
/**
 * @author Maxim Bogdanov <sin666m4a1fox@gmail.com>
 * @copyright maxim 1/2/15 | 1:15 PM
 */

$JS_ARRAY = array(
    'google-maps',
    'jquery.validate.min',
    'waitMe',
    'bjsq-1.3.min',
    'jquery.bxslider.min',
    'jquery.jcarousel.min',
    'foundation/vendor/modernizr',
    'theia-sticky-sidebar',
    'additional-methods',
    'jquery.plugin',
    'jquery.realperson',
    'classie',
    'custom',
);

foreach ($JS_ARRAY as $js): ?>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/<?php echo $js; ?>.js"></script>
<?php endforeach; ?>