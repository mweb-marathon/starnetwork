<?php
/**
 * @author Maxim Bogdanov <sin666m4a1fox@gmail.com>
 * @copyright maxim 1/2/15 | 12:40 PM
 */

$CSS_ARRAY = array(
    'bjqs',
    'jcarousel',
    'waitMe',
    'jquery.fancybox',
    'foundation/foundation',
    'bxslider/jquery.bxslider',

);

$SCSS_ARRAY = array(
    'app',
    'star-network',
    'spots/entry'
);

function include_css_file($file, $folder)
{
    echo PHP_EOL . '<link rel="stylesheet" type="text/css" href="' . get_stylesheet_directory_uri() . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $file . '.css" />';
}

foreach ($CSS_ARRAY as $css) {
    include_css_file($css, 'css');
}
foreach ($SCSS_ARRAY as $scss) {
    include_css_file($scss, 'scss');
}