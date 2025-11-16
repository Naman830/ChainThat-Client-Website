$( document ).ready(function() {

//============================

// carousel-1 - Hero Carousel
$("#owl-csel1").owlCarousel({
    items: 1,
    autoplay: true,
    autoplayTimeout: 5000,
    autoplaySpeed: 800,
    smartSpeed: 800,
    startPosition: 0,
    rtl: false,
    loop: true,
    margin: 0,
    dots: true,
    dotsEach: 1,
    nav: false,
    autoplayHoverPause: true,
    navText: [
        '<i class="fa fa-angle-left" aria-hidden="true"></i>',
        '<i class="fa fa-angle-right" aria-hidden="true"></i>'
    ],
    navContainer: '.main-content1 .custom-nav',
    responsive:{
        0: {
            items: 1
        },
        767: {
            items: 1
        },
        991: {
            items: 1
        },
        1199: {
            items: 1
        },
        1200: {
            items: 1
        }
    }
});


// carousel-2 - Partners Carousel - Continuous Marquee Style
if ($("#owl-csel2").length) {
    var partnersSettings = (typeof chainthatsettings !== "undefined") ? chainthatsettings.partners : { autoplay: true, speed: 5000 };
    var autoplayEnabled = partnersSettings.autoplay !== false;
    var baseSpeed = partnersSettings.speed ? Math.max(partnersSettings.speed, 5000) : 5000;
    var $partnersCarousel = $("#owl-csel2");

    $partnersCarousel.owlCarousel({
        items: 6,
        autoplay: autoplayEnabled,
        autoplayTimeout: 1,
        autoplaySpeed: baseSpeed,
        smartSpeed: baseSpeed,
        slideTransition: "linear",
        rtl: false,
        loop: true,
        margin: 40,
        dots: false,
        nav: false,
        autoplayHoverPause: false,
        mouseDrag: false,
        touchDrag: false,
        pullDrag: false,
        freeDrag: false,
        responsive: {
            0: {
                items: 2,
                margin: 16
            },
            480: {
                items: 3,
                margin: 16
            },
            768: {
                items: 4,
                margin: 24
            },
            1024: {
                items: 5,
                margin: 32
            },
            1280: {
                items: 6,
                margin: 40
            }
        }
    });

    // Smooth continuous scroll with hover slow-down
    if (autoplayEnabled) {
        var owlData = $partnersCarousel.data('owl.carousel');
        var isHovered = false;
        var slowSpeed = baseSpeed * 2.5;

        $partnersCarousel.on("mouseenter", function () {
            isHovered = true;
            owlData.settings.autoplaySpeed = slowSpeed;
            owlData.settings.smartSpeed = slowSpeed;
        }).on("mouseleave", function () {
            isHovered = false;
            owlData.settings.autoplaySpeed = baseSpeed;
            owlData.settings.smartSpeed = baseSpeed;
        });
    }
}


// carousel-3 - Updated for 4-column layout on desktop
$("#owl-csel3").owlCarousel({
    items: 4,
    // autoplay: true,
    // autoplayTimeout: 3000,
    startPosition: 0,
    rtl: false,
    loop: true,
    margin: 30,
    dots: true,
    dotsEach: 2,
    nav: true,
    autoplayHoverPause: false,
    navText: [
        '<i class="fa fa-angle-left" aria-hidden="true"></i>',
        '<i class="fa fa-angle-right" aria-hidden="true"></i>'
            ],
    navContainer: '.main-content3 .custom-nav',
    responsive:{
        0: {
            items: 1,
            margin: 20,                              
        },
        767: {
            items: 1,
            margin: 20,
        },
        991: {
            items: 2,
            margin: 25,       
        },
        1199: {
            items: 3,
            margin: 25,       
        },
        1400: {
            items: 4,
            margin: 30,
        }
    }
});

// carousel-4
$("#owl-csel4").owlCarousel({
    items: 1,
    //stagePadding: 70,
    autoplay: true,
    autoplayTimeout: 3000,
    startPosition: 0,
    rtl: false,
    loop: true,
    margin: 70,
    dots: true,
    dotsEach: 1,
    nav: false,
    autoplayHoverPause: false,
    navText: [
        '<i class="fa fa-angle-left" aria-hidden="true"></i>',
        '<i class="fa fa-angle-right" aria-hidden="true"></i>'
            ],
    navContainer: '.main-content4 .custom-nav',
    responsive:{
        0: {
            items: 1,
                                
        },
        767: {
            items: 1,

        },
        991: {
            items: 1,       
                
        },
        1199: {
            items: 1,       
                
        },
        1200: {
            items: 1,

        }
    }

});


// carousel-5
$("#owl-csel5").owlCarousel({
    items: 2,
    stagePadding: 200,
    autoplay: true,
    autoplayTimeout: 1800,
    autoplaySpeed: 1800,
    slideTransition: 'linear',
    smartSpeed: 100,
    startPosition: 0,
    rtl: false,
    loop: true,
    margin: 20,
    dots: true,
    //dotsEach: 1,
    nav: false,
    autoplayHoverPause: false,
    navText: [
        '<i class="fa fa-angle-left" aria-hidden="true"></i>',
        '<i class="fa fa-angle-right" aria-hidden="true"></i>'
            ],
    navContainer: '.main-content5 .custom-nav',
    responsive:{
        0: {
            items: 1,
            stagePadding: 40,
                                
        },
        767: {
            items: 2,

        },
        991: {
            items: 2,       
                
        },
        1199: {
            items: 2,       
                
        },
        1200: {
            items: 2,

        }
    }

});


// carousel-6
$("#owl-csel6").owlCarousel({
    items: 2,
    stagePadding: 440,
    autoplay: true,
    autoplayTimeout: 3000,
    startPosition: 0,
    rtl: false,
    loop: true,
    margin: 20,
    dots: true,
    dotsEach: 2,
    nav: false,
    autoplayHoverPause: false,
    navText: [
        '<i class="fa fa-angle-left" aria-hidden="true"></i>',
        '<i class="fa fa-angle-right" aria-hidden="true"></i>'
            ],
    navContainer: '.main-content6 .custom-nav',
    responsive:{
        0: {
            items: 1,
            stagePadding: 0,                  
        },
        767: {
            items: 2,
            stagePadding: 0,
        },
        991: {
            items: 2,       
                
        },
        1199: {
            items: 2,       
                
        },
        1200: {
            stagePadding: 200,
            items: 2,

        },
        1500: {
            stagePadding: 440,
            items: 2,

        }
    }

});


// carousel-8
$("#owl-csel8").owlCarousel({
    items: 2,
    //stagePadding: 440,
    autoplay: true,
    autoplayTimeout: 3000,
    startPosition: 0,
    rtl: false,
    loop: true,
    margin: 20,
    dots: true,
    dotsEach: 2,
    nav: false,
    autoplayHoverPause: false,
    navText: [
        '<i class="fa fa-angle-left" aria-hidden="true"></i>',
        '<i class="fa fa-angle-right" aria-hidden="true"></i>'
            ],
    navContainer: '.main-content8 .custom-nav',
    responsive:{
        0: {
            items: 1,                
        },
        767: {
            items: 1,
        },
        991: {
            items: 1,       
                
        },
        1199: {
            items: 2,       
                
        },
        1200: {
            items: 2,

        },
        1500: {
            items: 2,

        }
    }

});

// carousel-9
$("#owl-csel9").owlCarousel({
    items: 2,
    stagePadding: 440,
    autoplay: true,
    autoplayTimeout: 3000,
    startPosition: 0,
    rtl: false,
    loop: true,
    margin: 20,
    dots: true,
    dotsEach: 2,
    nav: false,
    autoplayHoverPause: false,
    navText: [
        '<i class="fa fa-angle-left" aria-hidden="true"></i>',
        '<i class="fa fa-angle-right" aria-hidden="true"></i>'
            ],
    navContainer: '.main-content9 .custom-nav',
    responsive:{
        0: {
            items: 1,
            stagePadding: 0,                  
        },
        767: {
            items: 2,
            stagePadding: 0,
        },
        991: {
            items: 2,       
                
        },
        1199: {
            items: 2,       
                
        },
        1200: {
            stagePadding: 200,
            items: 2,

        },
        1500: {
            stagePadding: 440,
            items: 2,

        }
    }

});


// carousel-10
$("#owl-csel10").owlCarousel({
    items: 2,
    stagePadding: 440,
    autoplay: true,
    autoplayTimeout: 1800,
    autoplaySpeed: 1800,
    slideTransition: 'linear',
    startPosition: 0,
    smartSpeed: 1000,
    rtl: false,
    loop: true,
    margin: 20,
    dots: true,
    dotsEach: 2,
    nav: false,
    autoplayHoverPause: false,
    navText: [
        '<i class="fa fa-angle-left" aria-hidden="true"></i>',
        '<i class="fa fa-angle-right" aria-hidden="true"></i>'
            ],
    navContainer: '.main-content10 .custom-nav',
    responsive:{
        0: {
            items: 1,
            stagePadding: 0,                  
        },
        767: {
            items: 2,
            stagePadding: 0,
        },
        991: {
            items: 2,       
                
        },
        1199: {
            items: 2,       
                
        },
        1200: {
            stagePadding: 200,
            items: 2,

        },
        1500: {
            stagePadding: 440,
            items: 2,

        }
    }

});


// carousel-11
$("#owl-csel11").owlCarousel({
    items: 1,
    //stagePadding: 70,
    autoplay: true,
    autoplayTimeout: 3000,
    startPosition: 0,
    rtl: false,
    loop: true,
    margin: 70,
    dots: true,
    dotsEach: 1,
    nav: false,
    autoplayHoverPause: false,
    navText: [
        '<i class="fa fa-angle-left" aria-hidden="true"></i>',
        '<i class="fa fa-angle-right" aria-hidden="true"></i>'
            ],
    navContainer: '.main-content11 .custom-nav',
    responsive:{
        0: {
            items: 1,
                                
        },
        767: {
            items: 1,

        },
        991: {
            items: 1,       
                
        },
        1199: {
            items: 1,       
                
        },
        1200: {
            items: 1,

        }
    }

});

// carousel-12
$("#owl-csel12").owlCarousel({
    items: 2,
    //stagePadding: 440,
    autoplay: true,
    autoplayTimeout: 3000,
    startPosition: 0,
    rtl: false,
    loop: true,
    margin: 20,
    dots: true,
    dotsEach: 2,
    nav: false,
    autoplayHoverPause: false,
    navText: [
        '<i class="fa fa-angle-left" aria-hidden="true"></i>',
        '<i class="fa fa-angle-right" aria-hidden="true"></i>'
            ],
    navContainer: '.main-content12 .custom-nav',
    responsive:{
        0: {
            items: 1,                
        },
        767: {
            items: 1,
        },
        991: {
            items: 1,       
                
        },
        1199: {
            items: 2,       
                
        },
        1200: {
            items: 2,

        },
        1500: {
            items: 2,

        }
    }

});

// carousel-12 Desktop (Desktop - careers values with 4 columns)
if ($("#owl-csel12-desktop").length) {
    console.log('Initializing desktop careers carousel with ' + $("#owl-csel12-desktop .features-item").length + ' items');
    $("#owl-csel12-desktop").owlCarousel({
        items: 4,
        autoplay: true,
        autoplayTimeout: 3000,
        autoplaySpeed: 1800,
        startPosition: 0,
        rtl: false,
        loop: true,
        margin: 30,
        dots: true,
        nav: false,
        autoplayHoverPause: true,
        navText: [
            '<i class="fa fa-angle-left" aria-hidden="true"></i>',
            '<i class="fa fa-angle-right" aria-hidden="true"></i>'
        ],
        navContainer: '.main-content12-desktop .custom-nav',
        responsive:{
            0: {
                items: 1,
            },
            768: {
                items: 2,
            },
            992: {
                items: 3,
            },
            1200: {
                items: 4,
            }
        }
    });
    console.log('Desktop careers carousel initialized successfully');
} else {
    console.log('Desktop careers carousel element #owl-csel12-desktop not found');
}

// Blog Desktop Carousel
$("#owl-csel-blog-desktop").owlCarousel({
    items: 3,
    autoplay: true,
    autoplayTimeout: 4000,
    startPosition: 0,
    rtl: false,
    loop: true,
    margin: 30,
    dots: true,
    nav: false,
    autoplayHoverPause: true,
    responsive:{
        0: {
            items: 1,
            margin: 15,
        },
        768: {
            items: 2,
            margin: 20,
        },
        992: {
            items: 3,
            margin: 30,
        },
        1200: {
            items: 3,
            margin: 30,
        },
        1400: {
            items: 3,
            margin: 30,
        }
    }
});

// Blog Tablet Carousel
$("#owl-csel-blog-tablet").owlCarousel({
    items: 2,
    autoplay: true,
    autoplayTimeout: 4000,
    startPosition: 0,
    rtl: false,
    loop: true,
    margin: 20,
    dots: true,
    nav: false,
    autoplayHoverPause: true,
    responsive:{
        0: {
            items: 1,
            margin: 15,
        },
        768: {
            items: 2,
            margin: 20,
        }
    }
});

// Blog Mobile Carousel
$("#owl-csel-blog-mobile").owlCarousel({
    items: 1,
    autoplay: true,
    autoplayTimeout: 4000,
    startPosition: 0,
    rtl: false,
    loop: true,
    margin: 15,
    dots: true,
    nav: false,
    autoplayHoverPause: true,
    responsive:{
        0: {
            items: 1,
            margin: 15,
        }
    }
});

// Policy Desktop Carousel (1024px+)
$("#owl-csel-policy-desktop").owlCarousel({
    items: 3,
    autoplay: true,
    autoplayTimeout: 4000,
    startPosition: 0,
    rtl: false,
    loop: true,
    margin: 30,
    dots: true,
    nav: false,
    autoplayHoverPause: true,
    responsive:{
        0: {
            items: 1,
            margin: 15,
        },
        768: {
            items: 2,
            margin: 20,
        },
        1024: {
            items: 3,
            margin: 30,
        }
    }
});

// Policy Tablet Carousel (768px - 1023px)
$("#owl-csel-policy-tablet").owlCarousel({
    items: 2,
    autoplay: true,
    autoplayTimeout: 4000,
    startPosition: 0,
    rtl: false,
    loop: true,
    margin: 30,
    dots: true,
    nav: false,
    autoplayHoverPause: true,
    responsive:{
        0: {
            items: 1,
            margin: 15,
        },
        768: {
            items: 2,
            margin: 30,
        },
        1024: {
            items: 2,
            margin: 30,
        }
    }
});

// Policy Mobile Carousel (below 768px)
$("#owl-csel-policy-mobile").owlCarousel({
    items: 1,
    autoplay: true,
    autoplayTimeout: 4000,
    startPosition: 0,
    rtl: false,
    loop: true,
    margin: 30,
    dots: true,
    nav: false,
    autoplayHoverPause: true,
    responsive:{
        0: {
            items: 1,
            margin: 15,
        },
        480: {
            items: 1,
            margin: 20,
        },
        768: {
            items: 1,
            margin: 30,
        }
    }
});

// Year Statistics Grid - No carousel needed, using CSS grid layout

// Year Background Carousel - Continuous Sliding Marquee
if ($("#owl-csel-year-bg").length > 0) {
    $("#owl-csel-year-bg").owlCarousel({
        items: 1,
        autoplay: true,
        autoplayTimeout: 5000, // 5 seconds between slides
        autoplaySpeed: 2000, // 2 seconds slide duration
        startPosition: 0,
        slideTransition: 'linear', // Linear sliding transition
        rtl: false,
        loop: true,
        margin: 0,
        dots: true,
        dotsEach: 1,
        nav: false,
        autoplayHoverPause: false, // Keep sliding even on hover
        smartSpeed: 2000, // Smooth transition speed
        fluidSpeed: false,
        dragEndSpeed: 2000,
        lazyLoad: false,
        lazyLoadEager: 0,
        responsive:{
            0: {
                items: 1,
                autoplayTimeout: 5000,
                autoplaySpeed: 2000,
                slideTransition: 'linear'
            }
        }
    }).on('changed.owl.carousel', function(event) {
        // Ensure carousel continues after changes
        $(this).trigger('play.owl.autoplay');
    });
}


    // Function to activate a tab within a specific set
    function activateTab(setId, tabId) {
      const container = document.querySelector(`.tab-container[data-set="${setId}"]`);
      if (!container) return;

      container.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
      container.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));

      const targetButton = container.querySelector(`.tab-btn[data-tab="${tabId}"]`);
      const targetPane = document.getElementById(tabId);

      if (targetButton && targetPane) {
        targetButton.classList.add('active');
        targetPane.classList.add('active');
      }
    }

    // Handle click events for each tab set
    document.querySelectorAll('.tab-container').forEach(container => {
      const setId = container.getAttribute('data-set');
      container.querySelectorAll('.tab-btn').forEach(button => {
        button.addEventListener('click', (e) => {
          e.preventDefault();
          const tabId = button.getAttribute('data-tab');
          activateTab(setId, tabId);
          window.history.pushState(null, null, `#${tabId}`); // Update URL
        });
      });
    });

    // Handle mobile dropdown change for set3 tabs
    const mobileTabSelect = document.getElementById('tabSelectMobile');
    if (mobileTabSelect) {
      mobileTabSelect.addEventListener('change', function() {
        const selectedValue = this.value;
        // Find the corresponding tab pane
        const targetPane = document.getElementById(selectedValue);
        
        if (targetPane) {
          // Hide all tab panes in set3
          const container = document.querySelector('.tab-container[data-set="set3"]');
          if (container) {
            container.querySelectorAll('.tab-pane').forEach(pane => {
              pane.classList.remove('active');
            });
            
            // Show selected tab pane
            targetPane.classList.add('active');
            
            // Also update desktop tab buttons if they exist
            container.querySelectorAll('.tab-btn').forEach(btn => {
              btn.classList.remove('active');
            });
            const desktopBtn = container.querySelector(`.tab-btn[data-tab="${selectedValue}"]`);
            if (desktopBtn) {
              desktopBtn.classList.add('active');
            }
            
            // Update URL
            window.history.pushState(null, null, `#${selectedValue}`);
          }
        }
      });
    }

    // On page load or refresh, activate first tab of each set
    window.addEventListener('load', () => {
      // Activate first tab of set1 (All tab)
      activateTab('set1', 'all-news');
      // Activate first tab of set2
      activateTab('set2', 'set2-tab1');
      // Don't set any default hash - let the page load without URL fragments
    });

    const videoWrappers = document.querySelectorAll('.video-wrapper');
    function stopVideo(wrapper) {
        const iframe = wrapper.querySelector('iframe');
        const covers = wrapper.querySelectorAll('.video-cover');
        const playButton = wrapper.querySelector('.play-btn-kp');
        const closeButton = wrapper.querySelector('.close-btn-kp');

        // Stop video
        iframe.src = iframe.src.split('?')[0];
        iframe.style.display = 'none';
        closeButton.style.display = 'none';
        playButton.style.display = 'flex';

        covers.forEach(img => {
            if (img.classList.contains('img-desktop') && window.innerWidth > 768) img.style.display = 'block';
            if (img.classList.contains('img-mobile') && window.innerWidth <= 768) img.style.display = 'block';
        });
    }

    videoWrappers.forEach(wrapper => {
        const covers = wrapper.querySelectorAll('.video-cover');
        const iframe = wrapper.querySelector('iframe');
        const playButton = wrapper.querySelector('.play-btn-kp');
        const closeButton = wrapper.querySelector('.close-btn-kp');
        const videoType = wrapper.dataset.videoType;

        wrapper.addEventListener('click', function(event) {
            if ([...covers].some(img => event.target === img || event.target.closest('.play-btn-kp'))) {
                if (!iframe.src.includes('autoplay')) {
                    if (videoType === 'vimeo') iframe.src += "?autoplay=1";
                    else if (videoType === 'youtube') iframe.src += "?autoplay=1";
                }
                iframe.style.display = 'block';
                closeButton.style.display = 'flex';
                playButton.style.display = 'none';
                covers.forEach(img => img.style.display = 'none');
            }
        });

        closeButton.addEventListener('click', function(event) {
            event.stopPropagation();
            stopVideo(wrapper);
        });
    });

    // Stop any video if clicked outside
    document.addEventListener('click', function(event) {
        videoWrappers.forEach(wrapper => {
            const iframe = wrapper.querySelector('iframe');
            if (iframe.style.display === 'block' && !wrapper.contains(event.target)) {
                stopVideo(wrapper);
            }
        });
    });




});

// Service Read More/Less Toggle Function - Moved completely outside document ready
function toggleServiceText(button) {
    const serviceItem = button.closest('.service-item');
    const shortText = serviceItem.querySelector('.service-short');
    const fullText = serviceItem.querySelector('.service-full');
    const ellipsis = serviceItem.querySelector('.service-ellipsis');
    
    if (fullText.style.display === 'none' || fullText.style.display === '') {
        // Show full text (keep short text visible, append full text, hide ellipsis)
        fullText.style.display = 'inline';
        if (ellipsis) ellipsis.style.display = 'none';
        button.textContent = 'read less';
        button.setAttribute('data-expanded', 'true');
    } else {
        // Show only short text (hide full text, show ellipsis)
        fullText.style.display = 'none';
        if (ellipsis) ellipsis.style.display = 'inline';
        button.textContent = 'read more';
        button.setAttribute('data-expanded', 'false');
    }
}

