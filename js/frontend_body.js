if(typeof jQuery != 'undefined')
{
    // check required fields; this fixes a problem with Chrome/Iron (v56)
    // sending the form though there are missing fields
    $("div.fbform form").on("submit", function() {
        var missing = 0;
        $(this).find(':required').each(function(){
            $(this).removeClass('ui-state-highlight');
            if($(this).val()==""){ 
                missing = missing + 1;
                $(this).addClass('ui-state-highlight');
            }
        });
        if(missing==0) {
            return true;
        }
        return false;
    });
}