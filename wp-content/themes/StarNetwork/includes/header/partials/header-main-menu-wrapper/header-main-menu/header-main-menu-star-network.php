<?php
/**
 * @author Maxim Bogdanov <sin666m4a1fox@gmail.com>
 * @copyright maxim 1/2/15 | 12:28 PM
 */
 ?>
<div class="large-9 columns menu top-block">
    <nav class="top-bar" data-topbar role="navigation">
        <ul class="title-area">
            <li class="name">
                <?php if (wp_is_mobile()): ?>
                <a href="/">
                    <img width="200" src="<?php echo get_stylesheet_directory_uri(); ?>/img/logo-mobile.jpg" >
                </a>
                <?php endif; ?>
            </li>
            <li class="toggle-topbar">
                <a href="#">
                    <span class="menu-icon-schneps">
                        <b>&#9776;</b>
                    </span>
                </a>
            </li>
        </ul>
        <div class="top-bar-section">
            <?php  echo schneps_get_header_main_star_network_menu(); ?>
        </div>
    </nav>
</div>