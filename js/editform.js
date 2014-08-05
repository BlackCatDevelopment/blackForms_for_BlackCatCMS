if(typeof jQuery != 'undefined') {

    function bF_remove(field)
    {
        var page_id = $('div#fc_add_module').find('input[name="page_id"]').val();
        $.ajax(
		{
			type:		'POST',
			url:		CAT_URL + '/modules/blackForms/ajax/ajax_save.php',
			dataType:	'json',
			data:		{
                page_id    : page_id,
                preset_id  : $('#preset_id').val(),
                name       : field,
                do         : 'remove',
                '_cat_ajax': 1
            },
			cache:		false,
			beforeSend:	function( data )
			{
				data.process	= set_activity( 'Save...' );
			},
			success:	function( data, textStatus, jqXHR  )
			{
                $('.popup').dialog('destroy').remove();
                if ( data.success === true )
				{
					location.reload(true);
				}
				else {
					return_error( jqXHR.process , data.message );
				}
            }
        });

    }

    function bF_save()
    {
        var page_id = $('div#fc_add_module').find('input[name="page_id"]').val();
        var req     = 'N';
        if(typeof $("#required_Y:checked").val() != 'undefined') {
            req = 'Y';
        }
        $.ajax(
		{
			type:		'POST',
			url:		CAT_URL + '/modules/blackForms/ajax/ajax_save.php',
			dataType:	'json',
			data:		{
                page_id    : page_id,
                preset_id  : $('#preset_id').val(),
                name       : $('#name').val(),
                type       : $('#type').val(),
                where      : $('#where').val(),
                after      : $('#after').val(),
                required   : req,
                label      : $('#label').val(),
                '_cat_ajax': 1
            },
			cache:		false,
			beforeSend:	function( data )
			{
				data.process	= set_activity( 'Save...' );
			},
			success:	function( data, textStatus, jqXHR  )
			{
                $('.popup').dialog('destroy').remove();
                if ( data.success === true )
				{
					location.reload(true);
				}
				else {
					return_error( jqXHR.process , data.message );
				}
            }
        });
    }

    jQuery(document).ready(function($) {
        $('fieldset.ui-widget.ui-widget-content.ui-corner-all.ui-helper-clearfix').addClass('sortable');
        $('fieldset.ui-widget.ui-widget-content.ui-corner-all.ui-helper-clearfix button').attr("disabled","disabled");
        $('div#mod_blackforms fieldset.ui-widget.ui-widget-content.ui-corner-all.ui-helper-clearfix label,div#mod_blackforms fieldset.ui-widget.ui-widget-content.ui-corner-all.ui-helper-clearfix div.radiogroup, div#mod_blackforms fieldset.ui-widget.ui-widget-content.ui-corner-all.ui-helper-clearfix div.checkboxgroup').each( function() {
            $(this).add($(this).nextUntil('label,button')).wrapAll('<div class="line"></div>');
        });
        $('div.line').find('br').remove();
        $('div.line').append('<button class="right fc_gradient_blue fc_br_all icon icon-plus"></button>');
        $('div.line').append('<button class="right fc_gradient_red fc_br_all icon icon-minus"></button>');
        $('div.line').append('<button class="right gradient_green fc_br_all icon icon-tools"></button>');

        var dialog_settings = {
            modal: true,
            autoOpen: false,
            height: 350,
            width: 600,
            buttons: [
                {
                    text:  cattranslate('Cancel'),
                    click: function()
                    {
                        //dialog.find('form').get(0).reset();
                        $(this).dialog("close");
                    },
                    icons: {
                        primary: "ui-icon-close"
                    }
                },
                {
                    text:  cattranslate('Save'),
                    click: function()
                    {
                        bF_save();
                        //dialog.find('form').get(0).reset();
                    },
                    icons: {
                        primary: "ui-icon-check"
                    }
                }
            ]
        };
        var dialog = $('#dialog1').dialog(dialog_settings);
        var dialog2 = $('#dialog2').dialog(dialog_settings)

        /***********************************************************************
         * Add new form element
         **********************************************************************/
        $('button.icon-plus').click( function(e) {
            e.stopPropagation();
            e.preventDefault();
            var field = $(this).parent().find('input').prop('id');
            $('#dialog1').find('select#after option[value="' + field + '"]').prop({selected:true});
            $('#dialog1').find('select#where option[value="after"]').prop({selected:true});
            dialog.dialog('open');
        });
        /***********************************************************************
         * Remove form element
         **********************************************************************/
        $('button.icon-minus').click( function(e) {
            e.stopPropagation();
            e.preventDefault();
            var field = $(this).parent().find('input,select,textarea').prop('id');
            if(field == 'undefined') {
                field = $(this).parent().find('div').prop('id');
            }
            bF_remove(field);
        });
        /***********************************************************************
         * Edit form element
         **********************************************************************/
        $('button.icon-tools').click( function(e) {
            e.stopPropagation();
            e.preventDefault();
            var field = $(this).parent().find('input,select,textarea').prop('id');
            dialog2.find('input#name').val(field);
            var text  = $(this).parent().find('.fblabel').text();
            dialog2.find('input#label').val(text);
            var is_req = $(this).parent().find('span.fbrequired');
            if(typeof is_req != 'undefined' && is_req.length > 0 ) {
                 dialog2.find('label[for="required_Y"]').addClass('ui-state-active');
                 dialog2.find('label[for="required_N"]').removeClass('ui-state-active');
            }
            else
            {
                 dialog2.find('label[for="required_N"]').addClass('ui-state-active');
                 dialog2.find('label[for="required_Y"]').removeClass('ui-state-active');
            }
            dialog2.dialog('open');
        });

        $('.sortable').sortable({
            axis: "y",
            grid: [20,10],
            items: 'div',
            placeholder: "ui-state-highlight",
            forceHelperSize: true,
            forcePlaceholderSize: true,
            update: function( event, ui ) { ui.item.effect('highlight','slow'); }
        }).disableSelection();
    });
}