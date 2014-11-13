/**
 * Created by Sam on 20/10/14.
 * Script to control interaction on elements which are common to each page goes in here
 * e.g. Header, footer, other chrome elements
 */

jQuery(document).ready(function($){
    'use strict';

    // Variable declaration

    // Callback methods

    function isMediumUp() {
        return window.matchMedia ? matchMedia(Foundation.media_queries.medium).matches : true;
    }

    function initGeneral() {
        //alert('Modernizr.svg: ' + Modernizr.svg);

        window.toggleScroll = function() {
            if (document.getElementById('listener-mobile-navigation').checked || document.getElementById('listener-mobile-search').checked) {
                $('body').addClass('no-scroll');
            } else {
                $('body').removeClass('no-scroll');
            }
            //return;
        };



        // Placeholder polyfill
        if ( ! Modernizr.input.placeholder ) {
            $('input, textarea').placeholder();
        }

        if( $('figure').length ) {
            $('figure').css({'width':'auto'});
        }

        if( $('.mobile-search-category') ) {
            $('.mobile-search-category').on('change', function() {
                var $this = $(this);

                $('.mobile-search-tick').removeClass('ticked');

                $this
                    .siblings('label')
                    .find('.mobile-search-tick')
                    .addClass('ticked');
            });
        }

        if( $('#page').length && isMediumUp() ) {
            var $page = $('#page');

            if( $page.outerHeight(true) < $(window).height() ) {
                $('#colophon').addClass('sticky');
            }
        }

        // Tap listener

        //if( $('.header-content').length && Modernizr.touch ) {
        //
        //    var getPointerEvent = function(event) {
        //        return event.originalEvent.targetTouches ? event.originalEvent.targetTouches[0] : event;
        //    };
        //
        //    var $touchArea = $('.header-content').find('label'),
        //        touchStarted = false,
        //        currX = 0,
        //        currY = 0,
        //        cachedX = 0,
        //        cachedY = 0;
        //
        //    $touchArea.on('touchstart mousedown', function(e){
        //        //log(e);
        //        e.preventDefault();
        //
        //        var $this = $(this);
        //
        //        var pointer = getPointerEvent(e);
        //        // caching the current x
        //        cachedX = currX = pointer.pageX;
        //        // caching the current y
        //        cachedY = currY = pointer.pageY;
        //
        //        touchStarted = true;
        //
        //        var $target = $('#' + $this.attr('for'));
        //
        //        $this.addClass('active');
        //
        //        setTimeout(function (){
        //            if ((cachedX === currX) && !touchStarted && (cachedY === currY)) {
        //                // Here you get the Tap event
        //                //$touchArea.text('Tap');
        //                $target.trigger('click');
        //                $this.removeClass('active');
        //            }
        //        },200);
        //    });
        //
        //    $touchArea.on('touchend mouseup touchcancel',function (e){
        //        e.preventDefault();
        //        // here we can consider finished the touch event
        //        touchStarted = false;
        //        //$touchArea.text('Touchended');
        //    });
        //}


    }

    function log(_toLog) {
        return ZD.debug(_toLog);
    }

    $(document).on('init zd.page_loaded', initGeneral).trigger('init');





});