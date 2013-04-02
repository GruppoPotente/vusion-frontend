function addCounter(){		
    $.each(
        $("[name*='content']"),        
        function (key, elt){
            if ($(elt).prev('span').length>0) {
                return;
            }
            $("<span style='float: right; padding-right: 13px;'> <span id="+$(elt).attr('name')+">"+$(elt).val().replace(/{.*}/g, '').length+"</span> Characters</span>").insertBefore($(elt));
            $(elt).keyup(function(){
                    //alert("hi");
                    $('[id="'+$(elt).attr('name')+'"]').text($(this).val().replace(/{.*}/g, '').length);
            }); 
        });
}     
