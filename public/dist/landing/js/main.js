(function ($) {
    "use strict";

    // Spinner - Hide after page load
    var spinner = function () {
        // Try to hide immediately for fast page loads
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 500);
        
        // Also hide on window load to catch slower loads
        $(window).on('load', function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        });
    };
    
    // Run spinner after DOM is ready
    if (document.readyState === 'loading') {
        $(document).ready(spinner);
    } else {
        spinner();
    }

    // Initiate the wowjs
    if (typeof WOW !== 'undefined') {
        new WOW().init();
    }

    // Navbar on scrolling
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.navbar').fadeIn('slow').css('display', 'flex');
        } else {
            $('.navbar').fadeOut('slow').css('display', 'none');
        }
    });

    // Owl Carousel
    if ($.fn.owlCarousel) {
        $(".header-carousel").owlCarousel({
            autoplay: true,
            smartSpeed: 1000,
            items: 1,
            dots: false,
            loop: true,
            nav : true,
            navText : ['<i class="bi bi-chevron-left"></i>', '<i class="bi bi-chevron-right"></i>']
        });

        // Testimonials carousel
        $(".testimonials-carousel").owlCarousel({
            autoplay: true,
            smartSpeed : 1000,
            margin: 24,
            dots: false,
            loop: true,
            nav : false,
            items: 1,
            itemsDesktop      : [1199,1],
            itemsDesktopSmall : [768, 1],
            itemsMobile      : [600, 1],
            itemsTablet      : [600, 1]
        });
    }

    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        return false;
    });

    // Facts counter - Simple counter up animation
    function animateCounter($element, target, duration) {
        var current = 0;
        var increment = target / (duration / 16);
        
        var timer = setInterval(function() {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            $element.text(Math.floor(current));
        }, 16);
    }

    // Apply counter animation when element comes into view
    if ($('[data-toggle="counter-up"]').length) {
        var counterElements = $('[data-toggle="counter-up"]');
        var hasScroll = false;
        
        $(window).scroll(function() {
            if (!hasScroll) {
                counterElements.each(function() {
                    var $this = $(this);
                    if ($this.offset().top - $(window).scrollTop() < $(window).height() && $this.offset().top > 0) {
                        var target = parseInt($this.attr('data-target')) || parseInt($this.text());
                        if (target > 0) {
                            animateCounter($this, target, 2000);
                            hasScroll = true;
                        }
                    }
                });
            }
        });
    }

})(jQuery);
