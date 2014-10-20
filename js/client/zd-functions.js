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

                $this.on('click', function() {

                    var linkTarget = $this.attr('href');

                    // Check if the href starts with # (is a local anchor)

                    if( linkTarget.indexOf('#') === 0) {
                        var targetElement = $(linkTarget);

                        if( targetElement.length ) {
                            $('html, body').animate({
                                scrollTop: targetElement.offset().top
                            });
                        }
                        else {
                            return true;
                        }
                    }
                    else {
                        return true;
                    }

                    return false;
                });

            });
        },
        responsiveImage: function() {
            return this.each(function() {
                var $this = $(this);

                var windowWidth = $(window).width();

                var imageWidth = $this.outerWidth();
                var imageHeight = $this.outerHeight();

                //console.log('containerWidth: ' + containerWidth);
                //console.log('imageWidth: ' + imageWidth);
                //console.log('imageHeight: ' + imageHeight);

                // Image width exceeds container width, get the ratio of the image width
                // To the container width and apply that ratio to the height
                if( imageWidth > windowWidth ) {
                    var widthRatio = windowWidth / imageWidth;

                    //console.log(widthRatio);

                    var newHeight = Math.floor(imageHeight * widthRatio);

                    //console.log(newHeight);

                    $this.css({
                        'height': newHeight + 'px',
                        'width': '100%'
                    });
                }
            });
        },
        forceFullWidth: function() {
            var i = 0;

            return this.each(function() {
                var $this = $(this);

                var windowWidth = $(window).width();

                //console.log($this.outerWidth());

                //var currentOffset = (windowWidth / 2) - $this.offset().left;
                var currentOffset = (windowWidth / 2) - ($this.outerWidth() / 2);

                //console.log($this.offset().left);

                //var paddingTop = parseInt($this.css('padding-top').replace('px', ''), 10);
                //var paddingBottom = parseInt($this.css('padding-bottom').replace('px', ''), 10);

                var currentHeight = $this.outerHeight(true);

                console.log(currentHeight);

                var fullWidthStyle = 'position: absolute; left: -' + currentOffset + 'px; right: -' + currentOffset + 'px;';

                //console.log(fullWidthStyle);

                $this.removeClass('force-full-width');

                var currentClass = typeof $this.attr('class') !== 'undefined' ? $this.attr('class') : 'ffw-element';

                // Get current ID if there is one, otherwise generate an ID
                var currentID = typeof $this.attr('id') !== 'undefined' ? $this.attr('id') : 'ffw-element-' + i;

                $this
                    //.removeAttr('class')
                    .removeAttr('id')
                    .addClass('force-full-width')
                    .addClass('container')
                    .wrap('<div id="'+currentID+'-container" style="position:relative; height: ' + currentHeight + 'px;"></div>')
                    .wrap('<div id="'+currentID+'" class="'+currentClass+'" style="'+fullWidthStyle+'"></div>');

                i ++;

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
