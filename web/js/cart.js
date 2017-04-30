        function updateQty(id, qty) {
            $.ajax({
                method: "POST",
                data: {
                    product_id: id,
                    product_qty: qty
                },
                url: Routing.generate('change_cart'),
                success: function (response) {
                    $("#total_price").html(response.new_total);
                    $("#menu_cart").html("Cart (" + response.total_count + ")");
                    cartEffect();
                }
            });
        }

        function trashItem(id) {
            $.ajax({
                method: "POST",
                data: {
                    product_id: id,
                    product_qty: 0
                },
                url: Routing.generate('change_cart'),
                success: function (response) {
                    $("#menu_cart").html("Cart (" + response.total_count + ")");
                    $("#total_price").html(response.new_total);
                    $("#item"+id).slideUp();
                }
            });
            cartEffect();
            
            
        }