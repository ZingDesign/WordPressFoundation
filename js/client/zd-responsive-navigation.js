/**
 * Created by Sam on 2/07/14.
 */

jQuery(document).ready(function($){
    'use strict';

    window.ResponsiveNavigation = (function() {


        // Variables
        var menuOpen = false,

            menuButton = $('#showLeft'),

            menu = $('#cbp-spmenu-s1'),

            $body = $('body'),

            mediumUp = matchMedia(window.ZD.medium).matches,

            largeUp = matchMedia(window.ZD.large).matches;


        // Private functions
        var openMenuPrivate = function () {

            menuButton.addClass('active');

            menu.addClass('cbp-spmenu-open');

            $body.css({
                'right': menu.outerWidth() + 'px',
                'overflow-y': 'hidden'
            });

            menuOpen = true;
        };

        var closeMenuPrivate = function () {

            //var $this = $(this);

            menuButton.removeClass('active');

            menu.removeClass('cbp-spmenu-open');

            //$body.removeClass('open');

            $body.css({
                'right': '0',
                'overflow-y': 'visible'
            });

            menuOpen = false;
        };


        var initPrivate = function() {


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