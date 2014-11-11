/**
 * Created by Sam on 6/05/14.
 */


jQuery(document).ready(function($) {
    if( $('.zd-color-input').length ) {

        $('.zd-color-input').each(function(){
            var $this = $(this),
                currentColor = $this.val();

            //console.log(spectrum);

            $this.spectrum({
                preferredFormat: "hex",
                color:      currentColor,
                showInput:  true,
                showInitial: true,
                allowEmpty: true

            });

        });
    }

    if( $('.zd-admin-tab-nav').length ) {
//        console.log( window.location.search );

        var tabNav = $('.zd-admin-tab-nav'),
            zdTabs = $('.zd-tab'),
            currentTab = '',
            currentTabInStorage = (typeof localStorage.zdAdminCurrentTab !== 'undefined' && localStorage.zdAdminCurrentTab !== null);

//        if(  ) {
        currentTab = currentTabInStorage ? localStorage.zdAdminCurrentTab : '#tab-general';
        tabNav.find('a').removeClass('nav-tab-active');

        tabNav.find('a').each(function(){
            var $this = $(this);
            if( currentTab === $this.attr('href') ) {
                $($this).addClass('nav-tab-active');
            }
        });

        zdTabs.removeClass('zd-active-tab');
        $(currentTab).addClass('zd-active-tab');
//        }

        tabNav.find('a').on('click', function(){
            var $this = $(this);

            currentTab = $this.attr('href');

            tabNav.find('a').removeClass('nav-tab-active');
            $this.addClass('nav-tab-active');

            zdTabs.removeClass('zd-active-tab');
            $(currentTab).addClass('zd-active-tab');

            localStorage.zdAdminCurrentTab = currentTab;

            return false;
        });
    }

    if( $('.zd-insert-image-button').length ) {
        var custom_uploader,
            insertImgBtn = $('.zd-insert-image-button');

        insertImgBtn.on('click', function(e) {

            e.preventDefault();

            var $this = $(this),
                parentElement = $this.parent(),
                srcOutput = parentElement.find("[id^='zd-image-src']"),
                idOutput = parentElement.find("input[id^='zd-image-id']"),
                imagePreview = parentElement.find("div[id^='zd-image-preview']");

            //If the uploader object has already been created, reopen the dialog
            if (custom_uploader) {
                custom_uploader.open();
                return;
            }

            //Extend the wp.media object
            custom_uploader = wp.media.frames.file_frame = wp.media({
                title: 'Choose Image',
                button: {
                    text: 'Choose Image'
                },
                multiple: false
            });

            //When a file is selected, grab the URL and set it as the text field's value
            custom_uploader.on('select', function() {
                var attachment = custom_uploader.state().get('selection').first().toJSON();

                srcOutput.val(attachment.url);
//                console.log( attachment);
                idOutput.val( attachment.id );
//                var altText = '';
//                if( '' !== attachment.alt ) {
//                    altText = attachment.alt;
//                }
//                else {
//                    altText = attachment.name;
//                }
//                altOutput.val( altText );
//                widthOutput.val( attachment.width );
//                heightOutput.val( attachment.height );

//                displayImage();

                imagePreview
                    .css({
                        'background': 'url(' + attachment.url +') no-repeat',
                        'width': attachment.width,
                        'height': attachment.height
                    })
                    .removeClass('zd-hide');

                $('.image-preview-label').removeClass('zd-hide');
//                    .attr({
////                        'src': attachment.url,
////                        'alt': attachment.alt,
////                        'width': attachment.width,
////                        'height': attachment.height
//                    });
            });

            //Open the uploader dialog
            custom_uploader.open();

        });

        $('.zd-remove-image-button').on('click', function() {

            var $this = $(this),
                parentElement = $this.parent(),
                srcOutput = parentElement.find("[id^='zd-image-src']"),
                idOutput = parentElement.find("input[id^='zd-image-id']"),
                imagePreview = parentElement.find("div[id^='zd-image-preview']");

            srcOutput.val('');
            idOutput.val('');
            imagePreview.removeAttr('style');

            insertImgBtn.text('Insert image');

            $('.image-preview-label').addClass('zd-hide');

            return false;
        });
    }

    if( $('.zd-icon-preview').length ) {

        //$('.zd-icon-preview').each(function() {
        //    var $this = $(this);
        //
        //    var icon = $this.find('i');
        //
        //    var radioElements = $this.siblings('.f-dropdown').find('input[type="radio"]');
        //
        //    //var initValue = selectElement.val();
        //    //
        //    //icon.addClass('fa-' + initValue);
        //
        //    radioElements.on('click', function(){
        //        var currentValue = $(this).val();
        //
        //        //console.log(currentValue);
        //
        //        icon.removeAttr('class')
        //            .addClass('fa-' + currentValue)
        //            .addClass('fa');
        //    });
        //});

        if( $('.zd-icon-list-item') ) {
            $('.zd-icon-list-item').on('click', function() {
                var $this = $(this);

                var currentIcon = $this.attr('title');

                console.log(currentIcon);

                $this
                    .parents('.zd-input-group')
                    .find('.zd-icon-preview')
                    .find('i')
                    .attr('class', 'fa fa-' + currentIcon);

                //return false;
            })
        }

    }

    // Toggle metabox view based on page template selected

    if( $('#page_template').length && $('#landing_page_options').length ) {

        var $pageTemplate = $('#page_template');

        var landingPageOptions = '#landing_page_options';

        $pageTemplate.on('init change', null, {'targetElement':landingPageOptions}, onPageTemplateChange);
        $pageTemplate.trigger('init');
    }

    function onPageTemplateChange(event) {

        //console.log(event);

        var $this = $(this);
        var _targetElement = $(event.data.targetElement);

        //console.log($this.val());

        if( $this.val().indexOf( 'landing-page') > -1 ) {
            _targetElement.show();
        }
        else {
            _targetElement.hide();
        }
    }


    //var targetType = '';
    //var toggle = '';

    if( $('[data-zd-target]').length ) {
        var zdTargets = $('[data-zd-target]');
        var $doc = $(document);

        zdTargets.each(function() {
            var $this = $(this);

            var target = $this.attr('data-zd-target');
            var state = $this.attr('data-zd-state');
            var toggle = $this.attr('data-zd-toggle');

            // If to-toggle is set, set target to jQuery(elementToToggle)
            // Else set to $this by default

            var toToggle = $this.attr('data-zd-to-toggle') ? $( $this.attr('data-zd-to-toggle') ) : $this;

            // Put in initial 'off' state
            toggleOff($this, toggle);

            if( ! $(target).length ) {
                console.log( 'ZD Error: Invalid target selector: ' + target );
            }
            else {
                var $target = $(target);
                var eventType = 'click';

                var targetType = $target.attr('type');

                var eventData = {
                    'targetType':   targetType,
                    'toggleType':   toggle,
                    'toToggle':     toToggle,
                    'targetState':  state
                }

                $target.on('init ' + eventType, null, eventData, toggleState);

                // Initial toggle based on initial state
                $target.trigger('init');
            }


        });

        //console.log( zdTargets.length );
    }

    function toggleOn(element, toggleType) {

        switch(toggleType) {
            case 'enable' : element.removeAttr('disabled'); break;
            case 'display' : element.show(); break;
            default : console.log('Invalid toggle type'); break;
        }

    }

    function toggleOff(element, toggleType) {

        switch(toggleType) {
            case 'enable' : element.attr('disabled', true); break;
            case 'display' : element.hide(); break;
            default : console.log('Invalid toggle type'); break;
        }

    }

    function toggleState(event) {
        var $currentTarget = $(this);

        //console.log($currentTarget);
        //console.log(event);

        var _toggle = event.data.toggleType;
        var _targetType = event.data.targetType;
        var _toToggle = event.data.toToggle;
        var _state = event.data.targetState;

        //console.log( _toggle );
        //console.log( 'Target clicked! Value: ' + $currentTarget.is(':checked') );

        // Default condition is that the state matches the current target value
        // Which is true for text, select
        var condition =  $currentTarget.val() === _state;

        // Checkbox is an exception to the rule, so the condition must change
        if( 'checkbox' === _targetType ) {
            condition = $currentTarget.is(':checked');
        }

        if( condition ) {
            toggleOn(_toToggle, _toggle);
        }
        else {
            toggleOff(_toToggle, _toggle);
        }

        //return false;
    }
});

jQuery(document).foundation();
