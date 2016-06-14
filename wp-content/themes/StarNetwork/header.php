<?php
if (!session_id()) {
    session_start();
}
?>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title></title>


    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen"/>
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>"/>
    <?php get_template_part('includes/header/ie/ie'); ?>
    <?php get_template_part('includes/header/css/css'); ?>
    <script type="text/javascript">
        document.documentElement.className = 'js';
    </script>
    <?php wp_head(); ?>
    <style type="text/css">
        html {
            margin-top: 0 !important;
        }
    </style>
    <?php get_template_part('includes/header/js/js'); ?>
</head>
<body <?php body_class(); ?> data-is-mobile="<?php echo wp_is_mobile() ? 'true' : 'false'?>">
<?php get_template_part('includes/header/header', 'basic_star'); ?>
<div class="clearfix"></div>
<div class="wrapper">