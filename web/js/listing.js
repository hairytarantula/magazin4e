var w = $(window).width();
var w_con = $("#mainContainer").css("margin-left");
console.log(w_con);
$(document).ready(function () {


});


function showOverlay() {
    $(".overlay").fadeIn();
}

function hideOverlay() {
    $(".overlay").fadeOut();
}

$(window).resize(function () {
    w = $(window).width();
    w_con = $("#mainContainer").css("margin-left");
    console.log(w_con);
});



function openNavPush() {
    document.getElementById("sideMenu").style.width = "250px";
    document.getElementById("mainContainer").style.marginLeft = "250px";
}

function closeNavPush() {
    document.getElementById("sideMenu").style.width = "0";
    document.getElementById("mainContainer").style.marginLeft= w_con;
}

/* Set the width of the side navigation to 250px */
function openNavOverlay() {
    document.getElementById("sideMenu").style.width = "250px";
    showOverlay();
}

/* Set the width of the side navigation to 0 */
function closeNavOverlay() {
    document.getElementById("sideMenu").style.width = w_con;

}

function openNav() {
    if(w > 768){
        // big
        openNavPush();
    }else{
        openNavOverlay();
    }
}

function closeNav() {
    document.getElementById("sideMenu").style.width = "0";
    document.getElementById("mainContainer").style.marginLeft= w_con;
    hideOverlay();
}
