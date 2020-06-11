$('.slider-nav').slick({
    slidesToShow: 5,
    slidesToScroll: 1,
    dots: true,
    focusOnSelect: true,
    autoplay: true,
    autoplaySpeed: 1000,
    responsive: [
        {
            breakpoint: 900,
            settings: {
                slidesToShow: 4
            }
        },
        {
            breakpoint: 700,
            settings: {
                arrows: false,
                centerMode: true,
                slidesToShow: 2,
            }
        },
    ]
});