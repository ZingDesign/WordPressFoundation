/**
 * Created by Sam on 10/11/14.
 */
jQuery(document).ready(function($) {
    'use strict';

    // Variable declaration
    var imageModalSelector = '#zd-image-modal';
    //var revealModalBgSelector = '.reveal-modal-bg';
    var imageSelector = '.entry-content img:not(.wp-smiley)';
    var $doc = $(document);

    //function getImageCount() {
    //    return $(imageSelector).length;
    //}

    function openImageInModal(e) {
        var $this = $(this);
        var postImages = $(imageSelector);
        var index = postImages.index($this);
        //ZD.debug(index);

        var imageModal = $(imageModalSelector);
        var imageSlider = imageModal.find('.image-slider');

        //ZD.debug(e.data.allImages);

        //var allImageLinks = e.data.allImageLinks;

        // Smiley fix
        //if( ! $this.hasClass('wp-smiley')) {
        //
        //}


        if ( imageModal.length ) {
            e.preventDefault();

            //ZD.debug( postImages.length );

            if( postImages.length > 1 ) {
                imageSlider.slickGoTo(index);
            }

            //$(revealModalBgSelector).attr('aria-hidden', false);

            imageModal
                .attr('aria-hidden', 'false')
                .foundation('reveal', 'open');
        }
        else {
            ZD.debug('Image modal element missing: ' + imageModalSelector);
        }



        //return false;
    }

    function initImageModal() {

        //ZD.debug('Page loaded');

        var imageLinks = $(imageSelector);

        var imageModal = $(imageModalSelector);

        var imageSlider = imageModal.find('.image-slider');

        //ZD.debug( imageSlider.getSlick() );

        if( imageSlider.getSlick() ) {
            imageSlider.unslick();
            imageSlider.html('');
        }

        //imageSlider.html('');

        //imageSlider.slick();

        //ZD.debug('imageLinks.length: ' + imageLinks.length);

        imageSlider.slick({
            //adaptiveHeight: true,
            useCSS: Modernizr.cssanimations,
            swipe: Modernizr.touch,
            arrows: !Modernizr.touch
        });

        imageLinks.each(function () {
            var img = $(this);
            var title = false;
            var caption = false;
            var width = img.attr('width');

            if( ZD.isset( img.attr('title') ) ) {
                title = img.attr('title');
            }

            if( img.parents('figure').length ) {
                //ZD.debug(img.parents('figure').find('figcaption').text());
                caption = img.parents('figure').find('figcaption').text();
            }
            //ZD.debug(img.attr('src'));
            //ZD.debug(img[0].outerHTML);

            //var img = $imgLink.find('img');

            //addImageToSlider(imageSlider, img);

            var newSlide = '<div class="image-slide">\n';

            if( title ) {
                newSlide += '<h2>' + title + '</h2>';
            }

            newSlide += '<img src="' + img.attr('src') + '" ' +
                'alt="' + img.attr('alt') + '" ' +
                'width="' + width + '" ' +
                'height="' + img.attr('height') + '" />\n';

            if( caption ) {
                newSlide += '<p class="caption" style="width:'+width+'px;">' + caption + '</p>\n';
            }

            newSlide += '</div>\n';

            imageSlider.slickAdd( newSlide );
        });
    }

    // Event listeners

    //console.log( $('[data-ajax-content-area]').length );

    if ($(imageSelector).length && isLargeUp() ) {
        //initImageModal();

        var postImages = $(imageSelector);
        postImages.css({ 'cursor' : 'pointer' });

        $doc.on('init zd.page_loaded', initImageModal);

        $(document).trigger('init');

        $(document).on('click', imageSelector, {}, openImageInModal);
    }

    $doc.on('opened.fndtn.reveal', imageModalSelector, function () {
        var modal = $(this);
        //ZD.debug('CLOSE!');
        var slider = modal.find('.image-slider');

        if( slider.length && slider.children().length ) {
            try {
                slider.resize();
            }
            catch(e) {
                ZD.debug(e);
            }
        }
    });

    $doc.on('close.fndtn.reveal', imageModalSelector, function () {
        var modal = $(this);
        //ZD.debug('CLOSE!');
        modal.attr('aria-hidden', true);
        //$(revealModalBgSelector).attr('aria-hidden', true);
    });

    // Disable link around image
    if( $('a.img').length ) {

        $('a.img').on('click', function() {
            //event.preventDefault();
            //alert('image link clicked');
            $(this).find('img').trigger('click');

            return false;
        });

    }

    function isMediumUp() {
        return window.matchMedia ? matchMedia(Foundation.media_queries.medium).matches : true;
    }

    function isLargeUp() {
        return window.matchMedia ? matchMedia(Foundation.media_queries.large).matches : true;
    }

});