$(function() {
    $(".autocomplete").autocomplete({
        minLength: 2,
        scrollHeight: 220,
        source: function(req, add){
            $.ajax({
                url:'/app_dev.php/category/autocomplete',
                type:"get",
                dataType: 'json',
                data: 'term='+req.term,
                async: true,
                cache: true,
                success: function(data){
                    add(data);
                }
            });
        }
    });
});