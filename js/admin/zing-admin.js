/**
 * Created by Sam on 6/05/14.
 */

jQuery(document).ready(function($) {
    if( $('input[type="color"]').length ) {

        $('input[type="color"]').each(function(){
            var $this = $(this),
                currentColor = $this.val();

            if( typeof window.spectrum !== 'undefined' ) {
                $this.spectrum({
                    color:      currentColor,
                    showInput:  true
                });
            }

        });
    }

    if( $('.zd-admin-tab-nav').length ) {
//        console.log( window.location.search );

        var tabNav = $('.zd-admin-tab-nav'),
            zdTabs = $('.zd-tab'),
            currentTab = '',
            currentTabInStorage = (typeof localStorage.ledaAdminCurrentTab !== 'undefined' && localStorage.ledaAdminCurrentTab !== null);

//        if(  ) {
        currentTab = currentTabInStorage ? localStorage.ledaAdminCurrentTab : '#tab-general';
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

            localStorage.ledaAdminCurrentTab = currentTab;

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
                    });

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
});
