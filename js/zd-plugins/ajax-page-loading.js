/**
 * Created by Sam on 7/11/14.
 */

jQuery(document).ready(function ($) {

    (function ($, d) {
        'use strict';

        $.fn.loadAjaxContent = function (options) {

            var settings = $.extend({}, $.fn.loadAjaxContent.defaults, options);

            var $doc = $(d);

            var $this = $(this);

            //debug($this.selector);

            var triggerEvent = 'click';

            var linkSelector = isset(settings.linkSelector) ? settings.linkSelector : $this.selector;
            //debug(linkSelector);
            //return false;

            // Check if the element has a data attr which specifies the target
            // This would be used for a single element
            // Otherwise, get the 'container' setting provided in the plugin options
            // Data attr takes precendence over settings
            var theContainer = isset($this.attr('data-ajax-container')) ? $this.attr('data-ajax-container') : settings.container;
            //var theTarget = isset($this.attr('data-ajax-target')) ? $this.attr('data-ajax-target') : settings.targetElement;

            // Container validation
            if ( ! isset(theContainer) ) {
                debug('Container element is required.');
                return false;
                //window.location = $request;
            }
            else if (0 === $(theContainer).length) {
                debug('No element found: ' + theContainer);
                return false;
                //window.location = $request;
            }

            // Set up event data
            var eventData = {
                container: theContainer,
                stickToBottom: settings.stickToBottom,
                stickToTop: settings.stickToTop
            };

            // Add live event listener
            $doc.on(triggerEvent, linkSelector, eventData, $.fn.loadAjaxContent.loadPageContent);

            $doc.on('zd.page_loaded', function(){
                //debug('page_loaded');
                $('.tooltip').remove();

                if( $('.crayon-syntax').length ) {
                    //debug('.crayon-syntax on page');
                    //CrayonUtil.init();
                    CrayonSyntax.init();
                }

                // Re-jiggle Foundation
                $(document).foundation();
            });

            //return this;
        };

        $.fn.loadAjaxContent.defaults = $.extend({
            // These are the defaults.
            // Set container to the data-attribute by default
            container: '.ajax-content-area',
            linkSelector: null,
            stickToBottom: false,
            stickToTop: true
        });


        // Load page content
        $.fn.loadAjaxContent.loadPageContent = function (e) {
            //e.preventDefault();
            //debug(e);
            //debug(e);

            var $this = $(this);

            var $request = $this.attr('href');

            //debug($request);

            if (!$request) {
                debug('No href specified in anchor tag!');
                window.location = $request;
            }

            //var _container = '.ajax-content-area';

            //var containerSet = ( e.data.container !== 'undefined' );
            //var targetSet = ( typeof e.data.targetElement !== 'undefined' );

            //var _container = containerSet ? e.data.container : '[data-ajax-content-area]';
            //var _container = '.ajax-content-area';
            var _container = '';
            //var _target = '';

            if ( isset(e.data.container) ) {

                var $container = $(e.data.container);

                // If it is an ID, then use the container
                if (e.data.container.indexOf('#') === 0) {
                    _container = e.data.container;
                }
                // Get the ID if that's set
                else if (isset( $container.attr('id') )) {
                    _container = '#' + $container.attr('id');
                }
                // Or get the class(es) if no ID
                else if (isset( $container.attr('class') )) {
                    var containerClass = $container.attr('class');

                    // Concat classes to form a stronger selector
                    _container = '.' + containerClass.replace(' ', '.', containerClass);
                }
                else {
                    debug('Invalid container: ' + _container);
                    window.location = $request;
                    return false;
                }

                var $_container = $(_container);

                $_container.addClass('loading');

                $.get($request, function (response, status) {

                    //debug( status );
                    //debug(response);

                    if ('success' === status) {

                        var newContent = $(response).find(_container).html();

                        //debug(newContent);

                        //debug($(response).find(_target));

                        if (Modernizr.history) {
                            window.history.pushState(null, null, $request);
                        }

                        if(e.data.stickToTop) {
                            window.scrollTo(0, 0);
                            //ZD.debug(e);
                            //ZD.animateScrollVertical(0, e.pageY );
                        }
                        if (e.data.stickToBottom && isMediumUp()) {
                            window.scrollTo(0, document.body.scrollHeight);
                        }

                        $_container
                            .html(newContent)
                            .removeClass('loading');

                        //return false;
                        // Re-jiggle the DOM

                        $(document).trigger('zd.page_loaded');
                    }
                    else {
                        window.location = $request;
                    }

                    //return true;
                    //return false;
                }).fail(function(){
                    window.location = $request;
                });
            }
            else {
                debug('Container not set');
            }

            //debug( _container );

            //var _target = '.ajax-target-content';
            //var _target = (containerSet && targetSet) ? e.data.targetElement : $(_container).attr('data-ajax-content-area');

            //var contentRequest = $request + ' ' + _container + ' ' + _target;

            //debug( contentRequest );

            //debug(_container);

            return false;
        };

        // Private function for debugging.
        function debug(toDebug) {
            //console.log(window.devMode);
            if ( window.console && window.console.log && window.devMode ) {
                if (typeof toDebug === 'object' || toDebug instanceof Array) {
                    window.console.log('ZD Debug:');
                    window.console.log(toDebug);
                }
                else {
                    window.console.log('ZD Debug: ' + toDebug);
                }
            }
        }

        function isset(_var) {
            return (typeof _var !== 'undefined' && _var !== null);
        }

        function isMediumUp() {
            return window.matchMedia ? matchMedia(Foundation.media_queries.medium).matches : true;
        }

        function isLargeUp() {
            return window.matchMedia ? matchMedia(Foundation.media_queries.large).matches : true;
        }

    })(jQuery, document);



    // AJAX pagination listeners
    if( $('.pagination').length ) {
        $('.pagination').find('a').loadAjaxContent();
    }


    if( $('.nav-links').length ) {
        $('.nav-links a').loadAjaxContent();
    }


});