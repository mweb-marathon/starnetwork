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


    var category = $('.categories');
    if (category.length > 0) {
        category.on('click', '.content a', function (evt) {
            evt.preventDefault();
            var $_this = $(this),
                category_name = $_this.data('category-name') !== undefined ? $_this.data('category-name') : 'all',
                main_spot_upcoming_event_wrapper = $('.spots-wrapper', '.main-content-data');

            $_this.closest('li').addClass('current-category').siblings().removeClass('current-category');


            goAjax(
                "action=calendar_events&post_category_name=" + category_name + "&post_per_page=-1",
                function () {
                    waitMe('show', $('#waitMeSpot'));
                    $('.main-content-data .spots-wrapper').html('');
                },
                function (html) {

                    waitMe('hide', $('#waitMeSpot'));

                    if (html === '') {
                        main_spot_upcoming_event_wrapper.html('<div class="empty-ajax-request">Sorry. No events for <u>' + category_name + '</u>.</div>');
                    } else {
                        main_spot_upcoming_event_wrapper.html(html);
                    }
                }
            );


        })
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

            if (current_page == total) {
                start_network_homepage_content.find('.arrow.right').hide();
                start_network_homepage_content.find('.arrow.left').show();
            } else if (current_page == 1) {
                start_network_homepage_content.find('.arrow.right').show();
                start_network_homepage_content.find('.arrow.left').hide();
            } else {
                start_network_homepage_content.find('.arrow.right').show();
                start_network_homepage_content.find('.arrow.left').show();
            }


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


//        if (calendarEvents.length > 0) {
//            $('li', calendarEvents).click(function () {
//                var $_this = $(this),
//                    date = $_this.data('date');
//
//                $_this.addClass('current-day').siblings().removeClass('current-day');
//
//                goAjax(
//                    "action=calendar_events&date=" + date + "&loop_file=loop_event",
//                    function () {
//                        $("#waitMe").waitMe({color: '#525252'}).waitMe('show');
//                    },
//                    function (html) {
//                        $("#waitMe").waitMe('hide');
//                        $("#events-list").html(html);
//                    }
//                );
//            });
//        }

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