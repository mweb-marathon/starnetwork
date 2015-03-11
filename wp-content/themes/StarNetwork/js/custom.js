/**
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * @author Maxim Bogdanov <sin666m4a1fox@gmail.com>
 */

jQuery(document).ready(function ($) {

    $('.extra-post-gallery').bjqs({
        animtype: 'slide',
        width: 1120,
        height: 460,
        responsive: true,
        showmarkers: false,
        nexttext: '',
        prevtext: '',
        showcontrols: 1,
        automatic: 1,
        centercontrols: false,
        animspeed: 8000
    });

    $('.post-gallery').bjqs({
        animtype: 'slide',
        width: 715,
        height: 574,
        responsive: true,
        showmarkers: false,
        nexttext: '',
        prevtext: '',
        showcontrols: 1,
        automatic: 1,
        centercontrols: false
    });

    var people_honoree = $('.people-honoree-data');

    if (people_honoree.find('>div').length > 0) {
        people_honoree.bxSlider({
            slideWidth: 750,
            minSlides: 1,
            maxSlides: 1,
            infiniteLoop: false,
            pager: false,
            hideControlOnEnd: true
        });
    }

    var post_social_button = $('#post-social-button');

    if (post_social_button.length > 0) {
        post_social_button.theiaStickySidebar({
            additionalMarginTop: 90
        });
    }

    $('#defaultReal').realperson();

    $('.slider-image-info-wrapper.expandable').click(function () {
        var $_this = $(this);
        $_this.toggleClass('expanded');
    })

});
