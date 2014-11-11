/**
 * Created by Sam on 20/10/14.
 * Script to control interaction on elements which are common to each page goes in here
 * e.g. Header, footer, other chrome elements
 */

jQuery(document).ready(function($){
    'use strict';

    // Variable declaration

    // Callback methods

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



});