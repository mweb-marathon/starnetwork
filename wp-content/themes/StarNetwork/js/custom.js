/**
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * @author Maxim Bogdanov <sin666m4a1fox@gmail.com>
 */

jQuery(document).ready(function ($) {
    var URL = window.location.href;
    var PATHNAME = window.location.pathname;
    var getUrlPart = PATHNAME.replace(/(^\/|\/$)/g, '').split('/');

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
        centercontrols: false
    });
});
