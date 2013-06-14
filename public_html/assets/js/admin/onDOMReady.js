$(document).ready(function(){
    
    $('<fieldset>').appendTo('.formElementRowRadio .radioOptions #elementExtras-element').addClass('radioOptionSet');
    $('<input>').attr({ type: 'text', id: 'radioOptionInput', name: 'radioOptionInput', recname: 'radioOption' }).appendTo('.radioOptionSet').addClass('radioOptionInput');
    
    var optionsData = $('.formElementRowRadio #elementExtras').val();
    optionsData = optionsData.split("|*|");
    parsedOptions = new Array();
        
    $.each(optionsData, function(i, val){
        optionsData[i] = { radioOption : val };
    });
    
    $('.radioOptionSet').EnableMultiField({
                                    data:               optionsData,
                                    addEventCallback:   function(newElem, clonnedFrom){ 
                                                                newElem.find("input").change(function() { setOptionsData(); });
                                    }
    });
    
    $('#submitFields').click(function(e) {
                                e.preventDefault();
                                setOptionsData();
                                $("#appFields").submit();
    });     
});

function setOptionsData(){
    var optionsData = '';
    $('.radioOptionInput').each( function(){ if($(this).val() != ''){ optionsData = optionsData + $(this).val() + '|*|'; } });
    optionsData = optionsData.substring(0, optionsData.length - 3);
    
    $('.formElementRowRadio .radioOptions #elementExtras').val(optionsData);
}