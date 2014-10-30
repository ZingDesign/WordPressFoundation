/**
 * Created by Sam on 2/07/14.
 */

jQuery(document).ready(function($){
    'use strict';

    window.ResponsiveNavigation = (function() {


        // Variables
        var menuOpen = false,

            slideDirection = $('#showLeft').length ? 'right' : 'left',

            menuButton = $('#showLeft').length ? $('#showLeft') : $('#showRight'),

            menu = $('#cbp-spmenu-s1'),

            $body = $('body').addClass('cbp-menu-body'),

            mediumUp = matchMedia(window.ZD.medium).matches,

            largeUp = matchMedia(window.ZD.large).matches,

            menuOverlay = $('.cbp-menu-overlay');

        //console.log(slideDirection);


        // Private functions
        var openMenuPrivate = function () {

            menuButton.addClass('active');

            menu.addClass('cbp-spmenu-open');

            $body
                .addClass('cbp-body-open')
                .css(slideDirection, menu.outerWidth() + 'px');

            if( slideDirection === 'right' ) {
                $body.css('left', 'initial');
            }

            menuOpen = true;
        };

        var closeMenuPrivate = function () {
            //console.log('overlay clicked');

            //var $this = $(this);

            menuButton.removeClass('active');

            menu.removeClass('cbp-spmenu-open');

            //$body.removeClass('open');

            $body
                .removeClass('cbp-body-open')
                .css(slideDirection, '0');

            menuOpen = false;
        };


        var initPrivate = function() {

            //if( $('.cbp-menu-overlay').length === 0 ) {
            //    menuOverlay.appendTo($body);
            //}



            // Restrict body width to window width
            // If mobile/tablet
            if( ! largeUp ) {

                $body.css({
                    'width': $(window).width() + 'px'
                });

            }
        };

        // Event listeners

        menuButton.on('click', function() {
            //console.log('menu button clicked');

            //console.log('menuOpen: ' + menuOpen);

            if(menuOpen) {
                closeMenuPrivate();

                //notMenu.off('click');

                //console.log('closeMenuPrivate');
            }
            else {
                openMenuPrivate();

                //console.log('openMenuPrivate');
            }

            return false;
        });

        menuOverlay.on( 'click', closeMenuPrivate );


        // Mobile-only - close the menu when a mobile nav link is 'clicked'
        if( ! mediumUp ) {
            menu.find('a').on('click', closeMenuPrivate);
        }

        // Expose methods
        return {
            openMenu: function() {
                openMenuPrivate();
            },

            closeMenu: function() {
                closeMenuPrivate();
            },

            init: function() {
                initPrivate();
            }
        };
        
    })();


    window.ResponsiveNavigation.init();




    //---------------------------------------------------

});