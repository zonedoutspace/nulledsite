



$(document).ready(function () {

    var $cSlide = $(".category-banner-slider");
    $cSlide.owlCarousel({
        navigation: false, // Show next and prev buttons
        pagination: false,
        slideSpeed: 300,
        paginationSpeed: 400,
        singleItem: true
    });
    // Custom Navigation Events
    $(".sliderNav .next").click(function () {
        $cSlide.trigger('owl.next');
    })
    $(".sliderNav .prev").click(function () {
        $cSlide.trigger('owl.prev');
    })


    var owlsm = $("#similar-events");

    owlsm.owlCarousel({
        navigation: false,
        pagination: false,
        items: 5, //10 items above 1000px browser width
        itemsDesktop: [1000, 4], //5 items between 1000px and 901px
        itemsDesktopSmall: [900, 3], // betweem 900px and 601px
        itemsTablet: [600, 2], //2 items between 600 and 0
        itemsMobile: false // itemsMobile disabled - inherit from itemsTablet option
    });

    // Custom Navigation Events
    $(".featured-navi .next").click(function () {
        owlsm.trigger('owl.next');
    })
    $(".featured-navi .prev").click(function () {
        owlsm.trigger('owl.prev');
    })




    var owl = $("#featured-movie");

    owl.owlCarousel({
        navigation: false,
        pagination: false,
        items: 5, //10 items above 1000px browser width
        itemsDesktop: [1000, 4], //5 items between 1000px and 901px
        itemsDesktopSmall: [900, 3], // betweem 900px and 601px
        itemsTablet: [600, 2], //2 items between 600 and 0
        itemsMobile: false // itemsMobile disabled - inherit from itemsTablet option
    });

    // Custom Navigation Events
    $(".featured-navi .next").click(function () {
        owl.trigger('owl.next');
    })
    $(".featured-navi .prev").click(function () {
        owl.trigger('owl.prev');
    })

    $(".MovieSlider").owlCarousel({
        items: 8, //10 items above 1000px browser width
        itemsDesktop: [1000, 5], //5 items between 1000px and 901px
        itemsDesktopSmall: [900, 3], // betweem 900px and 601px
        itemsTablet: [600, 2], //2 items between 600 and 0
        itemsMobile: false // itemsMobile disabled - inherit from itemsTablet option

    });

    $("#offer-promo-slider").owlCarousel({
        navigation: false, // Show next and prev buttons
        slideSpeed: 300,
        paginationSpeed: 400,
        singleItem: true
    });



    $('.navx>li').each(function () {
        var timer;
        var nav = $(this);
        var menu = nav.find('.dropdown-menu');

        // Override the hover event
        nav.hover(function () {
            // Mouse over
            timer = setTimeout(function () {
                //menu.css({"display":"block"});
                nav.addClass('open');
            }, 500);
        }, function () {
            // Mouse leave
            clearTimeout(timer);
            nav.removeClass('open');
            //menu.css({"display":"none"});
        });

        // Disable touchend event if menu is closed
        // Touch device compatibility
        nav.bind('touchend', function () {
            if (nav.find('.dropdown-menu').css('display') == 'none') {
                event.preventDefault();
            }
        });
    });
});
// Dropdown Menu Fade    
jQuery(document).ready(function () {
    $(".dropdownx").hover(
            function () {
                $('.dropdown-menu', this).stop().fadeIn("fast");
            },
            function () {
                $('.dropdown-menu', this).stop().fadeOut("fast");
            });
});



$(document).ready(function () {

//    $('.price-box .btn-primary').click(function (e) {
//        $('#notify').modal('show');
//        $('#notify').removeClass('hide');
//        e.preventDefault();
//    });

    $('a.scrollto').click(function (e) {
        $('html,body').scrollTo(this.hash, this.hash);
        e.preventDefault();
    });

});



if (navigator.userAgent.match(/(iPod|iPhone|iPad)/)) {
    $('.ifios').addClass("isios");
} else {
    //alert('non ios');

}

$(window).on('load', function () {
});

// Parallax 


$(window).bind('scroll', function (e) {
    parallaxScroll();
});

function parallaxScroll() {
    var scrolledY = $(window).scrollTop();
    //$('.x ').css('marginTop','-'+((scrolledY*0.5))+'px');
    //$('.x').css('top','-'+((scrolledY*0.8))+'px');
    //$('.fullParallex').css('background-position','center -'+((scrolledY*0.4))+'px');
    $('.background-image').css('background-position', 'center -' + ((scrolledY * 0.3)) + 'px');
    //$('.sliderContainer ').css('marginTop','-'+((scrolledY*0.5))+'px');
    //$('.fish').css('top','-'+((scrolledY*0.8))+'px');
}



// top navbar IE & Mobile Device fix
var isMobile = function () {
    //console.log("Navigator: " + navigator.userAgent);
    return /(iphone|ipod|ipad|android|blackberry|windows ce|palm|symbian)/i.test(navigator.userAgent);
};


if (isMobile()) {
    // For  mobile , ipad, tab

} else {


    $(function () {

        //Keep track of last scroll
        var teventScroll = 0;
        $(window).scroll(function (event) {
            //Sets the current scroll position
            var ts = $(this).scrollTop();
            //Determines up-or-down scrolling
            if (ts > teventScroll) {
                // downward-scrolling
                $('.header-wrapper').addClass('stuck');

            } else {
                // upward-scrolling
                $('.header-wrapper').removeClass('stuck');
            }

            if (ts < 600) {
                // downward-scrolling
                $('.navbar').removeClass('stuck');
                //alert()
            }


            teventScroll = ts;

            //Updates scroll position

        });
    });



} // end Desktop else






