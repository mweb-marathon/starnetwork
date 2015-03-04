</div>
<div class="footer-wrapper footer-wrapper-star-network">
    <div id="footer" class="row">
        <div class="large-12 columns footer-menu">
            <div class="large-4 columns">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/star-network-footer-logo.png" alt=""/>
            </div>
            <div class="large-8 columns">
                <?php
                echo schneps_get_footer_top_menu();
                echo schneps_get_star_network_footer_second_menu();
                ?>
            </div>
        </div>
    </div>
</div>


<?php

$not_sticky = array(
    'post_type' => array('event'),
    'posts_per_page' => 6,
    'order_by' => 'date',
    'order' => 'DESC',
    'paged' => $paged
);

$wp_query_not_sticky = new WP_Query($not_sticky);
wp_reset_query();
?>

<script type="text/javascript">
jQuery(document).ready(function ($) {

    var count = 2;
    var total = <?php echo $wp_query_not_sticky->max_num_pages; ?>;


    function waitMe(status, block) {
        var waitMe = block || $("#waitMe");

        if (status == 'hide') {
            waitMe.waitMe('hide');
        } else {
            waitMe.waitMe({color: '#525252'}).waitMe('show');
        }
    }

    var star_attend_page = $('.star-network-attend-pages');
    if (star_attend_page.length > 0) {
        var URL = window.location.href;
        var PATHNAME = window.location.pathname;
        var getUrlPart = PATHNAME.replace(/(^\/|\/$)/g, '').split('/');

        var event_location_by_url = getUrlPart.pop();


        if (event_location_by_url === 'location-upcoming-events') {
            $('#radio2').trigger('click', click_on_filter());
        }
        click_on_filter();
    }

    var event_single_category = $('.single-event-category');
    if(event_single_category.length > 0) {
        var event_single_category_parent = event_single_category.data('parent-single-event-category');
        var event_single_category_name = event_single_category.data('single-event-category');

        if(event_single_category_parent == 7) {

            $('#radio2').prop('checked', true).next('span').addClass('checked').closest('label').addClass('disabled');
            $('#radio1').prop('checked', false).next('span').removeClass('checked').closest('label').removeClass('disabled')
        }



        $('.categories a[data-category-name="' + event_single_category_name + '"]').trigger('click', click_on_category());
    }

    function click_on_filter() {
        $('input', '.filter-wrapper').click(function () {
            var $_this = $(this),
                $_this_label = $_this.closest('label'),
                categories_wrapper = $('.categories', '.filter');


            if (!$_this_label.hasClass('disabled')) {
                $_this_label.addClass('disabled').siblings().removeClass('disabled');

                categories_wrapper.find('ul').html('');

                goAjax(
                    "action=star_network_calendar_filter&label=" + $_this_label.text().trim(),
                    function () {
                        waitMe();
                    },
                    function (html) {
                        waitMe('hide');
                        $('.categories', '.filter').find('.content').html(html)
                    }
                )
            }
        });
    }

    function click_on_category() {
        $('.categories').on('click', '.content a', function (evt) {
            evt.preventDefault();
            var $_this = $(this),
                category_name = $_this.data('category-name') !== undefined ? $_this.data('category-name') : 'all',
                main_spot_upcoming_event_wrapper = $('.star-network-homepage-content', '.main-content-data');

            var start_network_homepage_current_page_block = $('.spots-wrapper');
            start_network_homepage_current_page_block.data('start-event-spots', 1);
            var start_network_homepage_current_page_number = start_network_homepage_current_page_block.data('start-event-spots');

            left_right_spot_arrows(start_network_homepage_current_page_number, total, start_network_homepage_current_page_block);

            $_this.closest('li').addClass('current-category').siblings().removeClass('current-category');


            goAjax(
                "action=calendar_events&post_category_name=" + category_name,
                function () {
                    waitMe('show', $('#waitMeSpot'));
                    main_spot_upcoming_event_wrapper.hide().html('');
                },
                function (html) {

                    waitMe('hide', $('#waitMeSpot'));

                    if (html === '') {
                        main_spot_upcoming_event_wrapper.show().html('<div class="empty-ajax-request">Sorry. No events for <u>' + category_name + '</u>.</div>');
                        start_network_homepage_current_page_block.find('.arrow.right, .arrow.left').hide();
                    } else {
                        main_spot_upcoming_event_wrapper.show().html(html);
                        var amount_event_per_category = $('.amount-event-per-category');

                        if (amount_event_per_category.length > 0) {
                            var amount_event_per_category_data = amount_event_per_category.data('amount-per-category');
                            if (amount_event_per_category_data <= 6) {
                                start_network_homepage_current_page_block.find('.arrow.right, .arrow.left').hide();
                            }
                        }
                    }
                }
            );
        })
    }


    var category = $('.categories');
    if (category.length > 0) {
        click_on_category();
    }


    var nominate_form = $('#nominate-form');
    if (nominate_form.length > 0) {
        nominate_form.validate({
            submitHandler: function (form) {
                sendStarNetwork_form(nominate_form, $(form));
            }
        });
    }

    var speak_form = $('#speak-form');
    if (speak_form.length > 0) {
        speak_form.validate({
            submitHandler: function (form) {
                sendStarNetwork_form(speak_form, $(form));
            }
        });
    }


    var sponsor_form = $('#sponsor-form');
    if (sponsor_form.length > 0) {
        sponsor_form.validate({
            submitHandler: function (form) {
                sendStarNetwork_form(sponsor_form, $(form));
            }
        });
    }

    var newsletter_form = $('#newsletter-form');
    if (newsletter_form.length > 0) {
        newsletter_form.validate({
            submitHandler: function (form) {
                sendStarNetwork_form(newsletter_form, $(form));
            }
        });
    }


    var start_network_homepage_content_wrapper = $('.star-network-homepage-content-wrapper');

    if (start_network_homepage_content_wrapper.length > 0) {
        start_network_homepage_content_wrapper.find('.arrow a').click(function (evt) {
            evt.preventDefault();

            var start_network_homepage_content = start_network_homepage_content_wrapper.find('#content'),
                start_network_homepage_current_page = start_network_homepage_content.data('start-event-spots'),
                start_network_homepage_content_div = start_network_homepage_content.find('.star-network-homepage-content'),
                $_this = $(this),
                value = $_this.hasClass('next') ? start_network_homepage_current_page + 1 : start_network_homepage_current_page - 1;


            start_network_homepage_content.data('start-event-spots', value);

            var current_page = start_network_homepage_content.data('start-event-spots');

            left_right_spot_arrows(current_page, total, start_network_homepage_content);

            goAjax(
                "action=calendar_events&page=" + current_page,
                function () {
                    waitMe('show');
                    start_network_homepage_content_div.html('');
                },
                function (html) {
                    waitMe('hide');
                    start_network_homepage_content_div.html(html);
                }
            );


        });
    }


    var star_network_upcoming_event_page_wrapper = $('.upcoming-event-page-wrapper');

    if (star_network_upcoming_event_page_wrapper.length > 0) {
        star_network_upcoming_event_page_wrapper.find('.arrow a').click(function (evt) {
            evt.preventDefault();
            var star_network_upcoming_event_page_content = star_network_upcoming_event_page_wrapper.find('#content');
            var star_network_upcoming_event_page_content_current_page = star_network_upcoming_event_page_content.data('start-event-spots');
            var $_this = $(this);
            var start_network_homepage_content_div = star_network_upcoming_event_page_content.find('.star-network-homepage-content');

            var value = $_this.hasClass('next') ? star_network_upcoming_event_page_content_current_page + 1 : star_network_upcoming_event_page_content_current_page - 1;
            star_network_upcoming_event_page_content.data('start-event-spots', value);

            var current_page = star_network_upcoming_event_page_content.data('start-event-spots');

            left_right_spot_arrows(current_page, total, star_network_upcoming_event_page_content);

            var amount_event_per_category = $('.amount-event-per-category');
            var link = "action=calendar_events&page=" + current_page;
            if (amount_event_per_category.length > 0) {
                link = link + "&post_category_name=" + amount_event_per_category.data('category-name');
            }

            goAjax(
                link,
                function () {
                    waitMe('show', $('#waitMeSpot'));
                    start_network_homepage_content_div.hide();
                },
                function (html) {

                    waitMe('hide', $('#waitMeSpot'));
                    start_network_homepage_content_div.show().html(html);

                    var amount_event_per_category = $('.amount-event-per-category');

                    if (amount_event_per_category.length > 0) {
                        var current_page = amount_event_per_category.data('current-paged');
                        var all_paged = amount_event_per_category.data('all-paged');
                        left_right_spot_arrows(current_page, all_paged, star_network_upcoming_event_page_content);
                    }
                }
            );
        })

    }


    function left_right_spot_arrows(current, total, selector) {

        if (current == total) {
            selector.find('.arrow.right').hide();
            selector.find('.arrow.left').show();
        } else if (current == 1) {
            selector.find('.arrow.right').show();
            selector.find('.arrow.left').hide();
        } else if (current == 0) {
            selector.find('.arrow.left, .arrow.right').hide();
        } else {
            selector.find('.arrow.right').show();
            selector.find('.arrow.left').show();
        }
    }

    function sendStarNetwork_form(selector, form) {
        goAjax(
            "action=star_network_send_form_email&" + form.serialize(),
            function () {
                waitMe();
            },
            function (html) {
                waitMe('hide');
                if (html === 'error') {
                    $('#defaultReal').addClass('error');
                } else {
                    if (html !== '') {
                        selector.html('<div class="ajax-success">' + html + '</div>');
                    }
                }
            }
        );
        return false;
    }

    var relatedStoryOnNewsAndPhotoPage = $('.news-photo-related-list');


    if (relatedStoryOnNewsAndPhotoPage.length > 0) {


        goAjax(
            "action=schneps_get_related_stories_story_page_&post_id=" + relatedStoryOnNewsAndPhotoPage.data('post-id') + "",
            function () {
                relatedStoryOnNewsAndPhotoPage.waitMe({color: '#525252'}).waitMe('show');
            },
            function (html) {
                relatedStoryOnNewsAndPhotoPage.waitMe('hide').html(html);
            }
        );
    }

    function goAjax(data, before, success) {
        $.ajax({
            url: "<?php bloginfo('wpurl') ?>/wp-admin/admin-ajax.php",
            type: 'POST',
            data: data,
            beforeSend: function () {
                before();
            },
            success: function (html) {
                success(html);
            }
        });
    }
});
</script>
<script type="text/javascript"
        src="<?php echo get_stylesheet_directory_uri(); ?>/js/foundation/foundation.min.js"></script>
<script>
    jQuery(document).foundation();
</script>
</body>
</html>