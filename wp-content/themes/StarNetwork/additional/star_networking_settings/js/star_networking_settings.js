/**
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * @author Maxim Bogdanov <sin666m4a1fox@gmail.com>
 */

jQuery(document).ready(function ($) {


    /**
     * Gallery. Start Code.
     */
    var frame;

    function getImageUrl(event, buttonLink, parentBlock) {


        var $el = $(buttonLink);
        parentBlock.data('lastClicked', $el.data('updateLink'));
        event.preventDefault();

        if (frame) {
            frame.open();
            return;
        }

        frame = wp.media.frames.customHeader = wp.media({
            title: $el.data('choose'),
            library: {
                type: 'image'
            },
            button: {
                text: $el.data('update'),
                close: false
            }
        });

        frame.on('select', function () {
            var attachment = frame.state().get('selection').first(),
                imgUrl = attachment.attributes.url,
                inputId = parentBlock.data('lastClicked'),
                inputField = $("div[data-id='" + inputId + "']", parentBlock);
            inputField.find('input.imgUrl').val(imgUrl);
            inputField.find('input.imgUrl').show();

            frame.close();
        });
        frame.open();
    }


    /**
     * Gallery End Code.
     */

    function countImagesBlock(block) {
        var col = $("> div:last", block).data("id");
        if (col == undefined) {
            col = 0;
        }

        return ++col;
    }

    function imageBlock(col_inputs, whichBlock) {
        var blocks = [];

        blocks['slideBlockImage'] = '' +
            '<div data-id="' + col_inputs + '" class="js-image-parent-block image-parent-block">' +
            '<span class="js-delete-button delete-button"></span>' +
            '<div class="media-block">' +
            '<div class="gallery-text text">Picture</div>' +
            '<div class="gallery-input input">' +
            '<a class="button choose-from-library-link"  href="#" data-update-link="' + col_inputs + '">Open Media Library</a>' +
            '<div class="select-image-description">Choose your image, then click "Select" to apply it.</div>' +
            '<input class="imgUrl" style="display: none;" id="inp' + col_inputs + '" type="text" name="star_network_images[' + col_inputs + '][url]" value="" />' +
            '</div>' +
            '</div>' +
            '<div class="link-block hide">' +
            '<div class="link-text text">Link</div>' +
            '<div class="link-input input"><input class="link" id="inp' + col_inputs + '" type="hidden" name="star_network_images[' + col_inputs + '][link]" value="" placeholder="http://" /></div>' +
            '</div>' +
            '<div class="event-block">' +
            '<div class="link-text text">Event: </div>' +
            '<div class="event-input input">' +
            '<input class="event" id="inp' + col_inputs + '" type="text" name="star_network_images[' + col_inputs + '][event]" value="" placeholder="Event or Post"/>' +
            '<input class="event_id" id="inp' + col_inputs + '" type="hidden" name="star_network_images[' + col_inputs + '][event_id]" value=""/>' +
            '</div>' +
            '</div>' +
            '<div class="sortable-handler"></div>' +
            '</div>'
        ;
        return blocks[whichBlock];
    }


    $('a', '.schneps-curation-tabs').click(function (evt) {
        evt.preventDefault();
        var $_this = $(this),
            $_thisPageName = $_this.data('page');
        $_this.addClass('nav-tab-active').siblings().removeClass('nav-tab-active');

        $('.divider-settings').removeClass('show').addClass('hide');
        $('.' + $_thisPageName, '.schneps-settings').addClass('show');
    });


    $("#add-new-slide").on('click', function () {
        var block = imageBlock(countImagesBlock($(".js-star-network #div_inputs")), 'slideBlockImage');
        $(block).appendTo($("#div_inputs"));
    });


    $('.home-page-carousel').on('click', '.choose-from-library-link', function (event) {
        getImageUrl(event, this, $('#div_inputs', '.js-star-network'));
    });

    $("#div_inputs").on('click', ".js-delete-button", function () {
        $(this).parent().remove();
    });

    $('.wp-admin .em-location-data-name th').text('Borough');
    $('.wp-admin .em-location-data-town th').text('City');

});



