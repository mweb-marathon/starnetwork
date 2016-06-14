<?php
/**
 * @author Maxim Bogdanov <sin666m4a1fox@gmail.com>
 * @copyright maxim 1/2/15 | 11:55 AM
 */
?>
<!-- Fixed header. Start -->
<div
    class="fixed-header fixed-header-wrapper-bg-image  <?php echo $_GET['type'] != 'slideshow' ? 'hide active' : ''; ?>">
    <div class="fixed-header-wrapper-bg-color">
        <header class="row <?php echo wp_is_mobile() ? 'mobile' : ''; ?> fixed-new-header">
            <div class="logo-wrapper">
                <?php include 'partials/logo/logo.php'; ?>
            </div>
            <div class="fixed-header-menu-title">
                <div class="page-title-wrapper relative">
                    <div class="page-title">
                        <?php if (is_category()) { ?>
                            <?php include 'partials/page-title/page-title.php'; ?>
                        <?php } ?>
                    </div>

                    <div class="secondary-menu">
                        <?php include 'partials/header-secondary-menu/header-secondary-menu.php'; ?>
                    </div>
                </div>
                <div class="main-menu-wrapper">
                    <?php include 'partials/header-main-menu-wrapper/header-main-menu-wrapper.php'; ?>
                </div>
            </div>
        </header>
    </div>
</div>
<!-- Fixed header. End -->