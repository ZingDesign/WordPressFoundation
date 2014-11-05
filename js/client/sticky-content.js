/**
 * Created by Sam on 3/11/14.
 */

jQuery(document).ready(function($) {

    /*
     * Sticky content
     */
    if( $('.zd-sticky-content').length ) {

        var stickyContent = $('.zd-sticky-content');
        var headerHeight = $('header.header').outerHeight(true);
        var footerHeight = $('#colophon').outerHeight(true);

        var initContentOffset = stickyContent.offset().top - headerHeight - 40;

        if( $('#wpadminbar').length ) {
            initContentOffset -= $('#wpadminbar').outerHeight(true);
        }

        //console.log( initContentOffset );

        //$(window).on('scroll', function() {
        //    console.log(window.scrollY);
        //});

        $(stickyContent).affix({
            offset: {
                top: function() {
                    return ( this.top = initContentOffset )
                }
                //,bottom: function () {
                //    return (this.bottom = footerHeight )
                //}
            }
        })
    }

});