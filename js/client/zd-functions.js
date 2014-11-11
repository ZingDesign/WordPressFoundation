/**
 * Created by Sam on 2/07/14.
 *
 * Zing Design Custom functions:
 */

(function($, window){
    'use strict';

    window.ZD = {
        medium: 'screen and (min-width:768px)',
        large: 'screen and (min-width:980px)',
        mediumUp: matchMedia('screen and (min-width:768px)').matches,
        largeUp: matchMedia('screen and (min-width:980px)').matches,
        baseUrl: location.protocol + '//' + location.host,
        ltIE9: $('html').hasClass('lt-ie9'),


        expandToTallest: function ( selector ) {

            // check if the selector is a string
            if( typeof selector === 'string' ) {
                this.generateStyleForTallest( $(selector) );

            }
            else if( selector instanceof Array ) {
                for(var i = 0; i < selector.length; i++) {
                    this.generateStyleForTallest( $(selector[i]) );
                }
            }
            return false;


        },
        generateStyleForTallest: function(_jqObj) {

            // Check if the selected element exists in the DOM
            if( _jqObj.length ) {
                // Get the value of the tallest element
                var tallest = this.getTallest(_jqObj);

                //console.log(tallest);

                // If the tallest value is returned
                // Add the style to the head
                if(tallest !== 0) {

//                    console.log(_jqObj);
                    this.addStyleToHead(_jqObj.selector, 'height:' + tallest + 'px;');
                }
            }

        },
        expandToWidest: function ( selector ) {
            var jqObj = $(selector),
                widest = this.getWidest(jqObj);

            if(widest !== 0) {
                this.addStyleToHead(selector, 'width:' + widest + 'px;');
            }

        },
        getTallest: function(obj) {
            var tallest = 0;
            obj.each(function(){
                var $this = $(this);

                if( $this.outerHeight() > tallest ) {
                    tallest = $this.outerHeight();
                }

            });
            return tallest;
        },
        getWidest: function(obj) {
            var widest = 0;
            obj.each(function(){
                var $this = $(this);

                if( $this.outerWidth() > widest ) {
                    widest = $this.outerWidth();
                }

            });
            return widest;
        },
        addStyleToHead: function(selector, rules) {
            if( ! $(selector).length ) {
                console.log('Error: the element you have selected does not exist');
                return false;
            }

            if (!$('#style-output').length) {
                var headElement = $('head');
                $('<style type="text/css" id="style-output"></style>').appendTo(headElement);
            }

            var css = selector + '{';

            //        console.log( typeof rules );

            if( typeof rules === 'object') {
                for(var i in rules) {
                    var value = rules[i].toString();

                    css += i + ':' + value + ';';
                }
            }
            else if( typeof rules === 'string' ) {
                css += rules;
            }
            else {
                console.log('Error: CSS Rules can only be passed as either an object or string');
            }

            css += '}';

            $('#style-output').append(css);
            return true;
        },
        debug: function(toDebug) {
            //console.log(window.devMode);
            if ( window.console && window.console.log && window.ZD.isDevMode() ) {
                if (typeof toDebug === 'object' || toDebug instanceof Array) {
                    window.console.log('ZD Debug Object:');
                    window.console.log(toDebug);
                }
                else {
                    window.console.log('ZD Debug: ' + toDebug);
                }
            }
        },
        isset: function(_var) {
            return (typeof _var !== 'undefined' && _var !== null);
        },
        isDevMode: function() {
            return window.ZD.isset(window.devMode);
        }
    };

    // ==========================================================================
    // jQuery extensions:
    // ==========================================================================

    // center an object based on window width
    $.fn.extend({
        centerInWindow: function() {
            return this.each(function() {

                var $this = $(this),
                    leftPos = ($(window).width() / 2) - ($this.outerWidth() / 2);

                $this.css({
                    'left': Math.floor(leftPos) + 'px'
                    //'position': 'absolute'
                });
            });

        },
        scrollToLinkTarget: function() {
            return this.each(function() {

                var $this = $(this);

                $this.on('click', function(e) {

                    var linkTarget = $this.attr('href');

                    // Check if the href starts with # (is a local anchor)

                    if( linkTarget.indexOf('#') === 0) {
                        var targetElement = $(linkTarget);

                        if( targetElement.length ) {
                            e.preventDefault();

                            $('html, body').animate({
                                scrollTop: targetElement.offset().top
                            });
                        }
                    }
                });

            });
        }
    });
})(jQuery, window);

// JS polyfills

// Array.indexOf

if (!Array.prototype.indexOf)
{
    Array.prototype.indexOf = function(elt /*, from*/)
    {
        var len = this.length >>> 0;

        var from = Number(arguments[1]) || 0;
        from = (from < 0)
            ? Math.ceil(from)
            : Math.floor(from);
        if (from < 0)
            from += len;

        for (; from < len; from++)
        {
            if (from in this &&
                this[from] === elt)
                return from;
        }
        return -1;
    };
}
