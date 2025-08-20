document.addEventListener('DOMContentLoaded', function() {
    const config = $SlideshowConfigJSON.RAW;
    
    const swiper = new Swiper('.hero-swiper', {
        autoplay: config.autoplay ? {
            delay: config.delay,
            disableOnInteraction: false,
            pauseOnMouseEnter: true
        } : false,
        
        speed: config.speed,
        loop: config.loop,
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        },
        
        navigation: config.showNavigation ? {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        } : false,
        
        pagination: config.showPagination ? {
            el: '.swiper-pagination',
            clickable: true
        } : false
    });
});