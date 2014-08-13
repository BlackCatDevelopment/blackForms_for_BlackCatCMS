if(typeof jQuery != 'undefined')
{
    function bF_confirm(execute,attr,message)
    {
        $('<div></div>').appendTo('body')
            .html('<div><h6>'+message+'</h6></div>')
            .dialog({
                modal: true, title: cattranslate('Confirm'), zIndex: 10000, autoOpen: true,
                width: 'auto', resizable: false,
                buttons: {
                    Yes: function () {
                        execute(attr);
                        $(this).dialog("close");
                    },
                    No: function () {
                        $(this).dialog("close");
                    }
                },
                close: function (event, ui) {
                    $(this).remove();
                }
            });
    }

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
				data.process	= set_activity( 'Saving...' );
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

    function bF_save(form_id)
    {
        var page_id = $('div#fc_add_module').find('input[name="page_id"]').val();
        var req     = 'N';
        if(typeof $("#required_Y:checked").val() != 'undefined') {
            req = 'Y';
        }
        var formdata = $("#"+form_id).serialize();
        formdata = formdata + "&required=" + req;
        formdata = formdata + "&page_id=" + page_id;
        formdata = formdata + "&_cat_ajax=1";
        $.ajax(
		{
			type:		'POST',
			url:		CAT_URL + '/modules/blackForms/ajax/ajax_save.php',
			dataType:	'json',
			data:		formdata,
			cache:		false,
			beforeSend:	function( data )
			{
				data.process	= set_activity( 'Saving...' );
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

    function bF_save_preset(name,display_name)
    {
        var page_id = $('div#fc_add_module').find('input[name="page_id"]').val();
        $.ajax(
		{
			type:		'POST',
			url:		CAT_URL + '/modules/blackForms/ajax/ajax_save.php',
			dataType:	'json',
			data:		$('form#reset_form').serialize() + "&action=save_as_preset&name=" + name + "&display_name=" + display_name,
			cache:		false,
			beforeSend:	function( data )
			{
				data.process	= set_activity( 'Saving...' );
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
        if(!$('form#preset').length && !$('form#settings').length) {
            $('button#save_as_preset').removeClass('ui-state-default').addClass('fc_gradient_blue');
            $('fieldset.ui-widget.ui-widget-content.ui-corner-all.ui-helper-clearfix').addClass('sortable');
            $('fieldset.ui-widget.ui-widget-content.ui-corner-all.ui-helper-clearfix button').attr("disabled","disabled");

            // add buttons
            $(
                'div#mod_blackforms fieldset.ui-widget.ui-widget-content.ui-corner-all.ui-helper-clearfix label,' +
                'div#mod_blackforms fieldset.ui-widget.ui-widget-content.ui-corner-all.ui-helper-clearfix div.radiogroup'
            )
                .not('div.radiogroup > label')
                .not('div.checkboxgroup > label')
                .each( function() {
                $(this).add($(this).nextUntil('label,button,div')).wrapAll('<div class="line"></div>');
            });
            $('div.radiogroup,div.checkboxgroup').css('display','inline-block');
            $('div.line').find('br').remove();
            $('div.line').append('<button class="right fc_gradient_blue fc_br_all icon icon-plus"></button>');
            $('div.line').append('<button class="right fc_gradient_red fc_br_all icon icon-minus"></button>');
            $('div.line').append('<button class="right gradient_green fc_br_all icon icon-tools"></button>');

            var dialog_settings = {
                modal: true,
                autoOpen: false,
                height: 'auto',
                width: 'auto',
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
                            bF_save($(this).find('form').prop('id'));
                            //dialog.find('form').get(0).reset();
                        },
                        icons: {
                            primary: "ui-icon-check"
                        }
                    }
                ]
            };
            var dialog  = $('#dialog1').dialog(dialog_settings);
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
                bF_confirm(bF_remove,field,cattranslate('Are you sure that you really want to delete this field?','','','blackForms'));
            });
            /***********************************************************************
             * Edit form element
             **********************************************************************/
            $('button.icon-tools').click( function(e) {
                e.stopPropagation();
                e.preventDefault();
                var field = $(this).parent().find('input,select,textarea').prop('id');
                dialog2.find('input#name').val(field);
                dialog2.find('input#display_name').val(field);
                var text  = $(this).parent().find('.fblabel').text();
                dialog2.find('input#label').val(text);
                if( $(this).parent().find('select').length )
                {
                    var myOptions = [] ;
                    var select = $(this).parent().find('select').prop('id');
                    $('#' + select + ' option').each(function(){
                        if(this.value != this.text) {
                            myOptions.push( this.value + "|" + this.text );
                        } else {
                            myOptions.push( this.value );
                        }
                    });
                    dialog2.find('textarea#options').val(myOptions.join("\n"));
                    var defaultval = $('#'+select+' :checked').val();
                    dialog2.find('input#default_value').val(defaultval);
                }
                var is_req = $(this).parent().find('span.fbrequired');
                if(typeof is_req != 'undefined' && is_req.length > 0 ) {
                     dialog2.find('label[for="required_Y"]').addClass('ui-state-active');
                     dialog2.find('label[for="required_N"]').removeClass('ui-state-active');
                     dialog2.find('#required_Y').prop('checked',true);
                }
                else
                {
                     dialog2.find('label[for="required_N"]').addClass('ui-state-active');
                     dialog2.find('label[for="required_Y"]').removeClass('ui-state-active');
                     dialog2.find('#required_N').prop('checked',true);
                }
                dialog2.dialog('open');
            });

            /***********************************************************************
             * Add 'Are you sure' to buttons
             **********************************************************************/
            //$('button#reset_to_preset').unbind('click').removeAttr('onclick');
            $("form#reset_form").unbind('submit').submit(function (e) {
                var afterSend		= function()
            	{
            		location.reload(true);
            	};
                if($(document.activeElement).prop('id') == 'save_as_preset')
                {
                    $('<div></div>').appendTo('body')
                        .html('<div><form id="save_as_preset"><label for="preset_name">' + cattranslate('Name') + '</label><input type="text" name="preset_name" id="preset_name" /><br /><label for="display_name">Display name</label><input type="text" name="display_name" id="display_name" /></form></div>')
                        .dialog({
                            modal: true, title: cattranslate('Insert a name','','','blackForms'), zIndex: 10000, autoOpen: true,
                            width: 'auto', resizable: false,
                            buttons: {
                                OK: function () {
                                    var name = $(this).find('#preset_name').val(),
                                        display_name = $(this).find('#display_name').val();
                                    bF_save_preset(name,display_name);
                                    $(this).dialog("close");
                                },
                                Cancel: function () {
                                    $(this).dialog("close");
                                }
                            },
                            close: function (event, ui) {
                                $(this).remove();
                            }
                        });
                }
                //$("form#reset_form").unbind('submit');
                e.stopPropagation();
                e.preventDefault();
                if($(document.activeElement).prop('id') == 'reset_to_preset')
                {
                    dialog_confirm(
                        cattranslate('Are you sure that you really want to reset this form?','','','blackForms'),
                        'Confirm',
                        $('form#reset_form').attr('action'),
                        $('form#reset_form').serialize() + "&action=reset_to_preset",
                        'POST','HTML',afterSend
                    );
                }
                if($(document.activeElement).prop('id') == 'complete_reset')
                {
                    dialog_confirm(
                        cattranslate('Are you sure that you really want to DELETE this form?','','','blackForms'),
                        'Confirm',
                        $('form#reset_form').attr('action'),
                        $('form#reset_form').serialize() + "&action=complete_reset",
                        'POST','HTML',afterSend
                    );
                }
                return false;
            });

            $('.sortable').sortable({
                axis: "y",
                grid: [20,10],
                items: 'div',
                placeholder: "ui-state-highlight",
                forceHelperSize: true,
                forcePlaceholderSize: true,
                update: function( event, ui )
                {
                    ui.item.effect('highlight','slow');
                    var this_id = ui.item.find('input,textarea,select,radio,checkbox').attr('id');
                    var prev_id = ui.item.prev().find('input,textarea,select,radio,checkbox').attr('id');
                    var next_id = ui.item.next().find('input,textarea,select,radio,checkbox').attr('id');
                    var page_id = $('div#fc_add_module').find('input[name="page_id"]').val();
                    $.ajax(
            		{
            			type:		'POST',
            			url:		CAT_URL + '/modules/blackForms/ajax/ajax_sort.php',
            			dataType:	'json',
            			data:		{
                            id: this_id,
                            prev: prev_id,
                            next: next_id,
                            page_id: page_id,
                            preset_id: $('#preset_id').val()
                        },
            			cache:		false,
            			beforeSend:	function( data )
            			{
            				data.process	= set_activity( 'Saving...' );
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
            }).disableSelection();
        }
    });
}