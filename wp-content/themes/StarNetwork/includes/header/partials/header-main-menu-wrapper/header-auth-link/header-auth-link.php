<?php
/**
 * @author Maxim Bogdanov <sin666m4a1fox@gmail.com>
 * @copyright maxim 1/2/15 | 12:30 PM
 */
?>
<div class="auth-link-wrapper large-3 columns">
    <div class="right ">
        <div class="search-form hide">
            <form action="/" method="get">
                <input autocomplete="off" name="s" type="text" placeholder="Search..." class="search-input"/>
                <input type="hidden" name="post_type[]" value="event" />
                <input type="hidden" name="post_type[]" value="post" />
                <input type="submit" value="find" class="search-btn"/>
                <div class="float-none"></div>
            </form>
        </div>
        <div class="auth-links right">
            <?php schneps_get_header_social_menu(); ?>
        </div>
    </div>
</div>