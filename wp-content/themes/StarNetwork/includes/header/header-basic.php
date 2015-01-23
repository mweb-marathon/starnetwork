<?php
/**
 * @author Maxim Bogdanov <sin666m4a1fox@gmail.com>
 * @copyright maxim 1/2/15 | 11:55 AM
 */
?>
<div class="header-wrapper-bg-image <?php echo $_GET['type'] == 'slideshow' ? 'hide' : ''; ?>">
    <div class="header-wrapper-bg-color">
        <header class="row <?php echo wp_is_mobile() ? 'mobile' : ''; ?> new-header">
            <div class="logo-with-menu-wrapper">
                <div class="large-9 columns logo">
                    <?php include 'partials/logo/logo.php'; ?>
                    <?php if (is_category()) { ?>
                        <div class="page-title">
                            <?php
                            include 'partials/page-title/page-title.php';
                            include 'partials/page-powered/page-powered.php';
                            ?>
                        </div>
                    <?php } ?>
                </div>
                <div class="large-3 columns header-secondary-menu">
                    <?php include 'partials/header-secondary-menu/header-secondary-menu.php'; ?>
                </div>
            </div>
        </header>
    </div>
    <?php include 'partials/header-main-menu-wrapper/header-main-menu-wrapper.php'; ?>
</div>