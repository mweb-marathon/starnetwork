<?php
/**
 * @author Maxim Bogdanov <sin666m4a1fox@gmail.com>
 * @copyright maxim 1/2/15 | 11:55 AM
 */
?>
<div class="header-wrapper-bg-image header-wrapper-star-network-header">
    <div class="header-wrapper-bg-color">
        <header class="row <?php echo wp_is_mobile() ? 'mobile' : ''; ?> new-header">
            <div class="logo-with-menu-wrapper">
                <div class="large-9 columns logo">
                    <div class="hidden-for-small-only hidden-for-medium-only text-center leaderboard-header-box">
                        <?php include 'partials/banner/banner.php'; ?>
                    </div>
                    <?php include 'partials/logo/logo_star.php'; ?>
                </div>
            </div>
        </header>
    </div>
    <?php include 'partials/header-main-menu-wrapper/header-main-menu-star-network-wrapper.php'; ?>
</div>