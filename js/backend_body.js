var validator = jQuery("#settings").validate({
     errorClass: "bform_error"
    ,validClass: "bform_success"
//    ,debug: true // just for testing, avoids form submit
    ,errorPlacement: function(error, element) {
        error.insertAfter(element);
        error.before('<br />');
    }
    ,rules: {
         mail_to: {required: "#send_mail_y:checked", email: true}
        ,mail_from: { email: true }
        ,success_mail_to: {required: "#success_send_mail_y:checked", email: true}
        ,success_mail_to_field: {required: "#success_send_mail_y:checked"}
        ,success_mail_from: {required: "#success_send_mail_y:checked", email: true}
        ,success_mail_from_name: {required: "#success_send_mail_y:checked"}
        ,success_mail_subject: {required: "#success_send_mail_y:checked"}
        ,success_mail_body: {required: "#success_send_mail_y:checked"}
        ,attachment_maxsize: {required: "#attachments_y:checked"}
        ,attachment_types: {required: "#attachments_y:checked"}
    }
    ,messages: {
         mail_to: { required: cattranslate('This field is required because "send mail" is checked') }
        ,success_mail_to: { required: cattranslate('This field is required because "send mail" is checked') }
        ,attachment_maxsize: { required: cattranslate('This field is required because "allow attachments" is checked') }
        ,attachment_types: { required: cattranslate('This field is required because "allow attachments" is checked') }
        ,success_mail_to: {required: cattranslate('This field is required because "send mail" is checked')}
        ,success_mail_to_field: {required: cattranslate('This field is required because "send mail" is checked')}
        ,success_mail_from: {required: cattranslate('This field is required because "send mail" is checked')}
        ,success_mail_from_name: {required: cattranslate('This field is required because "send mail" is checked')}
        ,success_mail_subject: {required: cattranslate('This field is required because "send mail" is checked')}
        ,success_mail_body: {required: cattranslate('This field is required because "send mail" is checked')}
    }
});