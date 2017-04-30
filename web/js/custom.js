function updateCart() {
    $.ajax({
                method: "GET",
                url: "/app_dev.php/cart/count",
                success: function (response) {
                   $('a[href="/app_dev.php/cart/view"]').html('<i class="fa fa-shopping-cart" aria-hidden="true"></i> Cart (' + response['total_count'] + ')');
                   
                }
});
}
function cartEffect() {
          $('#menu_cart').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function (e) {
            $('#menu_cart').removeClass('animation-target');
        });

        $('#menu_cart').removeClass('animation-target').addClass('animation-target');
    
} 
$(document).ready(function () {
updateCart();
});
