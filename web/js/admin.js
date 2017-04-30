$(document).ready(function () {
    $(".js-processed-actions").click(function () {
        $.ajax({
            method: "GET",
            url: $(this).data('path'),
            success: function (response) {
                $(this).text(response.new_text);
                if(response.delete) {
                    $('.' + response.delete).hide('slow', function(){ $('.' + response.delete).remove(); });
                }
                if(response.redirect) {
                    window.location.href = response.redirect;
                }
            }
        });
    });
});