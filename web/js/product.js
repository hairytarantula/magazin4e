function removebuyButtons(text) {
    $("#add_to_card").hide('slow', function(){ $('#add_to_card').remove(); });
    $("#button-buy").hide('slow', function(){ $('#add_to_card').remove(); });
    $("#remaining-count").html(text);
}
$(document).ready(function () {
    $("#add_to_card").click(function () {
        $.ajax({
            method: "POST",
            data: {
                product_id: $(this).data('id'),
                product_qty: 1
            },
            url: $(this).data('path'),
            success: function (response) {
                if(response.success == false || response.remaining == 0) {
                    removebuyButtons("Out of stock");
                } else {
                    $("#remaining-count").html(response.remaining);
                }
            }
        });
        updateCart();
        cartEffect();
  
      
 
 
      
});
        
        
        
    });



