function pre(formData, jqForm, options) {
    //console.log(formData);
    //console.log(jqForm);
    //console.log(options);
}

function post(data, statusText, xhr, $form)  {
    //console.log(data);
    
    if(data == '"submit"') {
        var redir = $('#campaign').val();
        window.location = '/competition/'+redir+'/submit/';
    } else {    
        data = $.parseJSON(data)
        // remove  error msg first
        $('#fb_form .errors').remove();
        $('#fb_form .errorHolder').remove();
        $('#fb_form .errorField').removeClass('errorField');
        
        if( data != null ){
            var errorArray = data;
            var errorHtml = '<div class="errorHolder" style="display:none;">';
            $.each(errorArray, function(key,val) {
                //console.log(key); 
                // find type of input, return validation
                $('#'+key).addClass('errorField');
                errorHtml = errorHtml + '<p class="errors">' + val + '</p>';
            });
            errorHtml = errorHtml + '</div>';
            $('#fb_form').append(errorHtml);
        }
        
        $('#fb_form .errorHolder').fadeIn(400);
    }
} 

// validation
$(document).ready(function() {
    
    $('#fbForm input[type=text]').each(function() {
        var default_value = this.value;
        
        $(this).focus(function(){
            if(this.value == default_value) {
                this.value = '';
            }
        });
        
        $(this).blur(function(){
            if(this.value == '') {
                this.value = default_value;
            }
        });
    });  
    
    formopts = { beforeSubmit: pre, success: post };
    $('#fbForm').ajaxForm(formopts);
});