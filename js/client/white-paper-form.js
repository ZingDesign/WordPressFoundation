/**
 * Created by Sam on 17/11/14.
 */

jQuery(document).ready(function($){
    'use strict';

    if( $('#white-paper-form').length ) {
        $('#white-paper-form')
            .on('invalid.fndtn.abide', function () {
                //Hide all errors by default
                $('small.error').hide();

                var invalid_fields = $(this).find('[data-invalid]');
                //console.log(invalid_fields);

                invalid_fields.each(function(){
                    var $this = $(this);

                    var msg = $this[0].validationMessage;
                    //console.log($this);
                    console.log( msg );
                    ZD.debug( $this.parent().find('small.error').length );

                    if( $this.parent().find('small.error').length ) {
                        $this.parent().find('small.error').text(msg);
                    }
                    else {
                        $('<small class="error">'+msg+'</small>').insertAfter($this);
                    }

                    $this.parent().find('small.error').show();

                });
            });
    }
});